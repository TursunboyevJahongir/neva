<?php
/**
 * Created by Maxamadjonov Jaxongir.
 * User: Php
 * Date: 02.01.2021
 * Time: 19:37
 */

namespace App\Services\Kid;


use App\Enums\UserStatusEnum;
use App\Models\Image;
use App\Models\Kids;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;


class KidsService
{

    public function one($id)
    {
        return Kids::query()->where('parent_id', Auth::id())->where('id', $id)->first();
    }

    public function create(array $data)
    {
        $data['parent_id'] = Auth::id();
        return Kids::query()->create($data);
    }

    public function update(array $data, Kids $id)
    {
        $id->update($data);
        return $id;
    }

    public function delete(Kids $kid)
    {
        $kid->delete();
    }
}
