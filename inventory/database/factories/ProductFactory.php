<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->unique()->words(3, true);

        return [
            'category_id'         => Category::factory(),
            'name'                => ucwords($name),
            'slug'                => Str::slug($name),
            'sku'                 => strtoupper(fake()->unique()->bothify('???-####-??')),
            'description'         => fake()->optional()->paragraph(),
            'price'               => fake()->randomFloat(2, 1, 2000),
            'quantity'            => fake()->numberBetween(0, 100),
            'minimum_stock_level' => fake()->numberBetween(3, 20),
        ];
    }

    /**
     * State for a low-stock product (quantity <= minimum_stock_level).
     */
    public function lowStock(): static
    {
        return $this->state(fn (array $attributes) => [
            'quantity'            => 1,
            'minimum_stock_level' => 10,
        ]);
    }

    /**
     * State for a well-stocked product.
     */
    public function wellStocked(): static
    {
        return $this->state(fn (array $attributes) => [
            'quantity'            => 100,
            'minimum_stock_level' => 5,
        ]);
    }
}
