<?php

namespace App\Models\System;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SystemLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'method',
        'url',
        'user_id',
        'username',
        'data',
        'created_at'
    ];

    protected $casts = [
        'data' => 'array'
    ];
}
