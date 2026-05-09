<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ProductVariant>
 */
class ProductVariantFactory extends Factory
{
    protected $model = ProductVariant::class;

    public function definition(): array
    {
        $cost       = $this->faker->randomFloat(2, 50, 500);   // 50.00 – 500.00
        $salesPrice = $cost + $this->faker->randomFloat(2, 10, 200);

        return [
            'product_id'  => Product::factory(),
            'name'        => $this->faker->words(1, true),      // e.g. "Red / Large"
            'sku'         => $this->faker->unique()->bothify('SKU-####'),
            'barcode'     => $this->faker->unique()->ean13(),
            'cost'        => $cost,
            'sales_price' => $salesPrice,
            'stock'       => $this->faker->numberBetween(0, 200),
            'min_stock'   => $this->faker->numberBetween(0, 20),
            'is_default'  => false,
            'visible'     => true,
            'image'       => 'default/variants.jpg',
        ];
    }

    public function default(): self
    {
        return $this->state(fn () => ['is_default' => true]);
    }
}
