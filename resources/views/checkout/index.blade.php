<x-store-layout>
    <div class="bg-brand-gray min-h-screen py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-3xl font-serif font-bold text-brand-dark mb-8">Checkout</h1>

            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-6">
                    {{ session('error') }}
                </div>
            @endif

            <form action="{{ route('checkout.process') }}" method="POST">
                @csrf
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <!-- Shipping Information -->
                    <div class="lg:col-span-2 space-y-8">
                        <!-- Address Section -->
                        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                            <div class="flex items-center gap-3 mb-6">
                                <div class="w-8 h-8 rounded-full bg-brand-dark text-white flex items-center justify-center font-bold">1</div>
                                <h3 class="text-xl font-bold text-brand-dark">Shipping Address</h3>
                            </div>
                            
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Full Address *</label>
                                    <textarea name="address" rows="3" required class="w-full rounded-lg border-gray-200 focus:border-brand-orange focus:ring-brand-orange transition-colors" placeholder="Enter your full street address">{{ old('address') }}</textarea>
                                    @error('address')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                                </div>

                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">City *</label>
                                        <input type="text" name="city" value="{{ old('city') }}" required class="w-full rounded-lg border-gray-200 focus:border-brand-orange focus:ring-brand-orange transition-colors" placeholder="City">
                                        @error('city')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Postal Code *</label>
                                        <input type="text" name="postal_code" value="{{ old('postal_code') }}" required class="w-full rounded-lg border-gray-200 focus:border-brand-orange focus:ring-brand-orange transition-colors" placeholder="Postal Code">
                                        @error('postal_code')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Order Items by Store -->
                        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                            <div class="flex items-center gap-3 mb-6">
                                <div class="w-8 h-8 rounded-full bg-brand-dark text-white flex items-center justify-center font-bold">2</div>
                                <h3 class="text-xl font-bold text-brand-dark">Order Details & Shipping</h3>
                            </div>

                            <div class="space-y-8">
                                @foreach($cartByStore as $storeId => $items)
                                    @php
                                        $store = $items->first()->product->store;
                                        $storeSubtotal = $items->sum(function($cart) { return $cart->subtotal; });
                                    @endphp
                                    <div class="border border-gray-200 rounded-lg overflow-hidden">
                                        <div class="bg-gray-50 px-6 py-3 border-b border-gray-200 flex items-center gap-2">
                                            <div class="w-6 h-6 rounded-full bg-white flex items-center justify-center text-brand-orange font-bold text-xs border border-gray-200">
                                                {{ substr($store->name, 0, 1) }}
                                            </div>
                                            <h3 class="font-bold text-brand-dark">{{ $store->name }}</h3>
                                        </div>

                                        <div class="p-6 divide-y divide-gray-100">
                                            @foreach($items as $cart)
                                                <div class="flex gap-4 py-4 first:pt-0">
                                                    <div class="w-16 h-16 bg-gray-100 rounded-md overflow-hidden flex-shrink-0">
                                                        @if($cart->product->productImages->count() > 0)
                                                            <img src="{{ $cart->product->productImages->first()->image_url }}" class="w-full h-full object-cover">
                                                        @endif
                                                    </div>
                                                    <div class="flex-1">
                                                        <h4 class="font-bold text-brand-dark text-sm mb-1">{{ $cart->product->name }}</h4>
                                                        <p class="text-xs text-gray-500">{{ $cart->product->formatted_price }} × {{ $cart->quantity }}</p>
                                                    </div>
                                                    <div class="text-right">
                                                        <p class="font-bold text-brand-orange text-sm">Rp {{ number_format($cart->subtotal, 0, ',', '.') }}</p>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>

                                        <!-- Shipping Options for this store -->
                                        <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
                                            <input type="hidden" name="stores[{{ $loop->index }}][store_id]" value="{{ $storeId }}">
                                            
                                            <label class="block text-sm font-medium text-gray-700 mb-2">Shipping Method</label>
                                            <select name="stores[{{ $loop->index }}][shipping_type]" class="shipping-select w-full rounded-lg border-gray-300 text-sm focus:border-brand-orange focus:ring-brand-orange" data-index="{{ $loop->index }}" required>
                                                <option value="regular" data-cost="10000">Regular (Rp 10.000) - 3-5 days</option>
                                                <option value="express" data-cost="25000">Express (Rp 25.000) - 1-2 days</option>
                                                <option value="same_day" data-cost="50000">Same Day (Rp 50.000)</option>
                                            </select>
                                            <input type="hidden" name="stores[{{ $loop->index }}][shipping_cost]" class="shipping-cost" value="10000">
                                            
                                            <div class="flex justify-between mt-4 pt-4 border-t border-gray-200 text-sm">
                                                <span class="text-gray-600">Subtotal</span>
                                                <span class="font-bold text-brand-dark">Rp {{ number_format($storeSubtotal, 0, ',', '.') }}</span>
                                            </div>
                                            <div class="flex justify-between text-sm mt-1">
                                                <span class="text-gray-600">Shipping</span>
                                                <span class="font-bold text-brand-dark shipping-cost-display">Rp 10.000</span>
                                            </div>
                                            <div class="flex justify-between text-sm mt-1">
                                                <span class="text-gray-600">Tax (11%)</span>
                                                <span class="font-bold text-brand-dark">Rp {{ number_format($storeSubtotal * 0.11, 0, ',', '.') }}</span>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- Order Summary -->
                    <div class="lg:col-span-1">
                        <div class="bg-white rounded-xl shadow-sm p-6 sticky top-8 border border-gray-100">
                            <h3 class="text-xl font-serif font-bold text-brand-dark mb-6">Order Summary</h3>
                            
                            <div class="space-y-3 mb-6 text-sm">
                                <div class="flex justify-between text-gray-600">
                                    <span>Subtotal</span>
                                    <span id="summarySubtotal" class="font-medium">Rp {{ number_format($grandTotal, 0, ',', '.') }}</span>
                                </div>
                                <div class="flex justify-between text-gray-600">
                                    <span>Total Shipping</span>
                                    <span id="summaryShipping" class="font-medium">Rp 10.000</span>
                                </div>
                                <div class="flex justify-between text-gray-600">
                                    <span>Total Tax (11%)</span>
                                    <span id="summaryTax" class="font-medium">Rp {{ number_format($grandTotal * 0.11, 0, ',', '.') }}</span>
                                </div>
                            </div>

                            <div class="border-t border-gray-100 pt-6 mb-8">
                                <div class="flex justify-between items-end">
                                    <span class="text-gray-600 font-medium">Total Payment</span>
                                    <span class="text-2xl font-bold text-brand-orange" id="grandTotal">Rp {{ number_format($grandTotal + 10000 + ($grandTotal * 0.11), 0, ',', '.') }}</span>
                                </div>
                            </div>

                            <button type="submit" class="w-full bg-brand-dark text-white px-6 py-4 rounded-xl hover:bg-brand-orange transition-colors font-bold text-lg shadow-lg shadow-brand-orange/10">
                                Place Order
                            </button>
                            
                            <div class="mt-6 flex items-center justify-center gap-4 text-gray-400">
                                <svg class="h-8" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M45 35c0 2.209-1.791 4-4 4H7c-2.209 0-4-1.791-4-4V13c0-2.209 1.791-4 4-4h34c2.209 0 4 1.791 4 4v22z" fill="#1976D2"/><path d="M3 13c0-2.209 1.791-4 4-4h34c2.209 0 4 1.791 4 4v22c0 2.209-1.791 4-4 4H7c-2.209 0-4-1.791-4-4V13z" stroke="#42A5F5" stroke-width="2"/><path d="M3 18h42" stroke="#42A5F5" stroke-width="2"/></svg>
                                <svg class="h-8" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M45 35c0 2.209-1.791 4-4 4H7c-2.209 0-4-1.791-4-4V13c0-2.209 1.791-4 4-4h34c2.209 0 4 1.791 4 4v22z" fill="#ff5722"/><path d="M3 13c0-2.209 1.791-4 4-4h34c2.209 0 4 1.791 4 4v22c0 2.209-1.791 4-4 4H7c-2.209 0-4-1.791-4-4V13z" stroke="#ff8a65" stroke-width="2"/><path d="M3 18h42" stroke="#ff8a65" stroke-width="2"/></svg>
                            </div>
                        </div>
                    </div>
                </div>
            </form>

            <script>
                // Update shipping cost when shipping method changes
                document.querySelectorAll('.shipping-select').forEach(select => {
                    select.addEventListener('change', function() {
                        const index = this.getAttribute('data-index');
                        const selectedOption = this.options[this.selectedIndex];
                        const cost = selectedOption.getAttribute('data-cost');
                        
                        // Update hidden input
                        const costInput = this.closest('.bg-gray-50').querySelector('.shipping-cost');
                        costInput.value = cost;
                        
                        // Update display
                        const costDisplay = this.closest('.bg-gray-50').querySelector('.shipping-cost-display');
                        costDisplay.textContent = 'Rp ' + parseInt(cost).toLocaleString('id-ID').replace(/,/g, '.');
                        
                        // Recalculate total
                        updateGrandTotal();
                    });
                });

                function updateGrandTotal() {
                    let totalShipping = 0;
                    document.querySelectorAll('.shipping-cost').forEach(input => {
                        totalShipping += parseInt(input.value);
                    });
                    
                    const subtotal = {{ $grandTotal }};
                    const tax = subtotal * 0.11;
                    const grandTotal = subtotal + totalShipping + tax;
                    
                    document.getElementById('summaryShipping').textContent = 'Rp ' + totalShipping.toLocaleString('id-ID').replace(/,/g, '.');
                    document.getElementById('grandTotal').textContent = 'Rp ' + Math.round(grandTotal).toLocaleString('id-ID').replace(/,/g, '.');
                }
            </script>
        </div>
    </div>
</x-store-layout>
