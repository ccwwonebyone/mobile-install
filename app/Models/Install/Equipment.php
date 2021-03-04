<?php

namespace App\Models\Install;

use App\Models\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Equipment extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'equipments';

    protected $fillable = [
        'install_id',
        'content',
        'type',
        'created_at'
    ];
}
