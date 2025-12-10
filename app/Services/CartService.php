<?php

namespace App\Services;

use App\Models\Cart;
use App\Repositories\Contracts\CartRepositoryInterface;
use App\Services\ProductService;
use Exception;
use Illuminate\Database\Eloquent\Collection;

class CartService
{
    public function __construct(
        protected CartRepositoryInterface $cartRepository,
        protected ProductService $productService
    ) {}

    public function getUserCart(int $userId): Collection
    {
        return $this->cartRepository->getByUser($userId);
    }

    public function getCartGroupedByStore(int $userId): Collection
    {
        $cartItems = $this->getUserCart($userId);
        return $cartItems->groupBy(function ($cart) {
            return $cart->product->store_id;
        });
    }

    public function getGrandTotal(int $userId): float
    {
        return $this->getUserCart($userId)->sum(function ($cart) {
            return $cart->subtotal;
        });
    }

    public function addToCart(int $userId, int $productId, int $quantity): array
    {
        $product = $this->productService->getProduct($productId);

        if (!$product) {
            throw new Exception('Produk tidak ditemukan');
        }

        if (!$product->isAvailable($quantity)) {
            throw new Exception('Stok produk tidak mencukupi');
        }

        $cartItem = $this->cartRepository->findByUserAndProduct($userId, $productId);
        $message = '';

        if ($cartItem) {
            $newQuantity = $cartItem->quantity + $quantity;
            if (!$product->isAvailable($newQuantity)) {
                throw new Exception('Stok produk tidak mencukupi');
            }
            $this->cartRepository->update($cartItem, ['quantity' => $newQuantity]);
            $message = 'Jumlah produk di keranjang berhasil diperbarui';
        } else {
            $this->cartRepository->create([
                'user_id' => $userId,
                'product_id' => $productId,
                'quantity' => $quantity,
            ]);
            $message = 'Produk berhasil ditambahkan ke keranjang';
        }

        return ['message' => $message];
    }

    public function updateQuantity(Cart $cart, int $quantity): array
    {
        $product = $cart->product;

        if (!$product->isAvailable($quantity)) {
            throw new Exception('Stok produk tidak mencukupi');
        }

        $this->cartRepository->update($cart, ['quantity' => $quantity]);

        return ['message' => 'Keranjang berhasil diperbarui'];
    }

    public function removeItem(Cart $cart): void
    {
        $this->cartRepository->delete($cart);
    }

    public function clearCart(int $userId, ?int $storeId = null): void
    {
        $this->cartRepository->clearByUser($userId, $storeId);
    }

    public function getCartCount(int $userId): int
    {
        return $this->cartRepository->countByUser($userId);
    }
}
