<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory;
    use SoftDeletes;

    /**
     * The table associated with the model.
     */
    protected $table = 'products';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'product_type',
        'age_group',
        'track_inventory',
        'cost',
        'sales_price',
        'sales_tax_percent',
        'name',
        'description',
        'long_description',
        'notes',
        'barcode',
        'sku',
        'visible',
        'ribbon_text',
        'thumbnail',
        'weight',
        'volume',
        'stock',
        'min_stock',
        'is_digital',
        'slug',
        'brand',
        'sort_order',
        'meta_title',
        'meta_description',
        'meta_keywords',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'track_inventory'   => 'boolean',
        'visible'           => 'boolean',
        'is_digital'        => 'boolean',
        'cost'              => 'decimal:2',
        'sales_price'       => 'decimal:2',
        'sales_tax_percent' => 'decimal:2',
        'weight'            => 'decimal:3',
        'volume'            => 'decimal:2',
        'stock'             => 'integer',
        'min_stock'         => 'integer',
        'sort_order'        => 'integer',
    ];

    /**
     * All variants of this product
     */
    public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }

    /**
     * Default variant relation:
     * - Prefer the variant with is_default = true
     * - If none, fall back to the first variant (by id).
     *
     * Use: $product->defaultVariant (relation) or $product->default (accessor).
     */
    public function defaultVariant()
    {
        return $this->hasOne(ProductVariant::class)
            ->orderByDesc('is_default')  // true (1) first, false (0) after
            ->orderBy('id');             // within each group, pick the earliest
    }

    /**
     * Accessor that always returns a single variant instance or null.
     * Prefer the eager-loaded relation if available.
     */
    public function getDefaultAttribute()
    {
        // If the relation is already loaded (via with('defaultVariant')) use it
        if ($this->relationLoaded('defaultVariant')) {
            return $this->getRelation('defaultVariant');
        }

        // Otherwise, query it on demand
        return $this->defaultVariant()->first();
    }

    /**
     * All product attributes through product variants.
     */
    public function attributes()
    {
        return $this->hasManyThrough(ProductAttribute::class, ProductVariant::class);
    }

    /**
     * Get visible attributes for the current selected variant.
     */
    public function getVisibleAttributesForVariant($variantId)
    {
        return $this->attributes()
            ->where('product_variant_id', $variantId)
            ->where('visible', true)
            ->get();
    }

    /**
     * Get all visible attributes across all variants (unique by attribute_name).
     */
    public function getUniqueVisibleAttributes()
    {
        return $this->attributes()
            ->where('visible', true)
            ->get()
            ->unique('attribute_name');
    }

    /**
     * All categories via the pivot table product_categories.
     */
    public function categories()
    {
        return $this->belongsToMany(Category::class, 'product_categories')
            ->withTimestamps();
    }

    /**
     * Product medias.
     */
    public function medias()
    {
        return $this->hasMany(ProductMedia::class);
    }

    /**
     * Get the variant with the lowest price for this product.
     */
    public function getLowestPriceVariantAttribute()
    {
        // If variants are already loaded, use them
        if ($this->relationLoaded('variants')) {
            return $this->variants->sortBy('sales_price')->first();
        }

        // Otherwise, query for the lowest price variant
        return $this->variants()->orderBy('sales_price', 'asc')->first();
    }

    public function getLowestPriceVariantPriceAttribute()
    {
        // If variants are already loaded, use them
        if ($this->relationLoaded('variants')) {
            return $this->variants->sortBy('sales_price')->first()->sales_price;
        }

        // Otherwise, query for the lowest price variant
        return $this->variants()->orderBy('sales_price', 'asc')->first()->sales_price;
    }

    /**
     * Get the variant with the highest price for this product.
     */
    public function getHighestPriceVariantAttribute()
    {
        // If variants are already loaded, use them
        if ($this->relationLoaded('variants')) {
            return $this->variants->sortByDesc('sales_price')->first();
        }

        // Otherwise, query for the highest price variant
        return $this->variants()->orderBy('sales_price', 'desc')->first();
    }

    public function getRibbonClassesAttribute(): array
    {
        $ribbon = strtolower($this->ribbon_text);

        return match ($ribbon) {
            'new' => ['bg-emerald-100', 'text-emerald-600'],
            'sale' => ['bg-rose-100', 'text-rose-600'],
            default => ['bg-sky-100', 'text-sky-600'],
        };
    }

}
