<?php

namespace Database\Factories;

use App\Models\Subscriber as ModelsSubscriber;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class SubscriberFactory extends Factory
{
    protected $model = ModelsSubscriber::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->unique()->name(),
            'subscription_tier' => fake()->numberBetween(1, 3),
            'read' => 0,
            'created_at' => fake()->dateTimeBetween('-3 months', 'now')
        ];
    }
}
