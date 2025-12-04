<x-app-layout>
    <x-slot name="header">
        <h2 class="font-serif font-bold text-2xl text-brand-dark leading-tight">
            {{ __('Manage Store Profile') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
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

            <div class="bg-white overflow-hidden shadow-lg rounded-2xl border border-gray-100">
                <div class="p-8">
                    <form method="POST" action="{{ route('seller.stores.update') }}" enctype="multipart/form-data" class="space-y-8">
                        @csrf
                        @method('PATCH')

                        <!-- Store Identity -->
                        <div>
                            <h3 class="text-xl font-serif font-bold text-brand-dark mb-6 border-b border-gray-100 pb-4">Store Identity</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                <div class="space-y-6">
                                    <div>
                                        <label for="name" class="block font-bold text-sm text-gray-700 mb-2">Store Name</label>
                                        <input id="name" class="w-full border-gray-300 focus:border-brand-orange focus:ring-brand-orange rounded-xl shadow-sm" type="text" name="name" value="{{ old('name', $store->name) }}" required>
                                        @error('name')
                                            <span class="text-red-600 text-sm mt-1 block">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="phone" class="block font-bold text-sm text-gray-700 mb-2">Phone Number</label>
                                        <input id="phone" class="w-full border-gray-300 focus:border-brand-orange focus:ring-brand-orange rounded-xl shadow-sm" type="text" name="phone" value="{{ old('phone', $store->phone) }}" required>
                                        @error('phone')
                                            <span class="text-red-600 text-sm mt-1 block">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="about" class="block font-bold text-sm text-gray-700 mb-2">About Store</label>
                                        <textarea id="about" class="w-full border-gray-300 focus:border-brand-orange focus:ring-brand-orange rounded-xl shadow-sm" name="about" rows="4" required>{{ old('about', $store->about) }}</textarea>
                                        @error('about')
                                            <span class="text-red-600 text-sm mt-1 block">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div>
                                    <label class="block font-bold text-sm text-gray-700 mb-2">Store Logo</label>
                                    <div class="mt-1 flex flex-col items-center justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-xl hover:border-brand-orange transition-colors">
                                        @if($store->logo)
                                            <img src="{{ asset('storage/' . $store->logo) }}" alt="{{ $store->name }}" class="h-32 w-32 object-cover rounded-xl shadow-md mb-4">
                                        @else
                                            <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                            </svg>
                                        @endif
                                        <div class="flex text-sm text-gray-600">
                                            <label for="logo" class="relative cursor-pointer bg-white rounded-md font-medium text-brand-orange hover:text-brand-dark focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-brand-orange">
                                                <span>Upload a new logo</span>
                                                <input id="logo" name="logo" type="file" class="sr-only" accept="image/*">
                                            </label>
                                        </div>
                                        <p class="text-xs text-gray-500">PNG, JPG, GIF up to 2MB</p>
                                    </div>
                                    @error('logo')
                                        <span class="text-red-600 text-sm mt-1 block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Location & Address -->
                        <div>
                            <h3 class="text-xl font-serif font-bold text-brand-dark mb-6 border-b border-gray-100 pb-4">Location & Address</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="city" class="block font-bold text-sm text-gray-700 mb-2">City</label>
                                    <input id="city" class="w-full border-gray-300 focus:border-brand-orange focus:ring-brand-orange rounded-xl shadow-sm" type="text" name="city" value="{{ old('city', $store->city) }}" required>
                                    @error('city')
                                        <span class="text-red-600 text-sm mt-1 block">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div>
                                    <label for="postal_code" class="block font-bold text-sm text-gray-700 mb-2">Postal Code</label>
                                    <input id="postal_code" class="w-full border-gray-300 focus:border-brand-orange focus:ring-brand-orange rounded-xl shadow-sm" type="text" name="postal_code" value="{{ old('postal_code', $store->postal_code) }}" required>
                                    @error('postal_code')
                                        <span class="text-red-600 text-sm mt-1 block">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="md:col-span-2">
                                    <label for="address" class="block font-bold text-sm text-gray-700 mb-2">Full Address</label>
                                    <textarea id="address" class="w-full border-gray-300 focus:border-brand-orange focus:ring-brand-orange rounded-xl shadow-sm" name="address" rows="3" required>{{ old('address', $store->address) }}</textarea>
                                    @error('address')
                                        <span class="text-red-600 text-sm mt-1 block">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="md:col-span-2">
                                    <label for="address_id" class="block font-bold text-sm text-gray-700 mb-2">
                                        Address ID <span class="text-gray-400 font-normal">(Auto-generated)</span>
                                    </label>
                                    <input id="address_id" class="w-full border-gray-300 bg-gray-50 text-gray-500 cursor-not-allowed rounded-xl shadow-sm" type="text" name="address_id" value="{{ old('address_id', $store->address_id) }}" readonly>
                                </div>
                            </div>
                        </div>

                        <!-- Bank Information -->
                        <div>
                            <h3 class="text-xl font-serif font-bold text-brand-dark mb-6 border-b border-gray-100 pb-4">Bank Information</h3>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div>
                                    <label for="bank_name" class="block font-bold text-sm text-gray-700 mb-2">Bank Name</label>
                                    <input id="bank_name" class="w-full border-gray-300 focus:border-brand-orange focus:ring-brand-orange rounded-xl shadow-sm" type="text" name="bank_name" value="{{ old('bank_name', $store->bank_name) }}" placeholder="e.g. BCA">
                                    @error('bank_name')
                                        <span class="text-red-600 text-sm mt-1 block">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div>
                                    <label for="bank_account_number" class="block font-bold text-sm text-gray-700 mb-2">Account Number</label>
                                    <input id="bank_account_number" class="w-full border-gray-300 focus:border-brand-orange focus:ring-brand-orange rounded-xl shadow-sm" type="text" name="bank_account_number" value="{{ old('bank_account_number', $store->bank_account_number) }}" placeholder="e.g. 1234567890">
                                    @error('bank_account_number')
                                        <span class="text-red-600 text-sm mt-1 block">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div>
                                    <label for="bank_account_name" class="block font-bold text-sm text-gray-700 mb-2">Account Holder Name</label>
                                    <input id="bank_account_name" class="w-full border-gray-300 focus:border-brand-orange focus:ring-brand-orange rounded-xl shadow-sm" type="text" name="bank_account_name" value="{{ old('bank_account_name', $store->bank_account_name) }}" placeholder="e.g. John Doe">
                                    @error('bank_account_name')
                                        <span class="text-red-600 text-sm mt-1 block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center justify-between pt-6 border-t border-gray-100">
                            <button type="button"
                                    onclick="document.getElementById('delete-store-modal').classList.remove('hidden')"
                                    class="px-6 py-2.5 bg-red-50 text-red-600 font-bold rounded-xl hover:bg-red-600 hover:text-white transition-colors">
                                Deactivate Store
                            </button>

                            <button type="submit" class="px-8 py-3 bg-brand-dark text-white font-bold rounded-xl hover:bg-brand-orange transition-colors shadow-md transform hover:-translate-y-0.5">
                                Update Store Profile
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
        title="Deactivate Store?" 
        message="Are you sure you want to deactivate this store? Your store will not be visible to buyers, but product and transaction data will be preserved. You can restore your store at any time."
        confirmText="Yes, Deactivate Store"
        cancelText="Cancel">
    </x-confirmation-modal>
    <form id="delete-store-modal-form" method="POST" action="{{ route('seller.stores.destroy') }}" class="hidden">
        @csrf
        @method('DELETE')
    </form>
</x-app-layout>
