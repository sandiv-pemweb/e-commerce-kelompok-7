<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductCategory extends Model
{

    protected $fillable = [
        'store_id',
        'parent_id',
        'image',
        'name',
        'slug',
        'tagline',
        'description',
    ];

    public function parent()
    {
        return $this->belongsTo(ProductCategory::class, 'parent_id', 'id');
    }
    public function children()
    {
        return $this->hasMany(ProductCategory::class, 'parent_id', 'id');
    } 

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
