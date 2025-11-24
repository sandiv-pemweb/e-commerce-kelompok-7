<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Check if user is an admin
     * 
     * @return bool
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Check if user is a member (default role)
     * 
     * @return bool
     */
    public function isMember(): bool
    {
        return $this->role === 'member';
    }
    
    /**
     * Check if user is a verified seller
     * More efficient: checks if store relationship is loaded first
     * 
     * @return bool
     */
    public function isSeller(): bool
    {
        // If store is already loaded, use it
        if ($this->relationLoaded('store')) {
            return $this->store !== null && $this->store->is_verified;
        }
        
        // Otherwise, check existence and verification in one query
        return $this->store()->where('is_verified', true)->exists();
    }

    /**
     * Check if user has a verified store (alias for isSeller)
     * 
     * @return bool
     */
    public function hasVerifiedStore(): bool
    {
        return $this->isSeller();
    }

    /**
     * Check if user has a store (verified or not)
     * 
     * @return bool
     */
    public function hasStore(): bool
    {
        return $this->relationLoaded('store') 
            ? $this->store !== null 
            : $this->store()->exists();
    }

    /**
     * Get the store owned by this user
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function store()
    {
        return $this->hasOne(Store::class);
    }

    /**
     * Get the buyer profile for this user
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function buyer()
    {
        return $this->hasOne(Buyer::class);
    }
}
