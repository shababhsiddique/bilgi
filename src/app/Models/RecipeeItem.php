<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;

class RecipeeItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'recipee_id',
        'material_key',
        'quantity',
    ];

    public function recipee(): BelongsTo
    {
        return $this->belongsTo(Recipee::class);
    }

    public function material(): BelongsTo
    {
        return $this->belongsTo(Material::class, 'material_key', 'material_key');
    }
}
