<x-app-layout>
    <x-slot name="header">
        <h2 class=" font-bold text-2xl text-brand-dark leading-tight">
            {{ __('Manajemen Toko') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="bg-white overflow-hidden shadow-sm rounded-3xl border border-gray-100 mb-8">
                <div class="p-8">
                    <form method="GET" action="{{ route('admin.stores.index') }}"
                        class="flex flex-col md:flex-row gap-4 items-end">
                        <div class="w-full md:w-1/3">
                            <label for="search" class="block font-bold text-sm text-gray-700 mb-2">Cari Toko</label>
                            <input type="text" name="q" id="search" value="{{ request('q') }}"
                                placeholder="Nama toko..."
                                class="w-full border-gray-200 focus:border-brand-orange focus:ring-brand-orange rounded-xl shadow-sm bg-gray-50/50">
                        </div>
                        <div class="w-full md:w-1/4">
                            <label for="status" class="block font-bold text-sm text-gray-700 mb-2">Status</label>
                            <select name="status" id="status"
                                class="w-full border-gray-200 focus:border-brand-orange focus:ring-brand-orange rounded-xl shadow-sm cursor-pointer bg-gray-50/50">
                                <option value="">Semua Status</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Menunggu
                                    Verifikasi</option>
                                <option value="verified" {{ request('status') == 'verified' ? 'selected' : '' }}>
                                    Terverifikasi</option>
                                <option value="deleted" {{ request('status') == 'deleted' ? 'selected' : '' }}>Dihapus
                                    (Nonaktif)</option>
                            </select>
                        </div>
                        <div class="w-full md:w-auto flex gap-3">
                            <button type="submit"
                                class="px-6 py-2.5 bg-brand-dark text-white font-bold rounded-xl hover:bg-brand-orange transition-all shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                                Filter
                            </button>
                            <a href="{{ route('admin.stores.index') }}"
                                class="px-6 py-2.5 bg-white border border-gray-200 text-gray-700 font-bold rounded-xl hover:bg-gray-50 transition-colors">
                                Reset
                            </a>
                        </div>
                    </form>
                </div>
            </div>


            <div class="bg-white overflow-hidden shadow-sm rounded-3xl border border-gray-100">
                <div class="p-8">
                    <div class="overflow-x-auto">
                        <table class="min-w-full">
                            <thead>
                                <tr class="border-b border-gray-100">
                                    <th
                                        class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-wider pl-4">
                                        Toko</th>
                                    <th
                                        class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">
                                        Pemilik</th>
                                    <th
                                        class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">
                                        Status</th>
                                    <th
                                        class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">
                                        Saldo</th>
                                    <th
                                        class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">
                                        Bergabung</th>
                                    <th
                                        class="px-6 py-4 text-right text-xs font-bold text-gray-400 uppercase tracking-wider pr-4">
                                        Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50">
                                @foreach ($stores as $store)
                                    <tr class="hover:bg-blue-50/50 transition-colors duration-200 group">
                                        <td class="px-6 py-4 whitespace-nowrap pl-4">
                                            <div class="flex items-center gap-4">
                                                @if($store->logo)
                                                    <img src="{{ $store->logo_url }}" alt="{{ $store->name }}"
                                                        class="w-10 h-10 rounded-full object-cover border border-white shadow-sm">
                                                @else
                                                    <div
                                                        class="w-10 h-10 rounded-full bg-brand-orange text-white flex items-center justify-center text-sm font-bold shadow-sm">
                                                        {{ substr($store->name, 0, 1) }}
                                                    </div>
                                                @endif
                                                <div>
                                                    <div class="text-sm font-bold text-gray-900">{{ $store->name }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="text-sm font-medium text-gray-600">{{ $store->user->name }}</span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($store->trashed())
                                                <span
                                                    class="px-3 py-1 inline-flex items-center gap-1.5 text-xs font-bold rounded-full bg-red-50 text-red-700 border border-red-100">
                                                    Nonaktif
                                                </span>
                                            @elseif($store->is_verified)
                                                <span
                                                    class="px-3 py-1 inline-flex items-center gap-1.5 text-xs font-bold rounded-full bg-green-50 text-green-700 border border-green-100">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M5 13l4 4L19 7"></path>
                                                    </svg>
                                                    Terverifikasi
                                                </span>
                                            @else
                                                <span
                                                    class="px-3 py-1 inline-flex items-center gap-1.5 text-xs font-bold rounded-full bg-yellow-50 text-yellow-700 border border-yellow-100">
                                                    Belum Verifikasi
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="text-sm font-bold text-gray-700">Rp
                                                {{ number_format($store->storeBalance ? $store->storeBalance->balance : 0, 0, ',', '.') }}</span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $store->created_at->format('d M Y') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium pr-4">
                                            <div class="flex items-center justify-end gap-2">
                                                @if($store->trashed())
                                                    <form method="POST" action="{{ route('admin.stores.restore', $store) }}">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit"
                                                            class="px-4 py-2 bg-green-50 text-green-700 font-bold rounded-xl hover:bg-green-100 transition-colors border border-green-200 text-xs shadow-sm">
                                                            Aktifkan Kembali
                                                        </button>
                                                    </form>
                                                @else
                                                    <a href="{{ route('admin.stores.show', $store) }}"
                                                        class="inline-flex items-center px-4 py-2 bg-white border border-gray-200 rounded-xl text-sm font-bold text-gray-700 hover:bg-brand-dark hover:text-white hover:border-brand-dark transition-all shadow-sm group-hover:shadow-md">
                                                        Detail
                                                    </a>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-8 px-4 border-t border-gray-100 pt-6">
                        {{ $stores->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>