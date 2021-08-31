<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class Image extends Model
{
    use HasFactory;
    protected $fillable = [
        'url', 'cover_image'
    ];

    public static function uploadFile(UploadedFile $file, $model)
    {
        return Storage::putFile("uploads/$model", $file);
    }

    public function removeFile()
    {
        if (Storage::exists($this->url))
            return Storage::delete($this->url);
    }

    public function imageable()
    {
        return $this->morphTo();
    }
}
