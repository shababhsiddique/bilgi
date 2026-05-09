<?php

namespace Database\Seeders;

use App\Models\Material;
use Illuminate\Database\Seeder;

class MaterialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i = 1; $i <= 100; $i++) {
            Material::create([
                'source_id' => 'SRC' . str_pad((string) $i, 3, '0', STR_PAD_LEFT),
                'material_key' => 'material_' . str_pad((string) $i, 3, '0', STR_PAD_LEFT),
                'displayname' => 'Material ' . $i,
                'description' => 'Seeded material #' . $i,
                'quantity' => 200,
            ]);
        }
    }
}
