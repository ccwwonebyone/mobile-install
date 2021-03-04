<?php

namespace App\Models\Install;

use App\Models\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Install extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'install_date',
        'account',
        'address',
        'type',
        'remark',
        'user_id',
        'created_at'
    ];

    public function equipments()
    {
        return $this->hasMany(Equipment::class, 'install_id', 'id');
    }
}
