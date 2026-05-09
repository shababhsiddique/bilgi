<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductMedia extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     */
    protected $table = 'product_medias';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'product_id',
        'media_url',
        'media_type',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'product_id' => 'integer',
    ];

    /**
     * The product that this image belongs to.
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
