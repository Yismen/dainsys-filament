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

    $ordered = Category::roots()->get();

    expect($ordered)->toHaveCount(3);
});
// display order test retained below
it('maintains display order', function (): void {
    Category::factory()->create(['display_order' => 2]);
    Category::factory()->create(['display_order' => 1]);
    Category::factory()->create(['display_order' => 3]);

    $ordered = Category::roots()->get();

    expect($ordered[0]->display_order)->toBe(1)
        ->and($ordered[1]->display_order)->toBe(2)
        ->and($ordered[2]->display_order)->toBe(3);
});
