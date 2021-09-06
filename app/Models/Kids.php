<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Kids extends Model
{
    public static function InsertOrUpdate(array $kids)
    {
        foreach($kids as $kid) {
            static::updateOrCreate(['title' => $kid['name']], $kid);
        }
    }
}
