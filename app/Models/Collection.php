<?php

namespace App\Models;

use App\Casts\TranslatableJson;
use App\Traits\HasHeaders;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Collection extends Model
{
    use HasFactory, HasHeaders;

    protected $fillable = [
        'name',
        'type',
        'type_id',
    ];

    protected $casts = [
        'name' => TranslatableJson::class
    ];


}
