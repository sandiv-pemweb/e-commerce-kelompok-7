<x-app-layout>
    <x-slot name="header">
        <h2 class="font-serif font-bold text-2xl text-brand-dark leading-tight">
            {{ __('Register Your Store') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-lg rounded-2xl border border-gray-100">
                <div class="p-8">
                    @if (session('error'))
                        <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl flex items-center shadow-sm">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            {{ session('error') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('seller.stores.store') }}" enctype="multipart/form-data" class="space-y-6">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <!-- Left Column -->
                            <div class="space-y-6">
                                <div>
                                    <label for="name" class="block font-bold text-sm text-gray-700 mb-2">Store Name</label>
                                    <input id="name" class="w-full border-gray-300 focus:border-brand-orange focus:ring-brand-orange rounded-xl shadow-sm" type="text" name="name" value="{{ old('name') }}" required autofocus placeholder="e.g. My Awesome Store">
                                    @error('name')
                                        <span class="text-red-600 text-sm mt-1 block">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div>
                                    <label for="phone" class="block font-bold text-sm text-gray-700 mb-2">Phone Number</label>
                                    <input id="phone" class="w-full border-gray-300 focus:border-brand-orange focus:ring-brand-orange rounded-xl shadow-sm" type="text" name="phone" value="{{ old('phone') }}" required placeholder="e.g. 08123456789">
                                    @error('phone')
                                        <span class="text-red-600 text-sm mt-1 block">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div>
                                    <label for="logo" class="block font-bold text-sm text-gray-700 mb-2">Store Logo</label>
                                    <input id="logo" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-bold file:bg-brand-orange file:text-white hover:file:bg-brand-dark transition-colors" type="file" name="logo" accept="image/*" required>
                                    @error('logo')
                                        <span class="text-red-600 text-sm mt-1 block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <!-- Right Column -->
                            <div class="space-y-6">
                                <div>
                                    <label for="city" class="block font-bold text-sm text-gray-700 mb-2">City</label>
                                    <input id="city" class="w-full border-gray-300 focus:border-brand-orange focus:ring-brand-orange rounded-xl shadow-sm" type="text" name="city" value="{{ old('city') }}" required placeholder="e.g. Jakarta">
                                    @error('city')
                                        <span class="text-red-600 text-sm mt-1 block">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div>
                                    <label for="postal_code" class="block font-bold text-sm text-gray-700 mb-2">Postal Code</label>
                                    <input id="postal_code" class="w-full border-gray-300 focus:border-brand-orange focus:ring-brand-orange rounded-xl shadow-sm" type="text" name="postal_code" value="{{ old('postal_code') }}" required placeholder="e.g. 12345">
                                    @error('postal_code')
                                        <span class="text-red-600 text-sm mt-1 block">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div>
                                    <label for="address" class="block font-bold text-sm text-gray-700 mb-2">Full Address</label>
                                    <textarea id="address" class="w-full border-gray-300 focus:border-brand-orange focus:ring-brand-orange rounded-xl shadow-sm" name="address" rows="3" required placeholder="Street name, building, etc.">{{ old('address') }}</textarea>
                                    @error('address')
                                        <span class="text-red-600 text-sm mt-1 block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Full Width -->
                        <div>
                            <label for="about" class="block font-bold text-sm text-gray-700 mb-2">About Store</label>
                            <textarea id="about" class="w-full border-gray-300 focus:border-brand-orange focus:ring-brand-orange rounded-xl shadow-sm" name="about" rows="4" required placeholder="Tell us about your store...">{{ old('about') }}</textarea>
                            @error('about')
                                <span class="text-red-600 text-sm mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="flex items-center justify-end pt-6 border-t border-gray-100">
                            <button type="submit" class="px-8 py-3 bg-brand-dark text-white font-bold rounded-xl hover:bg-brand-orange transition-colors shadow-md transform hover:-translate-y-0.5">
                                Register Store
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
