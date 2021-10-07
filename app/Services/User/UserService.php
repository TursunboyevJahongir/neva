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
        if (isset($attributes['address'])) {//todo agarm ko'p  address tanlanadigan bo'lsa
            $address = Address::query()
                ->create(['user_id' => $user->id, 'address' => $attributes['address'], 'lat' => $attributes['lat'] ?? null, 'long' => $attributes['long'] ?? null]);
            $user->update(['main_address_id' => $address->id]);
        }
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
