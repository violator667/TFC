<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Ticket>
 */
class TicketFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'ticket' => fake()->randomNumber(8),
            'user_id' => '3',
            'category_id' => rand(1,5),
            'status_id' => rand(1,3),
            'subject' => fake()->text(150),
            'description' => fake()->paragraph(),
        ];
    }
}
