<x-store-layout>
    <div class="py-12 bg-brand-gray min-h-screen">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-8 text-center">
                <h2 class="font-serif font-bold text-3xl text-brand-dark leading-tight">
                    {{ __('Daftarkan Toko Anda') }}
                </h2>
                <p class="mt-2 text-gray-500 max-w-2xl mx-auto">Mulai perjalanan Anda sebagai penjual di platform kami. Isi detail di bawah ini untuk membuat toko Anda dan menjangkau lebih banyak pelanggan.</p>
            </div>

            <div class="bg-white overflow-hidden shadow-xl rounded-2xl border border-gray-100">
                <div class="p-8 md:p-12">
                    @if (session('error'))
                        <div
                            class="mb-8 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl flex items-center shadow-sm">
                            <svg class="w-5 h-5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            {{ session('error') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('seller.stores.store') }}" enctype="multipart/form-data"
                        class="space-y-8">
                        @csrf

                        <!-- Store Details Section -->
                        <div>
                            <h3 class="text-lg font-bold text-brand-dark mb-6 flex items-center gap-2">
                                <span class="w-8 h-8 bg-brand-orange text-white rounded-full flex items-center justify-center text-sm">1</span>
                                Detail Toko
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                <div>
                                    <label for="name" class="block font-bold text-sm text-gray-700 mb-2">Nama Toko</label>
                                    <input id="name" class="w-full border-gray-300 focus:border-brand-orange focus:ring-brand-orange rounded-xl shadow-sm py-3 px-4" type="text" name="name" value="{{ old('name') }}" required autofocus placeholder="e.g. My Awesome Bookstore">
                                    @error('name')
                                        <span class="text-red-600 text-sm mt-1 block">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div>
                                    <label for="phone" class="block font-bold text-sm text-gray-700 mb-2">Nomor Telepon</label>
                                    <input id="phone" class="w-full border-gray-300 focus:border-brand-orange focus:ring-brand-orange rounded-xl shadow-sm py-3 px-4" type="text" name="phone" value="{{ old('phone') }}" required placeholder="e.g. 08123456789">
                                    @error('phone')
                                        <span class="text-red-600 text-sm mt-1 block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="mt-6">
                                <label for="about" class="block font-bold text-sm text-gray-700 mb-2">Tentang Toko</label>
                                <textarea id="about" class="w-full border-gray-300 focus:border-brand-orange focus:ring-brand-orange rounded-xl shadow-sm py-3 px-4" name="about" rows="4" required placeholder="Ceritakan sedikit tentang toko Anda dan apa yang Anda jual...">{{ old('about') }}</textarea>
                                @error('about')
                                    <span class="text-red-600 text-sm mt-1 block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <hr class="border-gray-100">

                        <!-- Location Section -->
                        <div>
                            <h3 class="text-lg font-bold text-brand-dark mb-6 flex items-center gap-2">
                                <span class="w-8 h-8 bg-brand-orange text-white rounded-full flex items-center justify-center text-sm">2</span>
                                Lokasi
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                <div>
                                    <label for="city" class="block font-bold text-sm text-gray-700 mb-2">Kota</label>
                                    <input id="city" class="w-full border-gray-300 focus:border-brand-orange focus:ring-brand-orange rounded-xl shadow-sm py-3 px-4" type="text" name="city" value="{{ old('city') }}" required placeholder="e.g. Jakarta Selatan">
                                    @error('city')
                                        <span class="text-red-600 text-sm mt-1 block">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div>
                                    <label for="postal_code" class="block font-bold text-sm text-gray-700 mb-2">Kode Pos</label>
                                    <input id="postal_code" class="w-full border-gray-300 focus:border-brand-orange focus:ring-brand-orange rounded-xl shadow-sm py-3 px-4" type="text" name="postal_code" value="{{ old('postal_code') }}" required placeholder="e.g. 12345">
                                    @error('postal_code')
                                        <span class="text-red-600 text-sm mt-1 block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="mt-6">
                                <label for="address" class="block font-bold text-sm text-gray-700 mb-2">Alamat Lengkap</label>
                                <textarea id="address" class="w-full border-gray-300 focus:border-brand-orange focus:ring-brand-orange rounded-xl shadow-sm py-3 px-4" name="address" rows="3" required placeholder="Street name, building number, etc.">{{ old('address') }}</textarea>
                                @error('address')
                                    <span class="text-red-600 text-sm mt-1 block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <hr class="border-gray-100">

                        <!-- Branding Section -->
                        <div>
                            <h3 class="text-lg font-bold text-brand-dark mb-6 flex items-center gap-2">
                                <span
                                    class="w-8 h-8 bg-brand-orange text-white rounded-full flex items-center justify-center text-sm">3</span>
                                Branding
                            </h3>
                            <div>
                                <label class="block font-bold text-sm text-gray-700 mb-2">Logo Toko</label>
                                <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-xl hover:border-brand-orange transition-colors group bg-gray-50 hover:bg-orange-50/30">
                                    <div class="space-y-1 text-center">
                                        <svg class="mx-auto h-12 w-12 text-gray-400 group-hover:text-brand-orange transition-colors"
                                            stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                            <path
                                                d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                        <div class="flex text-sm text-gray-600 justify-center">
                                            <label for="logo" class="relative cursor-pointer bg-white rounded-md font-medium text-brand-orange hover:text-brand-dark focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-brand-orange">
                                                <span>Unggah file</span>
                                                <input id="logo" name="logo" type="file" class="sr-only" accept="image/*" required>
                                            </label>
                                            <p class="pl-1">atau seret dan lepas</p>
                                        </div>
                                        <p class="text-xs text-gray-500">
                                            PNG, JPG, GIF hingga 2MB
                                        </p>
                                    </div>
                                </div>
                                @error('logo')
                                    <span class="text-red-600 text-sm mt-1 block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="flex items-center justify-end pt-8 border-t border-gray-100">
                            <button type="submit" class="w-full md:w-auto px-8 py-4 bg-brand-dark text-white font-bold rounded-xl hover:bg-brand-orange transition-all duration-300 shadow-lg transform hover:-translate-y-1 text-lg">
                                Buat Toko
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-store-layout>