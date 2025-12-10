<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use Illuminate\Support\Facades\Auth;
use App\Services\CartService;
use Exception;

class CartController extends Controller
{
    public function __construct(protected CartService $cartService)
    {
    }

    public function index()
    {
        $userId = Auth::id();
        $cartByStore = $this->cartService->getCartGroupedByStore($userId);
        $grandTotal = $this->cartService->getGrandTotal($userId);

        return view('cart.index', compact('cartByStore', 'grandTotal'));
    }

    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        try {
            $result = $this->cartService->addToCart(Auth::id(), $request->product_id, $request->quantity);

            if ($request->wantsJson()) {
                $cartCount = $this->cartService->getCartCount(Auth::id());
                return response()->json([
                    'success' => true,
                    'message' => $result['message'],
                    'cartCount' => $cartCount
                ]);
            }

            return redirect()->back()->with('success', $result['message']);
        } catch (Exception $e) {
            if ($request->wantsJson()) {
                return response()->json(['success' => false, 'message' => $e->getMessage()], 422);
            }
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function update(Request $request, Cart $cart)
    {
        if ($cart->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        try {
            $this->cartService->updateQuantity($cart, $request->quantity);

            if ($request->wantsJson()) {
                $userId = Auth::id();
                $grandTotal = $this->cartService->getGrandTotal($userId);
                $cartCount = $this->cartService->getCartCount($userId);

                return response()->json([
                    'success' => true,
                    'message' => 'Keranjang berhasil diperbarui',
                    'item_subtotal' => 'Rp ' . number_format($cart->subtotal, 0, ',', '.'),
                    'grand_total' => 'Rp ' . number_format($grandTotal, 0, ',', '.'),
                    'cart_count' => $cartCount
                ]);
            }

            return redirect()->route('cart.index')->with('success', 'Keranjang berhasil diperbarui');
        } catch (Exception $e) {
            if ($request->wantsJson()) {
                return response()->json(['success' => false, 'message' => $e->getMessage()], 422);
            }
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function remove(Request $request, Cart $cart)
    {
        if ($cart->user_id !== Auth::id()) {
            abort(403);
        }

        $this->cartService->removeItem($cart);

        if ($request->wantsJson()) {
            $userId = Auth::id();
            $cartCount = $this->cartService->getCartCount($userId);
            $grandTotal = $this->cartService->getGrandTotal($userId);
            
            // Re-fetch to check if empty
            $isEmpty = $this->cartService->getUserCart($userId)->isEmpty();

            return response()->json([
                'success' => true,
                'message' => 'Produk berhasil dihapus dari keranjang',
                'cartCount' => $cartCount,
                'grandTotal' => number_format($grandTotal, 0, ',', '.'),
                'totalItems' => $cartCount, // Approximation if count is items not types
                'isEmpty' => $isEmpty
            ]);
        }

        return redirect()->route('cart.index')->with('success', 'Produk berhasil dihapus dari keranjang');
    }

    public function clear(Request $request)
    {
        $this->cartService->clearCart(Auth::id(), $request->store_id);

        return redirect()->route('cart.index')->with('success', 'Keranjang berhasil dikosongkan');
    }
}
