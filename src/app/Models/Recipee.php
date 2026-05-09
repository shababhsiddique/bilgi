<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class Recipee extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'product_variant_id',
    ];

    public function items(): HasMany
    {
        return $this->hasMany(RecipeeItem::class);
    }

    public function productVariant(): BelongsTo
    {
        return $this->belongsTo(ProductVariant::class);
    }

    public function craft(): void
    {
        DB::transaction(function () {
            $this->loadMissing('items', 'productVariant');

            if (! $this->productVariant) {
                throw ValidationException::withMessages([
                    'craft' => 'No product variant is attached to this recipee.',
                ]);
            }

            foreach ($this->items as $item) {
                $material = Material::query()
                    ->where('material_key', $item->material_key)
                    ->lockForUpdate()
                    ->first();

                if (! $material) {
                    throw ValidationException::withMessages([
                        'craft' => "Material not found for key: {$item->material_key}.",
                    ]);
                }

                if ((int) $material->quantity < (int) $item->quantity) {
                    throw ValidationException::withMessages([
                        'craft' => "Not enough quantity for material {$item->material_key}.",
                    ]);
                }

                $material->decrement('quantity', (int) $item->quantity);
            }

            $variant = ProductVariant::query()
                ->whereKey($this->product_variant_id)
                ->lockForUpdate()
                ->first();

            if (! $variant) {
                throw ValidationException::withMessages([
                    'craft' => 'Attached product variant was not found.',
                ]);
            }

            $variant->increment('stock', 1);
        });
    }
}
