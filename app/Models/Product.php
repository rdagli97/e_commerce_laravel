<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'price',
        'category',
        'rate',
        'discount',
        'online',
        'image',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function favourites(): HasMany
    {
        return $this->hasMany(Favourite::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function productImages(): HasMany
    {
        return $this->hasMany(ProductImage::class);
    }
}
