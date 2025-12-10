<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\DashboardService;

class DashboardController extends Controller
{
    public function __construct(protected DashboardService $dashboardService)
    {
    }

    /**
     * Display the dashboard based on user role
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
            $adminStats = $this->dashboardService->getAdminStats();
            return view('dashboard', $adminStats);
        }

        $sellerStats = $this->dashboardService->getSellerStats($user);
        return view('dashboard', $sellerStats);
    }
}
