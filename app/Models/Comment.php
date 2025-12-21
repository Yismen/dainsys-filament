<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Comment extends Model
{
    /** @use HasFactory<\Database\Factories\CommentFactory> */
    use HasFactory;

    use HasUuids;

    /**
     * Get the parent commentable model (post or video).
     */
    public function commentable(): MorphTo
    {
        return $this->morphTo();
    }
}
