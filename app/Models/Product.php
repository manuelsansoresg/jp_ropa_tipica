<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    protected $fillable = ['category_id', 'name', 'slug', 'short_description', 'description', 'price', 'featured', 'active', 'sort_order', 'material', 'colors', 'availability'];

    protected function casts(): array
    {
        return ['featured' => 'boolean', 'active' => 'boolean', 'price' => 'decimal:2', 'colors' => 'array'];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class)->orderBy('sort_order');
    }

    public function sizes(): HasMany
    {
        return $this->hasMany(Size::class);
    }

    public function getPrimaryImageAttribute(): string
    {
        return $this->images->first()?->image ?? '/images/editorial/textura-lino.jpg';
    }
}
