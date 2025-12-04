<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;


class CartController extends Controller
{

    public function index()
    {
        $cartItems = Cart::where('user_id', Auth::id())
            ->whereHas('product.store', function($query) {
                $query->where('is_verified', true)
                      ->whereNotNull('slug');
            })
            ->with(['product.store', 'product.productImages'])
            ->get();

        // Group by store
        $cartByStore = $cartItems->groupBy(function ($cart) {
            return $cart->product->store_id;
        });

        // Calculate totals
        $grandTotal = $cartItems->sum(function ($cart) {
            return $cart->subtotal;
        });

        return view('cart.index', compact('cartByStore', 'grandTotal'));
    }

    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $product = Product::findOrFail($request->product_id);

        // Check stock availability
        if (!$product->isAvailable($request->quantity)) {
            if ($request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Stok produk tidak mencukupi'], 422);
            }
            return redirect()->back()->with('error', 'Stok produk tidak mencukupi');
        }

        // Check if already in cart
        $cartItem = Cart::where('user_id', Auth::id())
            ->where('product_id', $request->product_id)
            ->first();

        if ($cartItem) {
            // Update quantity
            $newQuantity = $cartItem->quantity + $request->quantity;
            
            if (!$product->isAvailable($newQuantity)) {
                if ($request->wantsJson()) {
                    return response()->json(['success' => false, 'message' => 'Stok produk tidak mencukupi'], 422);
                }
                return redirect()->back()->with('error', 'Stok produk tidak mencukupi');
            }

            $cartItem->update(['quantity' => $newQuantity]);
            $message = 'Jumlah produk di keranjang berhasil diperbarui';
        } else {
            // Add new item
            Cart::create([
                'user_id' => Auth::id(),
                'product_id' => $request->product_id,
                'quantity' => $request->quantity,
            ]);
            $message = 'Produk berhasil ditambahkan ke keranjang';
        }

        if ($request->wantsJson()) {
            $cartCount = Cart::where('user_id', Auth::id())->sum('quantity');
            return response()->json([
                'success' => true, 
                'message' => $message,
                'cartCount' => $cartCount
            ]);
        }

        return redirect()->back()->with('success', $message);
    }

    public function update(Request $request, Cart $cart)
    {
        // Ensure user owns this cart item
        if ($cart->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        // Check stock
        if (!$cart->product->isAvailable($request->quantity)) {
            if ($request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Stok produk tidak mencukupi'], 422);
            }
            return redirect()->back()->with('error', 'Stok produk tidak mencukupi');
        }

        $cart->update(['quantity' => $request->quantity]);

        if ($request->wantsJson()) {
            $cartItems = Cart::where('user_id', Auth::id())->get();
            $grandTotal = $cartItems->sum('subtotal');
            $totalItems = $cartItems->sum('quantity'); // Or count() depending on requirement, usually sum of quantities
            
            return response()->json([
                'success' => true,
                'message' => 'Keranjang berhasil diperbarui',
                'item_subtotal' => 'Rp ' . number_format($cart->subtotal, 0, ',', '.'),
                'grand_total' => 'Rp ' . number_format($grandTotal, 0, ',', '.'),
                'total_items' => $cartItems->count(), // Total unique items or sum of quantities? View uses sum(count) which is unique items count per store sum. Let's match view logic.
                // View logic: $cartByStore->sum(function($items) { return $items->count(); }) which is basically total number of rows in cart table.
                'cart_count' => $cartItems->sum('quantity') // This is usually for the badge
            ]);
        }

        return redirect()->route('cart.index')->with('success', 'Keranjang berhasil diperbarui');
    }

    public function remove(Request $request, Cart $cart)
    {
        // Ensure user owns this cart item
        if ($cart->user_id !== Auth::id()) {
            abort(403);
        }

        $cart->delete();

        if ($request->wantsJson()) {
            $cartItems = Cart::where('user_id', Auth::id())->get();
            $cartCount = $cartItems->sum('quantity');
            $grandTotal = $cartItems->sum('subtotal');
            $totalItems = $cartItems->count();
            
            // Re-calculate selected stores count (simplified for now as we don't persist selection state in backend yet)
            // Ideally we should pass the selected stores from frontend to calculate this accurately if needed
            
            return response()->json([
                'success' => true,
                'message' => 'Produk berhasil dihapus dari keranjang',
                'cartCount' => $cartCount,
                'grandTotal' => number_format($grandTotal, 0, ',', '.'),
                'totalItems' => $totalItems,
                'isEmpty' => $cartItems->isEmpty()
            ]);
        }

        return redirect()->route('cart.index')->with('success', 'Produk berhasil dihapus dari keranjang');
    }

    public function clear(Request $request)
    {
        $query = Cart::where('user_id', Auth::id());

        // If store_id provided, clear only that store's items
        if ($request->has('store_id')) {
            $query->whereHas('product', function ($q) use ($request) {
                $q->where('store_id', $request->store_id);
            });
        }

        $query->delete();

        return redirect()->route('cart.index')->with('success', 'Keranjang berhasil dikosongkan');
    }
}
