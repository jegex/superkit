<?php

namespace Database\Factories;

use App\Enums\ContentType;
use App\Models\Content;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ContentFactory extends Factory
{
    protected $model = Content::class;

    public function definition(): array
    {
        $titleEn = fake()->sentence();

        return [
            'title' => [
                'en' => $titleEn,
                'id' => fake()->sentence(),
            ],
            'slug' => [
                'en' => str($titleEn)->slug(),
                'id' => str(fake()->sentence())->slug(),
            ],
            'excerpt' => [
                'en' => fake()->paragraph(),
                'id' => fake()->paragraph(),
            ],
            'content' => [
                'en' => fake()->paragraphs(3, true),
                'id' => fake()->paragraphs(3, true),
            ],
            'type' => fake()->randomElement(ContentType::cases()),
            'is_featured' => fake()->boolean(20),
            'published_at' => null,
            'scheduled_at' => null,
            'last_published_at' => null,
            'metadata' => null,
            'author_id' => User::factory(),
        ];
    }

    public function post(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => ContentType::Post,
        ]);
    }

    public function page(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => ContentType::Page,
        ]);
    }

    public function product(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => ContentType::Product,
        ]);
    }

    public function published(): static
    {
        return $this->state(fn (array $attributes) => [
            'published_at' => now()->subHours(fake()->randomNumber(3)),
            'last_published_at' => now()->subHours(fake()->randomNumber(2)),
        ]);
    }

    public function draft(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => ContentType::Page,
            'published_at' => null,
            'scheduled_at' => null,
            'last_published_at' => null,
        ]);
    }

    public function featured(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_featured' => true,
        ]);
    }

    public function scheduled(): static
    {
        return $this->state(fn (array $attributes) => [
            'published_at' => null,
            'scheduled_at' => now()->addDays(fake()->numberBetween(1, 30)),
        ]);
    }
}
