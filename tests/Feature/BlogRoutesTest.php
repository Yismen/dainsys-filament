<?php

use App\Models\Article;
use App\Models\Category;
use App\Models\CategoryAccess;
use App\Models\User;

it('redirects guests to login when accessing the blog index', function (): void {
    $this->get('/blog')->assertRedirect('/login');
});

it('allows authenticated users to view the blog index and shows accessible articles', function (): void {
    $user = User::factory()->create();
    $category = Category::factory()->create();

    // grant access to user
    CategoryAccess::create([
        'category_id' => $category->id,
        'user_id' => $user->id,
    ]);

    $article = Article::factory()->published()->create();
    $article->categories()->attach($category);

    $this->actingAs($user)
        ->get('/blog')
        ->assertOk()
        ->assertSee($article->title);
});

it('shows an accessible article on the show route and hides drafts', function (): void {
    $user = User::factory()->create();
    $category = Category::factory()->create();

    CategoryAccess::create([
        'category_id' => $category->id,
        'user_id' => $user->id,
    ]);

    $published = Article::factory()->published()->create();
    $published->categories()->attach($category);

    $draft = Article::factory()->draft()->create();
    $draft->categories()->attach($category);

    // published visible
    $this->actingAs($user)
        ->get(route('blog.show', $published->slug))
        ->assertOk()
        ->assertSee($published->title);

    // draft returns 404 for published-only access
    $this->actingAs($user)
        ->get(route('blog.show', $draft->slug))
        ->assertStatus(404);
});

it('lists accessible categories on the index sidebar', function (): void {
    $user = User::factory()->create();
    $catA = Category::factory()->create();
    $catB = Category::factory()->create();

    CategoryAccess::create(['category_id' => $catA->id, 'user_id' => $user->id]);
    CategoryAccess::create(['category_id' => $catB->id, 'user_id' => $user->id]);

    Article::factory()->published()->create()->categories()->attach($catA);

    $response = $this->actingAs($user)->get('/blog');

    $response->assertOk()
        ->assertSee($catA->name)
        ->assertSee($catB->name)
        ->assertSee('/blog?category='.$catA->slug);
});

it('filters articles by search term', function (): void {
    $user = User::factory()->create();
    $category = Category::factory()->create();

    CategoryAccess::create([
        'category_id' => $category->id,
        'user_id' => $user->id,
    ]);

    $matching = Article::factory()->published()->create([
        'title' => 'SpecialSearchTerm',
    ]);
    $matching->categories()->attach($category);

    $other = Article::factory()->published()->create([
        'title' => 'Other Article',
    ]);
    $other->categories()->attach($category);

    $this->actingAs($user)
        ->get('/blog?search=SpecialSearchTerm')
        ->assertOk()
        ->assertSee('SpecialSearchTerm')
        ->assertDontSee('Other Article');
});

it('filters articles by category parameter', function (): void {
    $user = User::factory()->create();
    $category = Category::factory()->create();
    $otherCategory = Category::factory()->create();

    // give user access to both categories so index can load them
    CategoryAccess::create([
        'category_id' => $category->id,
        'user_id' => $user->id,
    ]);
    CategoryAccess::create([
        'category_id' => $otherCategory->id,
        'user_id' => $user->id,
    ]);

    $inCategory = Article::factory()->published()->create();
    $inCategory->categories()->attach($category);

    $outCategory = Article::factory()->published()->create();
    $outCategory->categories()->attach($otherCategory);

    $this->actingAs($user)
        ->get('/blog?category='.$category->slug)
        ->assertOk()
        ->assertSee($inCategory->title)
        ->assertDontSee($outCategory->title);
});
