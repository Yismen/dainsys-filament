<?php

namespace App\Services;

use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;

class CategoryService
{
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
    public static function accessibleFor(?User $user)
    {
        if (! $user) {
            return collect();
        }

        return Category::whereHas('accesses', function (Builder $q) use ($user): void {
            $q->where('user_id', $user->id);
        })->get();
    }
}
