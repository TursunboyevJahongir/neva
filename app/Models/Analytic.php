<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Analytic extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id', 'ip_address', 'user_agent', 'url' ,'start_time','end_time'
    ];
    protected $table='analytic_page';
}
