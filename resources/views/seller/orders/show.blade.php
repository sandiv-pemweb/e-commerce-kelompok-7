<x-app-layout>
    <x-slot name="header">
        <h2 class="font-serif font-bold text-2xl text-brand-dark leading-tight">
            {{ __('Detail Pesanan #') . $order->code }}
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

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Left Column: Order Info & Items -->
                <div class="lg:col-span-2 space-y-8">
                    <!-- Order Info -->
                    <div class="bg-white overflow-hidden shadow-lg rounded-2xl border border-gray-100">
                        <div class="p-8">
                            <h3 class="text-xl font-serif font-bold text-brand-dark mb-6 border-b border-gray-100 pb-4">Informasi Pesanan</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                <div>
                                    <p class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Kode Pesanan</p>
                                    <p class="text-lg font-bold text-brand-dark">#{{ $order->code }}</p>
                                </div>
                                <div>
                                    <p class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Tanggal Pesanan</p>
                                    <p class="text-lg font-medium text-gray-900">{{ $order->created_at->format('d M Y H:i') }}</p>
                                </div>
                                <div>
                                    <p class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Pembeli</p>
                                    <p class="text-lg font-medium text-gray-900">{{ $order->buyer->user->name }}</p>
                                </div>
                                <div>
                                    <p class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Status Pembayaran</p>
                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-bold rounded-full {{ $order->payment_status == 'paid' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                        {{ match($order->payment_status) {
                                            'paid' => 'Lunas',
                                            'unpaid' => 'Belum Dibayar',
                                            default => ucfirst($order->payment_status)
                                        } }}
                                    </span>
                                </div>
                                @if($order->payment_proof)
                                    <div class="md:col-span-2">
                                        <p class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Bukti Pembayaran</p>
                                        <a href="{{ asset('storage/' . $order->payment_proof) }}" target="_blank" class="block">
                                            <img src="{{ asset('storage/' . $order->payment_proof) }}" alt="Payment Proof" class="max-w-sm rounded-lg border-2 border-gray-200 hover:border-brand-orange transition-colors shadow-sm">
                                        </a>
                                    </div>
                                @endif
                                <div class="md:col-span-2">
                                    <p class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Alamat Pengiriman</p>
                                    <p class="text-lg font-medium text-gray-900">{{ $order->address }}, {{ $order->city }}, {{ $order->postal_code }}</p>
                                </div>
                                <div class="md:col-span-2">
                                    <p class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Metode Pengiriman</p>
                                    <p class="text-lg font-medium text-gray-900">{{ $order->shipping }} - {{ $order->shipping_type }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Order Items -->
                    <div class="bg-white overflow-hidden shadow-lg rounded-2xl border border-gray-100">
                        <div class="p-8">
                            <h3 class="text-xl font-serif font-bold text-brand-dark mb-6 border-b border-gray-100 pb-4">Item Pesanan</h3>
                            <div class="overflow-x-auto rounded-xl border border-gray-200">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-brand-dark text-white">
                                        <tr>
                                            <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider">Produk</th>
                                            <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider">Harga</th>
                                            <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider">Jml</th>
                                            <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider">Subtotal</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach($order->transactionDetails as $detail)
                                            <tr class="hover:bg-blue-50 transition-colors duration-150">
                                                <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $detail->product->name }}</td>
                                                <td class="px-6 py-4 text-sm text-gray-500">Rp {{ number_format($detail->price, 0, ',', '.') }}</td>
                                                <td class="px-6 py-4 text-sm text-gray-500">{{ $detail->quantity }}</td>
                                                <td class="px-6 py-4 text-sm font-bold text-gray-900">Rp {{ number_format($detail->price * $detail->quantity, 0, ',', '.') }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot class="bg-gray-50">
                                        <tr>
                                            <td colspan="3" class="px-6 py-3 text-right text-sm font-bold text-gray-700">Biaya Pengiriman:</td>
                                            <td class="px-6 py-3 text-sm font-bold text-gray-900">Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</td>
                                        </tr>
                                        <tr>
                                            <td colspan="3" class="px-6 py-3 text-right text-sm font-bold text-gray-700">Pajak:</td>
                                            <td class="px-6 py-3 text-sm font-bold text-gray-900">Rp {{ number_format($order->tax, 0, ',', '.') }}</td>
                                        </tr>
                                        <tr class="bg-brand-dark text-white">
                                            <td colspan="3" class="px-6 py-4 text-right text-base font-bold">Total:</td>
                                            <td class="px-6 py-4 text-base font-bold">Rp {{ number_format($order->grand_total, 0, ',', '.') }}</td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column: Status Update -->
                <div>
                    <div class="bg-white overflow-hidden shadow-lg rounded-2xl border border-gray-100 sticky top-6">
                        <div class="p-8">
                            <h3 class="text-xl font-serif font-bold text-brand-dark mb-6 border-b border-gray-100 pb-4">Perbarui Status Pesanan</h3>
                            
                            <form method="POST" action="{{ route('seller.orders.update', $order) }}" class="space-y-6">
                                @csrf
                                @method('PATCH')

                                <div>
                                    <label for="payment_status" class="block font-bold text-sm text-gray-700 mb-2">Status Pembayaran</label>
                                    <select id="payment_status" name="payment_status" class="w-full border-gray-300 focus:border-brand-orange focus:ring-brand-orange rounded-xl shadow-sm">
                                        <option value="unpaid" {{ $order->payment_status == 'unpaid' ? 'selected' : '' }}>Belum Dibayar</option>
                                        <option value="paid" {{ $order->payment_status == 'paid' ? 'selected' : '' }}>Dibayar</option>
                                    </select>
                                    @error('payment_status')
                                        <span class="text-red-600 text-sm mt-1 block">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div>
                                    <label for="order_status" class="block font-bold text-sm text-gray-700 mb-2">Status Pesanan</label>
                                    <select id="order_status" name="order_status" class="w-full border-gray-300 focus:border-brand-orange focus:ring-brand-orange rounded-xl shadow-sm">
                                        <option value="pending" {{ $order->order_status == 'pending' ? 'selected' : '' }}>Menunggu</option>
                                        <option value="processing" {{ $order->order_status == 'processing' ? 'selected' : '' }}>Diproses</option>
                                        <option value="shipped" {{ $order->order_status == 'shipped' ? 'selected' : '' }}>Dikirim</option>
                                        <option value="completed" {{ $order->order_status == 'completed' ? 'selected' : '' }}>Selesai</option>
                                        <option value="cancelled" {{ $order->order_status == 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                                    </select>
                                    @error('order_status')
                                        <span class="text-red-600 text-sm mt-1 block">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div>
                                    <label for="tracking_number" class="block font-bold text-sm text-gray-700 mb-2">Nomor Resi <span class="text-gray-400 font-normal">(Wajib jika Dikirim)</span></label>
                                    <input id="tracking_number" type="text" name="tracking_number" value="{{ old('tracking_number', $order->tracking_number) }}" class="w-full border-gray-300 focus:border-brand-orange focus:ring-brand-orange rounded-xl shadow-sm" placeholder="Masukkan nomor resi">
                                    @error('tracking_number')
                                        <span class="text-red-600 text-sm mt-1 block">{{ $message }}</span>
                                    @enderror
                                </div>

                                <button type="submit" class="w-full px-6 py-3 bg-brand-dark text-white font-bold rounded-xl hover:bg-brand-orange transition-colors shadow-md">
                                    Perbarui Status
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
