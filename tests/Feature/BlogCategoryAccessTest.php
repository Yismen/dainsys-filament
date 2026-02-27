<?php

use App\Models\Article;
use App\Models\Category;
use App\Models\CategoryAccess;
use App\Models\Role;
use App\Models\User;

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
