<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $fillable = [
        'user_id',
        'product_id',
        'quantity',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // Helper Methods
    public function getSubtotalAttribute()
    {
        return $this->product->price * $this->quantity;
    }

    // Scopes
    public function scopeGroupedByStore($query)
    {
        return $query->with(['product.store', 'product.productImages'])
            ->get()
            ->groupBy(function ($cart) {
                return $cart->product->store_id;
            });
    }
}
