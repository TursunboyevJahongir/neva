<?php

namespace App\Http\Controllers\api;

use App\Enums\SocialiteEnum;
use App\Http\Controllers\ApiController;
use App\Http\Request;
use App\Http\Requests\api\Auth\SocialiteRequest;
use App\Models\SocialAccount;
use App\Models\User;
use App\Services\User\UserService;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;

class SocialiteController extends ApiController
{
    public function __construct(public UserService $user_service)
    {
    }

    function guessNumber($n)
    {
        $min = 1;
        $rand = floor($n / 2);
        switch ($this->guess($rand)) {
            case -1:
                $n = $rand;
                break;
            case 1:
                $min = $rand;
                break;
            case 0:
                goto end;
        }
        hear:
        $rand = rand($min, $n);
        switch ($this->guess($rand)) {
            case -1:
                $n = $rand;
                goto hear;
            case 1:
                $min = $rand;
                goto hear;
            case 0:
                goto end;
        }
        end:
        return $rand;
    }

    public function social(SocialiteRequest $request)
    {
        $socialite = $request->validated()['socialite'];
        $social_user = match ($socialite) {
            SocialiteEnum::FACEBOOK => Socialite::driver(SocialiteEnum::FACEBOOK)->fields([
                'name',
                'first_name',
                'last_name',
                'email'
            ]),
            SocialiteEnum::GOOGLE => Socialite::driver(SocialiteEnum::GOOGLE)
                ->scopes(['profile', 'email']),
            SocialiteEnum::TELEGRAM => Socialite::driver(SocialiteEnum::TELEGRAM)->user(),

            default => null,
        };
        if ($social_user == null) {
            return $this->error(__('messages.socialite_missing'), null, 422);//todo message
        }

        $social_user_details = $social_user->userFromToken($request->validated()['access_token']);

//        abort_if($social_user_details == null, 400, 'Invalid credentials'); //|| $fb_user->id != $request->input('userID')
        if ($social_user_details == null) {
            return $this->error(__('messages.invalid_credentials'), null, 400);//todo message
        }

        $account = SocialAccount::where("user_socialite_id", $social_user_details->id)
            ->where("socialite", $socialite)
            ->with('user')->first();

        if ($account) {
            return $this->success('', $this->issueToken($account->user));
        } else {
            // create new user and social login if user with social id not found.
            $user = User::where("email", $social_user_details->getEmail())->first();
            if (!$user) {
                // create new social login if user already exist.
                $user = new User;
                switch ($socialite) {
                    case SocialiteEnum::GOOGLE:
                        $user->first_name = $social_user_details->user['first_name'];
                        $user->last_name = $social_user_details->user['last_name'];
                        break;
                    case SocialiteEnum::FACEBOOK:
                        $user->first_name = $social_user_details->user['name']['givenName'];
                        $user->last_name = $social_user_details->user['name']['familyName'];
                        break;
                    default :
                }
                $user->email = $social_user_details->getEmail();
                $user->username = $social_user_details->getEmail();
//                $user->password = Hash::make('social');
                $user->save();
            }
            $social_account = new SocialAccount;
            $social_account->socialite = $socialite;
            $social_account->user_socialite_id = $social_user_details->id;
            $user->social_accounts()->save($social_account);
            return $this->success('', $this->issueToken($account->user));
        }
    }

    private function issueToken(User $user): User
    {
        $this->user_service->verifyEmailUser($user);
        $token = $user->createToken('socialLogin');
        $user->auth_token = $token->plainTextToken;
        return $user;
    }
}
