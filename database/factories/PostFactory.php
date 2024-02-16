<?php

namespace Database\Factories;

use App\Enums\PostType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Post>
 */
class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'slug' => fake()->unique()->slug(),
            'title' => fake()->sentence(),
            'content' => fake()->randomHtml,
            'type' => (mt_rand(0, 1) == 0) ? PostType::Article : PostType::Project,
            'is_published' => fake()->boolean()
        ];
    }
}