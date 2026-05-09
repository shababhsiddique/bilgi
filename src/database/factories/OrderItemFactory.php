<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\OrderItem>
 */
class OrderItemFactory extends Factory
{
    protected $model = OrderItem::class;

    public function definition(): array
    {
        // Ensure we have related models
        $product = Product::inRandomOrder()->first() ?? Product::factory()->create();
        $variant = ProductVariant::where('product_id', $product->id)
            ->inRandomOrder()
            ->first() ?? ProductVariant::factory()->create(['product_id' => $product->id]);

        $quantity   = $this->faker->numberBetween(1, 5);
        $unitPrice  = $this->faker->numberBetween(100, 10000); // in cents
        $lineTotal  = $unitPrice * $quantity;

        return [
            'order_id'           => Order::factory(), // can be overridden when seeding
            'product_id'         => $product->id,
            'product_variant_id' => $variant->id,
            'product_name'       => $product->name,
            'variant_name'       => $variant->name ?? $this->faker->word(),
            'sku'                => $variant->sku ?? $this->faker->unique()->bothify('SKU-####'),
            'barcode'            => $variant->barcode ?? $this->faker->unique()->ean13(),
            'quantity'           => $quantity,
            'unit_price'         => $unitPrice,
            'line_total'         => $lineTotal,
        ];
    }
}
