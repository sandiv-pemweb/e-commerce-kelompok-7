<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use App\Models\Buyer;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;


class CheckoutController extends Controller
{

    public function index(Request $request)
    {
        // Get cart items for selected stores
        $query = Cart::where('user_id', Auth::id())
            ->with(['product.store', 'product.productImages']);

        // If specific stores selected
        if ($request->has('stores') && is_array($request->stores)) {
            $query->whereHas('product', function ($q) use ($request) {
                $q->whereIn('store_id', $request->stores);
            });
        }

        $cartItems = $query->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Keranjang belanja kosong');
        }

        // Group by store
        $cartByStore = $cartItems->groupBy(function ($cart) {
            return $cart->product->store_id;
        });

        // Calculate grand total
        $grandTotal = $cartItems->sum(function ($cart) {
            return $cart->subtotal;
        });

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

        DB::beginTransaction();
        try {
            // Get or create buyer
            $buyer = Buyer::firstOrCreate(
                ['user_id' => Auth::id()],
                ['phone_number' => Auth::user()->email] // Default to email if no phone
            );

            $createdOrders = [];

            // Process each store separately
            foreach ($request->stores as $storeData) {
                $storeId = $storeData['store_id'];
                
                // Get cart items for this store
                $cartItems = Cart::where('user_id', Auth::id())
                    ->whereHas('product', function ($q) use ($storeId) {
                        $q->where('store_id', $storeId);
                    })
                    ->with('product')
                    ->get();

                if ($cartItems->isEmpty()) {
                    continue;
                }

                // Validate stock for all products
                foreach ($cartItems as $cartItem) {
                    if (!$cartItem->product->isAvailable($cartItem->quantity)) {
                        throw new \Exception("Produk {$cartItem->product->name} stok tidak mencukupi");
                    }
                }

                // Calculate totals
                $subtotal = $cartItems->sum(function ($cart) {
                    return $cart->subtotal;
                });
                $shippingCost = $storeData['shipping_cost'];
                $tax = $subtotal * 0.11; // 11% PPN
                $grandTotal = $subtotal + $shippingCost + $tax;

                // Generate unique transaction code
                $transactionCode = 'TRX-' . strtoupper(Str::random(10));

                // Create transaction
                $transaction = Transaction::create([
                    'code' => $transactionCode,
                    'buyer_id' => $buyer->id,
                    'store_id' => $storeId,
                    'address' => $request->address,
                    'address_id' => 'ADDR-' . time(),
                    'city' => $request->city,
                    'postal_code' => $request->postal_code,
                    'shipping' => $storeData['shipping_type'],
                    'shipping_type' => $storeData['shipping_type'],
                    'shipping_cost' => $shippingCost,
                    'tax' => $tax,
                    'grand_total' => $grandTotal,
                    'payment_status' => 'unpaid',
                    'order_status' => 'pending',
                ]);

                // Create transaction details
                foreach ($cartItems as $cartItem) {
                    TransactionDetail::create([
                        'transaction_id' => $transaction->id,
                        'product_id' => $cartItem->product_id,
                        'qty' => $cartItem->quantity,
                        'subtotal' => $cartItem->subtotal,
                    ]);

                    // Update product stock
                    $cartItem->product->decrement('stock', $cartItem->quantity);
                }

                // Remove items from cart
                $cartItems->each->delete();

                $createdOrders[] = $transaction->id;
            }

            DB::commit();

            // Redirect to orders list with success message
            return redirect()->route('orders.index')->with('success', 'Pesanan berhasil dibuat! Silakan lakukan pembayaran.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Checkout gagal: ' . $e->getMessage())->withInput();
        }
    }
}
