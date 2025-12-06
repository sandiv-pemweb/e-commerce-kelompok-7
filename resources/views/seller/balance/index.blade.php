<x-app-layout>
    <x-slot name="header">
        <h2 class=" font-bold text-2xl text-brand-dark leading-tight">
            {{ __('Saldo Toko') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
            @if (session('success'))
                <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl flex items-center shadow-sm">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl flex items-center shadow-sm">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    {{ session('error') }}
                </div>
            @endif

            <!-- Balance Card -->
            <div class="bg-white shadow-sm rounded-3xl border border-gray-100 overflow-hidden">
                <div class="p-8">
                    <div class="flex flex-col md:flex-row justify-between items-center gap-8">
                        <div class="flex-1">
                            <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Saldo Aktif</h3>
                            <div class="flex items-baseline gap-2">
                                <span class="text-4xl font-bold text-brand-dark">Rp {{ number_format($storeBalance->balance, 0, ',', '.') }}</span>
                                <span class="text-sm font-medium text-green-500 bg-green-50 px-2 py-1 rounded-lg">Siap Ditarik</span>
                            </div>
                        </div>
                        
                        <div class="hidden md:block w-px h-20 bg-gray-100"></div>

                        <div class="flex-1">
                            <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2 flex items-center gap-2">
                                Saldo Tertahan
                                <div class="group relative">
                                    <svg class="w-4 h-4 text-gray-300 hover:text-gray-500 cursor-help transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    <div class="absolute top-full left-1/2 transform -translate-x-1/2 mt-2 w-56 bg-gray-800 text-white text-xs rounded-xl py-3 px-4 hidden group-hover:block transition-all z-10 text-center shadow-xl">
                                        Dana dari pesanan yang sedang berlangsung. Dana akan masuk ke Saldo Aktif setelah pesanan selesai.
                                        <!-- Triangle pointing up -->
                                        <div class="absolute bottom-full left-1/2 transform -translate-x-1/2 border-4 border-transparent border-b-gray-800"></div>
                                    </div>
                                </div>
                            </h3>
                            <p class="text-2xl font-bold text-gray-300">Rp {{ number_format($pendingBalance, 0, ',', '.') }}</p>
                        </div>
                        
                        <div>
                            @if($hasPendingWithdrawal)
                                <div class="bg-yellow-50 border border-yellow-200 text-yellow-800 px-6 py-4 rounded-xl flex items-center shadow-sm">
                                    <div class="p-2 bg-yellow-100 rounded-lg mr-3">
                                        <svg class="w-5 h-5 text-yellow-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    </div>
                                    <div>
                                        <p class="font-bold text-sm text-yellow-900">Penarikan Diproses</p>
                                        <p class="text-xs text-yellow-700 mt-0.5">Mohon tunggu verifikasi admin</p>
                                    </div>
                                </div>
                            @else
                                <a href="{{ route('seller.withdrawals.create') }}" class="inline-flex items-center px-8 py-4 bg-brand-dark border border-transparent rounded-xl font-bold text-sm text-white hover:bg-brand-orange transition-all shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 group">
                                    <svg class="w-5 h-5 mr-3 group-hover:animate-bounce" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                    Tarik Dana
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tabs -->
            <div class="bg-white overflow-hidden shadow-sm rounded-3xl border border-gray-100">
                <div class="border-b border-gray-100 px-8 pt-6">
                    <nav class="-mb-px flex gap-6" aria-label="Tabs">
                        <button onclick="showTab('history')" id="tab-history" class="tab-button border-b-2 border-brand-orange text-brand-orange pb-4 px-2 font-bold text-sm transition-all focus:outline-none">
                            Riwayat Saldo
                        </button>
                        <button onclick="showTab('withdrawal')" id="tab-withdrawal" class="tab-button border-b-2 border-transparent text-gray-400 hover:text-gray-600 hover:border-gray-200 pb-4 px-2 font-bold text-sm transition-all focus:outline-none">
                            Riwayat Penarikan
                        </button>
                    </nav>
                </div>

                <!-- Balance History Tab -->
                <div id="content-history" class="tab-content p-8">
                    @if($balanceHistory->isEmpty())
                        <div class="text-center py-6">
                            <svg class="mx-auto h-16 w-16 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                            <p class="text-lg font-medium text-gray-500">Tidak ada riwayat transaksi ditemukan.</p>
                        </div>
                    @else
                        <div class="overflow-x-auto rounded-xl">
                            <table class="min-w-full">
                                <thead>
                                    <tr class="border-b border-gray-100">
                                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-wider pl-8">Tanggal</th>
                                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Tipe</th>
                                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Jumlah</th>
                                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Keterangan</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-50">
                                    @foreach($balanceHistory as $history)
                                        <tr class="hover:bg-blue-50/50 transition-colors duration-200">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-700 pl-8">{{ $history->created_at->format('d M Y H:i') }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                                <span class="px-3 py-1 inline-flex text-[10px] uppercase tracking-wider font-bold rounded-full {{ $history->type == 'income' || $history->type == 'credit' ? 'bg-green-50 text-green-700 border border-green-100' : 'bg-red-50 text-red-700 border border-red-100' }}">
                                                    {{ match($history->type) {
                                                    'income' => 'Pemasukan',
                                                    'credit' => 'Pemasukan',
                                                    'withdraw' => 'Penarikan',
                                                    default => ucfirst($history->type)
                                                } }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm {{ $history->type == 'income' || $history->type == 'credit' ? 'text-green-600' : 'text-red-600' }} font-bold">
                                                {{ $history->type == 'income' || $history->type == 'credit' ? '+' : '-' }} Rp {{ number_format($history->amount, 0, ',', '.') }}
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-500">{{ $history->remarks ?? '-' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-6">
                            {{ $balanceHistory->links() }}
                        </div>
                    @endif
                </div>

                <!-- Withdrawal Tab -->
                <div id="content-withdrawal" class="tab-content hidden p-8">
                    @if($withdrawals->isEmpty())
                        <div class="text-center py-6">
                            <svg class="mx-auto h-16 w-16 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                            <p class="text-lg font-medium text-gray-500">Tidak ada riwayat penarikan ditemukan.</p>
                        </div>
                    @else
                        <div class="overflow-x-auto rounded-xl">
                            <table class="min-w-full">
                                <thead>
                                    <tr class="border-b border-gray-100">
                                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-wider pl-8">Tanggal</th>
                                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Jumlah</th>
                                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Bank</th>
                                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">No. Rekening</th>
                                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Status</th>
                                        <th class="px-6 py-4 text-right text-xs font-bold text-gray-400 uppercase tracking-wider pr-8">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-50">
                                    @foreach($withdrawals as $withdrawal)
                                        <tr class="hover:bg-blue-50/50 transition-colors duration-200">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-700 pl-8">{{ $withdrawal->created_at->format('d M Y H:i') }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-brand-orange">Rp {{ number_format($withdrawal->amount, 0, ',', '.') }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $withdrawal->bank_name }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 font-mono">{{ $withdrawal->bank_account_number }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="px-3 py-1 inline-flex text-[10px] uppercase tracking-wider font-bold rounded-full 
                                                    @if($withdrawal->status == 'approved') bg-green-50 text-green-700 border border-green-100
                                                    @elseif($withdrawal->status == 'rejected') bg-red-50 text-red-700 border border-red-100
                                                    @else bg-yellow-50 text-yellow-700 border border-yellow-100
                                                    @endif">
                                                    {{ match($withdrawal->status) {
                                                    'pending' => 'Menunggu',
                                                    'approved' => 'Disetujui',
                                                    'rejected' => 'Ditolak',
                                                    default => ucfirst($withdrawal->status)
                                                } }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium pr-8">
                                                @if($withdrawal->status == 'pending')
                                                    <button type="button"
                                                            onclick="document.getElementById('cancel-withdrawal-{{ $withdrawal->id }}').classList.remove('hidden')"
                                                            class="text-red-500 hover:text-red-700 font-bold transition-colors text-xs uppercase tracking-wider flex items-center justify-end gap-1 ml-auto">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                                        Batalkan
                                                    </button>
                                                @else
                                                    <span class="text-gray-300">-</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-6">
                            {{ $withdrawals->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Cancel Withdrawal Modals -->
    @foreach($withdrawals as $withdrawal)
        @if($withdrawal->status == 'pending')
        <x-confirmation-modal 
            id="cancel-withdrawal-{{ $withdrawal->id }}"
            type="warning" 
            title="Batalkan Permintaan Penarikan" 
            message="Apakah Anda yakin ingin membatalkan permintaan penarikan sebesar Rp {{ number_format($withdrawal->amount, 0, ',', '.') }}?"
            confirmText="Ya, Batalkan"
            cancelText="Kembali">
        </x-confirmation-modal>
        <form id="cancel-withdrawal-{{ $withdrawal->id }}-form" method="POST" action="{{ route('seller.withdrawals.destroy', $withdrawal) }}" class="hidden">
            @csrf
            @method('DELETE')
        </form>
        @endif
    @endforeach

    <script>
        function showTab(tabName) {
            // Hide all tabs
            document.querySelectorAll('.tab-content').forEach(el => el.classList.add('hidden'));
            document.querySelectorAll('.tab-button').forEach(el => {
                el.classList.remove('border-brand-orange', 'text-brand-orange');
                el.classList.add('border-transparent', 'text-gray-500');
            });

            // Show selected tab
            document.getElementById('content-' + tabName).classList.remove('hidden');
            document.getElementById('tab-' + tabName).classList.remove('border-transparent', 'text-gray-500');
            document.getElementById('tab-' + tabName).classList.add('border-brand-orange', 'text-brand-orange');
        }
    </script>
</x-app-layout>
