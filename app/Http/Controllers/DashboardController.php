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
            
            // ADMIN CHARTS DATA
            
            // 1. Global Sales (Last 30 Days)
            $endDate = now();
            $startDate = now()->subDays(29);
            
            $globalSalesData = \App\Models\Transaction::where('payment_status', 'paid')
                ->where('order_status', 'completed') // Only completed orders
                ->whereBetween('created_at', [$startDate->startOfDay(), $endDate->endOfDay()])
                ->selectRaw('DATE(created_at) as date, SUM(grand_total) as total')
                ->groupBy('date')
                ->orderBy('date')
                ->get()
                ->pluck('total', 'date')
                ->toArray();

            $adminSalesLabels = [];
            $adminSalesValues = [];
            for ($i = 29; $i >= 0; $i--) {
                $date = now()->subDays($i)->format('Y-m-d');
                $adminSalesLabels[] = now()->subDays($i)->format('d M');
                $adminSalesValues[] = $globalSalesData[$date] ?? 0;
            }

            $adminSalesChartData = [
                'labels' => $adminSalesLabels,
                'data' => $adminSalesValues
            ];

            // 2. Order Status Distribution
            $orderStatusData = \App\Models\Transaction::selectRaw('order_status, count(*) as total')
                ->groupBy('order_status')
                ->pluck('total', 'order_status')
                ->toArray();
            
            // Define all possible statuses to ensure consistent colors/labels if needed, 
            // or just use what's in DB. using DB data for now.
            $adminOrderStatusChartData = [
                'labels' => array_keys($orderStatusData),
                'data' => array_values($orderStatusData)
            ];

            return view('dashboard', compact('pendingWithdrawalsCount', 'adminSalesChartData', 'adminOrderStatusChartData'));
        }

        // Prepare data for Seller Dashboard Charts
        $salesChartData = null;
        $topProductsChartData = null;

        if ($user->hasStore() && $user->store->is_verified) {
            // 1. Sales Chart Data (Last 30 Days)
            $endDate = now();
            $startDate = now()->subDays(29);
            
            $salesData = \App\Models\Transaction::where('store_id', $user->store->id)
                ->where('payment_status', 'paid')
                ->where('order_status', 'completed') // Only completed orders
                ->whereBetween('created_at', [$startDate->startOfDay(), $endDate->endOfDay()])
                ->selectRaw('DATE(created_at) as date, SUM(grand_total) as total')
                ->groupBy('date')
                ->orderBy('date')
                ->get()
                ->pluck('total', 'date')
                ->toArray();

            // Fill missing dates with 0
            $salesChartLabels = [];
            $salesChartValues = [];
            for ($i = 29; $i >= 0; $i--) {
                $date = now()->subDays($i)->format('Y-m-d');
                $salesChartLabels[] = now()->subDays($i)->format('d M');
                $salesChartValues[] = $salesData[$date] ?? 0;
            }

            $salesChartData = [
                'labels' => $salesChartLabels,
                'data' => $salesChartValues
            ];

            // 2. Top 5 Products Data
            $topProducts = \App\Models\TransactionDetail::whereHas('transaction', function ($q) use ($user) {
                    $q->where('store_id', $user->store->id)
                      ->where('payment_status', 'paid')
                      ->where('order_status', 'completed'); // Only completed orders
                })
                ->selectRaw('product_id, SUM(qty) as total_qty')
                ->groupBy('product_id')
                ->orderByDesc('total_qty')
                ->limit(5)
                ->with('product:id,name') // Optimize: select only id and name
                ->get();

            $topProductsChartData = [
                'labels' => $topProducts->pluck('product.name')->toArray(),
                'data' => $topProducts->pluck('total_qty')->toArray(),
            ];
        }

        return view('dashboard', compact('salesChartData', 'topProductsChartData'));
    }
}
