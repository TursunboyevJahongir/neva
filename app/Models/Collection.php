<?php

namespace App\Models;

use App\Casts\TranslatableJson;
use App\Traits\HasTranslatableJson;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Collection extends Model
{
    use HasFactory, HasTranslatableJson;

    protected $fillable = [
        'name',
        'type',
        'type_id',
    ];

    protected $casts = [
        'name' => TranslatableJson::class
    ];


}
