<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\StockMovement;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\StockMovement>
 */
class StockMovementFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'product_id' => Product::factory(),
            'user_id'    => User::factory(),
            'type'       => fake()->randomElement(['in', 'out']),
            'quantity'   => fake()->numberBetween(1, 50),
            'reason'     => fake()->sentence(),
        ];
    }

    /**
     * State for a stock IN movement (restock / receiving).
     */
    public function in(): static
    {
        return $this->state(fn (array $attributes) => [
            'type'     => 'in',
            'quantity' => fake()->numberBetween(10, 100),
            'reason'   => 'Supplier delivery — ' . fake()->bothify('PO-####'),
        ]);
    }

    /**
     * State for a stock OUT movement (dispatch / sale).
     */
    public function out(): static
    {
        return $this->state(fn (array $attributes) => [
            'type'     => 'out',
            'quantity' => fake()->numberBetween(1, 5),
            'reason'   => 'Customer order — ' . fake()->bothify('ORD-####'),
        ]);
    }
}
