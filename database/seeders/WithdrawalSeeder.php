<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Withdrawal;
use App\Models\StoreBalance;
use App\Models\StoreBalanceHistory;
use App\Models\Store;

class WithdrawalSeeder extends Seeder
{

    public function run(): void
    {
        $storeBalances = StoreBalance::with('store')->where('balance', '>', 0)->get();

        foreach ($storeBalances as $storeBalance) {


            if ($storeBalance->balance > 1000000) {
                $amount = 500000;


                $storeBalance->decrement('balance', $amount);


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


            if ($storeBalance->balance > 200000) {
                $pendingAmount = 100000;


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


            $rejectedAmount = 5000000;

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


        }
    }
}
