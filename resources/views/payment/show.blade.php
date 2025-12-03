<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Payment - Order #{{ $transaction->code }}
            </h2>
            <a href="{{ route('orders.show', $transaction->id) }}" class="text-indigo-600 hover:text-indigo-700">
                ← Back to Order
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Payment Status -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-xl font-bold">Payment Status</h3>
                    <span class="px-4 py-2 rounded-full text-sm font-semibold
                        @if($transaction->payment_status === 'paid') bg-green-100 text-green-800
                        @else bg-yellow-100 text-yellow-800 @endif">
                        {{ ucfirst($transaction->payment_status) }}
                    </span>
                </div>

                @if($transaction->payment_status === 'unpaid')
                    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4">
                        <p class="text-yellow-800 font-semibold">Payment Required</p>
                        <p class="text-sm text-yellow-700 mt-1">Please complete payment to process your order</p>
                    </div>
                @else
                    <div class="bg-green-50 border-l-4 border-green-400 p-4">
                        <p class="text-green-800 font-semibold">Payment Received</p>
                        <p class="text-sm text-green-700 mt-1">Thank you for your payment! Your order is being processed.</p>
                    </div>
                @endif
            </div>

            <!-- Order Summary -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <h3 class="text-xl font-bold mb-4">Order Summary</h3>
                
                <div class="space-y-3 mb-4">
                    @foreach($transaction->transactionDetails as $detail)
                        <div class="flex justify-between items-center py-2 border-b">
                            <div class="flex items-center gap-3">
                                <div class="w-12 h-12 bg-gray-200 rounded overflow-hidden">
                                    @if($detail->product->productImages->count() > 0)
                                        <img src="{{ $detail->product->productImages->first()->image_url }}" class="w-full h-full object-cover">
                                    @endif
                                </div>
                                <div>
                                    <p class="font-semibold text-sm">{{ $detail->product->name }}</p>
                                    <p class="text-xs text-gray-600">{{ $detail->qty }} × Rp {{ number_format($detail->product->price, 0, ',', '.') }}</p>
                                </div>
                            </div>
                            <p class="font-bold text-indigo-600">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</p>
                        </div>
                    @endforeach
                </div>

                <div class="border-t pt-3 space-y-2">
                    <div class="flex justify-between text-sm">
                        <span>Subtotal:</span>
                        <span>Rp {{ number_format($transaction->grand_total - $transaction->shipping_cost - $transaction->tax, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span>Shipping:</span>
                        <span>Rp {{ number_format($transaction->shipping_cost, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span>Tax (11%):</span>
                        <span>Rp {{ number_format($transaction->tax, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between font-bold text-lg pt-2 border-t">
                        <span>Total Amount:</span>
                        <span class="text-indigo-600">Rp {{ number_format($transaction->grand_total, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>

            <!-- Payment Instructions -->
            @if($transaction->payment_status === 'unpaid')
                <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                    <h3 class="text-xl font-bold mb-4">Payment Instructions</h3>
                    
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-4">
                        <p class="font-semibold text-blue-900 mb-2">Bank Transfer</p>
                        <div class="space-y-1 text-sm">
                            <p class="text-blue-800">Bank: BCA</p>
                            <p class="text-blue-800">Account Number: 1234567890</p>
                            <p class="text-blue-800">Account Name: E-Commerce Platform</p>
                        </div>
                    </div>

                    <divclass="bg-gray-50 border border-gray-200 rounded-lg p-4">
                        <p class="font-semibold text-gray-900 mb-2">E-Wallet / QRIS</p>
                        <p class="text-sm text-gray-700">Coming soon - Available after payment gateway integration</p>
                    </div>
                </div>

                <!-- Upload Payment Proof -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-xl font-bold mb-4">Upload Payment Proof</h3>
                    
                    <form action="{{ route('payment.upload', $transaction->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Payment Proof Image (JPEG, PNG) *
                            </label>
                            <input type="file" name="payment_proof" accept="image/jpeg,image/png,image/jpg" required class="w-full border border-gray-300 rounded-md p-2">
                            @error('payment_proof')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                            <p class="text-xs text-gray-500 mt-1">Upload a screenshot or photo of your payment confirmation</p>
                        </div>

                        <button type="submit" class="w-full bg-indigo-600 text-white px-6 py-3 rounded-lg hover:bg-indigo-700 transition font-semibold">
                            Submit Payment Proof
                        </button>
                    </form>

                    <div class="mt-4 bg-yellow-50 border-l-4 border-yellow-400 p-4">
                        <p class="text-sm text-yellow-700">
                            <strong>Note:</strong> After uploading payment proof, please wait for seller confirmation. You will be notified once payment is verified.
                        </p>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
