<x-store-layout>
    <div class="py-12 bg-brand-gray min-h-screen">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
                <div>
                    <div class="flex items-center gap-3 mb-2">
                        <a href="{{ route('orders.index') }}" class="text-gray-400 hover:text-brand-orange transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                        </a>
                        <h2 class="font-serif font-bold text-3xl text-brand-dark leading-tight">
                            Order Details
                        </h2>
                    </div>
                    <p class="text-gray-500 ml-9">Order ID: <span class="font-mono font-bold text-brand-dark">#{{ $order->code }}</span></p>
                </div>
                <div class="flex gap-3">
                    @if($order->payment_status === 'unpaid')
                        <a href="{{ route('payment.show', $order->id) }}" class="px-6 py-2.5 bg-brand-dark text-white font-bold rounded-xl hover:bg-brand-orange transition-all duration-300 shadow-lg transform hover:-translate-y-0.5 flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                            Pay Now
                        </a>
                    @endif
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Left Column: Order Info & Items -->
                <div class="lg:col-span-2 space-y-8">
                    <!-- Status Cards -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                            <p class="text-xs text-gray-500 uppercase tracking-wider font-bold mb-2">Payment Status</p>
                            <div class="flex items-center justify-between">
                                <span class="text-lg font-bold text-brand-dark">{{ ucfirst($order->payment_status) }}</span>
                                <div class="w-10 h-10 rounded-full flex items-center justify-center
                                    @if($order->payment_status === 'paid') bg-green-100 text-green-600
                                    @else bg-yellow-100 text-yellow-600 @endif">
                                    @if($order->payment_status === 'paid')
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                    @else
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                            <p class="text-xs text-gray-500 uppercase tracking-wider font-bold mb-2">Order Status</p>
                            <div class="flex items-center justify-between">
                                <span class="text-lg font-bold text-brand-dark">{{ ucfirst($order->order_status) }}</span>
                                <div class="w-10 h-10 rounded-full flex items-center justify-center
                                    @if($order->order_status === 'completed') bg-green-100 text-green-600
                                    @elseif($order->order_status === 'cancelled') bg-red-100 text-red-600
                                    @elseif($order->order_status === 'shipped') bg-blue-100 text-blue-600
                                    @else bg-gray-100 text-gray-600 @endif">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if($order->tracking_number)
                        <div class="bg-blue-50 border border-blue-200 rounded-2xl p-6 flex items-start gap-4">
                            <div class="p-3 bg-blue-100 rounded-xl text-blue-600">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            </div>
                            <div>
                                <h4 class="font-bold text-blue-900 text-lg mb-1">Tracking Number</h4>
                                <p class="text-blue-800 font-mono text-xl">{{ $order->tracking_number }}</p>
                                <p class="text-sm text-blue-600 mt-2">Use this number to track your package delivery.</p>
                            </div>
                        </div>
                    @endif

                    <!-- Order Items -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50">
                            <h3 class="font-bold text-lg text-brand-dark">Order Items</h3>
                        </div>
                        <div class="p-6 space-y-6">
                            @foreach($order->transactionDetails as $detail)
                                <div class="flex gap-6 items-center">
                                    <div class="w-24 h-24 bg-gray-100 rounded-xl overflow-hidden flex-shrink-0 border border-gray-200">
                                        @if($detail->product->productImages->count() > 0)
                                            <img src="{{ $detail->product->productImages->first()->image_url }}" class="w-full h-full object-cover">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center text-gray-400">
                                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="flex-1">
                                        <h4 class="font-bold text-brand-dark text-lg mb-1">{{ $detail->product->name }}</h4>
                                        <p class="text-sm text-gray-500 mb-2">{{ $detail->qty }} x Rp {{ number_format($detail->product->price, 0, ',', '.') }}</p>
                                        <div class="flex items-center gap-2">
                                            <span class="text-xs font-bold px-2 py-1 bg-gray-100 text-gray-600 rounded-lg">{{ $detail->product->category->name ?? 'Uncategorized' }}</span>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <p class="font-bold text-brand-dark text-lg">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Right Column: Summary & Info -->
                <div class="space-y-8">
                    <!-- Order Summary -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50">
                            <h3 class="font-bold text-lg text-brand-dark">Order Summary</h3>
                        </div>
                        <div class="p-6 space-y-4">
                            <div class="flex justify-between text-gray-600">
                                <span>Subtotal</span>
                                <span class="font-medium">Rp {{ number_format($order->grand_total - $order->shipping_cost - $order->tax, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between text-gray-600">
                                <span>Shipping ({{ $order->shipping_type }})</span>
                                <span class="font-medium">Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between text-gray-600">
                                <span>Tax (11%)</span>
                                <span class="font-medium">Rp {{ number_format($order->tax, 0, ',', '.') }}</span>
                            </div>
                            <div class="border-t border-dashed border-gray-200 pt-4 mt-2 flex justify-between items-center">
                                <span class="font-bold text-lg text-brand-dark">Total</span>
                                <span class="font-bold text-2xl text-brand-orange">Rp {{ number_format($order->grand_total, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Shipping Address -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50">
                            <h3 class="font-bold text-lg text-brand-dark">Shipping Address</h3>
                        </div>
                        <div class="p-6">
                            <div class="flex items-start gap-4">
                                <div class="p-2 bg-orange-50 rounded-lg text-brand-orange mt-1">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                </div>
                                <div>
                                    <p class="text-gray-700 leading-relaxed">{{ $order->address }}</p>
                                    <p class="text-gray-700 font-medium mt-1">{{ $order->city }}, {{ $order->postal_code }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Store Info -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50">
                            <h3 class="font-bold text-lg text-brand-dark">Store Information</h3>
                        </div>
                        <div class="p-6">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center text-gray-500 font-bold text-xl">
                                    {{ substr($order->store->name, 0, 1) }}
                                </div>
                                <div>
                                    <h4 class="font-bold text-brand-dark text-lg">
                                        @if($order->store && $order->store->slug)
                                            <a href="{{ route('stores.show', $order->store) }}" class="hover:text-brand-orange transition-colors">
                                                {{ $order->store->name }}
                                            </a>
                                        @else
                                            {{ $order->store->name ?? 'Unknown Store' }}
                                        @endif
                                    </h4>
                                    @if($order->store && $order->store->slug)
                                        <a href="{{ route('stores.show', $order->store) }}" class="text-sm text-brand-orange hover:underline">Visit Store</a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-store-layout>
