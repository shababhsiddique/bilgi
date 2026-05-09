<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
    use HasFactory;

    protected $fillable = [
        'source_id',
        'material_key',
        'displayname',
        'description',
        'quantity',
    ];

    public function recipeeItems(): HasMany
    {
        return $this->hasMany(RecipeeItem::class, 'material_key', 'material_key');
    }
}
