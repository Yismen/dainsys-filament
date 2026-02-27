<?php

namespace App\Services;

use App\Models\Article;

class ArticleService
{
    /**
     * Get published articles only
     */
    public static function getPublished()
    {
        return Article::published();
    }

    /**
     * Get draft articles only
     */
    public static function getDrafts()
    {
        return Article::draft();
    }

    /**
     * Generate slug from title
     */
    public static function generateSlug(string $title): string
    {
        return str_replace(' ', '-', strtolower($title));
    }
}
