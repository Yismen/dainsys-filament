<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

// Models

class Category extends \App\Models\BaseModels\AppModel
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'parent_id',
        'display_order',
    ];

    // Flat categories: no parent/children relationships

    public function articles(): BelongsToMany
    {
        return $this->belongsToMany(Article::class, 'article_category');
    }

    public function accesses(): HasMany
    {
        return $this->hasMany(CategoryAccess::class);
    }

    /**
     * Get all root (top-level) categories
     */
    #[\Illuminate\Database\Eloquent\Attributes\Scope]
    protected function roots($query)
    {
        return $query->orderBy('display_order');
    }

    /**
     * Get category with all descendants (children, grandchildren, etc.)
     */
    // No descendants/ancestors for flat categories

    protected static function booted(): void
    {
        static::creating(function (Category $category): void {
            if (empty($category->slug)) {
                $base = Str::slug($category->name ?: 'category');
                $slug = $base;
                while (self::where('slug', $slug)->exists()) {
                    $slug = $base.'-'.Str::random(6);
                }
                $category->slug = $slug;
            }
        });
    }
}
