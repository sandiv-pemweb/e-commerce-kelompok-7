<x-app-layout>
    <x-slot name="header">
        <h2 class="font-serif font-bold text-2xl text-brand-dark leading-tight">
            {{ __('Saldo Toko') }}
        </h2>
    </x-slot>

    <div class="py-12">
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
            <div class="bg-white overflow-hidden shadow-lg rounded-2xl border border-gray-100">
                <div class="p-8">
                    <div class="flex flex-col md:flex-row justify-between items-center gap-6">
                        <div>
                            <h3 class="text-sm font-bold text-gray-500 uppercase tracking-wider mb-2">Saldo Saat Ini</h3>
                            <p class="text-4xl font-bold text-brand-dark">Rp {{ number_format($storeBalance->balance, 0, ',', '.') }}</p>
                        </div>
                        <div>
                            @if($hasPendingWithdrawal)
                                <div class="bg-yellow-50 border border-yellow-200 text-yellow-800 px-6 py-3 rounded-xl flex items-center shadow-sm">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    <p class="font-bold text-sm">Penarikan sedang diproses</p>
                                </div>
                            @else
                                <a href="{{ route('seller.withdrawals.create') }}" class="inline-flex items-center px-6 py-3 bg-brand-dark border border-transparent rounded-xl font-bold text-sm text-white uppercase tracking-widest hover:bg-brand-orange transition-colors shadow-md transform hover:-translate-y-0.5">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                    Tarik Dana
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tabs -->
            <div class="bg-white overflow-hidden shadow-lg rounded-2xl border border-gray-100">
                <div class="border-b border-gray-100">
                    <nav class="-mb-px flex" aria-label="Tabs">
                        <button onclick="showTab('history')" id="tab-history" class="tab-button border-b-2 border-brand-orange text-brand-orange w-1/2 py-4 px-1 text-center font-bold text-sm transition-colors">
                            Riwayat Saldo
                        </button>
                        <button onclick="showTab('withdrawal')" id="tab-withdrawal" class="tab-button border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 w-1/2 py-4 px-1 text-center font-bold text-sm transition-colors">
                            Riwayat Penarikan
                        </button>
                    </nav>
                </div>

                <!-- Balance History Tab -->
                <div id="content-history" class="tab-content p-8">
                    @if($balanceHistory->isEmpty())
                        <div class="text-center py-12">
                            <svg class="mx-auto h-16 w-16 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                            <p class="text-lg font-medium text-gray-500">Tidak ada riwayat transaksi ditemukan.</p>
                        </div>
                    @else
                        <div class="overflow-x-auto rounded-xl border border-gray-200">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-brand-dark text-white">
                                    <tr>
                                        <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider">Tanggal</th>
                                        <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider">Tipe</th>
                                        <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider">Jumlah</th>
                                        <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider">Keterangan</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($balanceHistory as $history)
                                        <tr class="hover:bg-blue-50 transition-colors duration-150">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $history->created_at->format('d M Y H:i') }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-bold rounded-full {{ $history->type == 'credit' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                    {{ match($history->type) {
                                                    'income' => 'Pemasukan',
                                                    'withdraw' => 'Penarikan',
                                                    default => ucfirst($history->type)
                                                } }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm {{ $history->type == 'credit' ? 'text-green-600' : 'text-red-600' }} font-bold">
                                                {{ $history->type == 'credit' ? '+' : '-' }} Rp {{ number_format($history->amount, 0, ',', '.') }}
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
                        <div class="text-center py-12">
                            <svg class="mx-auto h-16 w-16 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                            <p class="text-lg font-medium text-gray-500">Tidak ada riwayat penarikan ditemukan.</p>
                        </div>
                    @else
                        <div class="overflow-x-auto rounded-xl border border-gray-200">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-brand-dark text-white">
                                    <tr>
                                        <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider">Tanggal</th>
                                        <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider">Jumlah</th>
                                        <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider">Bank</th>
                                        <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider">No. Rekening</th>
                                        <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider">Status</th>
                                        <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($withdrawals as $withdrawal)
                                        <tr class="hover:bg-blue-50 transition-colors duration-150">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $withdrawal->created_at->format('d M Y H:i') }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-brand-orange">Rp {{ number_format($withdrawal->amount, 0, ',', '.') }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $withdrawal->bank_name }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $withdrawal->bank_account_number }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-bold rounded-full 
                                                    @if($withdrawal->status == 'approved') bg-green-100 text-green-800
                                                    @elseif($withdrawal->status == 'rejected') bg-red-100 text-red-800
                                                    @else bg-yellow-100 text-yellow-800
                                                    @endif">
                                                    {{ match($withdrawal->status) {
                                                    'pending' => 'Menunggu',
                                                    'approved' => 'Disetujui',
                                                    'rejected' => 'Ditolak',
                                                    default => ucfirst($withdrawal->status)
                                                } }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                @if($withdrawal->status == 'pending')
                                                    <button type="button"
                                                            onclick="document.getElementById('cancel-withdrawal-{{ $withdrawal->id }}').classList.remove('hidden')"
                                                            class="text-red-600 hover:text-red-800 font-bold transition-colors">
                                                        Batalkan
                                                    </button>
                                                @else
                                                    <span class="text-gray-400">-</span>
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
