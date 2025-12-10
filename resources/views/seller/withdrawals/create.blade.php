<x-app-layout>
    <x-slot name="header">
        <h2 class=" font-bold text-2xl text-brand-dark leading-tight">
            {{ __('Ajukan Penarikan') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
            @if (session('error'))
                <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl flex items-center shadow-sm">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    {{ session('error') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm rounded-3xl border border-gray-100">
                <div class="p-8">

                    <div
                        class="mb-8 p-8 bg-gradient-to-br from-blue-50 to-white rounded-2xl border border-blue-100 flex items-center justify-between">
                        <div>
                            <p class="text-xs font-bold text-blue-600 uppercase tracking-wider mb-2">Saldo Tersedia
                                Untuk Ditarik</p>
                            <p class="text-4xl font-bold text-brand-dark">Rp
                                {{ number_format($storeBalance->balance, 0, ',', '.') }}</p>
                        </div>
                        <div
                            class="h-16 w-16 bg-blue-100 rounded-2xl flex items-center justify-center text-blue-600 shadow-inner">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                                </path>
                            </svg>
                        </div>
                    </div>


                    @if($store->bank_name && $store->bank_account_name && $store->bank_account_number)
                        <div class="mb-8 p-6 bg-gray-50 rounded-2xl border border-gray-100">
                            <h4 class=" font-bold text-lg text-brand-dark mb-4 flex items-center gap-2">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"></path>
                                </svg>
                                Rekening Tujuan
                            </h4>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div>
                                    <span
                                        class="text-xs text-gray-400 font-bold uppercase tracking-wider block mb-1">Bank</span>
                                    <span class="text-gray-900 font-bold text-lg">{{ $store->bank_name }}</span>
                                </div>
                                <div>
                                    <span
                                        class="text-xs text-gray-400 font-bold uppercase tracking-wider block mb-1">Pemilik
                                        Rekening</span>
                                    <span class="text-gray-900 font-bold text-lg">{{ $store->bank_account_name }}</span>
                                </div>
                                <div>
                                    <span class="text-xs text-gray-400 font-bold uppercase tracking-wider block mb-1">Nomor
                                        Rekening</span>
                                    <span
                                        class="text-gray-900 font-bold text-lg font-mono">{{ $store->bank_account_number }}</span>
                                </div>
                            </div>
                            <p class="text-xs text-gray-400 mt-6 pt-4 border-t border-gray-200 flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Info: Anda dapat mengubah informasi rekening bank di <a
                                    href="{{ route('seller.stores.edit') }}"
                                    class="text-brand-orange hover:text-brand-dark font-bold transition-colors ml-1">Profil
                                    Toko</a>
                            </p>
                        </div>
                    @else
                        <div class="mb-8 p-6 bg-yellow-50 border border-yellow-200 rounded-2xl flex items-start shadow-sm">
                            <div class="p-2 bg-yellow-100 rounded-lg mr-3">
                                <svg class="w-6 h-6 text-yellow-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                                    </path>
                                </svg>
                            </div>
                            <div>
                                <h4 class="font-bold text-yellow-800 mb-1 text-lg">Informasi Bank Tidak Lengkap</h4>
                                <p class="text-sm text-yellow-700 leading-relaxed">Harap lengkapi informasi rekening bank
                                    Anda di <a href="{{ route('seller.stores.edit') }}"
                                        class="font-bold underline decoration-2 decoration-yellow-400 hover:text-yellow-900">Profil
                                        Toko</a> sebelum mengajukan penarikan.</p>
                            </div>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('seller.withdrawals.store') }}" class="space-y-6">
                        @csrf


                        <div>
                            <label for="amount" class="block font-bold text-sm text-gray-700 mb-2">Jumlah Penarikan
                                <span class="text-gray-400 font-normal">(Min. Rp 10.000)</span></label>
                            <div class="relative rounded-xl shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 sm:text-sm font-bold">Rp</span>
                                </div>
                                <input id="amount"
                                    class="pl-10 block w-full border-gray-300 focus:border-brand-orange focus:ring-brand-orange rounded-xl shadow-sm"
                                    type="number" name="amount" value="{{ old('amount') }}" min="10000"
                                    max="{{ $storeBalance->balance }}" step="1000" required placeholder="0">
                            </div>
                            @error('amount')
                                <span class="text-red-600 text-sm mt-1 block">{{ $message }}</span>
                            @enderror
                            <p class="mt-2 text-xs text-gray-500 font-medium">Maksimal penarikan: Rp
                                {{ number_format($storeBalance->balance, 0, ',', '.') }}</p>
                        </div>

                        <div class="flex items-center justify-between pt-4">
                            <a href="{{ route('seller.balance.index') }}"
                                class="px-6 py-3 bg-gray-100 text-gray-700 font-bold rounded-xl hover:bg-gray-200 transition-colors">
                                Batal
                            </a>

                            <button type="submit"
                                class="px-6 py-3 bg-brand-dark text-white font-bold rounded-xl hover:bg-brand-orange transition-colors shadow-md transform hover:-translate-y-0.5"
                                {{ !($store->bank_name && $store->bank_account_name && $store->bank_account_number) ? 'disabled style=opacity:0.5;cursor:not-allowed' : '' }}>
                                Ajukan Penarikan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>