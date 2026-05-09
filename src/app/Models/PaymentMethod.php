<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PaymentMethod extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'code',
        'type',
        'description',
        'icon',
        'sort_order',
        'is_active',
        'config',
        'payment_charge',
        'payment_charge_type',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'payment_charge' => 'decimal:2',
    ];

    /**
     * Scope: active payment methods only.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope: order by sort order ASC.
     */
    public function scopeSorted($query)
    {
        return $query->orderBy('sort_order', 'asc');
    }

    /**
     * Example: easy accessor for logo URL
     */
    public function getIconUrlAttribute()
    {
        return $this->icon
            ? asset('storage/' . $this->icon)
            : null;
    }
}
