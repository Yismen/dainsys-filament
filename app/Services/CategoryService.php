<?php

namespace App\Services;

use App\Models\Article;
use App\Models\Category;
use App\Models\CategoryAccess;
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

        if ($user->isSuperAdmin()) {
            return Category::all();
        }

        // Sources 1 + 2: category-access records (user + role based)
        $accessibleCategoryIds = CategoryAccess::query()
            ->where(function (Builder $q) use ($user): void {
                $q->where('user_id', $user->id)
                    ->orWhereIn('role_id', $user->roles()->pluck('id'));
            })
            ->pluck('category_id')
            ->unique()
            ->toArray();

        // Source 3: categories belonging to public articles
        $publicCategoryIds = Article::where('is_public', true)
            ->whereHas('categories')
            ->with('categories')
            ->get()
            ->flatMap(fn (Article $article) => $article->categories->pluck('id'))
            ->unique()
            ->toArray();

        $allIds = array_unique([...$accessibleCategoryIds, ...$publicCategoryIds]);

        if (empty($allIds)) {
            return collect();
        }

        return Category::whereIn('id', $allIds)->get();
    }
}
