<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{

    protected $fillable = [
        'store_id',
        'product_category_id',
        'name',
        'slug',
        'author',
        'publisher',
        'published_year',
        'description',
        'condition',
        'price',
        'discount_price',
        'weight',
        'stock',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'discount_price' => 'decimal:2',
    ];

    public function productImages()
    {
        return $this->hasMany(ProductImage::class);
    }

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function productCategory()
    {
        return $this->belongsTo(ProductCategory::class);
    }

    public function transactionDetails()
    {
        return $this->hasMany(TransactionDetail::class);
    }

    public function productReviews()
    {
        return $this->hasMany(ProductReview::class);
    }

    public function wishlists()
    {
        return $this->hasMany(Wishlist::class);
    }


    public function scopeAvailable($query)
    {
        return $query->where('stock', '>', 0);
    }


    public function getFormattedPriceAttribute()
    {
        return 'Rp ' . number_format((float) $this->price, 0, ',', '.');
    }

    public function getFormattedDiscountPriceAttribute()
    {
        return 'Rp ' . number_format((float) $this->discount_price, 0, ',', '.');
    }

    public function getDiscountPercentageAttribute()
    {
        if ($this->discount_price && $this->discount_price > 0 && $this->price > 0) {
            $discount = (($this->price - $this->discount_price) / $this->price) * 100;
            return round($discount);
        }
        return 0;
    }

    public function getFirstImageAttribute()
    {
        return $this->productImages->first()?->image ?? '/images/no-image.png';
    }

    public function getImageUrlAttribute()
    {
        return $this->productImages->first()?->image_url ?? asset('images/no-image.png');
    }

    public function getWishlistCountAttribute()
    {
        return $this->wishlists()->count();
    }

    public function getSoldCountAttribute()
    {

        return $this->transactionDetails()
            ->whereHas('transaction', function ($query) {
                $query->where('payment_status', 'paid');
            })
            ->sum('qty');
    }


    public function isAvailable($quantity = 1)
    {
        return $this->stock >= $quantity;
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }

    protected static function booted()
    {
        static::creating(function ($product) {
            if (empty($product->slug)) {
                $product->slug = \Illuminate\Support\Str::slug($product->name) . '-' . \Illuminate\Support\Str::random(5);
            }
        });
    }
}
