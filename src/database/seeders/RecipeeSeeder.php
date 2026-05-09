<?php

namespace Database\Seeders;

use App\Models\ProductVariant;
use App\Models\Recipee;
use App\Models\RecipeeItem;
use Illuminate\Database\Seeder;

class
RecipeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $variantIds = ProductVariant::query()
            ->orderBy('id')
            ->limit(3)
            ->pluck('id')
            ->values()
            ->all();

        $recipees = [
            [
                'name' => 'Recipee A',
                'description' => 'Seeded recipee A',
                'product_variant_id' => $variantIds[0] ?? null,
                'items' => [
                    ['material_key' => 'material_001', 'quantity' => 10],
                    ['material_key' => 'material_002', 'quantity' => 5],
                    ['material_key' => 'material_003', 'quantity' => 8],
                ],
            ],
            [
                'name' => 'Recipee B',
                'description' => 'Seeded recipee B',
                'product_variant_id' => $variantIds[1] ?? null,
                'items' => [
                    ['material_key' => 'material_004', 'quantity' => 12],
                    ['material_key' => 'material_005', 'quantity' => 7],
                    ['material_key' => 'material_006', 'quantity' => 3],
                ],
            ],
            [
                'name' => 'Recipee C',
                'description' => 'Seeded recipee C',
                'product_variant_id' => $variantIds[2] ?? null,
                'items' => [
                    ['material_key' => 'material_007', 'quantity' => 6],
                    ['material_key' => 'material_008', 'quantity' => 9],
                    ['material_key' => 'material_009', 'quantity' => 4],
                ],
            ],
        ];

        foreach ($recipees as $data) {
            $recipee = Recipee::create([
                'name' => $data['name'],
                'description' => $data['description'],
                'product_variant_id' => $data['product_variant_id'],
            ]);

            foreach ($data['items'] as $item) {
                RecipeeItem::create([
                    'recipee_id' => $recipee->id,
                    'material_key' => $item['material_key'],
                    'quantity' => $item['quantity'],
                ]);
            }
        }
    }
}
