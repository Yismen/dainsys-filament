<?php

use App\Livewire\ArticleShow;
use App\Livewire\BlogIndex;
use App\Models\Article;
use App\Models\Category;
use App\Models\CategoryAccess;
use App\Models\User;
use Livewire\Livewire;

it('redirects guests to login when accessing the blog index', function (): void {
    $this->get('/blog')->assertRedirect('/login');
});

it('allows authenticated users to view the blog index and shows accessible articles', function (): void {
    $user = User::factory()->create();
    $category = Category::factory()->create();

    CategoryAccess::create([
        'category_id' => $category->id,
        'user_id' => $user->id,
    ]);

    $article = Article::factory()->published()->create();
    $article->categories()->attach($category);

    Livewire::actingAs($user)
        ->test(BlogIndex::class)
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

    // published visible via Livewire component
    Livewire::actingAs($user)
        ->test(ArticleShow::class, ['article' => $published])
        ->assertSee($published->title);

    // draft should return 404
    Livewire::actingAs($user)
        ->test(ArticleShow::class, ['article' => $draft])
        ->assertNotFound();
});

it('lists accessible categories on the index sidebar', function (): void {
    $user = User::factory()->create();
    $catA = Category::factory()->create();
    $catB = Category::factory()->create();

    CategoryAccess::create(['category_id' => $catA->id, 'user_id' => $user->id]);
    CategoryAccess::create(['category_id' => $catB->id, 'user_id' => $user->id]);

    Article::factory()->published()->create()->categories()->attach($catA);

    Livewire::actingAs($user)
        ->test(BlogIndex::class)
        ->assertSee($catA->name)
        ->assertSee($catB->name);
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

    Livewire::actingAs($user)
        ->test(BlogIndex::class)
        ->set('search', 'SpecialSearchTerm')
        ->assertSee('SpecialSearchTerm')
        ->assertDontSee('Other Article');
});

it('can clear the search term via the inline button', function (): void {
    $user = User::factory()->create();
    $category = Category::factory()->create();

    CategoryAccess::create([
        'category_id' => $category->id,
        'user_id' => $user->id,
    ]);

    $article = Article::factory()->published()->create(['title' => 'Foo Bar']);
    $article->categories()->attach($category);

    $other = Article::factory()->published()->create(['title' => 'Another']);
    $other->categories()->attach($category);

    Livewire::actingAs($user)
        ->test(BlogIndex::class)
        ->set('search', 'Foo')
        ->assertSet('search', 'Foo')
        ->call('clearSearch')
        ->assertSet('search', '')
        ->assertSee('Foo Bar')
        ->assertSee('Another');
});

it('filters articles by category parameter', function (): void {
    $user = User::factory()->create();
    $category = Category::factory()->create();
    $otherCategory = Category::factory()->create();

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

    Livewire::actingAs($user)
        ->test(BlogIndex::class)
        ->call('filterByCategory', $category->slug)
        ->assertSee($inCategory->title)
        ->assertDontSee($outCategory->title);
});
