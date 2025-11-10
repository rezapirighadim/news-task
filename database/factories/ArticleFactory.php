<?php

namespace Database\Factories;

use App\Models\Source;
use App\Models\Author;
use Illuminate\Database\Eloquent\Factories\Factory;

class ArticleFactory extends Factory
{
    public function definition(): array
    {
        return [
            'source_id' => Source::factory(),
            'author_id' => Author::factory(),
            'title' => fake()->sentence(),
            'description' => fake()->paragraph(),
            'content' => fake()->paragraphs(3, true),
            'url' => fake()->unique()->url(),
            'url_to_image' => fake()->imageUrl(),
            'published_at' => fake()->dateTimeBetween('-1 month', 'now'),
        ];
    }

    public function withoutAuthor(): ArticleFactory
    {
        return $this->state(fn (array $attributes) => [
            'author_id' => null,
        ]);
    }
}
