<?php

namespace App\Models\Talk;

use App\Models\Model;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class TalkComment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'talk_id',
        'pid',
        'content',
        'files',
        'imgs',
        'user_id',
        'notice',
        'is_read',
        'created_at'
    ];

    protected $casts = [
        'notice' => 'array',
        'files' => 'array',
        'imgs' => 'array'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
}
