<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Product;

class ProductFactory extends Factory
{
    protected $model = Product::class; // Ensure this line exists

    public function definition()
    {
        return [
            'name' => $this->faker->word(),
            'hsincode' => $this->faker->numerify('######'),
            'category' => $this->faker->randomElement(['Electronics', 'Fashion', 'Home Appliances']),
            'sub_category' => $this->faker->randomElement(['Mobile Phones', 'Clothing', 'Kitchen']),
            'description' => $this->faker->sentence(),
            'brand' => $this->faker->company(),
            'design' => $this->faker->randomElement(['Modern', 'Classic', 'Sleek']),
            'price' => $this->faker->randomFloat(2, 10, 1000),
            'stocks' => $this->faker->numberBetween(1, 500),
            'unit' => $this->faker->randomElement(['piece', 'box', 'set']),
            'varient' => $this->faker->randomElement(['Red', 'Black', 'White']),
            'status' => $this->faker->boolean(),
            'test1' => $this->faker->text(50),
            'test2' => $this->faker->text(50),
        ];
    }
}
