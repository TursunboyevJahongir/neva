<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;
    protected $fillable = [
        'invoice_id',
        'system',
        'system_transaction_id',
        'status',
        'amount',
        'currency_code',
        'details',
        'reason',
        'performed_at',
        'cancelled_at'
    ];

    protected $casts = [
        'performed_at' => 'datetime',
        'cancelled_at' => 'datetime'
    ];


}
