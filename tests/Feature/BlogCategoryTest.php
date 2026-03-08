<?php

use App\Models\Category;

it('can create a category', function (): void {
    $category = Category::factory()->create();

    expect($category)->toBeInstanceOf(Category::class)
        ->and($category->name)->not->toBeEmpty();
});

it('can get all categories ordered', function (): void {
    Category::factory()->create(['display_order' => 2]);
    Category::factory()->create(['display_order' => 1]);
    Category::factory()->create(['display_order' => 3]);

    $ordered = Category::get();

    expect($ordered)->toHaveCount(3);
});
