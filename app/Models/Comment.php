<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = ['post_id', 'user_id', 'body'];

    const CREATED_AT = 'at_time';

    const UPDATED_AT = null;

    public function on_post(): BelongsTo {
        return $this->belongsTo(Post::class, 'post_id');
    }

    public function from_user(): BelongsTo {
        return $this->belongsTo(User::class, 'user_id');
    }
}
