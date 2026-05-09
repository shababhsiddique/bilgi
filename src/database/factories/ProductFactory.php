<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Product>
 */
class ProductFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var class-string<\App\Models\Product>
     */
    protected $model = Product::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = $this->faker->unique()->words(1, true);

        return [
            'product_type'       => $this->faker->randomElement(['goods', 'service']),
            'track_inventory'    => $this->faker->boolean(80),

            /*'cost'               => $this->faker->randomFloat(2, 1, 100),
            'sales_price'        => $this->faker->randomFloat(2, 5, 150),*/
            'sales_tax_percent'  => $this->faker->randomElement([0, 5, 10, 15]),

            'name'               => ucfirst($name),
            'description'        => $this->faker->paragraph(),
            'long_description'    => $this->faker->paragraphs(3, true),
            'notes'              => $this->faker->optional()->sentence(),

            /*'barcode'            => $this->faker->optional()->ean13(),
            'sku'                => strtoupper(Str::random(8)),*/

            'visible'            => $this->faker->boolean(90),
            'ribbon_text'        => $this->faker->optional()->randomElement(['New', 'Sale', 'Limited', 'Bestseller']),

            // Example image path; adjust to match your storage setup if needed
            'thumbnail'          => 'default/product.png',

            /*'weight'             => $this->faker->optional()->randomFloat(3, 0.1, 10),
            'volume'             => $this->faker->optional()->randomFloat(2, 0.1, 5),*/

            /*'stock'              => $this->faker->numberBetween(0, 500),
            'min_stock'          => $this->faker->numberBetween(0, 50),*/

            'is_digital'         => $this->faker->boolean(20),

            'slug'               => Str::slug($name) . '-' . $this->faker->unique()->numberBetween(1, 9999),
            'brand'              => $this->faker->optional()->company(),
            'sort_order'         => $this->faker->numberBetween(0, 1000),

            'meta_title'         => $this->faker->optional()->sentence(6),
            'meta_description'   => $this->faker->optional()->sentence(12),
            'meta_keywords'      => $this->faker->optional()->words(6, true),
        ];
    }
}
