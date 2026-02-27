<?php

namespace App\Services;

use App\Models\Category;
use Illuminate\Support\Arr;

class CategoryService
{
    /**
     * Get all root categories with their tree
     */
    public static function getRootCategoriesWithChildren()
    {
        return Category::roots()->with('children')->get();
    }

    /**
     * Generate slug from name
     */
    public static function generateSlug(string $name): string
    {
        return str_replace(' ', '-', strtolower($name));
    }

    /**
     * Get all categories for a breadcrumb path
     */
    public static function getBreadcrumbPath(Category $category): array
    {
        $path = [$category];
        $current = $category;

        while ($current->parent) {
            $current = $current->parent;
            $path = Arr::prepend($path, $current);
        }

        return $path;
    }

    /**
     * Return all categories a given user has access to.
     *
     * Used by the blog index to render a sidebar of links that
     * the user can click in order to filter the article list.
     */
    public static function accessibleFor(?\App\Models\User $user)
    {
        if (! $user) {
            return collect();
        }

        return Category::whereHas('accesses', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        })->get();
    }
}
