<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detail Toko: ') . $store->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('info'))
                <div class="bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded">
                    {{ session('info') }}
                </div>
            @endif

            @if (session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                    {{ session('error') }}
                </div>
            @endif

            <!-- Store Information -->
            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-semibold mb-4">Informasi Toko</h3>
                    
                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            @if($store->logo)
                                <img src="{{ asset('storage/' . $store->logo) }}" alt="{{ $store->name }}" class="h-32 w-32 object-cover rounded-lg border mb-4">
                            @endif
                            
                            <div class="space-y-3">
                                <div>
                                    <p class="text-sm text-gray-600">Nama Toko</p>
                                    <p class="font-medium">{{ $store->name }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600">Pemilik</p>
                                    <p class="font-medium">{{ $store->user->name }} ({{ $store->user->email }})</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600">No. Telepon</p>
                                    <p class="font-medium">{{ $store->phone }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600">Status Verifikasi</p>
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $store->is_verified ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                        {{ $store->is_verified ? 'Terverifikasi' : 'Menunggu Verifikasi' }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="space-y-3">
                            <div>
                                <p class="text-sm text-gray-600">Tentang Toko</p>
                                <p class="font-medium">{{ $store->about }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Alamat</p>
                                <p class="font-medium">{{ $store->address }}, {{ $store->city }}, {{ $store->postal_code }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Jumlah Produk</p>
                                <p class="font-medium">{{ $store->products->count() }} produk</p>
                            </div>
                            @if($store->storeBalance)
                                <div>
                                    <p class="text-sm text-gray-600">Saldo Toko</p>
                                    <p class="font-medium">Rp {{ number_format($store->storeBalance->balance, 0, ',', '.') }}</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="mt-6 flex gap-3">
                        @if(!$store->is_verified)
                            <button type="button" 
                                    onclick="document.getElementById('verify-modal').classList.remove('hidden')"
                                    class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">
                                Verifikasi Toko
                            </button>

                            <button type="button" 
                                    onclick="document.getElementById('reject-modal').classList.remove('hidden')"
                                    class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">
                                Tolak Toko
                            </button>
                        @endif

                        <button type="button" 
                                onclick="document.getElementById('delete-modal').classList.remove('hidden')"
                                class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700">
                            Hapus Toko
                        </button>
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
        message="Apakah Anda yakin ingin memverifikasi toko {{ $store->name }}? Toko akan mendapatkan akses penuh untuk mulai berjualan."
        confirmText="Ya, Verifikasi"
        cancelText="Batal">
    </x-confirmation-modal>
    <form id="verify-modal-form" method="POST" action="{{ route('admin.stores.verify', $store) }}" class="hidden">
        @csrf
        @method('PATCH')
    </form>

    <!-- Reject Modal -->
    <x-confirmation-modal 
        id="reject-modal"
        type="danger" 
        title="Tolak Pendaftaran Toko" 
        message="Apakah Anda yakin ingin menolak dan menghapus toko {{ $store->name }}? Tindakan ini tidak dapat dibatalkan."
        confirmText="Ya, Tolak & Hapus"
        cancelText="Batal">
    </x-confirmation-modal>
    <form id="reject-modal-form" method="POST" action="{{ route('admin.stores.reject', $store) }}" class="hidden">
        @csrf
        @method('DELETE')
    </form>
    @endif

    <!-- Delete Modal -->
    <x-confirmation-modal 
        id="delete-modal"
        type="danger" 
        title="Hapus Toko" 
        message="Apakah Anda yakin ingin menghapus toko {{ $store->name }}? Semua data terkait akan dihapus dan tindakan ini tidak dapat dibatalkan."
        confirmText="Ya, Hapus"
        cancelText="Batal">
    </x-confirmation-modal>
    <form id="delete-modal-form" method="POST" action="{{ route('admin.stores.destroy', $store) }}" class="hidden">
        @csrf
        @method('DELETE')
    </form>
</x-app-layout>
