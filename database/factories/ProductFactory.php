<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Category;

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
        return [
            'name' => $this->faker->word(),
            'description' => $this->faker->sentence(),
            'price' => $this->faker->randomFloat(2, 1, 100), // Precio entre 1 y 100
            'stock' => $this->faker->numberBetween(0, 50),
            'category_id' => Category::inRandomOrder()->first()->id,
            'status' => $this->faker->randomElement(['active', 'inactive']),
        ];
    }
}
