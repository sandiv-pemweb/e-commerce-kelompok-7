<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Order #{{ $order->code }}
            </h2>
            <a href="{{ route('orders.index') }}" class="text-indigo-600 hover:text-indigo-700">
                ← Back to Orders
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <!-- Order Status -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <div class="flex justify-between items-center mb-4">
                    <div>
                        <h3 class="text-lg font-bold">Order Status</h3>
                        <p class="text-sm text-gray-600">Placed on {{ $order->created_at->format('d M Y, H:i') }}</p>
                    </div>
                    <div class="text-right">
                        <span class="px-4 py-2 rounded-full text-sm font-semibold
                            @if($order->payment_status === 'paid') bg-green-100 text-green-800
                            @else bg-yellow-100 text-yellow-800 @endif">
                            Payment: {{ ucfirst($order->payment_status) }}
                        </span>
                        <span class="ml-2 px-4 py-2 rounded-full text-sm font-semibold
                            @if($order->order_status === 'completed') bg-green-100 text-green-800
                            @elseif($order->order_status === 'cancelled') bg-red-100 text-red-800
                            @elseif($order->order_status === 'shipped') bg-blue-100 text-blue-800
                            @else bg-gray-100 text-gray-800 @endif">
                            Order: {{ ucfirst($order->order_status) }}
                        </span>
                    </div>
                </div>

                @if($order->payment_status === 'unpaid')
                    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-4">
                        <div class="flex justify-between items-center">
                            <div>
                                <p class="text-yellow-800 font-semibold">Payment Pending</p>
                                <p class="text-sm text-yellow-700">Please complete payment to process your order</p>
                            </div>
                            <a href="{{ route('payment.show', $order->id) }}" class="px-4 py-2 bg-yellow-600 text-white rounded-md hover:bg-yellow-700 transition">
                                Pay Now
                            </a>
                        </div>
                    </div>
                @endif

                @if($order->tracking_number)
                    <div class="bg-blue-50 border-l-4 border-blue-400 p-4">
                        <p class="text-blue-800 font-semibold">Tracking Number</p>
                        <p class="text-lg text-blue-900">{{ $order->tracking_number }}</p>
                    </div>
                @endif
            </div>

            <!-- Store Info -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <h3 class="text-lg font-bold mb-2">Store</h3>
                <p class="text-indigo-600 font-semibold">{{ $order->store->name }}</p>
            </div>

            <!-- Order Items -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <h3 class="text-lg font-bold mb-4">Order Items</h3>
                <div class="space-y-4">
                    @foreach($order->transactionDetails as $detail)
                        <div class="flex gap-4 pb-4 border-b last:border-0">
                            <div class="w-20 h-20 bg-gray-200 rounded overflow-hidden flex-shrink-0">
                                @if($detail->product->productImages->count() > 0)
                                    <img src="{{ $detail->product->productImages->first()->image_url }}" class="w-full h-full object-cover">
                                @endif
                            </div>
                            <div class="flex-1">
                                <h4 class="font-semibold">{{ $detail->product->name }}</h4>
                                <p class="text-sm text-gray-600">Rp {{ number_format($detail->product->price, 0, ',', '.') }} × {{ $detail->qty }}</p>
                            </div>
                            <div class="text-right">
                                <p class="font-bold text-indigo-600">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Shipping Address -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <h3 class="text-lg font-bold mb-2">Shipping Address</h3>
                <p class="text-gray-700">{{ $order->address }}</p>
                <p class="text-gray-700">{{ $order->city }}, {{ $order->postal_code }}</p>
            </div>

            <!-- Order Summary -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-bold mb-4">Order Summary</h3>
                <div class="space-y-2">
                    <div class="flex justify-between text-gray-600">
                        <span>Subtotal:</span>
                        <span>Rp {{ number_format($order->grand_total - $order->shipping_cost - $order->tax, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between text-gray-600">
                        <span>Shipping ({{ $order->shipping_type }}):</span>
                        <span>Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between text-gray-600">
                        <span>Tax (11%):</span>
                        <span>Rp {{ number_format($order->tax, 0, ',', '.') }}</span>
                    </div>
                    <div class="border-t pt-2 mt-2 flex justify-between font-bold text-lg">
                        <span>Total:</span>
                        <span class="text-indigo-600">Rp {{ number_format($order->grand_total, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
