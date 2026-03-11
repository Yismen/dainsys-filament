<?php

namespace App\Models;

use App\Enums\ArticleStatus;
use App\Models\BaseModels\AppModel;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;

class Article extends AppModel
{
    protected $fillable = [
        'title',
        'slug',
        'excerpt',
        'content',
        'featured_image_path',
        'meta_description',
        'meta_keywords',
        'author_id',
        'status',
        // 'published_at',
    ];

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'article_category');
    }

    /**
     * Get only draft articles
     */
    #[Scope]
    protected function draft($query)
    {
        return $query->where('status', ArticleStatus::Draft);
    }

    /**
     * Get only published articles
     */
    #[Scope]
    protected function published($query)
    {
        return $query->where('status', ArticleStatus::Published);
    }

    /**
     * Filter articles accessible to a user based on category access
     */
    #[Scope]
    protected function onlyAccessibleTo($query, User $user)
    {
        $accessibleCategoryIds = CategoryAccess::query()
            ->where(function ($q) use ($user): void {
                $q->where('user_id', $user->id)
                    ->orWhereIn('role_id', $user->roles()->pluck('id'));
            })
            ->pluck('category_id')
            ->unique()
            ->toArray();

        if (empty($accessibleCategoryIds)) {
            return $query->whereRaw('1 = 0'); // Return empty result
        }

        return $query->whereHas('categories', function ($q) use ($accessibleCategoryIds): void {
            $q->whereIn('categories.id', $accessibleCategoryIds);
        });
    }

    protected static function booted(): void
    {
        static::creating(function (Article $article): void {
            if (empty($article->slug)) {
                $base = Str::slug($article->title ?: 'article');
                $slug = $base;
                // Ensure uniqueness
                while (self::where('slug', $slug)->exists()) {
                    $slug = $base.'-'.Str::random(6);
                }
                $article->slug = $slug;
            }
        });

        static::saved(function (Article $article): void {
            // Sync categories if they were set during creation/update
            if (request()->has('categories')) {
                $article->categories()->sync(request()->input('categories', []));
            }

            // dd(request()->all());
            // if (request()->has('status')) {
            //     // If article is published, set published_at if not already set
            //     dd($article->status);
            //     if ($article->status === ArticleStatus::Published && empty($article->published_at)) {
            //         $article->published_at = now();
            //         $article->saveQuietly();
            //     }
            //     // If article is draft, clear published_at
            //     elseif ($article->status === ArticleStatus::Draft) {
            //         $article->published_at = null;
            //         $article->saveQuietly();
            //     }
            // }

        });
    }

    protected function casts(): array
    {
        return [
            'status' => ArticleStatus::class,
            'published_at' => 'datetime',
        ];
    }
}
