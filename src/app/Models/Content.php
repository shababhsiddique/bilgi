<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Content extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'content_key',
        'content_data',
        'product_id',
        'is_active',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get the product that owns the content.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Scope a query to only include active contents.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to filter by content key.
     */
    public function scopeByKey($query, string $key)
    {
        return $query->where('content_key', $key);
    }

    /**
     * Get a single content by key.
     *
     * @param string $key
     * @param bool $activeOnly Whether to only return active content
     * @return static|null
     */
    public static function key(string $key, bool $activeOnly = true): ?static
    {
        $query = static::byKey($key)->with('product');

        if ($activeOnly) {
            $query->active();
        }

        return $query->first();
    }

    /**
     * Get content data by key, return default if not found.
     *
     * @param string $key
     * @param mixed $default
     * @param bool $activeOnly
     * @return mixed
     */
    public static function getContentData(string $key, $default = null, bool $activeOnly = true)
    {
        $content = static::key($key, $activeOnly);

        return $content ? $content->content_data : $default;
    }
}
