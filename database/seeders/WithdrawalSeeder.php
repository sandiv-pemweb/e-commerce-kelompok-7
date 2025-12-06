<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Withdrawal;
use App\Models\StoreBalance;
use App\Models\StoreBalanceHistory;
use App\Models\Store;

class WithdrawalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Only run for stores that have balance/transactions
        $storeBalances = StoreBalance::with('store')->where('balance', '>', 0)->get();

        foreach ($storeBalances as $storeBalance) {
            // 1. Create an Approved Withdrawal (Past)
            // We assume the current balance is AFTER this withdrawal.
            // So we need to create history and ensuring the math works.
            // However, since TransactionSeeder pushed balance UP, 
            // and we want to show some widthdrawals, we can just deduct from current balance.
            
            if ($storeBalance->balance > 1000000) {
                $amount = 500000;
                
                // Deduct balance
                $storeBalance->decrement('balance', $amount);
                
                // Create Withdrawal Record
                $withdrawal = Withdrawal::create([
                    'store_balance_id' => $storeBalance->id,
                    'amount' => $amount,
                    'bank_name' => $storeBalance->store->bank_name ?? 'Bank BCA',
                    'bank_account_name' => $storeBalance->store->bank_account_name ?? 'Store Owner',
                    'bank_account_number' => $storeBalance->store->bank_account_number ?? '123456789',
                    'status' => 'approved',
                    'created_at' => now()->subDays(2),
                    'updated_at' => now()->subDays(1),
                ]);

                // Create History Record (Manual, as Controller doesn't do it yet)
                StoreBalanceHistory::create([
                    'store_balance_id' => $storeBalance->id,
                    'reference_id' => $withdrawal->id,
                    'reference_type' => Withdrawal::class,
                    'amount' => $amount,
                    'type' => 'withdrawal',
                    'remarks' => 'Penarikan dana ke ' . $withdrawal->bank_name,
                    'created_at' => now()->subDays(1),
                ]);
            }

            // 2. Create a Pending Withdrawal (Current)
            if ($storeBalance->balance > 200000) {
                $pendingAmount = 100000;
                
                // Deduct balance (Held)
                $storeBalance->decrement('balance', $pendingAmount);

                Withdrawal::create([
                    'store_balance_id' => $storeBalance->id,
                    'amount' => $pendingAmount,
                    'bank_name' => $storeBalance->store->bank_name ?? 'Bank BCA',
                    'bank_account_name' => $storeBalance->store->bank_account_name ?? 'Store Owner',
                    'bank_account_number' => $storeBalance->store->bank_account_number ?? '123456789',
                    'status' => 'pending',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            // 3. Create a Rejected Withdrawal (Past)
            // Balance shouldn't change (was deducted then refunded)
            $rejectedAmount = 5000000; // Large amount rejected
            
            Withdrawal::create([
                'store_balance_id' => $storeBalance->id,
                'amount' => $rejectedAmount,
                'bank_name' => $storeBalance->store->bank_name ?? 'Bank BCA',
                'bank_account_name' => $storeBalance->store->bank_account_name ?? 'Store Owner',
                'bank_account_number' => $storeBalance->store->bank_account_number ?? '123456789',
                'status' => 'rejected',
                'created_at' => now()->subDays(5),
                'updated_at' => now()->subDays(4),
            ]);
            
            // Note: No history for rejected withdrawals as net impact is 0
        }
    }
}
