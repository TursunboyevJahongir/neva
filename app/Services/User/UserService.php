<?php
/**
 * Created by Maxamadjonov Jaxongir.
 * User: Php
 * Date: 02.01.2021
 * Time: 19:37
 */

namespace App\Services\User;


use App\Enums\UserStatusEnum;
use App\Models\Image;
use App\Models\User;
use Illuminate\Http\UploadedFile;


class UserService
{

    private const AVATARS_PATH = 'avatar';
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

    /**
     * @param UploadedFile $avatar
     * @return false|string
     */
    public function saveAvatar(UploadedFile $avatar)
    {
        $name = md5(time() . $avatar->getFilename()) . '.' . $avatar->extension();
        return $avatar->storePubliclyAs(self::AVATARS_PATH, $name);
    }

    public function delete(User $user)
    {
        return $user->delete();
    }


    /**
     * @param UploadedFile $file
     * @param User $user
     * @param string $identifier
     */
    private function saveImage(UploadedFile $file, User $user, string $identifier): void
    {


        $fileName = md5(time() . $file->getFilename()) . '.' . $file->getClientOriginalExtension();
        $file->storeAs('images', $fileName);

        $user->resources()->create([
            'name' => $fileName,
            'type' => $file->getExtension(),
            'full_url' => public_path('uploads/' . $fileName),
            'additional_identifier' => $identifier
        ]);
    }

}
