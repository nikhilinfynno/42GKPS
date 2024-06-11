<?php

namespace Database\Factories;

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
            'title' => $this->faker->sentence,
            'created_by' => \App\Models\User::where('role', \App\Models\User::ADMIN_ROLE)->inRandomOrder()->first()->id, 
            'slug' => $this->faker->unique()->slug,
            'banner_image' => 'demo-image.jpeg', //Static image
            'description' => $this->faker->paragraph,
            'excerpt' => $this->faker->text(200),
            'published_at' => $this->faker->dateTimeBetween('-1 years', 'now'),
            'code_snippet' => $this->faker->text,
            'is_free' => $this->faker->boolean,
            'status' => \App\Models\Post::POST_PUBLISHED,
            'visibility' => \App\Models\Post::IS_VISIBLE
        ];
    }
}
