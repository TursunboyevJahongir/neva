<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;

class Image extends Model
{
    use HasFactory;

    protected $fillable = [
        'url', 'cover_image'
    ];
    protected $casts = [
        'cover_image' => 'boolean'
    ];

    public static function uploadFile(UploadedFile $file, $model)
    {
        return Storage::disk('public')->putFile('/uploads/' .$model, $file);
    }

    public function removeFile()
    {
        @unlink(public_path() . $this->url);
    }

    public function getImageUrlAttribute(): ?string
    {
        return $this->url ? URL::to($this->url) : null;
    }

    public function imageable()
    {
        return $this->morphTo();
    }


}
