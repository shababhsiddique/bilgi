<?php

namespace Database\Factories;

use App\Models\Customer;
use App\Models\Order;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Order>
 */
class OrderFactory extends Factory
{
    protected $model = Order::class;

    public function definition(): array
    {
        $subtotal        = $this->faker->randomFloat(2, 500, 5000);
        $discountAmount  = $this->faker->randomFloat(2, 0, $subtotal * 0.3);
        $shippingAmount  = $this->faker->randomFloat(2, 50, 500);
        $totalAmount     = max(0, $subtotal - $discountAmount + $shippingAmount);

        $status = $this->faker->randomElement([
            'pending',
            'processing',
            'on_hold',
            'completed',
            'cancelled',
            'refunded',
            'failed',
        ]);

        $paymentStatus = $this->faker->randomElement([
            'unpaid',
            'pending',
            'paid',
            'refunded',
            'failed',
        ]);

        return [
            'customer_id'      => Customer::factory(),
            'order_number'     => 'ORD-' . $this->faker->unique()->numerify('########'),
            'status'           => $status,
            'subtotal'         => $subtotal,
            'discount_amount'  => $discountAmount,
            'shipping_amount'  => $shippingAmount,
            'total_amount'     => $totalAmount,
            'payment_method_id' => 1,
            'payment_status'   => $paymentStatus,
            'payment_note'     => $this->faker->boolean(30) ? $this->faker->sentence() : null,
            'notes'            => $this->faker->boolean(40) ? $this->faker->sentence() : null,
            'seller_note'      => $this->faker->boolean(20) ? $this->faker->sentence() : null,
            'placed_at'        => $this->faker->dateTimeBetween('-3 months', 'now'),
        ];
    }
}
