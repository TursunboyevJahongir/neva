<?php
/**
 * Created by Maxamadjonov Jaxongir.
 * User: Php
 * Date: 02.01.2021
 * Time: 19:37
 */

namespace App\Services\User;


use App\Enums\UserStatusEnum;
use App\Models\Address;
use App\Models\Image;
use App\Models\User;


class UserService
{
    /**
     * @var Image
     */
    private $image;

    public function __construct()
    {
        $this->image = new Image();
    }

    public function all($role_id = false)
    {
        return User::with('role')
            ->latest('id')
            ->roleId($role_id)
            ->get();
    }

    public function create(array $attributes)
    {
        $user = User::create($attributes);
        if (array_key_exists('image', $attributes)) {
            $file = $this->image->uploadFile($attributes['image'], 'users');

            $user->image()->create([
                'url' => '/' . $file
            ]);
        }


        return $user;
    }

    public function update(array $attributes, User $user)
    {
        $user->update($attributes);
        if (array_key_exists('avatar', $attributes)) {
            if ($user->avatar()->exists()) {
                $user->avatar->removeFile();
                $user->avatar()->delete();
            }

            $file = $this->image->uploadFile($attributes['avatar'], User::CUSTOMER);

            $user->avatar()->create([
                'url' => '/' . $file
            ]);
            $user->load('avatar');
        }
        return $user;
    }

    public function address(array $attributes, User $user)
    {
        $attributes['user_id'] = $user->id;
        if (isset($attributes['address'])) {//todo agarm ko'p  address tanlanadigan bo'lsa
            $address = Address::query()->create($attributes);
            $user->update(['main_address_id' => $address->id]);
        } else {
            $address = Address::query()->findOrFail($user->main_address_id);
            $address->update($attributes);
        }
    }

    /**
     * @param string $phone
     * @return User
     * @throws UserNotFoundException
     */
    public function generateToken(string $phone): User
    {
        /**
         * @var User $user
         */
        $user = User::query()->where(['phone' => $phone])->get()->first();
        if ($user === null) {
            throw new UserNotFoundException(__('messages.not_found'));
        }
//        $token = $user->tokens()->where(['name' => $deviceUID])->get()->first();
//        if ($token !== null) {
//            $token->delete();
//        }
        $this->confirmPhoneUser($user);
        $token = $user->createToken($phone);
        $user->auth_token = $token->plainTextToken;
        return $user;
    }

    /**
     * @param User $user
     */
    private function confirmPhoneUser(User $user): void
    {
        $user->phone_verified_at = now();
        if (!in_array($user->status, [UserStatusEnum::ACTIVE, UserStatusEnum::BLOCKED], true)) {
            //todo bu yerda birinchi registratsiyadagi cupon beriladi
            $user->status = UserStatusEnum::ACTIVE;
            $user->update();
        }
    }

    /**
     * @param User $user
     */
    public function verifyEmailUser(User $user): void
    {
        $user->email_verified_at = now();
        if (!in_array($user->status, [UserStatusEnum::ACTIVE, UserStatusEnum::BLOCKED], true)) {
            //todo bu yerda birinchi registratsiyadagi cupon beriladi
            $user->status = UserStatusEnum::ACTIVE;
            $user->update();
        }
    }

    public function delete(User $user)
    {
        return $user->delete();
    }

}
