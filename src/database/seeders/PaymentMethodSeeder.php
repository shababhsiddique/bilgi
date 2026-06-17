<?php

namespace Database\Seeders;

use App\Models\PaymentMethod;
use Illuminate\Database\Seeder;

/**
 * Idempotent payment-method seeder. Safe to run on any environment
 * (including production) — it only creates methods that don't already
 * exist, matched by their `code`, and never touches other data.
 *
 *   php artisan db:seed --class=PaymentMethodSeeder
 */
class PaymentMethodSeeder extends Seeder
{
    public function run(): void
    {
        $methods = [
            [
                'code'        => 'cod',
                'name'        => 'Cash on Delivery',
                'type'        => 'cash',
                'description' => 'Pay with cash upon delivery.',
                'sort_order'  => 1,
            ],
            [
                'code'        => 'bkash',
                'name'        => 'bKash',
                'type'        => 'mobile_wallet',
                'description' => 'Pay securely using bKash mobile wallet.',
                'sort_order'  => 2,
            ],
            [
                'code'        => 'nagad',
                'name'        => 'Nagad',
                'type'        => 'mobile_wallet',
                'description' => 'Pay securely using Nagad mobile wallet.',
                'sort_order'  => 3,
            ],
        ];

        foreach ($methods as $method) {
            PaymentMethod::firstOrCreate(
                ['code' => $method['code']],
                array_merge($method, [
                    'icon'      => null,
                    'is_active' => true,
                    'config'    => null,
                ]),
            );
        }
    }
}
