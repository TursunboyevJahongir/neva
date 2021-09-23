<?php
/**
 * Created by Maxamadjonov Jaxongir.
 * User: Php
 * Date: 02.01.2021
 * Time: 19:37
 */

namespace App\Services\User;


use App\Enums\RoleEnum;
use App\Enums\UserStatusEnum;
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
        $array = $user->kids ?: [];
        if (array_key_exists('kids', $attributes))
            $attributes['kids'] = array_merge_recursive_distinct($array, $attributes['kids']);

        $user->update($attributes);

        if (array_key_exists('image', $attributes)) {
            if ($user->image()->exists()) {
                $user->image->removeFile();
                $user->image()->delete();
            }

            $file = $this->image->uploadFile($attributes['image'], 'Users');

            $user->image()->create([
                'url' => '/' . $file
            ]);
        }

        return $user;
    }

    /**
     * @param string $phone
     * @param string $deviceUID
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
        $this->confirmUser($user);
        $token = $user->createToken($phone);
        $user->auth_token = $token->plainTextToken;
        return $user;
    }

    /**
     * @param User $user
     */
    private function confirmUser(User $user): void
    {
        if (!in_array($user->status, [UserStatusEnum::ACTIVE, UserStatusEnum::BLOCKED], true)) {
            //todo bu yerda birinchi registratsiyadagi cupon beriladi
            $user->status = UserStatusEnum::ACTIVE;
            $user->phone_verified_at = now();
            $user->update();
        }
    }

    public function delete(User $user)
    {
        return $user->delete();
    }

}