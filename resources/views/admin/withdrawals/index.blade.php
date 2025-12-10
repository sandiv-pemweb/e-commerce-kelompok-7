<x-app-layout>
    <x-slot name="header">
        <h2 class=" font-bold text-2xl text-brand-dark leading-tight">
            {{ __('Manajemen Penarikan') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            @if (session('success'))
                <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl flex items-center shadow-sm">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    {{ session('success') }}
                </div>
            @endif


            <div class="bg-white overflow-hidden shadow-sm rounded-3xl border border-gray-100 mb-8">
                <div class="p-8">
                    <form method="GET" class="flex flex-col md:flex-row gap-4 items-end">
                        <div class="flex-1 w-full">
                            <label for="status" class="block text-sm font-bold text-gray-700 mb-2">Filter Status</label>
                            <select name="status" id="status" class="w-full border-gray-200 focus:border-brand-orange focus:ring-brand-orange rounded-xl shadow-sm cursor-pointer bg-gray-50/50">
                                <option value="">Semua Status</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Menunggu</option>
                                <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Disetujui</option>
                                <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Ditolak</option>
                            </select>
                        </div>
                        <button type="submit"
                            class="w-full md:w-auto px-6 py-2.5 bg-brand-dark text-white font-bold rounded-xl hover:bg-brand-orange transition-all shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                            Filter
                        </button>
                    </form>
                </div>
            </div>


            <div class="bg-white overflow-hidden shadow-sm rounded-3xl border border-gray-100">
                <div class="p-8">
                    @if($withdrawals->isEmpty())
                        <div class="text-center py-6">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">Tidak ada penarikan ditemukan</h3>
                            <p class="mt-1 text-sm text-gray-500">Tidak ada permintaan penarikan yang cocok dengan kriteria Anda.</p>
                        </div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full">
                                <thead>
                                    <tr class="border-b border-gray-100">
                                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-wider pl-4">Toko</th>
                                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Tanggal</th>
                                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Jumlah</th>
                                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Bank</th>
                                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">No. Rekening</th>
                                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Status</th>
                                        <th class="px-6 py-4 text-right text-xs font-bold text-gray-400 uppercase tracking-wider pr-4">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-50">
                                    @foreach($withdrawals as $withdrawal)
                                        <tr class="hover:bg-blue-50/50 transition-colors duration-200 group">
                                            <td class="px-6 py-4 whitespace-nowrap pl-4">
                                                <div class="flex items-center gap-4">
                                                    @if($withdrawal->storeBalance->store->logo)
                                                        <img class="w-10 h-10 rounded-full object-cover border border-white shadow-sm" src="{{ $withdrawal->storeBalance->store->logo_url }}" alt="{{ $withdrawal->storeBalance->store->name }}">
                                                    @else
                                                        <div class="w-10 h-10 rounded-full bg-brand-orange text-white flex items-center justify-center text-sm font-bold shadow-sm">
                                                            {{ substr($withdrawal->storeBalance->store->name, 0, 1) }}
                                                        </div>
                                                    @endif
                                                    <div>
                                                        <div class="text-sm font-bold text-gray-900">{{ $withdrawal->storeBalance->store->name }}</div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $withdrawal->created_at->format('d M Y H:i') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-green-600">
                                                Rp {{ number_format($withdrawal->amount, 0, ',', '.') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                                {{ $withdrawal->bank_name }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                                {{ $withdrawal->bank_account_number }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="px-3 py-1 inline-flex items-center gap-1.5 text-xs font-bold rounded-full 
                                                    @if($withdrawal->status == 'approved') bg-green-50 text-green-700 border border-green-100
                                                    @elseif($withdrawal->status == 'rejected') bg-red-50 text-red-700 border border-red-100
                                                    @else bg-yellow-50 text-yellow-700 border border-yellow-100
                                                    @endif">
                                                    @if($withdrawal->status == 'approved') Disetujui
                                                    @elseif($withdrawal->status == 'rejected') Ditolak
                                                    @else Menunggu
                                                    @endif
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium pr-4">
                                                <a href="{{ route('admin.withdrawals.show', $withdrawal) }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-200 rounded-xl text-sm font-bold text-gray-700 hover:bg-brand-dark hover:text-white hover:border-brand-dark transition-all shadow-sm group-hover:shadow-md">
                                                    Detail
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-8 px-4 border-t border-gray-100 pt-6">
                            {{ $withdrawals->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
