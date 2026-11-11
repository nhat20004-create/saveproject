<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Post>
 */
class PostFactory extends Factory
{
    public function definition(): array
    {
        $title = fake()->unique()->sentence(6);
        $suffix = fake()->unique()->numberBetween(1, 99999);

        return [
            'user_id' => User::factory(),
            'category_id' => Category::factory(),
            'title' => $title,
            'slug' => Str::slug($title).'-'.$suffix,
            'content' => fake()->paragraphs(3, true),
            'file_path' => null,
            'status' => Post::PUBLISHED,
            'published_at' => now()->subHour(),
        ];
    }

    public function draft(): static
    {
        return $this->state([
            'status' => Post::DRAFT,
            'published_at' => null,
        ]);
    }

    public function scheduled(?\DateTimeInterface $at = null): static
    {
        return $this->state([
            'status' => Post::SCHEDULED,
            'published_at' => $at ?? now()->addDay(),
        ]);
    }
}
