<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Display the dashboard based on user role
     * 
     * Eager loads necessary relationships to prevent N+1 queries:
     * - store (with storeBalance for sellers)
     * - product and category counts
     * 
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $user = Auth::user();

        if (!$user->hasStore() && !$user->isAdmin()) {
            return redirect()->route('home');
        }

        // Eager load store with balance and counts for sellers/admins
        if ($user->hasStore() || $user->isAdmin()) {
            $user->load([
                'store' => function ($query) {
                    $query->withCount(['products', 'transactions'])
                          ->with('storeBalance');
                }
            ]);
        }

        if ($user->isAdmin()) {
            $pendingWithdrawalsCount = \App\Models\Withdrawal::where('status', 'pending')->count();
            return view('dashboard', compact('pendingWithdrawalsCount'));
        }

        return view('dashboard');
    }
}
