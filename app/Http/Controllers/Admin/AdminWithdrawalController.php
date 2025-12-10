<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Withdrawal;
use Illuminate\Http\Request;
use App\Services\FinanceService;
use Exception;

class AdminWithdrawalController extends Controller
{
    public function __construct(protected FinanceService $financeService)
    {
    }

    public function index(Request $request)
    {
        $query = Withdrawal::with(['storeBalance.store'])->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $withdrawals = $query->paginate(20);

        return view('admin.withdrawals.index', compact('withdrawals'));
    }

    public function show(Withdrawal $withdrawal)
    {
        $withdrawal->load('storeBalance.store.user');
        return view('admin.withdrawals.show', compact('withdrawal'));
    }

    public function approve(Withdrawal $withdrawal)
    {
        try {
            $this->financeService->approveWithdrawal($withdrawal);
            return redirect()->route('admin.withdrawals.show', $withdrawal)
                ->with('success', 'Penarikan saldo berhasil disetujui.');
        } catch (Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function reject(Withdrawal $withdrawal)
    {
        try {
            $this->financeService->rejectWithdrawal($withdrawal);
            return redirect()->route('admin.withdrawals.show', $withdrawal)
                ->with('success', 'Penarikan saldo ditolak dan dana telah dikembalikan ke toko.');
        } catch (Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
