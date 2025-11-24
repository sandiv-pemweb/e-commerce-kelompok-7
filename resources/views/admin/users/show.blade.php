<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detail Pengguna: ') . $user->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
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

            <!-- User Information -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-semibold mb-4">Informasi Pengguna</h3>
                    
                    <div class="grid grid-cols-2 gap-6 mb-6">
                        <div class="space-y-3">
                            <div>
                                <p class="text-sm text-gray-600">Nama</p>
                                <p class="font-medium">{{ $user->name }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Email</p>
                                <p class="font-medium">{{ $user->email }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Role</p>
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $user->role == 'admin' ? 'bg-purple-100 text-purple-800' : 'bg-blue-100 text-blue-800' }}">
                                    {{ ucfirst($user->role) }}
                                </span>
                            </div>
                        </div>

                        <div class="space-y-3">
                            <div>
                                <p class="text-sm text-gray-600">Tanggal Daftar</p>
                                <p class="font-medium">{{ $user->created_at->format('d M Y H:i') }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Email Terverifikasi</p>
                                <p class="font-medium">{{ $user->email_verified_at ? 'Ya' : 'Tidak' }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Update Role Form -->
                    <div class="border-t pt-4">
                        <h4 class="font-semibold mb-3">Ubah Role</h4>
                        <form method="POST" action="{{ route('admin.users.update', $user) }}" class="flex gap-3 items-end">
                            @csrf
                            @method('PATCH')
                            
                            <div class="flex-1">
                                <select name="role" class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                    <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin</option>
                                    <option value="member" {{ $user->role == 'member' ? 'selected' : '' }}>Member</option>
                                </select>
                            </div>
                            
                            <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                                Perbarui Role
                            </button>
                        </form>
                    </div>

                    <!-- Delete User -->
                    <div class="border-t pt-4 mt-4">
                        <button type="button" 
                                onclick="document.getElementById('delete-user-modal').classList.remove('hidden')"
                                class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">
                            Hapus Pengguna
                        </button>
                    </div>
                </div>
            </div>

            <!-- Store Information (if user has a store) -->
            @if($user->store)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h3 class="text-lg font-semibold mb-4">Informasi Toko</h3>
                        
                        <div class="grid grid-cols-2 gap-6">
                            <div class="space-y-3">
                                <div>
                                    <p class="text-sm text-gray-600">Nama Toko</p>
                                    <p class="font-medium">{{ $user->store->name }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600">Kota</p>
                                    <p class="font-medium">{{ $user->store->city }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600">Status Verifikasi</p>
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $user->store->is_verified ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                        {{ $user->store->is_verified ? 'Terverifikasi' : 'Menunggu Verifikasi' }}
                                    </span>
                                </div>
                            </div>

                            <div class="space-y-3">
                                <div>
                                    <p class="text-sm text-gray-600">Jumlah Produk</p>
                                    <p class="font-medium">{{ $user->store->products->count() }} produk</p>
                                </div>
                                @if($user->store->storeBalance)
                                    <div>
                                        <p class="text-sm text-gray-600">Saldo</p>
                                        <p class="font-medium">Rp {{ number_format($user->store->storeBalance->balance, 0, ',', '.') }}</p>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="mt-4">
                            <a href="{{ route('admin.stores.show', $user->store) }}" class="text-indigo-600 hover:text-indigo-900">Lihat Detail Toko →</a>
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
        message="Apakah Anda yakin ingin menghapus pengguna {{ $user->name }}? Semua data terkait termasuk toko (jika ada) akan dihapus. Tindakan ini tidak dapat dibatalkan."
        confirmText="Ya, Hapus"
        cancelText="Batal">
    </x-confirmation-modal>
    <form id="delete-user-modal-form" method="POST" action="{{ route('admin.users.destroy', $user) }}" class="hidden">
        @csrf
        @method('DELETE')
    </form>
</x-app-layout>
