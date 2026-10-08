<?php

namespace App\Models;

use App\Models\Currency;
use App\Models\Category;
use App\Models\ProductImage;
use App\Models\Review;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Product extends Model
{
    protected $fillable = [
        'name_en',
        'name_mm',
        'description_en',
        'description_mm',
        'price_usd',
        'category_id',
        'stock',
        'reserved_stock',
        'eco_badge',
        'eco_badge_mm',
        'created_by',
        'updated_by',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }

    public function primaryImage()
    {
        return $this->hasOne(ProductImage::class)->where('is_primary', true);
    }

    public function getDisplayNameAttribute(): string
    {
        return app()->getLocale() === 'mm' && $this->name_mm
            ? $this->name_mm
            : $this->name_en;
    }

    public function getDisplayDescriptionAttribute(): ?string
    {
        return app()->getLocale() === 'mm' && $this->description_mm
            ? $this->description_mm
            : $this->description_en;
    }

    public function getDisplayEcoBadgeAttribute(): ?string
    {
        return app()->getLocale() === 'mm' && $this->eco_badge_mm
            ? $this->eco_badge_mm
            : $this->eco_badge;
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function wishlistedBy()
    {
        return $this->hasMany(Wishlist::class);
    }

    protected $appends = ['isInWishlist', 'price_mmk'];

    public function getIsInWishlistAttribute()
    {
        if (!Auth::check()) {
            return false;
        }

        return $this->wishlistedBy()
            ->where('user_id', Auth::id())
            ->exists();
    }

    public function getPriceMmkAttribute()
    {
        static $rate = null;

        if ($rate === null) {
            $rate = Currency::where('code', 'MMK')
                ->where('is_active', true)
                ->value('rate') ?? 0;
        }

        return round($this->price_usd * $rate, 2);
    }

    public function isNew($days = 7)
    {
        return $this->created_at->gte(now()->subDays($days));
    }

    /**
     * Only active products
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * In stock products
     */
    public function scopeInStock($query)
    {
        return $query->whereRaw('stock - reserved_stock > 0');
    }

    /**
     * With review stats
     */
    public function scopeWithReviewStats($query)
    {
        return $query->withAvg('reviews', 'rating')
                    ->withCount('reviews');
    }

    /**
     * Featured (highest rated)
     */
    public function scopeFeatured($query)
    {
        return $query->active()
                    ->inStock()
                    ->whereHas('reviews')
                    ->withReviewStats()
                    ->orderByDesc('reviews_avg_rating')
                    ->orderByDesc('reviews_count');
    }

    public function getAvailableStockAttribute()
    {
        return max(0, $this->stock - $this->reserved_stock);
    }
}
