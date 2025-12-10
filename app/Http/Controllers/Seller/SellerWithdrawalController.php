<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreWithdrawalRequest;
use App\Models\Withdrawal;
use App\Services\FinanceService;
use Exception;
use Illuminate\Support\Facades\Auth;

class SellerWithdrawalController extends Controller
{
    public function __construct(protected FinanceService $financeService)
    {
    }

    public function create()
    {
        $store = auth()->user()->store;
        $storeBalance = $this->financeService->getStoreBalance($store);
        return view('seller.withdrawals.create', compact('store', 'storeBalance'));
    }

    public function store(StoreWithdrawalRequest $request)
    {
        try {
            $this->financeService->createWithdrawal(Auth::user()->store, $request->validated());
            return redirect()->route('seller.balance.index')
                ->with('success', 'Pengajuan penarikan saldo berhasil dibuat. Saldo Anda telah dipotong sementara menunggu persetujuan admin.');
        } catch (Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function destroy(Withdrawal $withdrawal)
    {
        try {
            $this->financeService->cancelWithdrawal($withdrawal->id, Auth::user()->store->id);
            return redirect()->route('seller.balance.index')
                ->with('success', 'Pengajuan penarikan berhasil dibatalkan dan saldo telah dikembalikan.');
        } catch (Exception $e) {
             return back()->with('error', $e->getMessage());
        }
    }
}
