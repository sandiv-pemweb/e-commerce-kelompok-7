<?php

namespace App\Services;

use App\Models\Store;
use App\Repositories\Contracts\StoreRepositoryInterface;
use Illuminate\Support\Facades\Storage;
use Exception;

class StoreService
{
    public function __construct(
        protected StoreRepositoryInterface $storeRepository
    ) {}

    public function getStoreBySlug(string $slug): ?Store
    {
        return $this->storeRepository->findBySlug($slug);
    }

    public function getUserStore(int $userId, bool $withTrashed = false): ?Store
    {
        return $this->storeRepository->findByUser($userId, $withTrashed);
    }

    public function createStore(int $userId, array $data, $logoFile = null): Store
    {
        if (isset($data['logo'])) {
            unset($data['logo']);
        }
        
        $data['user_id'] = $userId; // Ensure user_id is set
        $data['is_verified'] = false;
        
        if (empty($data['address_id'])) {
            $data['address_id'] = 'ADDR-' . strtoupper(uniqid());
        }

        if ($logoFile && $logoFile->isValid()) {
            $data['logo'] = $logoFile->store('store-logos', 'public');
        } else {
             throw new Exception('Logo toko harus diunggah dan harus berupa file gambar yang valid.');
        }

        return $this->storeRepository->create($data);
    }

    public function updateStore(Store $store, array $data, $logoFile = null): Store
    {
        if ($logoFile && $logoFile->isValid()) {
            if ($store->logo && Storage::disk('public')->exists($store->logo)) {
                Storage::disk('public')->delete($store->logo);
            }
            $data['logo'] = $logoFile->store('store-logos', 'public');
        }

        return $this->storeRepository->update($store, $data);
    }

    public function deleteStore(Store $store): bool
    {
        return $this->storeRepository->delete($store);
    }

    public function restoreStore(int $userId): bool
    {
        $store = $this->storeRepository->findByUser($userId, true);
        
        if (!$store || !$store->trashed()) {
             throw new Exception('Tidak ada toko yang dapat dipulihkan.');
        }

        return $this->storeRepository->restore($store);
    }

    public function verifyStore(Store $store): void
    {
        if ($store->is_verified) {
             throw new Exception('Toko sudah terverifikasi.');
        }

        $store->update(['is_verified' => true]);

        // Create balance if not exists (though FinanceService handles this, verifying usually inits it)
        $store->storeBalance()->firstOrCreate(['balance' => 0]);
    }
}
