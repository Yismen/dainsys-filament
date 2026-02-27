<?php

namespace App\Services;

use App\Models\Article;
use App\Models\User;

class ArticleAccessService
{
    /**
     * Get articles accessible to a specific user
     */
    public static function getAccessibleArticles(User $user)
    {
        return Article::onlyAccessibleTo($user);
    }

    /**
     * Check if user can access a specific article
     */
    public static function canUserAccessArticle(User $user, Article $article): bool
    {
        // Check if article is in any of the user's accessible categories
        $accessibleArticles = static::getAccessibleArticles($user);

        return $accessibleArticles->where('id', $article->id)->exists();
    }
}
