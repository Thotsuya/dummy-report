<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => random_int(1, 10),
            'total' => random_int(100, 100000),
            'amount_of_products' => random_int(1, 50),
            'purchase_date' => now()->subDays(random_int(1, 1460)),
            'payment_method' => $this->faker->randomElement(['credit_card', 'paypal', 'bank_transfer']),
            'order_status' => $this->faker->randomElement(['pending', 'shipped', 'delivered', 'canceled']),
            'discount_applied' => fake()->randomElement([0, 5, 10, 15, 20]),
            'shipping_cost' => random_int(0, 100),
            'delivery_date' => now()->subDays(random_int(1, 30)),
            'product_category' => $this->faker->randomElement(['electronics', 'clothing', 'books', 'home', 'sports', 'toys', 'beauty', 'food', 'other']),
        ];
    }
}
