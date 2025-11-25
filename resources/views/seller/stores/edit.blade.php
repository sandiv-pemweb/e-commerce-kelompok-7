<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Kelola Profil Toko') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                <div class="p-6 text-gray-900">
                    @if (session('success'))
                        <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                            {{ session('error') }}
                        </div>
                    @endif

                    <!-- Current Logo -->
                    @if($store->logo)
                        <div class="mb-6">
                            <label class="block font-medium text-sm text-gray-700 mb-2">Logo Saat Ini</label>
                            <img src="{{ asset('storage/' . $store->logo) }}" alt="{{ $store->name }}" class="h-32 w-32 object-cover rounded-lg border">
                        </div>
                    @endif

                    <form method="POST" action="{{ route('seller.stores.update') }}" enctype="multipart/form-data" class="space-y-6">
                        @csrf
                        @method('PATCH')

                        <!-- Store Name -->
                        <div>
                            <label for="name" class="block font-medium text-sm text-gray-700">Nama Toko</label>
                            <input id="name" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" type="text" name="name" value="{{ old('name', $store->name) }}" required>
                            @error('name')
                                <span class="text-red-600 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Logo -->
                        <div>
                            <label for="logo" class="block font-medium text-sm text-gray-700">Logo Toko (Kosongkan jika tidak ingin mengubah)</label>
                            <input id="logo" class="block mt-1 w-full" type="file" name="logo" accept="image/*">
                            @error('logo')
                                <span class="text-red-600 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- About -->
                        <div>
                            <label for="about" class="block font-medium text-sm text-gray-700">Tentang Toko</label>
                            <textarea id="about" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" name="about" rows="4" required>{{ old('about', $store->about) }}</textarea>
                            @error('about')
                                <span class="text-red-600 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Phone -->
                        <div>
                            <label for="phone" class="block font-medium text-sm text-gray-700">Nomor Telepon</label>
                            <input id="phone" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" type="text" name="phone" value="{{ old('phone', $store->phone) }}" required>
                            @error('phone')
                                <span class="text-red-600 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Address ID (Auto-generated, Read-only) -->
                        <div>
                            <label for="address_id" class="block font-medium text-sm text-gray-700">
                                ID Alamat <span class="text-gray-500 font-normal">(Auto-generated)</span>
                            </label>
                            <input id="address_id" class="block mt-1 w-full border-gray-300 bg-gray-100 cursor-not-allowed rounded-md shadow-sm" type="text" name="address_id" value="{{ old('address_id', $store->address_id) }}" readonly>
                            <p class="mt-1 text-sm text-gray-500">
                                ID unik untuk alamat toko ini. Dihasilkan otomatis oleh sistem.
                            </p>
                        </div>

                        <!-- City -->
                        <div>
                            <label for="city" class="block font-medium text-sm text-gray-700">Kota</label>
                            <input id="city" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" type="text" name="city" value="{{ old('city', $store->city) }}" required>
                            @error('city')
                                <span class="text-red-600 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Address -->
                        <div>
                            <label for="address" class="block font-medium text-sm text-gray-700">Alamat Lengkap</label>
                            <textarea id="address" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" name="address" rows="3" required>{{ old('address', $store->address) }}</textarea>
                            @error('address')
                                <span class="text-red-600 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Postal Code -->
                        <div>
                            <label for="postal_code" class="block font-medium text-sm text-gray-700">Kode Pos</label>
                            <input id="postal_code" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" type="text" name="postal_code" value="{{ old('postal_code', $store->postal_code) }}" required>
                            @error('postal_code')
                                <span class="text-red-600 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <hr class="my-6">
                        <h3 class="text-lg font-semibold mb-4">Informasi Rekening Bank</h3>

                        <!-- Bank Name -->
                        <div>
                            <label for="bank_name" class="block font-medium text-sm text-gray-700">Nama Bank</label>
                            <input id="bank_name" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" type="text" name="bank_name" value="{{ old('bank_name', $store->bank_name) }}">
                            @error('bank_name')
                                <span class="text-red-600 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Bank Account Name -->
                        <div>
                            <label for="bank_account_name" class="block font-medium text-sm text-gray-700">Nama Pemilik Rekening</label>
                            <input id="bank_account_name" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" type="text" name="bank_account_name" value="{{ old('bank_account_name', $store->bank_account_name) }}">
                            @error('bank_account_name')
                                <span class="text-red-600 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Bank Account Number -->
                        <div>
                            <label for="bank_account_number" class="block font-medium text-sm text-gray-700">Nomor Rekening</label>
                            <input id="bank_account_number" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" type="text" name="bank_account_number" value="{{ old('bank_account_number', $store->bank_account_number) }}">
                            @error('bank_account_number')
                                <span class="text-red-600 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="flex items-center justify-between mt-6">
                            <button type="button"
                                    onclick="document.getElementById('delete-store-modal').classList.remove('hidden')"
                                    class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:bg-red-700 active:bg-red-900 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Nonaktifkan Toko
                            </button>

                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Perbarui Profil Toko
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Store Modal -->
    <x-confirmation-modal 
        id="delete-store-modal"
        type="danger" 
        title="Nonaktifkan Toko?" 
        message="Apakah Anda yakin ingin menonaktifkan toko ini? Toko Anda tidak akan terlihat oleh pembeli, namun data produk dan transaksi tetap tersimpan. Anda dapat memulihkan toko Anda kapan saja."
        confirmText="Ya, Nonaktifkan Toko"
        cancelText="Batal">
    </x-confirmation-modal>
    <form id="delete-store-modal-form" method="POST" action="{{ route('seller.stores.destroy') }}" class="hidden">
        @csrf
        @method('DELETE')
    </form>
</x-app-layout>
