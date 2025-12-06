<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use App\Models\StoreBalance;
use App\Models\StoreBalanceHistory;
use App\Models\ProductReview;
use App\Models\User;
use App\Models\Buyer;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class TransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = \Faker\Factory::create('id_ID');
        $products = Product::with('store')->get();

        if ($products->isEmpty()) {
            return;
        }

        // 1. Create Completed Orders (Income for Sellers)
        // Focus on "Laut Bercerita" (Best Seller)
        $bestSeller = Product::where('slug', 'laut-bercerita')->first();
        if ($bestSeller) {
            for ($i = 0; $i < 5; $i++) {
                $this->createOrder($faker, $bestSeller, 'paid', 'completed', true);
            }
        }

        // Focus on "Think and Grow Rich" (With Reviews)
        $tgr = Product::where('name', 'Think and Grow Rich')->first();
        if ($tgr) {
            $reviews = [
                'Buku yang sangat menginspirasi!',
                'Pengiriman cepat, packing aman.',
                'Isinya daging semua, wajib baca.',
                'Kualitas kertas bagus, original.',
                'Recommended seller!'
            ];
            foreach ($reviews as $review) {
                $this->createOrder($faker, $tgr, 'paid', 'completed', true, $review);
            }
        }

        // 2. Create Active Orders (Held Balance / Saldo Tertahan)
        // These orders are PAID but NOT COMPLETED
        foreach ($products->random(5) as $product) {
            // Status: Processing
            $this->createOrder($faker, $product, 'paid', 'processing');
            // Status: Shipped
            $this->createOrder($faker, $product, 'paid', 'shipped');
        }

        // 3. Create Pending/Unpaid Orders
        foreach ($products->random(3) as $product) {
            $this->createOrder($faker, $product, 'unpaid', 'pending');
        }

        // 4. Create Waiting Verification Orders
        foreach ($products->random(2) as $product) {
            $this->createOrder($faker, $product, 'waiting', 'pending');
        }

        // 5. Create Cancelled Orders
        foreach ($products->random(2) as $product) {
            $this->createOrder($faker, $product, 'refunded', 'cancelled');
        }
    }

    private function createOrder($faker, $product, $paymentStatus, $orderStatus, $addReview = false, $reviewText = null)
    {
        // Create User & Buyer
        $user = User::create([
            'name' => $faker->name,
            'email' => $faker->unique()->safeEmail,
            'password' => Hash::make('password'),
            'role' => 'member',
            'email_verified_at' => now(),
        ]);

        $buyer = Buyer::create([
            'user_id' => $user->id,
            'phone_number' => $faker->phoneNumber,
        ]);

        // Calculate Amounts (Fixed for consistency)
        $shippingCost = 10000;
        $tax = 0;
        $productPrice = $product->price;
        $grandTotal = $productPrice + $shippingCost + $tax;

        // Create Transaction
        $transaction = Transaction::create([
            'code' => 'TRX-' . Str::upper(Str::random(10)),
            'buyer_id' => $buyer->id,
            'store_id' => $product->store_id,
            'address' => $faker->address,
            'address_id' => 'ADR-' . Str::random(5),
            'city' => $faker->city,
            'postal_code' => $faker->postcode,
            'shipping' => 'JNE',
            'shipping_type' => 'REG',
            'shipping_cost' => $shippingCost,
            'tax' => $tax,
            'grand_total' => $grandTotal,
            'payment_status' => $paymentStatus,
            'order_status' => $orderStatus,
            'payment_proof' => $paymentStatus !== 'unpaid' ? 'payment_proofs/sample.jpg' : null,
            'balance_credited_at' => $orderStatus === 'completed' ? now() : null,
        ]);

        // Create Detail
        TransactionDetail::create([
            'transaction_id' => $transaction->id,
            'product_id' => $product->id,
            'qty' => 1,
            'subtotal' => $product->price,
        ]);

        // If Completed, Credit Balance & Create History
        if ($orderStatus === 'completed') {
            $storeBalance = StoreBalance::firstOrCreate(
                ['store_id' => $product->store_id],
                ['balance' => 0]
            );

            // EXACT FORMULA FROM OrderController.php
            $productSubtotal = $grandTotal - $shippingCost - $tax; // Should be $productPrice
            $platformCommission = $productSubtotal * 0.03; // 3%
            $sellerEarnings = $productSubtotal + $shippingCost - $platformCommission;

            // Use direct update
            $storeBalance->increment('balance', $sellerEarnings);

            StoreBalanceHistory::create([
                'store_balance_id' => $storeBalance->id,
                'reference_id' => $transaction->id,
                'reference_type' => Transaction::class,
                'amount' => $sellerEarnings,
                'type' => 'income',
                'remarks' => "Pesanan Diterima User #{$transaction->code} (Produk: Rp " . number_format($productSubtotal, 0, ',', '.') . " + Ongkir: Rp " . number_format($shippingCost, 0, ',', '.') . " - Komisi 3%: Rp " . number_format($platformCommission, 0, ',', '.') . ")",
            ]);

            // Add Wishlist (Simulate user liked it)
            \App\Models\Wishlist::firstOrCreate([
                'user_id' => $user->id,
                'product_id' => $product->id,
            ]);
        }

        // Add Review if requested
        if ($addReview && $orderStatus === 'completed') {
            ProductReview::create([
                'transaction_id' => $transaction->id,
                'buyer_id' => $buyer->id,
                'product_id' => $product->id,
                'rating' => rand(4, 5),
                'review' => $reviewText ?? $faker->sentence,
            ]);
        }
    }
}
