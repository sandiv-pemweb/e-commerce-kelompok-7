<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Withdrawal;
use Illuminate\Http\Request;

class AdminWithdrawalController extends Controller
{
    /**
     * Display a listing of all withdrawal requests.
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $query = Withdrawal::with(['storeBalance.store'])
                          ->latest();

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $withdrawals = $query->paginate(20);

        return view('admin.withdrawals.index', compact('withdrawals'));
    }

    /**
     * Display the specified withdrawal.
     * 
     * @param  \App\Models\Withdrawal  $withdrawal
     * @return \Illuminate\View\View
     */
    public function show(Withdrawal $withdrawal)
    {
        $withdrawal->load('storeBalance.store.user');

        return view('admin.withdrawals.show', compact('withdrawal'));
    }

    /**
     * Approve a withdrawal request.
     * 
     * @param  \App\Models\Withdrawal  $withdrawal
     * @return \Illuminate\Http\RedirectResponse
     */
    public function approve(Withdrawal $withdrawal)
    {
        if ($withdrawal->status !== 'pending') {
            return back()->with('error', 'Hanya penarikan yang berstatus pending yang dapat disetujui.');
        }

        // Wrap in transaction for safety
        \Illuminate\Support\Facades\DB::transaction(function () use ($withdrawal) {
            $lockedWithdrawal = \App\Models\Withdrawal::where('id', $withdrawal->id)->lockForUpdate()->first();

            if ($lockedWithdrawal && $lockedWithdrawal->status === 'pending') {
                $lockedWithdrawal->update(['status' => 'approved']);
            }
        });

        return redirect()->route('admin.withdrawals.show', $withdrawal)
                       ->with('success', 'Penarikan saldo berhasil disetujui.');
    }

    /**
     * Reject a withdrawal request.
     * 
     * @param  \App\Models\Withdrawal  $withdrawal
     * @return \Illuminate\Http\RedirectResponse
     */
    public function reject(Withdrawal $withdrawal)
    {
        if ($withdrawal->status !== 'pending') {
            return back()->with('error', 'Hanya penarikan yang berstatus pending yang dapat ditolak.');
        }

        // Refund balance and reject in transaction
        \Illuminate\Support\Facades\DB::transaction(function () use ($withdrawal) {
            // Lock record
            $lockedWithdrawal = \App\Models\Withdrawal::where('id', $withdrawal->id)->lockForUpdate()->first();

            if ($lockedWithdrawal && $lockedWithdrawal->status === 'pending') {
                $lockedWithdrawal->storeBalance->increment('balance', $lockedWithdrawal->amount);
                $lockedWithdrawal->update(['status' => 'rejected']);
            }
        });

        return redirect()->route('admin.withdrawals.show', $withdrawal)
                       ->with('success', 'Penarikan saldo ditolak dan dana telah dikembalikan ke toko.');
    }
}
