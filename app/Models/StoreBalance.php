<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class StoreBalance extends Model
{

    protected $fillable = [
        'store_id',
        'balance',
    ];

    protected $casts = [
        'balance' => 'decimal:2'
    ];

    // relationships one store balance belongs to one store
    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function storeBalanceHistories()
    {
        return $this->hasMany(StoreBalanceHistory::class);
    }

    public function withdrawals()
    {
        return $this->hasMany(Withdrawal::class);
    }
}