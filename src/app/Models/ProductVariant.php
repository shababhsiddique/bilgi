<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductVariant extends Model
{
    use HasFactory, softDeletes;

    protected $table = 'product_variants';

    protected $fillable = [
        'product_id',
        'name',
        'sku',
        'barcode',
        'cost',
        'sales_price',
        'stock',
        'min_stock',
        'is_default',
        'visible',
        'image',
    ];

    protected $casts = [
        'cost'        => 'decimal:2',
        'sales_price' => 'decimal:2',
        'is_default'  => 'boolean',
        'visible'     => 'boolean',
    ];

    /**
     * Relationship: ProductItem belongs to Product.
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function productAttributes()
    {
        return $this->hasMany(ProductAttribute::class);
    }

}
