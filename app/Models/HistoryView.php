<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class HistoryView extends Model
{
    use HasFactory;
    protected $fillable = [
        'ip_address', 'user_agent', 'url' ,'start_time','end_time'
    ];

/*
    public function history_viewable()
    {
        return $this->morphTo();
    }*/
}
