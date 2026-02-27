<?php

namespace Database\Factories;

use App\Enums\ArticleStatus;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Article>
 */
class ArticleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = fake()->sentence(4);
        $slug = str_replace(' ', '-', strtolower($title));

        return [
            'title' => $title,
            'slug' => $slug,
            'excerpt' => fake()->paragraph(),
            'content' => fake()->paragraphs(3, asText: true),
            'featured_image_path' => null,
            'meta_description' => fake()->sentence(),
            'meta_keywords' => fake()->word().', '.fake()->word(),
            'author_id' => User::factory(),
            'status' => ArticleStatus::Draft,
            'published_at' => null,
        ];
    }

    /**
     * Make the article published
     */
    public function published(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => ArticleStatus::Published,
                'published_at' => now(),
            ];
        });
    }

    /**
     * Make the article draft
     */
    public function draft(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => ArticleStatus::Draft,
                'published_at' => null,
            ];
        });
    }
}
