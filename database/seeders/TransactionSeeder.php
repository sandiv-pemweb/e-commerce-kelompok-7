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

    public function run(): void
    {
        $faker = \Faker\Factory::create('id_ID');
        $products = Product::with('store')->get();

        if ($products->isEmpty()) {
            return;
        }


        $reviews = [
            "Bukunya sangat bagus, kertasnya original. Pengiriman juga cepat.",
            "Packing rapi banget, pake bubble wrap tebal. Bukunya mulus sampai tujuan.",
            "Sangat puas belanja di sini. Seller ramah dan fast response.",
            "Isi bukunya daging semua. Sangat inspiratif!",
            "Kualitas cetakan sangat baik, enak dibaca. Recommended seller.",
            "Pengiriman super cepat, pesan kemarin hari ini sampai. Mantap!",
            "Buku original, masih segel. Terima kasih bonus pembatas bukunya.",
            "Harga bersahabat untuk kualitas original seperti ini. Bakal langganan.",
            "Suka banget sama pelayanan tokonya. Bukunya juga sesuai ekspektasi.",
            "Jarang-jarang nemu toko buku selengkap ini. Sukses terus kak.",
            "Kondisi buku prima, tidak ada cacat sedikitpun.",
            "Tulisan jelas, jilid kuat. Puas banget pokoknya.",
            "Respon penjual sangat baik, pengiriman kilat.",
            "Barang sesuai deskripsi. Original dan berkualitas.",
            "Terima kasih, bukunya sangat bermanfaat buat skripsi saya."
        ];


        foreach ($products as $product) {

            // Determine number of sales
            // Special case for "Laut Bercerita" to be Best Seller
            if ($product->slug === 'laut-bercerita') {
                $salesCount = 25;
            } else {

                $salesCount = rand(3, 8);
            }

            for ($i = 0; $i < $salesCount; $i++) {

                $reviewText = $reviews[array_rand($reviews)];
                $this->createOrder($faker, $product, 'paid', 'completed', true, $reviewText);
            }


            if (rand(0, 1)) {
                $statuses = ['processing', 'shipped'];
                $this->createOrder($faker, $product, 'paid', $statuses[array_rand($statuses)]);
            }

            if (rand(0, 10) > 8) {
                $this->createOrder($faker, $product, 'unpaid', 'pending');
            }
        }
    }

    private function createOrder($faker, $product, $paymentStatus, $orderStatus, $addReview = false, $reviewText = null)
    {
        // Check stock first
        if ($product->stock <= 0) {
            return;
        }


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


        $shippingCost = 10000;
        $tax = 0;
        $productPrice = $product->price;
        $grandTotal = $productPrice + $shippingCost + $tax;


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
            'tracking_number' => ($orderStatus === 'shipped' || $orderStatus === 'completed') ? 'JNE' . Str::upper(Str::random(10)) : null,
        ]);


        TransactionDetail::create([
            'transaction_id' => $transaction->id,
            'product_id' => $product->id,
            'qty' => 1,
            'subtotal' => $product->price,
        ]);


        $product->decrement('stock', 1);


        if ($orderStatus === 'completed') {
            $storeBalance = StoreBalance::firstOrCreate(
                ['store_id' => $product->store_id],
                ['balance' => 0]
            );


            $productSubtotal = $grandTotal - $shippingCost - $tax;
            $platformCommission = $productSubtotal * 0.03;
            $sellerEarnings = $productSubtotal + $shippingCost - $platformCommission;


            $storeBalance->increment('balance', $sellerEarnings);

            StoreBalanceHistory::create([
                'store_balance_id' => $storeBalance->id,
                'reference_id' => $transaction->id,
                'reference_type' => Transaction::class,
                'amount' => $sellerEarnings,
                'type' => 'income',
                'remarks' => "Pesanan Diterima User #{$transaction->code}",
            ]);


            \App\Models\Wishlist::firstOrCreate([
                'user_id' => $user->id,
                'product_id' => $product->id,
            ]);
        }

        if ($addReview && $orderStatus === 'completed') {
            ProductReview::create([
                'transaction_id' => $transaction->id,
                'buyer_id' => $buyer->id,
                'product_id' => $product->id,
                'rating' => rand(4, 5),
                'review' => $reviewText ?? 'Sangat bagus!',
            ]);
        }
    }
}
