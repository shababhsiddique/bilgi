<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\Category;
use App\Models\Customer;
use App\Models\CustomerAddress;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\PaymentMethod;
use App\Models\Product;
use App\Models\ProductAttribute;
use App\Models\ProductMedia;
use App\Models\ProductVariant;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1 user
        $user = Admin::factory()->create([
            'name' => 'Administrator',
            'email' => 'shabab.h.siddique@gmail.com',
            'password' => bcrypt('1234'),
        ]);

        // 2 categories
        $categories = Category::factory(4)->create();

        // 5 products
        $products = Product::factory(20)->create();

        // Attach products to random categories
        foreach ($products as $product) {
            $product->categories()->attach(
                $categories->random(rand(1, $categories->count()))->pluck('id')->toArray()
            );
        }

        // Create product media for each product
        foreach ($products as $product) {
            // Create 2-4 media entries per product
            ProductMedia::factory(rand(2, 4))->create([
                'product_id' => $product->id,
            ]);
        }

        // Create variants for each product
        $variants = collect();
        $products->each(function (Product $product) use (&$variants) {
            // one default + a couple of extra variants
            $defaultVariant = ProductVariant::factory()
                ->default()
                ->create(['product_id' => $product->id]);
            $variants->push($defaultVariant);

            $extraVariants = ProductVariant::factory(2)->create([
                'product_id' => $product->id,
            ]);
            $variants = $variants->merge($extraVariants);
        });

        // Create 5-10 product attributes for each variant
        $variants->each(function (ProductVariant $variant) {
            $attributeCount = rand(5, 10);

            // Create a mix of different attribute types to avoid duplicates
            $attributeTypes = ['color', 'size', 'material', 'ageGroup'];
            $createdAttributes = [];

            for ($i = 0; $i < $attributeCount; $i++) {
                // First 4 attributes use specific types, then random
                if ($i < 4) {
                    $factory = ProductAttribute::factory()->{$attributeTypes[$i]}();
                } else {
                    $factory = ProductAttribute::factory();
                }

                $attribute = $factory->create([
                    'product_variant_id' => $variant->id,
                ]);

                $createdAttributes[] = $attribute;
            }
        });

        // 2 customers
        $customers = Customer::factory(2)->create();

        // Create addresses for each customer
        $customers->each(function (Customer $customer) {
            // Shipping address
            $shipping = CustomerAddress::factory()
                ->default()
                ->create([
                    'customer_id' => $customer->id,
                ]);

            // Billing address
            $billing = CustomerAddress::factory()
                ->create([
                    'customer_id' => $customer->id,
                ]);

            // Optionally: ensure the customer has at least these two addresses
            $customer->setRelation('addresses', collect([$shipping, $billing]));
        });

        // bKash
        PaymentMethod::factory()->create([
            'name'        => 'bKash',
            'code'        => 'bkash',
            'type'        => 'mobile_wallet',
            'description' => 'Pay securely using bKash mobile wallet.',
            'icon'        => null,
            'sort_order'  => 2,
            'is_active'   => true,
            'config'      => "merchant_number:017XXXXXXXX ,app_key:XX,app_secret:xx",
        ]);

        // Create 2 orders with items, tied to existing customers and their addresses
        $orders = collect();

        for ($i = 0; $i < 2; $i++) {
            /** @var \App\Models\Customer $customer */
            $customer = $customers->random();

            // Fetch addresses for this customer
            $shippingAddress = CustomerAddress::where('customer_id', $customer->id)
                ->inRandomOrder()
                ->first();

            $billingAddress = CustomerAddress::where('customer_id', $customer->id)
                ->inRandomOrder()
                ->first();

            // Fallback if type column is nullable / not set yet
            if (! $shippingAddress) {
                $shippingAddress = CustomerAddress::where('customer_id', $customer->id)->inRandomOrder()->first();
            }
            if (! $billingAddress) {
                $billingAddress = CustomerAddress::where('customer_id', $customer->id)->inRandomOrder()->first();
            }

            $orders->push(
                Order::factory()->create([
                    'customer_id'         => $customer->id,
                    'shipping_address_id' => $shippingAddress?->id,
                    'billing_address_id'  => $billingAddress?->id,
                    'payment_method_id'      => 1,
                ])
            );
        }

        // Create items for each order
        foreach ($orders as $order) {
            OrderItem::factory(rand(1, 4))->create([
                'order_id' => $order->id,
            ]);
        }


        // Insert initial data
        DB::table('contents')->insert([
            'content_key' => 'shop_banner_data',
            'content_data' => json_encode([
                'subtitle' => 'New Collection',
                'title' => 'Kids Toy',
                'text' => 'Flat <span class="font-semibold text-rose-500">20% Off</span> on magnetic construction toys for your kids.'
            ]),
            'product_id' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('contents')->insert([
            'content_key' => 'hero_data',
            'content_data' => json_encode([
                'discount' => 'Get Up to <span class="text-base">25% Discount</span>',
                'subtitle' => 'All New Best Latest',
                'title' => 'Toy Collection',
                'button_text' => 'Shop Now',
                'text' => 'Discover colorful, safe and joyful toys for your little ones. Perfect for gifts, birthdays and everyday fun.'
            ]),
            'product_id' => 2,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
