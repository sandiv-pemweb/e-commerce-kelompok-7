<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SellerBalanceController extends Controller
{
    /**
     * Display the seller's store balance, withdrawal list, and transaction history.
     */
    public function index()
    {
        $store = auth()->user()->store;
        $storeBalance = $store->storeBalance;

        // Create store balance if it doesn't exist
        if (!$storeBalance) {
            $storeBalance = $store->storeBalance()->create([
                'balance' => 0,
            ]);
        }

        // Get balance history
        $balanceHistory = $storeBalance->storeBalanceHistories()
                                      ->latest()
                                      ->paginate(10, ['*'], 'history_page');

        // Get withdrawals
        $withdrawals = $storeBalance->withdrawals()
                                   ->latest()
                                   ->paginate(10, ['*'], 'withdrawal_page');

        // Check if there's a pending withdrawal
        // Check if there's a pending withdrawal
        $hasPendingWithdrawal = $storeBalance->withdrawals()
                                            ->where('status', 'pending')
                                            ->exists();

        // Calculate "Held Balance" (Saldo Tertahan) from ongoing orders
        // Orders that are PAID but NOT COMPLETED/CANCELLED
        $pendingTransactions = $store->transactions()
            ->where('payment_status', 'paid')
            ->whereNotIn('order_status', ['completed', 'cancelled'])
            ->get();

        $pendingBalance = 0;
        foreach ($pendingTransactions as $transaction) {
            $productSubtotal = $transaction->grand_total - $transaction->shipping_cost - $transaction->tax;
            $platformCommission = $productSubtotal * 0.03; // 3% commission
            $sellerEarnings = $productSubtotal + $transaction->shipping_cost - $platformCommission;
            $pendingBalance += $sellerEarnings;
        }

        return view('seller.balance.index', compact('storeBalance', 'balanceHistory', 'withdrawals', 'hasPendingWithdrawal', 'pendingBalance'));
    }
}
