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
        $hasPendingWithdrawal = $storeBalance->withdrawals()
                                            ->where('status', 'pending')
                                            ->exists();

        return view('seller.balance.index', compact('storeBalance', 'balanceHistory', 'withdrawals', 'hasPendingWithdrawal'));
    }
}
