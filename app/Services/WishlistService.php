<?php

namespace App\Services;

use App\Models\Wishlist;
use App\Repositories\Contracts\WishlistRepositoryInterface;

class WishlistService
{
    public function __construct(
        protected WishlistRepositoryInterface $wishlistRepository
    ) {}

    public function getUserWishlist(int $userId)
    {
        return $this->wishlistRepository->getByUser($userId);
    }

    public function toggleWishlist(int $userId, int $productId): array
    {
        $wishlist = $this->wishlistRepository->findByUserAndProduct($userId, $productId);
        
        if ($wishlist) {
            $this->wishlistRepository->delete($wishlist);
            $added = false;
            $message = 'Produk dihapus dari wishlist';
        } else {
            $this->wishlistRepository->create([
                'user_id' => $userId,
                'product_id' => $productId,
            ]);
            $added = true;
            $message = 'Produk berhasil ditambahkan ke wishlist';
        }

        return [
            'added' => $added,
            'message' => $message,
            'count' => $this->wishlistRepository->countByUser($userId)
        ];
    }

    public function deleteWishlist(Wishlist $wishlist, int $userId): array
    {
        if ($wishlist->user_id !== $userId) {
            throw new \Exception('Unauthorized', 403);
        }

        $this->wishlistRepository->delete($wishlist);

        return [
            'message' => 'Produk dihapus dari wishlist',
            'count' => $this->wishlistRepository->countByUser($userId)
        ];
    }
}
