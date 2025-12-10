<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Withdrawal;
use Illuminate\Http\Request;

class AdminWithdrawalController extends Controller
{

    public function index(Request $request)
    {
        $query = Withdrawal::with(['storeBalance.store'])
            ->latest();


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
        if ($withdrawal->status !== 'pending') {
            return back()->with('error', 'Hanya penarikan yang berstatus pending yang dapat disetujui.');
        }


        \Illuminate\Support\Facades\DB::transaction(function () use ($withdrawal) {
            $lockedWithdrawal = \App\Models\Withdrawal::where('id', $withdrawal->id)->lockForUpdate()->first();

            if ($lockedWithdrawal && $lockedWithdrawal->status === 'pending') {
                $lockedWithdrawal->update(['status' => 'approved']);
            }
        });

        return redirect()->route('admin.withdrawals.show', $withdrawal)
            ->with('success', 'Penarikan saldo berhasil disetujui.');
    }


    public function reject(Withdrawal $withdrawal)
    {
        if ($withdrawal->status !== 'pending') {
            return back()->with('error', 'Hanya penarikan yang berstatus pending yang dapat ditolak.');
        }


        \Illuminate\Support\Facades\DB::transaction(function () use ($withdrawal) {

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
