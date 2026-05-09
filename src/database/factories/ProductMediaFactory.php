<?php

namespace Database\Factories;

use App\Models\ProductMedia;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ProductMedia>
 */
class ProductMediaFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ProductMedia::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'product_id' => null, // This will be set when creating the media
            'media_url' => $this->faker->randomElement([
                'default/product-image-3-min.jpg',
                'default/product-image-2-min.jpg'
            ]),
            'media_type' => 'image',
        ];
    }
}
