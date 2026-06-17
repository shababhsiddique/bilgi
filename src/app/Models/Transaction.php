<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Transaction extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'uuid',
        'transaction_id',
        'invoice',
        'type',
        'category_id',
        'wallet',
        'amount',
        'notes',
        'transaction_date',
    ];

    protected $casts = [
        'amount'           => 'decimal:2',
        'transaction_date' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::creating(function (Transaction $transaction): void {
            if (empty($transaction->uuid)) {
                $transaction->uuid = (string) Str::uuid();
            }
        });
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(TransactionCategory::class, 'category_id');
    }
}
