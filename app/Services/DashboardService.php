<?php

namespace App\Services;

use App\Models\User;
use App\Services\TransactionService;

class DashboardService
{
    public function getAdminStats()
    {
        $pendingWithdrawalsCount = \App\Models\Withdrawal::where('status', 'pending')->count();
        
        // 1. Global Sales (Last 30 Days)
        $endDate = now();
        $startDate = now()->subDays(29);
        
        $globalSalesData = \App\Models\Transaction::where('payment_status', 'paid')
            ->where('order_status', 'completed')
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
        
        $adminOrderStatusChartData = [
            'labels' => array_keys($orderStatusData),
            'data' => array_values($orderStatusData)
        ];

        return compact('pendingWithdrawalsCount', 'adminSalesChartData', 'adminOrderStatusChartData');
    }

    public function getSellerStats(User $user)
    {
        $salesChartData = null;
        $topProductsChartData = null;

        if ($user->hasStore() && $user->store->is_verified) {
            // 1. Sales Chart Data (Last 30 Days)
            $endDate = now();
            $startDate = now()->subDays(29);
            
            $salesData = \App\Models\Transaction::where('store_id', $user->store->id)
                ->where('payment_status', 'paid')
                ->where('order_status', 'completed')
                ->whereBetween('created_at', [$startDate->startOfDay(), $endDate->endOfDay()])
                ->selectRaw('DATE(created_at) as date, SUM(grand_total) as total')
                ->groupBy('date')
                ->orderBy('date')
                ->get()
                ->pluck('total', 'date')
                ->toArray();

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
                      ->where('order_status', 'completed');
                })
                ->selectRaw('product_id, SUM(qty) as total_qty')
                ->groupBy('product_id')
                ->orderByDesc('total_qty')
                ->limit(5)
                ->with('product:id,name')
                ->get();

            $topProductsChartData = [
                'labels' => $topProducts->pluck('product.name')->toArray(),
                'data' => $topProducts->pluck('total_qty')->toArray(),
            ];
        }

        return compact('salesChartData', 'topProductsChartData');
    }
}
