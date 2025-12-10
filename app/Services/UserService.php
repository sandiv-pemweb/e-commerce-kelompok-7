<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;

class UserService
{
    public function __construct(
        protected UserRepositoryInterface $userRepository
    ) {}

    public function getUser(int $id): ?User
    {
        return $this->userRepository->find($id);
    }

    public function updateUser(User $user, array $data): User
    {
        return $this->userRepository->update($user, $data);
    }

    public function deleteUser(User $user): bool
    {
        return $this->userRepository->delete($user);
    }

    public function getUsers(array $filters = [], int $perPage = 20)
    {
        return $this->userRepository->getAll($filters, $perPage);
    }

    public function changeRole(User $user, string $role): User
    {
        if ($role === 'admin' && $user->store()->exists()) {
             throw new \Exception('Pengguna yang memiliki toko tidak dapat dijadikan Administrator.');
        }

        return $this->userRepository->update($user, ['role' => $role]);
    }
}
