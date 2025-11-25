<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Ajukan Penarikan Saldo') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                <div class="p-6 text-gray-900">
                    @if (session('error'))
                        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                            {{ session('error') }}
                        </div>
                    @endif

                    <!-- Balance Info -->
                    <div class="mb-6 p-4 bg-indigo-50 rounded-lg">
                        <p class="text-sm text-gray-600">Saldo Tersedia</p>
                        <p class="text-2xl font-bold text-indigo-600">Rp {{ number_format($storeBalance->balance, 0, ',', '.') }}</p>
                    </div>

                    <!-- Bank Account Info -->
                    @if($store->bank_name && $store->bank_account_name && $store->bank_account_number)
                        <div class="mb-6 p-4 bg-gray-50 rounded-lg">
                            <h4 class="font-semibold mb-2">Rekening Tujuan</h4>
                            <p class="text-sm"><span class="font-medium">Bank:</span> {{ $store->bank_name }}</p>
                            <p class="text-sm"><span class="font-medium">Nama Pemilik:</span> {{ $store->bank_account_name }}</p>
                            <p class="text-sm"><span class="font-medium">Nomor Rekening:</span> {{ $store->bank_account_number }}</p>
                            <p class="text-xs text-gray-500 mt-2">Anda dapat mengubah informasi rekening di <a href="{{ route('seller.stores.edit') }}" class="text-indigo-600 hover:underline">Profil Toko</a></p>
                        </div>
                    @else
                        <div class="mb-6 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                            <p class="text-sm text-yellow-800">Harap lengkapi informasi rekening bank di <a href="{{ route('seller.stores.edit') }}" class="font-medium underline">Profil Toko</a> terlebih dahulu.</p>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('seller.withdrawals.store') }}" class="space-y-6">
                        @csrf

                        <!-- Amount -->
                        <div>
                            <label for="amount" class="block font-medium text-sm text-gray-700">Jumlah Penarikan (Min. Rp 10.000)</label>
                            <input id="amount" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" type="number" name="amount" value="{{ old('amount') }}" min="10000" max="{{ $storeBalance->balance }}" step="1000" required>
                            @error('amount')
                                <span class="text-red-600 text-sm">{{ $message }}</span>
                            @enderror
                            <p class="mt-1 text-xs text-gray-500">Maksimal: Rp {{ number_format($storeBalance->balance, 0, ',', '.') }}</p>
                        </div>

                        <div class="flex items-center justify-between">
                            <a href="{{ route('seller.balance.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-400">
                                Batal
                            </a>

                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700">
                                Ajukan Penarikan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
