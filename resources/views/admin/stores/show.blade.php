<x-app-layout>
    <x-slot name="header">
        <h2 class="font-serif font-bold text-2xl text-brand-dark leading-tight">
            {{ __('Detail Toko: ') . $store->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            @if (session('success'))
                <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl flex items-center shadow-sm">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    {{ session('success') }}
                </div>
            @endif

            @if (session('info'))
                <div class="bg-blue-50 border border-blue-200 text-blue-700 px-4 py-3 rounded-xl flex items-center shadow-sm">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    {{ session('info') }}
                </div>
            @endif

            @if (session('error'))
                <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl flex items-center shadow-sm">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    {{ session('error') }}
                </div>
            @endif

            <!-- Store Information -->
            <div class="bg-white overflow-hidden shadow-lg rounded-2xl border border-gray-100">
                <div class="p-8">
                    <div class="flex items-center justify-between mb-8 border-b border-gray-100 pb-6">
                        <h3 class="text-2xl font-serif font-bold text-brand-dark">Informasi Toko</h3>
                        <span class="px-4 py-2 inline-flex text-sm leading-5 font-bold rounded-full {{ $store->is_verified ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                            {{ $store->is_verified ? 'Terverifikasi' : 'Menunggu Verifikasi' }}
                        </span>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                        <!-- Logo Column -->
                        <div class="md:col-span-1">
                            @if($store->logo)
                                <img src="{{ asset('storage/' . $store->logo) }}" alt="{{ $store->name }}" class="w-full h-auto object-cover rounded-2xl shadow-md border border-gray-100">
                            @else
                                <div class="w-full h-64 bg-gray-100 rounded-2xl flex items-center justify-center text-gray-400 border border-gray-200">
                                    <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                </div>
                            @endif
                        </div>

                        <!-- Details Column -->
                        <div class="md:col-span-2 space-y-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <p class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Nama Toko</p>
                                    <p class="text-lg font-bold text-brand-dark">{{ $store->name }}</p>
                                </div>
                                <div>
                                    <p class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Pemilik</p>
                                    <p class="text-lg font-medium text-gray-900">{{ $store->user->name }}</p>
                                    <p class="text-sm text-gray-500">{{ $store->user->email }}</p>
                                </div>
                                <div>
                                    <p class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Nomor Telepon</p>
                                    <p class="text-lg font-medium text-gray-900">{{ $store->phone }}</p>
                                </div>
                                <div>
                                    <p class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Total Produk</p>
                                    <p class="text-lg font-medium text-gray-900">{{ $store->products->count() }} produk</p>
                                </div>
                                <div class="md:col-span-2">
                                    <p class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Alamat</p>
                                    <p class="text-lg font-medium text-gray-900">{{ $store->address }}, {{ $store->city }}, {{ $store->postal_code }}</p>
                                </div>
                                <div class="md:col-span-2">
                                    <p class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Tentang Toko</p>
                                    <p class="text-base text-gray-700 leading-relaxed">{{ $store->about }}</p>
                                </div>
                                @if($store->storeBalance)
                                    <div class="md:col-span-2">
                                        <p class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Saldo Toko</p>
                                        <p class="text-2xl font-bold text-green-600">Rp {{ number_format($store->storeBalance->balance, 0, ',', '.') }}</p>
                                    </div>
                                @endif
                            </div>

                            <!-- Action Buttons -->
                            <div class="pt-6 border-t border-gray-100 flex flex-wrap gap-4">
                                @if(!$store->is_verified)
                                    <button type="button" 
                                            onclick="document.getElementById('verify-modal').classList.remove('hidden')"
                                            class="px-6 py-3 bg-green-600 text-white font-bold rounded-xl hover:bg-green-700 shadow-md hover:shadow-lg transition-all transform hover:-translate-y-0.5">
                                        Verifikasi Toko
                                    </button>

                                    <button type="button" 
                                            onclick="document.getElementById('reject-modal').classList.remove('hidden')"
                                            class="px-6 py-3 bg-red-600 text-white font-bold rounded-xl hover:bg-red-700 shadow-md hover:shadow-lg transition-all transform hover:-translate-y-0.5">
                                        Tolak Toko
                                    </button>
                                @endif

                                <button type="button" 
                                        onclick="document.getElementById('delete-modal').classList.remove('hidden')"
                                        class="px-6 py-3 bg-gray-600 text-white font-bold rounded-xl hover:bg-gray-700 shadow-md hover:shadow-lg transition-all transform hover:-translate-y-0.5">
                                    Hapus Toko
                                </button>
                                
                                <a href="{{ route('admin.stores.index') }}" class="px-6 py-3 bg-white border border-gray-300 text-gray-700 font-bold rounded-xl hover:bg-gray-50 transition-colors">
                                    Kembali ke Daftar
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Verify Modal -->
    @if(!$store->is_verified)
    <x-confirmation-modal 
        id="verify-modal"
        type="success" 
        title="Verifikasi Toko" 
        message="Apakah Anda yakin ingin memverifikasi {{ $store->name }}? Toko akan mendapatkan akses penuh untuk mulai berjualan."
        confirmText="Ya, Verifikasi"
        cancelText="Batal">
    </x-confirmation-modal>
    <form id="verify-modal-form" method="POST" action="{{ route('admin.stores.verify', ['store' => $store->id]) }}" class="hidden">
        @csrf
        @method('PATCH')
    </form>

    <!-- Reject Modal -->
    <x-confirmation-modal 
        id="reject-modal"
        type="danger" 
        title="Tolak Pengajuan Toko" 
        message="Apakah Anda yakin ingin menolak dan menghapus {{ $store->name }}? Tindakan ini tidak dapat dibatalkan."
        confirmText="Ya, Tolak & Hapus"
        cancelText="Batal">
    </x-confirmation-modal>
    <form id="reject-modal-form" method="POST" action="{{ route('admin.stores.reject', ['store' => $store->id]) }}" class="hidden">
        @csrf
        @method('DELETE')
    </form>
    @endif

    <!-- Delete Modal -->
    <x-confirmation-modal 
        id="delete-modal"
        type="danger" 
        title="Hapus Toko" 
        message="Apakah Anda yakin ingin menghapus {{ $store->name }}? Semua data terkait akan dihapus dan tindakan ini tidak dapat dibatalkan."
        confirmText="Ya, Hapus"
        cancelText="Batal">
    </x-confirmation-modal>
    <form id="delete-modal-form" method="POST" action="{{ route('admin.stores.destroy', ['store' => $store->id]) }}" class="hidden">
        @csrf
        @method('DELETE')
    </form>
</x-app-layout>
