<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use App\Models\Rental;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Review>
 */
class ReviewFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'rating'=> fake()->numberBetween(1, 5),
            'comment'=> fake()->sentence(), // https://github.com/fzaninotto/Faker#formatters
            'user_id'=> User::inRandomOrder()->first()->id, // https://laravel.com/docs/12.x/queries#random-ordering
            'rental_id'=> Rental::inRandomOrder()->first()->id,
        ];
    }
}
