<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SellerBalanceController extends Controller
{

    public function index()
    {
        $store = auth()->user()->store;
        $storeBalance = $store->storeBalance;


        if (!$storeBalance) {
            $storeBalance = $store->storeBalance()->create([
                'balance' => 0,
            ]);
        }


        $balanceHistory = $storeBalance->storeBalanceHistories()
            ->latest()
            ->paginate(10, ['*'], 'history_page');


        $withdrawals = $storeBalance->withdrawals()
            ->latest()
            ->paginate(10, ['*'], 'withdrawal_page');


        $hasPendingWithdrawal = $storeBalance->withdrawals()
            ->where('status', 'pending')
            ->exists();


        $pendingTransactions = $store->transactions()
            ->where('payment_status', 'paid')
            ->whereNotIn('order_status', ['completed', 'cancelled'])
            ->get();

        $pendingBalance = 0;
        foreach ($pendingTransactions as $transaction) {
            $productSubtotal = $transaction->grand_total - $transaction->shipping_cost - $transaction->tax;
            $platformCommission = $productSubtotal * 0.03;
            $sellerEarnings = $productSubtotal + $transaction->shipping_cost - $platformCommission;
            $pendingBalance += $sellerEarnings;
        }

        return view('seller.balance.index', compact('storeBalance', 'balanceHistory', 'withdrawals', 'hasPendingWithdrawal', 'pendingBalance'));
    }
}
