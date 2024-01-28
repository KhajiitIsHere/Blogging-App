<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Post extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'body', 'author_id', 'active', 'slug'];

    const CREATED_AT = 'published_on';

    const UPDATED_AT = 'last_modified';

    public function author(): BelongsTo {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function comments(): HasMany {
        return $this->hasMany(Comment::class, 'post_id')->latest();
    }
}
