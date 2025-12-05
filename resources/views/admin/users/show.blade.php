<x-app-layout>
    <x-slot name="header">
        <h2 class="font-serif font-bold text-2xl text-brand-dark leading-tight">
            {{ __('Detail Pengguna: ') . $user->name }}
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

            @if (session('error'))
                <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl flex items-center shadow-sm">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    {{ session('error') }}
                </div>
            @endif

            <!-- User Information -->
            <div class="bg-white overflow-hidden shadow-lg rounded-2xl border border-gray-100">
                <div class="p-8">
                    <div class="flex items-center justify-between mb-8 border-b border-gray-100 pb-6">
                        <h3 class="text-2xl font-serif font-bold text-brand-dark">Informasi Pengguna</h3>
                        @php
                            $badgeClass = match(true) {
                                $user->role == 'admin' => 'bg-purple-100 text-purple-800',
                                $user->role == 'member' && $user->store => 'bg-brand-orange/10 text-brand-orange',
                                default => 'bg-blue-100 text-blue-800',
                            };
                            $roleLabel = match(true) {
                                $user->role == 'admin' => 'Admin',
                                $user->role == 'member' && $user->store => 'Penjual',
                                default => 'Anggota',
                            };
                        @endphp
                        <span class="px-4 py-2 inline-flex text-sm leading-5 font-bold rounded-full {{ $badgeClass }}">
                            {{ $roleLabel }}
                        </span>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                        <div class="space-y-6">
                            <div>
                                <p class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Nama</p>
                                <p class="text-lg font-bold text-brand-dark">{{ $user->name }}</p>
                            </div>
                            <div>
                                <p class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Email</p>
                                <p class="text-lg font-medium text-gray-900">{{ $user->email }}</p>
                            </div>
                        </div>

                        <div class="space-y-6">
                            <div>
                                <p class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Tanggal Bergabung</p>
                                <p class="text-lg font-medium text-gray-900">{{ $user->created_at->format('d M Y H:i') }}</p>
                            </div>
                            <div>
                                <p class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Email Terverifikasi</p>
                                <p class="text-lg font-medium text-gray-900 flex items-center">
                                    @if($user->email_verified_at)
                                        <svg class="w-5 h-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                        Ya
                                    @else
                                        <svg class="w-5 h-5 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                        Tidak
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Update Role Form -->
                    <div class="border-t border-gray-100 pt-8">
                        <h4 class="font-serif font-bold text-lg text-brand-dark mb-4">Ubah Peran</h4>
                        <form method="POST" action="{{ route('admin.users.update', $user) }}" class="flex flex-col md:flex-row gap-4 items-end">
                            @csrf
                            @method('PATCH')
                            
                            <div class="w-full md:w-1/3">
                                <select name="role" class="w-full border-gray-300 focus:border-brand-orange focus:ring-brand-orange rounded-xl shadow-sm">
                                    <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin</option>
                                    <option value="member" {{ $user->role == 'member' ? 'selected' : '' }}>Anggota</option>
                                </select>
                            </div>
                            
                            <button type="submit" class="w-full md:w-auto px-6 py-2 bg-brand-dark text-white font-bold rounded-xl hover:bg-brand-orange transition-colors shadow-md">
                                Perbarui Peran
                            </button>
                        </form>
                    </div>

                    <!-- Delete User -->
                    <div class="border-t border-gray-100 pt-8 mt-8">
                        <button type="button" 
                                onclick="document.getElementById('delete-user-modal').classList.remove('hidden')"
                                class="px-6 py-3 bg-red-600 text-white font-bold rounded-xl hover:bg-red-700 shadow-md hover:shadow-lg transition-all transform hover:-translate-y-0.5">
                            Hapus Pengguna
                        </button>
                    </div>
                </div>
            </div>

            <!-- Store Information (if user has a store) -->
            @if($user->store)
                <div class="bg-white overflow-hidden shadow-lg rounded-2xl border border-gray-100">
                    <div class="p-8">
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="text-2xl font-serif font-bold text-brand-dark">Informasi Toko</h3>
                            <a href="{{ route('admin.stores.show', ['store' => $user->store->id]) }}" class="text-brand-orange hover:text-brand-dark font-bold transition-colors flex items-center">
                                Lihat Detail Toko 
                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                            </a>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div class="space-y-6">
                                <div>
                                    <p class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Nama Toko</p>
                                    <p class="text-lg font-bold text-brand-dark">{{ $user->store->name }}</p>
                                </div>
                                <div>
                                    <p class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Kota</p>
                                    <p class="text-lg font-medium text-gray-900">{{ $user->store->city }}</p>
                                </div>
                                <div>
                                    <p class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Status Verifikasi</p>
                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-bold rounded-full {{ $user->store->is_verified ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                        {{ $user->store->is_verified ? 'Terverifikasi' : 'Menunggu Verifikasi' }}
                                    </span>
                                </div>
                            </div>

                            <div class="space-y-6">
                                <div>
                                    <p class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Total Produk</p>
                                    <p class="text-lg font-medium text-gray-900">{{ $user->store->products->count() }} produk</p>
                                </div>
                                @if($user->store->storeBalance)
                                    <div>
                                        <p class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Saldo</p>
                                        <p class="text-2xl font-bold text-green-600">Rp {{ number_format($user->store->storeBalance->balance, 0, ',', '.') }}</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Delete User Modal -->
    <x-confirmation-modal 
        id="delete-user-modal"
        type="danger" 
        title="Hapus Pengguna" 
        message="Apakah Anda yakin ingin menghapus {{ $user->name }}? Semua data terkait termasuk toko (jika ada) akan dihapus. Tindakan ini tidak dapat dibatalkan."
        confirmText="Ya, Hapus"
        cancelText="Batal">
    </x-confirmation-modal>
    <form id="delete-user-modal-form" method="POST" action="{{ route('admin.users.destroy', $user) }}" class="hidden">
        @csrf
        @method('DELETE')
    </form>
</x-app-layout>
