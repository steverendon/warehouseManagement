<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

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
    public function definition()
    {
        return [
            'category_id'   => Category::factory()->create(),
            'name'          => $this->faker->word(),
            'description'   => $this->faker->text(),
            'code'          => $this->faker->numberBetween(50000, 100000),
            'price'         => $this->faker->randomFloat(null, 2000, 10000),
            'due_date'      => $this->faker->date(),
            'batch'         => $this->faker->numberBetween(500, 1000)
        ];
    }
}
