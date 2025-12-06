<x-store-layout>
    <div class="bg-brand-gray min-h-screen py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-3xl font-serif font-bold text-brand-dark mb-8">Keranjang Belanja</h1>

            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-6"
                    role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-6" role="alert">
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif

            @if($cartByStore->count() > 0)
                    <form action="{{ route('checkout.index') }}" method="GET" id="checkoutForm">
                        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                            <!-- Cart Items -->
                            <div class="lg:col-span-2 space-y-6">
                                @foreach($cartByStore as $storeId => $items)
                                    @php
                                        $store = $items->first()->product->store;
                                        $storeTotal = $items->sum(function ($cart) {
                                            return $cart->subtotal; });
                                    @endphp
                                    <div class="bg-white rounded-xl shadow-sm overflow-hidden border border-gray-100">
                                        <!-- Store Header -->
                                        <div
                                            class="bg-gray-50 px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                                            <div class="flex items-center gap-3">
                                                <a href="{{ route('stores.show', $store->slug) }}" class="flex items-center gap-3 hover:opacity-80 transition-opacity group">
                                                    <div class="relative w-8 h-8 rounded-full overflow-hidden shrink-0" x-data="{ imageError: false }">
                                                        <img src="{{ str_starts_with($store->logo, 'http') ? $store->logo : asset('storage/' . $store->logo) }}"
                                                            alt="{{ $store->name }}" class="w-full h-full object-cover"
                                                            x-on:error="imageError = true" x-show="!imageError">
                                                        <div x-show="imageError"
                                                            class="w-full h-full bg-brand-orange/10 flex items-center justify-center text-brand-orange font-bold text-sm absolute inset-0"
                                                            style="display: none;">
                                                            {{ substr($store->name, 0, 1) }}
                                                        </div>
                                                    </div>
                                                    <div>
                                                        <h3 class="font-bold text-brand-dark group-hover:text-brand-orange transition-colors">{{ $store->name }}</h3>
                                                        <p class="text-xs text-gray-500">{{ $items->count() }} item</p>
                                                    </div>
                                                </a>
                                            </div>
                                            <input type="checkbox" name="stores[]" value="{{ $storeId }}"
                                                class="store-checkbox rounded border-gray-300 text-brand-orange focus:ring-brand-orange"
                                                checked>
                                        </div>

                                        <!-- Store Items -->
                                        <div class="divide-y divide-gray-100">
                                            @foreach($items as $cart)
                                                <div class="p-6 flex flex-col sm:flex-row gap-6">
                                                    <!-- Product Image -->
                                                    <div
                                                        class="w-full sm:w-24 h-24 bg-gray-100 rounded-lg overflow-hidden flex-shrink-0">
                                                        @if($cart->product->productImages->count() > 0)
                                                            <img src="{{ $cart->product->productImages->first()->image_url }}"
                                                                alt="{{ $cart->product->name }}" class="w-full h-full object-cover">
                                                        @endif
                                                    </div>

                                                    <!-- Product Details -->
                                                    <div class="flex-1">
                                                        <div class="flex justify-between items-start mb-2">
                                                            <h4 class="font-bold text-brand-dark text-lg">
                                                                <a href="{{ route('products.show', ['store' => $cart->product->store->slug, 'product' => $cart->product->slug]) }}"
                                                                    class="hover:text-brand-orange transition-colors">
                                                                    {{ $cart->product->name }}
                                                                </a>
                                                            </h4>
                                                            <p class="font-bold text-brand-orange text-lg"
                                                                id="item-subtotal-{{ $cart->id }}">Rp
                                                                {{ number_format($cart->subtotal, 0, ',', '.') }}</p>
                                                        </div>
                                                        <p class="text-sm text-gray-500 mb-4">{{ $cart->product->formatted_price }} /
                                                            unit</p>

                                                        <div class="flex items-center justify-between">
                                                            <!-- Quantity Controls -->
                                                            <div
                                                                class="flex items-center border border-gray-200 rounded-lg overflow-hidden">
                                                                <input type="number" value="{{ $cart->quantity }}" min="0"
                                                                    max="{{ $cart->product->stock }}"
                                                                    class="w-16 text-center border-none focus:ring-0 p-2 text-sm font-bold text-gray-700"
                                                                    onchange="updateQuantity(this, {{ $cart->id }})">
                                                            </div>

                                                            <!-- Remove Button -->
                                                            <button onclick="removeFromCart(this, {{ $cart->id }})"
                                                                class="text-gray-400 hover:text-red-500 transition-colors flex items-center gap-1 text-sm font-medium">
                                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                                    stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                                        d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                                                </svg>
                                                                Hapus
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <!-- Order Summary -->
                            <div class="lg:col-span-1">
                                <div class="bg-white rounded-xl shadow-sm p-6 sticky top-8 border border-gray-100">
                                    <h3 class="text-xl font-serif font-bold text-brand-dark mb-6">Ringkasan Pesanan</h3>

                                    <div class="space-y-4 mb-6">
                                        <div class="flex justify-between text-gray-600">
                                            <span>Toko Dipilih</span>
                                            <span id="selectedStoresCount"
                                                class="font-bold text-brand-dark">{{ $cartByStore->count() }}</span>
                                        </div>
                                        <div class="flex justify-between text-gray-600">
                                            <span>Total Item</span>
                                            <span id="totalItems"
                                                class="font-bold text-brand-dark">{{ $cartByStore->sum(function ($items) {
                return $items->count(); }) }}</span>
                                        </div>
                                    </div>

                                    <div class="border-t border-gray-100 pt-6 mb-8">
                                        <div class="flex justify-between items-end">
                                            <span class="text-gray-600 font-medium">Total Keseluruhan</span>
                                            <span class="text-2xl font-bold text-brand-orange" id="grandTotal">Rp
                                                {{ number_format($grandTotal, 0, ',', '.') }}</span>
                                        </div>
                                        <p class="text-xs text-gray-400 mt-2 text-right">*Biaya pengiriman dihitung saat
                                            checkout</p>
                                    </div>

                                    <button type="submit"
                                        class="w-full bg-brand-dark text-white px-6 py-4 rounded-xl hover:bg-brand-orange transition-colors font-bold text-lg shadow-lg shadow-brand-orange/10 flex items-center justify-center gap-2">
                                        Lanjut ke Checkout
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                                            stroke="currentColor" class="w-5 h-5">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M17.25 8.25L21 12m0 0l-3.75 3.75M21 12H3" />
                                        </svg>
                                    </button>

                                    <a href="{{ route('products.index') }}"
                                        class="block w-full text-center mt-4 text-gray-500 hover:text-brand-dark font-medium transition-colors">
                                        Lanjut Belanja
                                    </a>
                                </div>
                            </div>
                        </div>
                    </form>

                    <script>
                        // Update selected stores count when checkbox changes
                        document.querySelectorAll('.store-checkbox').forEach(checkbox => {
                            checkbox.addEventListener('change', function () {
                                const selectedCount = document.querySelectorAll('.store-checkbox:checked').length;
                                document.getElementById('selectedStoresCount').textContent = selectedCount;
                            });
                        });

                        function updateQuantity(input, cartId) {
                            const quantity = parseInt(input.value);

                            // If quantity is 0, remove the item instead
                            if (quantity <= 0) {
                                const removeButton = input.closest('.p-6').querySelector('button[onclick*="removeFromCart"]');
                                if (removeButton) {
                                    removeButton.click();
                                }
                                return;
                            }

                            const baseUrl = document.querySelector('meta[name="base-url"]');
                            const url = baseUrl ? `${baseUrl.getAttribute('content')}/cart/${cartId}` : `/cart/${cartId}`;

                            fetch(url, {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                    'Accept': 'application/json'
                                },
                                body: JSON.stringify({
                                    _method: 'PATCH',
                                    quantity: quantity
                                })
                            })
                                .then(response => response.json())
                                .then(data => {
                                    if (data.success) {
                                        // Update default value for future reverts
                                        input.defaultValue = quantity;

                                        // Update DOM elements
                                        document.getElementById(`item-subtotal-${cartId}`).textContent = data.item_subtotal;
                                        document.getElementById('grandTotal').textContent = data.grand_total;
                                        document.getElementById('totalItems').textContent = data.total_items;

                                        // Update navbar cart counts
                                        const cartCounts = document.querySelectorAll('.cart-count');
                                        cartCounts.forEach(countEl => {
                                            countEl.textContent = data.cart_count;
                                            countEl.style.display = data.cart_count > 0 ? 'inline-flex' : 'none';
                                            countEl.classList.add('scale-125');
                                            setTimeout(() => countEl.classList.remove('scale-125'), 200);
                                        });
                                    } else {
                                        // Show error toast
                                        if (window.showToast) {
                                            window.showToast(data.message || 'Gagal memperbarui keranjang', 'error');
                                        }
                                        // Revert to previous valid value
                                        input.value = input.defaultValue;
                                    }
                                })
                                .catch(error => {
                                    console.error('Error:', error);
                                    if (window.showToast) {
                                        window.showToast('Terjadi kesalahan sistem', 'error');
                                    }
                                    input.value = input.defaultValue;
                                });
                        }
                    </script>
            @else
                <div class="bg-white rounded-xl shadow-sm p-16 text-center border border-gray-100">
                    <div class="w-24 h-24 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="h-12 w-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-brand-dark  mb-2">Keranjang Anda kosong</h3>
                    <p class="text-gray-500 mb-8">Sepertinya Anda belum menambahkan buku apapun.</p>
                    <a href="{{ route('products.index') }}"
                        class="inline-flex items-center justify-center px-8 py-3 bg-brand-orange text-white font-bold rounded-xl hover:bg-brand-dark transition-colors shadow-lg shadow-brand-orange/20">
                        Mulai Belanja
                    </a>
                </div>
            @endif
        </div>
    </div>
</x-store-layout>