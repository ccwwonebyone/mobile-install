<?php

namespace App\Models\Talk;


use App\Enums\Enum;
use App\Models\Model;
use App\Models\User;
use Illuminate\Database\Eloquent\SoftDeletes;

class Talk extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'title',
        'content',
        'status',
        'user_id',
        'type',
        'files',
        'imgs',
        'plan_over_time',
        'created_at'
    ];

    protected $casts = [
        'files' => 'array',
        'imgs' => 'array'
    ];

    protected $appends = [
        'is_expire'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function member()
    {
        return $this->hasMany(TalkUser::class, 'talk_id', 'id');
    }

    public function getIsExpireAttribute()
    {
        return $this->status == Enum::IS && $this->plan_over_time < date('Y-m-d H:i:s');
    }

    public function comment()
    {
        return $this->hasMany(TalkComment::class, 'talk_id', 'id');
    }
}
