<x-app-layout>
    <x-slot name="header">
        <h2 class="font-serif font-bold text-2xl text-brand-dark leading-tight">
            {{ __('Request Withdrawal') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
            @if (session('error'))
                <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl flex items-center shadow-sm">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    {{ session('error') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-lg rounded-2xl border border-gray-100">
                <div class="p-8">
                    <!-- Balance Info -->
                    <div class="mb-8 p-6 bg-blue-50 rounded-xl border border-blue-100 flex items-center justify-between">
                        <div>
                            <p class="text-sm font-bold text-gray-500 uppercase tracking-wider mb-1">Available Balance</p>
                            <p class="text-3xl font-bold text-brand-dark">Rp {{ number_format($storeBalance->balance, 0, ',', '.') }}</p>
                        </div>
                        <div class="h-12 w-12 bg-blue-100 rounded-full flex items-center justify-center text-brand-dark">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                    </div>

                    <!-- Bank Account Info -->
                    @if($store->bank_name && $store->bank_account_name && $store->bank_account_number)
                        <div class="mb-8 p-6 bg-gray-50 rounded-xl border border-gray-100">
                            <h4 class="font-serif font-bold text-lg text-brand-dark mb-4">Destination Account</h4>
                            <div class="space-y-2">
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-500 font-medium">Bank</span>
                                    <span class="text-sm text-gray-900 font-bold">{{ $store->bank_name }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-500 font-medium">Account Holder</span>
                                    <span class="text-sm text-gray-900 font-bold">{{ $store->bank_account_name }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-500 font-medium">Account Number</span>
                                    <span class="text-sm text-gray-900 font-bold">{{ $store->bank_account_number }}</span>
                                </div>
                            </div>
                            <p class="text-xs text-gray-400 mt-4 pt-4 border-t border-gray-200">
                                You can change bank account information in <a href="{{ route('seller.stores.edit') }}" class="text-brand-orange hover:text-brand-dark font-bold transition-colors">Store Profile</a>
                            </p>
                        </div>
                    @else
                        <div class="mb-8 p-6 bg-yellow-50 border border-yellow-200 rounded-xl flex items-start">
                            <svg class="w-6 h-6 text-yellow-600 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                            <div>
                                <h4 class="font-bold text-yellow-800 mb-1">Missing Bank Information</h4>
                                <p class="text-sm text-yellow-700">Please complete your bank account information in <a href="{{ route('seller.stores.edit') }}" class="font-bold underline hover:text-yellow-900">Store Profile</a> before requesting a withdrawal.</p>
                            </div>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('seller.withdrawals.store') }}" class="space-y-6">
                        @csrf

                        <!-- Amount -->
                        <div>
                            <label for="amount" class="block font-bold text-sm text-gray-700 mb-2">Withdrawal Amount <span class="text-gray-400 font-normal">(Min. Rp 10.000)</span></label>
                            <div class="relative rounded-xl shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 sm:text-sm font-bold">Rp</span>
                                </div>
                                <input id="amount" class="pl-10 block w-full border-gray-300 focus:border-brand-orange focus:ring-brand-orange rounded-xl shadow-sm" type="number" name="amount" value="{{ old('amount') }}" min="10000" max="{{ $storeBalance->balance }}" step="1000" required placeholder="0">
                            </div>
                            @error('amount')
                                <span class="text-red-600 text-sm mt-1 block">{{ $message }}</span>
                            @enderror
                            <p class="mt-2 text-xs text-gray-500 font-medium">Max withdrawal: Rp {{ number_format($storeBalance->balance, 0, ',', '.') }}</p>
                        </div>

                        <div class="flex items-center justify-between pt-4">
                            <a href="{{ route('seller.balance.index') }}" class="px-6 py-3 bg-gray-100 text-gray-700 font-bold rounded-xl hover:bg-gray-200 transition-colors">
                                Cancel
                            </a>

                            <button type="submit" class="px-6 py-3 bg-brand-dark text-white font-bold rounded-xl hover:bg-brand-orange transition-colors shadow-md transform hover:-translate-y-0.5" {{ !($store->bank_name && $store->bank_account_name && $store->bank_account_number) ? 'disabled style=opacity:0.5;cursor:not-allowed' : '' }}>
                                Request Withdrawal
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
