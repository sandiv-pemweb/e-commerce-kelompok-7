<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;


    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];


    protected $hidden = [
        'password',
        'remember_token',
    ];


    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }


    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }


    public function isMember(): bool
    {
        return $this->role === 'member';
    }


    public function isSeller(): bool
    {
        if ($this->relationLoaded('store')) {
            return $this->store !== null && $this->store->is_verified;
        }


        return $this->store()->where('is_verified', true)->exists();
    }


    public function hasVerifiedStore(): bool
    {
        return $this->isSeller();
    }


    public function hasStore(): bool
    {
        return $this->relationLoaded('store')
            ? $this->store !== null
            : $this->store()->exists();
    }


    public function store()
    {
        return $this->hasOne(Store::class);
    }


    public function buyer()
    {
        return $this->hasOne(Buyer::class);
    }


    public function wishlists()
    {
        return $this->hasMany(Wishlist::class);
    }
}
