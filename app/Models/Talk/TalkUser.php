<?php

namespace App\Models\Talk;

use App\Models\Model;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class TalkUser extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'talk_id',
        'user_id',
        'source',
        'created_at'
    ];

    const SOURCE_COMMENT = 2;

    const SOURCE_MEMBER = 1;

    const SOURCE_CREATE = 0;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

}
