<?php

namespace Database\Factories;

use App\Models\MerchSale;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\MerchSale>
 */
class MerchSaleFactory extends Factory
{
    protected $model = MerchSale::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $items = array("Book", "Pen", "Cup", "Bag", "Glass", "T-Shirt");
        $prices = array(50, 20, 35, 60, 45, 70);

        $idx = fake()->numberBetween(0, 5);

        return [
            'name' => fake()->name(),
            'item_name' => $items[$idx],
            'amount' => fake()->randomDigitNotNull(),
            'price' => $prices[$idx],
            'read' => 0,
            'created_at' => fake()->dateTimeBetween('-3 months', 'now')
        ];
    }
}
