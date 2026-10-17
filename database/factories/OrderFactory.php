<?php

namespace Database\Factories;

use App\Models\Order;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    protected $model = Order::class;

    public function definition()
    {
        return [
            'customer_name' => $this->faker->name,
            'customer_email' => $this->faker->safeEmail,
            'customer_phone' => $this->faker->phoneNumber,
            'customer_address' => $this->faker->address,
            'total_amount' => $this->faker->randomFloat(2, 20, 500),
            'payment_status' => 'paid',
            'payment_method' => 'stripe',
            'status' => 'processing',
        ];
    }
}

