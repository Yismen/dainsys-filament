<?php

use App\Enums\ArticleStatus;
use App\Models\Article;
use App\Models\Category;

it('can create an article', function (): void {
    $article = Article::factory()->create();
    expect($article)->toBeInstanceOf(Article::class);
});

it('can publish an article', function (): void {
    $article = Article::factory()->create(['status' => ArticleStatus::Draft]);
    $article->update([
        'status' => ArticleStatus::Published,
        // 'published_at' => now()
        ]);
    expect($article->refresh()->status)->toBe(ArticleStatus::Published);
});

it('can update an article', function (): void {
    $article = Article::factory()->create();
    $article->update(['title' => 'New Title']);
    expect($article->refresh()->title)->toBe('New Title');
});

it('can delete an article', function (): void {
    $article = Article::factory()->create();
    $article->delete();
    expect($article->refresh()->deleted_at)->not->toBeNull();
});

it('scopes to draft articles', function (): void {
    Article::factory()->draft()->create();
    Article::factory()->draft()->create();
    Article::factory()->published()->create();
    expect(Article::draft()->count())->toBe(2);
});

it('attaches categories to articles', function (): void {
    $article = Article::factory()->create();
    $category = Category::factory()->create();
    $article->categories()->attach($category);
    expect($article->categories()->count())->toBe(1);
});
