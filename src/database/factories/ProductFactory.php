<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Condition;
use App\Models\User;


class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name,
            'price' => $this->faker->numberBetween(1, 10000),
            'product_image' => $this->faker->unique()->lexify('????') . '.jpg',
            'brand' => $this->faker->word,
            'content' => $this->faker->text(255),
            'condition_id' => Condition::factory(),
            'user_id' => User::factory()

        ];
    }
}
