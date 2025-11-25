<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Saldo Toko') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                    {{ session('error') }}
                </div>
            @endif

            <!-- Balance Card -->
            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between items-center">
                        <div>
                            <h3 class="text-lg font-semibold mb-2">Saldo Saat Ini</h3>
                            <p class="text-3xl font-bold text-indigo-600">Rp {{ number_format($storeBalance->balance, 0, ',', '.') }}</p>
                        </div>
                        <div>
                            @if($hasPendingWithdrawal)
                                <div class="bg-yellow-50 border border-yellow-300 text-yellow-800 px-4 py-3 rounded-md">
                                    <p class="text-sm font-medium">Ada penarikan yang sedang diproses</p>
                                </div>
                            @else
                                <a href="{{ route('seller.withdrawals.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700">
                                    Tarik Saldo
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tabs -->
            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                <div class="border-b border-gray-200">
                    <nav class="-mb-px flex" aria-label="Tabs">
                        <button onclick="showTab('history')" id="tab-history" class="tab-button border-b-2 border-indigo-500 text-indigo-600 w-1/2 py-4 px-1 text-center font-medium text-sm">
                            Riwayat Saldo
                        </button>
                        <button onclick="showTab('withdrawal')" id="tab-withdrawal" class="tab-button border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 w-1/2 py-4 px-1 text-center font-medium text-sm">
                            Penarikan Saldo
                        </button>
                    </nav>
                </div>

                <!-- Balance History Tab -->
                <div id="content-history" class="tab-content p-6">
                    @if($balanceHistory->isEmpty())
                        <p class="text-center text-gray-500 py-8">Belum ada riwayat transaksi.</p>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tipe</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jumlah</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Keterangan</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($balanceHistory as $history)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $history->created_at->format('d M Y H:i') }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $history->type == 'credit' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                    {{ ucfirst($history->type) }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm {{ $history->type == 'credit' ? 'text-green-600' : 'text-red-600' }} font-medium">
                                                {{ $history->type == 'credit' ? '+' : '-' }} Rp {{ number_format($history->amount, 0, ',', '.') }}
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-500">{{ $history->remarks ?? '-' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-4">
                            {{ $balanceHistory->links() }}
                        </div>
                    @endif
                </div>

                <!-- Withdrawal Tab -->
                <div id="content-withdrawal" class="tab-content hidden p-6">
                    @if($withdrawals->isEmpty())
                        <p class="text-center text-gray-500 py-8">Belum ada riwayat penarikan saldo.</p>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jumlah</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Bank</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">No. Rekening</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($withdrawals as $withdrawal)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $withdrawal->created_at->format('d M Y H:i') }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">Rp {{ number_format($withdrawal->amount, 0, ',', '.') }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $withdrawal->bank_name }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $withdrawal->bank_account_number }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                    @if($withdrawal->status == 'approved') bg-green-100 text-green-800
                                                    @elseif($withdrawal->status == 'rejected') bg-red-100 text-red-800
                                                    @else bg-yellow-100 text-yellow-800
                                                    @endif">
                                                    {{ ucfirst($withdrawal->status) }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                @if($withdrawal->status == 'pending')
                                                    <button type="button"
                                                            onclick="document.getElementById('cancel-withdrawal-{{ $withdrawal->id }}').classList.remove('hidden')"
                                                            class="text-red-600 hover:text-red-900">
                                                        Batalkan
                                                    </button>
                                                @else
                                                    -
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-4">
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
            title="Batalkan Pengajuan Penarikan" 
            message="Apakah Anda yakin ingin membatalkan pengajuan penarikan sebesar Rp {{ number_format($withdrawal->amount, 0, ',', '.') }}?"
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
                el.classList.remove('border-indigo-500', 'text-indigo-600');
                el.classList.add('border-transparent', 'text-gray-500');
            });

            // Show selected tab
            document.getElementById('content-' + tabName).classList.remove('hidden');
            document.getElementById('tab-' + tabName).classList.remove('border-transparent', 'text-gray-500');
            document.getElementById('tab-' + tabName).classList.add('border-indigo-500', 'text-indigo-600');
        }
    </script>
</x-app-layout>
