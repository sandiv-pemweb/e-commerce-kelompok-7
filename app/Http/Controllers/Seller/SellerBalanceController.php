<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\FinanceService;
use Illuminate\Support\Facades\Auth;

class SellerBalanceController extends Controller
{
    public function __construct(protected FinanceService $financeService)
    {
    }

    public function index()
    {
        $store = auth()->user()->store;
        $storeBalance = $this->financeService->getStoreBalance($store);
        $balanceHistory = $this->financeService->getBalanceHistory($storeBalance);
        $withdrawals = $this->financeService->getWithdrawalHistory($storeBalance);
        
        // Check for pending using helper or service if possible, or keep simple check
        $hasPendingWithdrawal = $storeBalance->withdrawals()->where('status', 'pending')->exists();
        
        $pendingBalance = $this->financeService->calculatePendingBalance($store);

        return view('seller.balance.index', compact('storeBalance', 'balanceHistory', 'withdrawals', 'hasPendingWithdrawal', 'pendingBalance'));
    }
}
