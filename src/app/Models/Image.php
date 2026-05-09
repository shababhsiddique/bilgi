<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

#[Fillable(['url', 'visible'])]
class Image extends Model
{
    protected $primaryKey = 'uniqid';

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(
            Tag::class,
            'image_tag',
            'image_uniqid',
            'tag_uniqid',
            'uniqid',
            'uniqid',
        );
    }
}
