<?php
/**
 * Created by Maxamadjonov Jaxongir.
 * User: Php
 * Date: 02.01.2021
 * Time: 19:37
 */

namespace App\Services\User;


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

    public function delete(User $user)
    {
        return $user->delete();
    }

}