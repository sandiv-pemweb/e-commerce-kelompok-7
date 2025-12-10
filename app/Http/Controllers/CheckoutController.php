<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\CartService;
use App\Services\TransactionService;
use Illuminate\Support\Facades\Auth;
use Exception;

class CheckoutController extends Controller
{
    public function __construct(
        protected CartService $cartService,
        protected TransactionService $transactionService
    ) {}

    public function index(Request $request)
    {
        $userId = Auth::id();
        
        $cartItems = $this->cartService->getUserCart($userId);

        if ($request->has('stores') && is_array($request->stores)) {
            $storeIds = $request->stores;
            $cartItems = $cartItems->filter(function ($item) use ($storeIds) {
                return in_array($item->product->store_id, $storeIds);
            });
        }

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Keranjang belanja kosong');
        }

        $cartByStore = $cartItems->groupBy(function ($cart) {
            return $cart->product->store_id;
        });

        $grandTotal = $cartItems->sum('subtotal');

        return view('checkout.index', compact('cartByStore', 'grandTotal'));
    }

    public function process(Request $request)
    {
        $request->validate([
            'address' => 'required|string',
            'city' => 'required|string',
            'postal_code' => 'required|string',
            'stores' => 'required|array',
            'stores.*.store_id' => 'required|exists:stores,id',
            'stores.*.shipping_type' => 'required|string',
            'stores.*.shipping_cost' => 'required|numeric|min:0',
        ]);

        try {
            $this->transactionService->checkout(Auth::id(), $request->all());
            return redirect()->route('orders.index')->with('success', 'Pesanan berhasil dibuat! Silakan lakukan pembayaran.');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Checkout gagal: ' . $e->getMessage())->withInput();
        }
    }
}
