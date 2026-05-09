<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

#[Fillable(['name'])]
class Tag extends Model
{
    protected $primaryKey = 'uniqid';

    public function images(): BelongsToMany
    {
        return $this->belongsToMany(
            Image::class,
            'image_tag',
            'tag_uniqid',
            'image_uniqid',
            'uniqid',
            'uniqid',
        );
    }
}
