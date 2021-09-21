<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    use HasFactory;
    const DISPOSABLE = 'disposable';
    const REUSABLE = 'reusable';
    const ONE_ITEM = 'one_item';
}
