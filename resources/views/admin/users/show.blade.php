<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.users.index') }}" class="p-2 bg-white rounded-xl border border-gray-200 hover:bg-gray-50 text-gray-500 hover:text-brand-dark transition-colors shadow-sm">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            </a>
            <div class="flex items-center gap-4">
                <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=random&color=fff&size=48" 
                     class="w-12 h-12 rounded-full border-2 border-white shadow-sm" alt="{{ $user->name }}">
                <div>
                    <h2 class=" font-bold text-2xl text-brand-dark leading-tight">
                        {{ $user->name }}
                    </h2>
                    <p class="text-sm text-gray-500">{{ $user->email }}</p>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- User Info -->
                <div class="lg:col-span-2 space-y-6">
                    <div class="bg-white overflow-hidden shadow-sm rounded-3xl border border-gray-100">
                        <div class="p-8 border-b border-gray-100 flex justify-between items-center">
                            <h3 class=" font-bold text-xl text-brand-dark">Informasi Akun</h3>
                            <span class="text-sm text-gray-400">ID: {{ $user->id }}</span>
                        </div>
                        <div class="p-8 grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
                            <div>
                                <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Nama Lengkap</label>
                                <p class="text-lg font-bold text-gray-900">{{ $user->name }}</p>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Email Address</label>
                                <p class="text-lg font-bold text-gray-900">{{ $user->email }}</p>
                            </div>
                             <div>
                                <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Status Verifikasi</label>
                                @if($user->email_verified_at)
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-green-50 text-green-700 border border-green-100">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                        Terverifikasi pada {{ $user->email_verified_at->format('d M Y') }}
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-yellow-50 text-yellow-700 border border-yellow-100">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        Belum Verifikasi Email
                                    </span>
                                @endif
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Bergabung Sejak</label>
                                <p class="text-lg font-bold text-gray-900">{{ $user->created_at->format('d F Y') }}</p>
                            </div>
                        </div>
                    </div>

                    @if($user->store)
                        <div class="bg-gradient-to-br from-orange-50 to-white overflow-hidden shadow-sm rounded-3xl border border-orange-100">
                            <div class="p-8 border-b border-orange-100/50 flex justify-between items-center">
                                <h3 class=" font-bold text-xl text-brand-dark flex items-center gap-2">
                                    <svg class="w-6 h-6 text-brand-orange" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                                    Informasi Toko
                                </h3>
                                <a href="{{ route('admin.stores.show', $user->store) }}" class="text-sm font-bold text-brand-orange hover:text-brand-dark transition-colors">Lihat Toko &rarr;</a>
                            </div>
                            <div class="p-8">
                                <div class="flex items-center gap-6">
                                     @if($user->store->logo)
                                        <img src="{{ $user->store->logo_url }}" alt="{{ $user->store->name }}" class="w-24 h-24 rounded-2xl object-cover border border-white shadow-md">
                                    @else
                                        <div class="w-24 h-24 rounded-2xl bg-brand-orange text-white flex items-center justify-center text-3xl font-bold shadow-md">
                                            {{ substr($user->store->name, 0, 1) }}
                                        </div>
                                    @endif
                                    <div>
                                        <h4 class="text-2xl font-bold text-gray-900 mb-1">{{ $user->store->name }}</h4>
                                        <p class="text-gray-600 mb-3">{{ Str::limit($user->store->description, 100) }}</p>
                                        <div class="flex gap-2">
                                            @if($user->store->is_verified)
                                                <span class="px-3 py-1 bg-green-100 text-green-700 text-xs font-bold rounded-full border border-green-200">Terverifikasi</span>
                                            @else
                                                <span class="px-3 py-1 bg-yellow-100 text-yellow-700 text-xs font-bold rounded-full border border-yellow-200">Menunggu Verifikasi</span>
                                            @endif
                                            <span class="px-3 py-1 bg-blue-100 text-blue-700 text-xs font-bold rounded-full border border-blue-200">Saldo: Rp {{ number_format($user->store->storeBalance ? $user->store->storeBalance->balance : 0, 0, ',', '.') }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Actions -->
                <div class="space-y-6">
                    <div class="bg-white overflow-hidden shadow-sm rounded-3xl border border-gray-100">
                        <div class="p-8">
                            <h3 class=" font-bold text-xl text-brand-dark mb-6">Kelola Role</h3>
                            <form action="{{ route('admin.users.update', $user) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <div class="mb-6">
                                    <label for="role" class="block text-sm font-bold text-gray-700 mb-2">Pilih Role Baru</label>
                                    @if($user->store)
                                        <div class="bg-yellow-50 border border-yellow-200 text-yellow-800 px-4 py-3 rounded-xl flex items-start gap-3 mb-4">
                                            <svg class="w-5 h-5 text-yellow-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                            <p class="text-sm">Pengguna ini memiliki toko. Role tidak dapat diubah menjadi Administrator untuk alasan keamanan.</p>
                                        </div>
                                        <input type="hidden" name="role" value="member">
                                    @endif
                                    
                                    <select name="role" id="role" class="w-full border-gray-200 focus:border-brand-orange focus:ring-brand-orange rounded-xl shadow-sm bg-gray-50/50 {{ $user->store ? 'cursor-not-allowed opacity-75' : '' }}" {{ $user->store ? 'disabled' : '' }}>
                                        <option value="member" {{ $user->role === 'member' ? 'selected' : '' }}>Member (Pengguna Biasa)</option>
                                        <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }} {{ $user->store ? 'disabled' : '' }}>Admin (Administrator)</option>
                                    </select>
                                    <p class="text-xs text-gray-500 mt-2">Mengubah role akan mempengaruhi hak akses pengguna ini.</p>
                                </div>
                                <button type="submit" class="w-full py-3 bg-brand-dark text-white font-bold rounded-xl hover:bg-brand-orange transition-all shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 {{ $user->store ? 'opacity-50 cursor-not-allowed' : '' }}" {{ $user->store ? 'disabled' : '' }}>
                                    Simpan Perubahan
                                </button>
                            </form>
                        </div>
                    </div>

                    <div class="bg-red-50 overflow-hidden shadow-sm rounded-3xl border border-red-100">
                        <div class="p-8">
                            <h3 class=" font-bold text-xl text-red-800 mb-2">Hapus Pengguna</h3>
                            <p class="text-sm text-red-600 mb-6">Tindakan ini tidak dapat dibatalkan. Semua data terkait pengguna ini akan dihapus.</p>
                            
                            <button type="button" onclick="document.getElementById('delete-user-modal').classList.remove('hidden')" class="w-full py-3 bg-white border-2 border-red-100 text-red-600 font-bold rounded-xl hover:bg-red-600 hover:text-white hover:border-red-600 transition-all shadow-sm">
                                Hapus Akun
                            </button>
                        </div>
                    </div>
                </div>
            </div>
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
