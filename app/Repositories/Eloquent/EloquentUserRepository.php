<?php

namespace App\Repositories\Eloquent;

use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;

class EloquentUserRepository implements UserRepositoryInterface
{
    public function find(int $id): ?User
    {
        return User::find($id);
    }

    public function update(User $user, array $data): User
    {
        $user->fill($data);
        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }
        $user->save();
        return $user;
    }

    public function delete(User $user): bool
    {
        return $user->delete();
    }

    public function getAll(array $filters = [], int $perPage = 20)
    {
        $query = User::with('store')->latest();

        if (isset($filters['q']) && $filters['q']) {
            $search = $filters['q'];
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if (isset($filters['role']) && $filters['role']) {
            if ($filters['role'] === 'admin') {
                $query->where('role', 'admin');
            } elseif ($filters['role'] === 'seller') {
                $query->whereHas('store');
            } elseif ($filters['role'] === 'buyer') {
                $query->where('role', 'member')->whereDoesntHave('store');
            }
        }

        return $query->paginate($perPage);
    }
}
