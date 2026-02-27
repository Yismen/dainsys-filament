<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CategoryAccess>
 */
class CategoryAccessFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'category_id' => Category::factory(),
            'user_id' => fake()->boolean() ? User::factory() : null,
            'role_id' => fake()->boolean() ? Role::inRandomOrder()->first()?->id : null,
        ];
    }

    /**
     * Create access for a specific user
     */
    public function forUser(User $user): static
    {
        return $this->state(function (array $attributes) use ($user) {
            return [
                'user_id' => $user->id,
                'role_id' => null,
            ];
        });
    }

    /**
     * Create access for a specific role
     */
    public function forRole(Role $role): static
    {
        return $this->state(function (array $attributes) use ($role) {
            return [
                'user_id' => null,
                'role_id' => $role->id,
            ];
        });
    }
}
