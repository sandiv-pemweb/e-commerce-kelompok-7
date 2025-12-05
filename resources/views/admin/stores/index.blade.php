<x-app-layout>
    <x-slot name="header">
        <h2 class="font-serif font-bold text-2xl text-brand-dark leading-tight">
            {{ __('Manajemen Toko') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Filter Form -->
            <div class="bg-white overflow-hidden shadow-lg rounded-2xl mb-8 border border-gray-100">
                <div class="p-6">
                    <form method="GET" action="{{ route('admin.stores.index') }}" class="flex flex-col md:flex-row gap-4">
                        <div class="flex-grow">
                            <select name="status" class="w-full border-gray-300 focus:border-brand-orange focus:ring-brand-orange rounded-xl shadow-sm">
                                <option value="">Semua Status</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Menunggu Verifikasi</option>
                                <option value="verified" {{ request('status') == 'verified' ? 'selected' : '' }}>Terverifikasi</option>
                                <option value="deleted" {{ request('status') == 'deleted' ? 'selected' : '' }}>Dihapus</option>
                            </select>
                        </div>

                        <button type="submit" class="px-6 py-2 bg-brand-dark text-white font-bold rounded-xl hover:bg-brand-orange transition-colors shadow-md">
                            Filter
                        </button>
                        <a href="{{ route('admin.stores.index') }}" class="px-6 py-2 bg-gray-100 text-gray-700 font-bold rounded-xl hover:bg-gray-200 transition-colors text-center">
                            Reset
                        </a>
                    </form>
                </div>
            </div>

            <!-- Stores List -->
            <div class="bg-white overflow-hidden shadow-lg rounded-2xl border border-gray-100">
                <div class="p-6">
                    @if (session('success'))
                        <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            {{ session('success') }}
                        </div>
                    @endif

                    @if($stores->isEmpty())
                        <div class="text-center py-12">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">Tidak ada toko ditemukan</h3>
                            <p class="mt-1 text-sm text-gray-500">Coba sesuaikan pencarian atau filter Anda untuk menemukan apa yang Anda cari.</p>
                        </div>
                    @else
                        <div class="overflow-x-auto rounded-xl border border-gray-200">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-brand-dark">
                                    <tr>
                                        <th class="px-6 py-4 text-left text-xs font-bold text-white uppercase tracking-wider">Nama Toko</th>
                                        <th class="px-6 py-4 text-left text-xs font-bold text-white uppercase tracking-wider">Pemilik</th>
                                        <th class="px-6 py-4 text-left text-xs font-bold text-white uppercase tracking-wider">Kota</th>
                                        <th class="px-6 py-4 text-left text-xs font-bold text-white uppercase tracking-wider">Status</th>
                                        <th class="px-6 py-4 text-left text-xs font-bold text-white uppercase tracking-wider">Tanggal Bergabung</th>
                                        <th class="px-6 py-4 text-left text-xs font-bold text-white uppercase tracking-wider">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($stores as $store)
                                        <tr class="hover:bg-blue-50 transition-colors duration-150">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-brand-dark">{{ $store->name }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $store->user->name }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $store->city }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @if($store->trashed())
                                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-bold rounded-full bg-red-100 text-red-800">
                                                        Dihapus
                                                    </span>
                                                @else
                                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-bold rounded-full {{ $store->is_verified ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                                        {{ $store->is_verified ? 'Terverifikasi' : 'Menunggu' }}
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $store->created_at->format('d M Y') }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                @if($store->trashed())
                                                    <button type="button" 
                                                            onclick="document.getElementById('restore-modal-{{ $store->id }}').classList.remove('hidden')"
                                                            class="text-green-600 hover:text-green-900 font-bold transition-colors">
                                                        Pulihkan
                                                    </button>
                                                    
                                                    <x-confirmation-modal 
                                                        id="restore-modal-{{ $store->id }}"
                                                        type="success"
                                                        title="Pulihkan Toko"
                                                        message="Apakah Anda yakin ingin memulihkan '{{ $store->name }}'? Toko akan aktif kembali."
                                                        confirmText="Ya, Pulihkan"
                                                        cancelText="Batal" />
                                                    
                                                    <form id="restore-modal-{{ $store->id }}-form" 
                                                          action="{{ route('admin.stores.restore', $store->id) }}" 
                                                          method="POST" 
                                                          class="hidden">
                                                        @csrf
                                                        @method('PATCH')
                                                    </form>
                                                @else
                                                    <a href="{{ route('admin.stores.show', ['store' => $store->id]) }}" class="text-brand-orange hover:text-brand-dark font-bold transition-colors">Lihat Detail</a>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-6">
                            {{ $stores->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
