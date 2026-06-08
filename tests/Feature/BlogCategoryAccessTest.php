<?php

use App\Models\Article;
use App\Models\Category;
use App\Models\CategoryAccess;
use App\Models\Role;
use App\Models\User;
use App\Services\ArticleAccessService;
use App\Services\CategoryService;

it('can grant user access to category', function (): void {
    $user = User::factory()->create();
    $category = Category::factory()->create();

    CategoryAccess::create([
        'category_id' => $category->id,
        'user_id' => $user->id,
    ]);

    $access = CategoryAccess::where('user_id', $user->id)->first();
    expect($access)->not->toBeNull()
        ->and($access->category_id)->toBe($category->id);
});

it('can grant role access to category', function (): void {
    $role = Role::create(['name' => 'Editor']);
    $category = Category::factory()->create();

    CategoryAccess::create([
        'category_id' => $category->id,
        'role_id' => $role->id,
    ]);

    $access = CategoryAccess::where('role_id', $role->id)->first();
    expect($access)->not->toBeNull()
        ->and($access->category_id)->toBe($category->id);
});

it('can revoke access from category', function (): void {
    $user = User::factory()->create();
    $category = Category::factory()->create();

    $access = CategoryAccess::create([
        'category_id' => $category->id,
        'user_id' => $user->id,
    ]);

    $access->delete();

    expect(CategoryAccess::find($access->id))->toBeNull();
});

it('filters articles by accessible categories', function (): void {
    $user = User::factory()->create();
    $accessibleCategory = Category::factory()->create();
    $restrictedCategory = Category::factory()->create();

    // Grant user access to only one category
    CategoryAccess::create([
        'category_id' => $accessibleCategory->id,
        'user_id' => $user->id,
    ]);

    // Create articles in both categories
    $accessibleArticle = Article::factory()->published()->create();
    $restrictedArticle = Article::factory()->published()->create();

    $accessibleArticle->categories()->attach($accessibleCategory);
    $restrictedArticle->categories()->attach($restrictedCategory);

    // User should only see accessible article
    $userArticles = Article::onlyAccessibleTo($user)->pluck('id')->toArray();

    expect($userArticles)->toContain($accessibleArticle->id)
        ->and($userArticles)->not->toContain($restrictedArticle->id);
});

// hierarchical category access test removed — categories are single-level now

it('grants access to public articles without category access', function (): void {
    $user = User::factory()->create();
    $restrictedArticle = Article::factory()->published()->create();
    $publicArticle = Article::factory()->published()->publicArticle()->create();

    // User has no category access at all
    $userArticles = Article::onlyAccessibleTo($user)->pluck('id')->toArray();

    expect($userArticles)->toContain($publicArticle->id)
        ->and($userArticles)->not->toContain($restrictedArticle->id);
});

it('still blocks non-public articles when user lacks category access', function (): void {
    $user = User::factory()->create();
    $category = Category::factory()->create();
    $article = Article::factory()->published()->create();
    $article->categories()->attach($category);

    $userArticles = Article::onlyAccessibleTo($user)->pluck('id')->toArray();

    expect($userArticles)->not->toContain($article->id);
});

it('includes public articles alongside category-accessible articles', function (): void {
    $user = User::factory()->create();
    $category = Category::factory()->create();

    CategoryAccess::create([
        'category_id' => $category->id,
        'user_id' => $user->id,
    ]);

    $categoryArticle = Article::factory()->published()->create();
    $categoryArticle->categories()->attach($category);

    $publicArticle = Article::factory()->published()->publicArticle()->create();

    $userArticles = Article::onlyAccessibleTo($user)->pluck('id')->toArray();

    expect($userArticles)->toContain($categoryArticle->id)
        ->and($userArticles)->toContain($publicArticle->id);
});

it('makes public articles accessible via ArticleAccessService', function (): void {
    $user = User::factory()->create();
    $publicArticle = Article::factory()->published()->publicArticle()->create();

    $accessible = ArticleAccessService::getAccessibleArticles($user);

    expect($accessible->pluck('id')->toArray())->toContain($publicArticle->id);
});

it('canUserAccessArticle returns true for public articles', function (): void {
    $user = User::factory()->create();
    $publicArticle = Article::factory()->published()->publicArticle()->create();

    expect(ArticleAccessService::canUserAccessArticle($user, $publicArticle))->toBeTrue();
});

it('gives superadmin access to all published articles regardless of category', function (): void {
    $superAdmin = User::factory()->create();
    $role = Role::firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);
    $superAdmin->assignRole($role);
    $restrictedCategory = Category::factory()->create();
    $article = Article::factory()->published()->create();
    $article->categories()->attach($restrictedCategory);

    $articles = Article::onlyAccessibleTo($superAdmin)->pluck('id')->toArray();

    expect($articles)->toContain($article->id);
});

it('gives superadmin access to all categories', function (): void {
    $superAdmin = User::factory()->create();
    $role = Role::firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);
    $superAdmin->assignRole($role);
    $categories = Category::factory(3)->create();

    $accessible = app(CategoryService::class)::accessibleFor($superAdmin);

    expect($accessible->count())->toBe(3);
});

it('canUserAccessArticle returns true for any article when user is superadmin', function (): void {
    $superAdmin = User::factory()->create();
    $role = Role::firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);
    $superAdmin->assignRole($role);
    $category = Category::factory()->create();
    $article = Article::factory()->published()->create();
    $article->categories()->attach($category);

    expect(ArticleAccessService::canUserAccessArticle($superAdmin, $article))->toBeTrue();
});
