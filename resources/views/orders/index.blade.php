<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('My Orders') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
                    {{ session('success') }}
                </div>
            @endif

            @if($orders->count() > 0)
                <div class="space-y-4">
                    @foreach($orders as $order)
                        <div class="bg-white rounded-lg shadow-md overflow-hidden">
                            <!-- Order Header -->
                            <div class="bg-gray-50 px-6 py-4 border-b flex justify-between items-center">
                                <div>
                                    <p class="text-sm text-gray-600">Order Date: {{ $order->created_at->format('d M Y, H:i') }}</p>
                                    <p class="font-bold text-lg">{{ $order->code }}</p>
                                    <p class="text-sm text-indigo-600">{{ $order->store->name }}</p>
                                </div>
                                <div class="text-right">
                                    <span class="px-3 py-1 rounded-full text-xs font-semibold
                                        @if($order->payment_status === 'paid') bg-green-100 text-green-800
                                        @else bg-yellow-100 text-yellow-800 @endif">
                                        {{ ucfirst($order->payment_status) }}
                                    </span>
                                    <span class="ml-2 px-3 py-1 rounded-full text-xs font-semibold
                                        @if($order->order_status === 'completed') bg-green-100 text-green-800
                                        @elseif($order->order_status === 'cancelled') bg-red-100 text-red-800
                                        @elseif($order->order_status === 'shipped') bg-blue-100 text-blue-800
                                        @else bg-gray-100 text-gray-800 @endif">
                                        {{ ucfirst($order->order_status) }}
                                    </span>
                                </div>
                            </div>

                            <!-- Order Items -->
                            <div class="p-6">
                                <div class="space-y-3">
                                    @foreach($order->transactionDetails as $detail)
                                        <div class="flex gap-4">
                                            <div class="w-16 h-16 bg-gray-200 rounded overflow-hidden flex-shrink-0">
                                                @if($detail->product->productImages->count() > 0)
                                                    <img src="{{ $detail->product->productImages->first()->image_url }}" class="w-full h-full object-cover">
                                                @endif
                                            </div>
                                            <div class="flex-1">
                                                <h4 class="font-semibold">{{ $detail->product->name }}</h4>
                                                <p class="text-sm text-gray-600">{{ $detail->qty }} × Rp {{ number_format($detail->product->price, 0, ',', '.') }}</p>
                                            </div>
                                            <div class="text-right">
                                                <p class="font-bold text-indigo-600">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</p>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                <div class="mt-4 pt-4 border-t">
                                    <div class="flex justify-between font-bold text-lg">
                                        <span>Total:</span>
                                        <span class="text-indigo-600">Rp {{ number_format($order->grand_total, 0, ',', '.') }}</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Order Actions -->
                            <div class="bg-gray-50 px-6 py-4 border-t flex justify-between items-center">
                                <a href="{{ route('orders.show', $order->id) }}" class="text-indigo-600 hover:text-indigo-700 font-semibold">
                                    View Details →
                                </a>
                                @if($order->payment_status === 'unpaid')
                                    <a href="{{ route('payment.show', $order->id) }}" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition text-sm font-semibold">
                                        Pay Now
                                    </a>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-6">
                    {{ $orders->links() }}
                </div>
            @else
                <div class="bg-white rounded-lg shadow-sm p-12 text-center">
                    <svg class="mx-auto h-24 w-24 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <h3 class="mt-4 text-xl font-medium text-gray-900">No orders yet</h3>
                    <p class="mt-2 text-gray-500">Start shopping to create your first order</p>
                    <a href="{{ route('products.index') }}" class="mt-6 inline-block bg-indigo-600 text-white px-6 py-3 rounded-lg hover:bg-indigo-700 transition">
                        Browse Products
                    </a>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
