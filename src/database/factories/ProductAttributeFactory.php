<?php

namespace Database\Factories;

use App\Models\ProductAttribute;
use App\Models\ProductVariant;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductAttributeFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var class-string<\App\Models\ProductAttribute>
     */
    protected $model = ProductAttribute::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Common attribute types for products
        $attributes = [
            'Color' => ['Red', 'Blue', 'Green', 'Black', 'White', 'Yellow', 'Purple', 'Orange', 'Pink', 'Gray'],
            'Size' => ['XS', 'S', 'M', 'L', 'XL', 'XXL', '32', '34', '36', '38', '40', '42'],
            'Material' => ['Cotton', 'Polyester', 'Wool', 'Silk', 'Denim', 'Leather', 'Plastic', 'Metal', 'Wood'],
            'Brand' => ['Nike', 'Adidas', 'Samsung', 'Apple', 'Sony', 'LG', 'Generic'],
            'Weight' => ['100g', '250g', '500g', '1kg', '2kg', '5kg'],
            'Dimensions' => ['10x10cm', '20x15cm', '30x25cm', '50x40cm'],
            'Power' => ['100W', '200W', '500W', '1000W'],
            'Connectivity' => ['WiFi', 'Bluetooth', 'USB', 'Wireless', 'Wired'],
            'Storage' => ['16GB', '32GB', '64GB', '128GB', '256GB', '512GB', '1TB']
        ];

        $attributeName = $this->faker->randomElement(array_keys($attributes));
        $attributeValue = $this->faker->randomElement($attributes[$attributeName]);

        return [
            'product_variant_id' => ProductVariant::factory(),
            'attribute_name' => $attributeName,
            'attribute_value' => $attributeValue,
            'visible' => $this->faker->boolean(85), // 85% chance of being visible
        ];
    }

    /**
     * Create attributes for specific attribute types
     */
    public function color(): static
    {
        return $this->state(fn (array $attributes) => [
            'attribute_name' => 'Color',
            'attribute_value' => $this->faker->randomElement(['Red', 'Blue', 'Green', 'Black', 'White', 'Yellow', 'Purple', 'Orange', 'Pink', 'Gray']),
        ]);
    }

    public function size(): static
    {
        return $this->state(fn (array $attributes) => [
            'attribute_name' => 'Size',
            'attribute_value' => $this->faker->randomElement(['XS', 'S', 'M', 'L', 'XL', 'XXL', '32', '34', '36', '38', '40', '42']),
        ]);
    }

    public function material(): static
    {
        return $this->state(fn (array $attributes) => [
            'attribute_name' => 'Material',
            'attribute_value' => $this->faker->randomElement(['Cotton', 'Polyester', 'Wool', 'Silk', 'Denim', 'Leather', 'Plastic', 'Metal', 'Wood']),
        ]);
    }

    public function ageGroup(): static
    {
        return $this->state(fn (array $attributes) => [
            'attribute_name' => 'Age Group',
            'attribute_value' => $this->faker->randomElement(['0-2 years', '3-5 years', '6-8 years', '9-12 years', '13+ years', 'Adult']),
        ]);
    }

    /**
     * Make the attribute invisible
     */
    public function invisible(): static
    {
        return $this->state(fn (array $attributes) => [
            'visible' => false,
        ]);
    }

    /**
     * Make the attribute visible
     */
    public function visible(): static
    {
        return $this->state(fn (array $attributes) => [
            'visible' => true,
        ]);
    }
}
