<?php

namespace Database\Factories;

use App\Models\Donation;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Donation>
 */
class DonationFactory extends Factory
{
    protected $model = Donation::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $currencies = ["USD", "CAD"];

        return [
            'name' => fake()->name(),
            'amount' => fake()->randomFloat(2, 1, 100000),
            'currency' => $currencies[array_rand($currencies)],
            'message' => fake()->sentence(),
            'read' => 0,
            'created_at' => fake()->dateTimeBetween('-3 months', 'now')
        ];
    }
}
