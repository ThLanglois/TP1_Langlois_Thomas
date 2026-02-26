<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use App\Models\Equipment;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Rental>
 */
class RentalFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $start = fake()->dateTimeBetween('-1 year', 'now'); // https://github.com/fzaninotto/Faker#formatters

        return [
            'start_date'=> $start,
            'end_date'=>fake()->dateTimeBetween($start, '+10 days'),
            'total_price'=> fake()->randomFloat(2, 20, 500),
            'user_id'=> User::inRandomOrder()->first()->id, // https://laravel.com/docs/12.x/queries#random-ordering
            'equipment_id'=> Equipment::inRandomOrder()->first()->id,
        ];
    }
}
