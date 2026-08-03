<?php

namespace Database\Factories;

use App\Models\Service;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Service>
 */
class ServiceFactory extends Factory
{
    protected $model = Service::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->sentence(3),
            'icon' => fake()->randomElement(['code', 'cloud', 'cog', 'shield', 'brain', 'compass']),
            'short_description' => fake()->sentence(10),
            'body' => fake()->paragraphs(3, true),
            'is_featured' => fake()->boolean(50),
            'sort_order' => fake()->numberBetween(0, 10),
            'is_published' => true,
        ];
    }

    public function unpublished(): static
    {
        return $this->state(fn () => ['is_published' => false]);
    }

    public function featured(): static
    {
        return $this->state(fn () => ['is_featured' => true]);
    }
}
