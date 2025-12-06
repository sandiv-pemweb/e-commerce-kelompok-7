<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="flex items-center gap-4">
                <a href="{{ route('admin.orders.index') }}" class="p-2 bg-white rounded-xl border border-gray-200 hover:bg-gray-50 text-gray-500 hover:text-brand-dark transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                </a>
                <div>
                    <h2 class=" font-bold text-2xl text-brand-dark leading-tight flex items-center gap-2">
                        <span class="text-gray-300">#</span>{{ $order->code }}
                    </h2>
                    <p class="text-sm text-gray-400 mt-1 font-medium">Dipesan pada {{ $order->created_at->format('d M Y, H:i') }}</p>
                </div>
            </div>

            <div class="flex items-center gap-3">
                 <span class="px-4 py-2 text-sm font-bold rounded-full 
                    {{ match($order->order_status) {
                        'completed' => 'bg-green-50 text-green-700 border border-green-100',
                        'cancelled' => 'bg-red-50 text-red-700 border border-red-100',
                        'shipped' => 'bg-blue-50 text-blue-700 border border-blue-100',
                        default => 'bg-yellow-50 text-yellow-700 border border-yellow-100'
                    } }}">
                    {{ match($order->order_status) {
                        'pending' => 'Menunggu Pembayaran',
                        'processing' => 'Diproses Penjual',
                        'shipped' => 'Dalam Pengiriman',
                        'completed' => 'Pesanan Selesai',
                        'cancelled' => 'Dibatalkan',
                        default => ucfirst($order->order_status)
                    } }}
                </span>
            </div>
        </div>
    </x-slot>

    <div class="py-6 bg-gray-50/50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Left Column: Order Items & details -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Tracking Number -->
                    @if($order->tracking_number)
                        <div class="bg-gradient-to-r from-blue-50 to-white border border-blue-100 rounded-3xl p-6 flex items-start gap-4">
                            <div class="p-3 bg-blue-100 rounded-2xl text-blue-600">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            </div>
                            <div>
                                <h4 class="font-bold text-blue-900 text-lg mb-1">Nomor Resi</h4>
                                <p class="text-blue-800 font-mono text-2xl tracking-wider">{{ $order->tracking_number }}</p>
                                <p class="text-sm text-blue-600 mt-2 font-medium">Pengiriman via JNE Regular</p>
                            </div>
                        </div>
                    @endif

                    <!-- Products Card -->
                    <div class="bg-white overflow-hidden shadow-sm rounded-3xl border border-gray-100">
                        <div class="p-8 border-b border-gray-50 flex items-center justify-between">
                            <h3 class=" font-bold text-xl text-brand-dark">Item Pesanan</h3>
                            <span class="text-sm text-gray-400 font-bold">{{ $order->transactionDetails->count() }} Item</span>
                        </div>
                        <div class="p-8 space-y-8">
                            @foreach($order->transactionDetails as $detail)
                                <div class="flex gap-6 items-start">
                                    <div class="w-24 h-24 flex-shrink-0 bg-gray-50 rounded-2xl overflow-hidden border border-gray-100">
                                        @if($detail->product->productImages->first())
                                            <img src="{{ $detail->product->productImages->first()->image_url }}" 
                                                 alt="{{ $detail->product->name }}" 
                                                 class="w-full h-full object-cover">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center text-gray-300">
                                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="flex-1 min-w-0 py-1">
                                        <h4 class="font-bold text-brand-dark text-lg leading-tight mb-2">{{ $detail->product->name }}</h4>
                                        <p class="text-sm text-gray-500 mb-1 font-medium">{{ $detail->qty }} x Rp {{ number_format($detail->product->price, 0, ',', '.') }}</p>
                                        <p class="text-xs text-brand-orange font-bold uppercase tracking-wider">{{ $detail->product->productCategory->name ?? 'General' }}</p>
                                    </div>
                                    <div class="font-bold text-lg text-brand-dark py-1">
                                        Rp {{ number_format($detail->subtotal, 0, ',', '.') }}
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        
                        <div class="bg-gray-50/50 p-8 border-t border-gray-50 space-y-4">
                             <div class="flex justify-between text-sm">
                                <span class="text-gray-500 font-medium">Subtotal Produk</span>
                                <span class="font-bold text-gray-700">Rp {{ number_format($order->grand_total - $order->shipping_cost - $order->tax, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-500 font-medium">Biaya Pengiriman</span>
                                <span class="font-bold text-gray-700">Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</span>
                            </div>
                             <div class="flex justify-between text-sm">
                                <span class="text-gray-500 font-medium">Pajak (PPN)</span>
                                <span class="font-bold text-gray-700">Rp {{ number_format($order->tax, 0, ',', '.') }}</span>
                            </div>
                            <div class="pt-6 border-t border-gray-200 mt-2 flex justify-between items-center">
                                <span class="font-bold text-xl text-brand-dark">Total Pembayaran</span>
                                <span class="font-bold text-3xl text-brand-orange">Rp {{ number_format($order->grand_total, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column: Info & Actions -->
                <div class="space-y-6">
                    <!-- Store Info -->
                    <div class="bg-white shadow-sm rounded-3xl border border-gray-100 p-8">
                        <div class="flex justify-between items-start mb-6">
                            <h3 class=" font-bold text-lg text-brand-dark">Informasi Toko</h3>
                            <a href="{{ route('admin.stores.show', $order->store) }}" class="text-xs font-bold text-brand-orange hover:text-brand-dark transition-colors">Lihat Detail &rarr;</a>
                        </div>
                        <div class="flex items-center gap-4">
                            @if($order->store->logo)
                                <img src="{{ $order->store->logo_url }}" class="w-12 h-12 rounded-full border-2 border-gray-100 object-cover">
                            @else
                                <div class="w-12 h-12 rounded-full bg-brand-orange text-white flex items-center justify-center text-lg font-bold border-2 border-orange-100 shadow-sm">
                                    {{ substr($order->store->name, 0, 1) }}
                                </div>
                            @endif
                            <div>
                                <h4 class="font-bold text-brand-dark leading-tight">{{ $order->store->name }}</h4>
                                <div class="flex items-center gap-2 mt-1">
                                    @if($order->store->is_verified)
                                        <span class="px-2 py-0.5 bg-green-100 text-green-700 text-[10px] font-bold rounded-full border border-green-200">Verified</span>
                                    @else
                                        <span class="px-2 py-0.5 bg-yellow-100 text-yellow-700 text-[10px] font-bold rounded-full border border-yellow-200">Unverified</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Customer Info -->
                    <div class="bg-white shadow-sm rounded-3xl border border-gray-100 p-8">
                        <h3 class=" font-bold text-lg text-brand-dark mb-6">Informasi Pembeli</h3>
                        <div class="space-y-6">
                            <div class="flex items-center gap-4">
                                <img src="https://ui-avatars.com/api/?name={{ $order->buyer->user->name }}&background=random" class="w-12 h-12 rounded-full border-2 border-gray-100">
                                <div>
                                    <p class="font-bold text-brand-dark">{{ $order->buyer->user->name }}</p>
                                    <p class="text-xs text-gray-500 font-medium">{{ $order->buyer->user->email }}</p>
                                </div>
                            </div>
                            <div class="bg-gray-50 rounded-2xl p-4 border border-gray-100">
                                <p class="text-xs font-bold text-gray-400 uppercase mb-2 tracking-wider">Alamat Pengiriman</p>
                                <p class="text-sm text-gray-700 leading-relaxed font-medium">
                                    {{ $order->address }}<br>
                                    {{ $order->city }} {{ $order->postal_code }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Proof & Verification -->
                    <div class="bg-white shadow-sm rounded-3xl border border-gray-100 p-8">
                        <h3 class=" font-bold text-lg text-brand-dark mb-6">Status Pembayaran</h3>

                        <div class="text-center mb-8">
                            <span class="inline-flex items-center gap-2 px-6 py-3 rounded-2xl text-sm font-bold w-full justify-center
                                {{ match($order->payment_status) {
                                    'paid' => 'bg-green-50 text-green-700 border border-green-100',
                                    'refunded' => 'bg-red-50 text-red-700 border border-red-100',
                                    'waiting' => 'bg-purple-50 text-purple-700 border-purple-100',
                                    default => 'bg-yellow-50 text-yellow-700 border border-yellow-100'
                                } }}">
                                {{ match($order->payment_status) {
                                    'paid' => 'Pembayaran Lunas',
                                    'unpaid' => 'Belum Dibayar',
                                    'refunded' => 'Dana Dikembalikan',
                                    'waiting' => 'Menunggu Verifikasi',
                                    default => ucfirst($order->payment_status)
                                } }}
                            </span>
                        </div>

                        @if($order->payment_proof)
                            <div class="mb-6">
                                <p class="text-xs font-bold text-gray-400 uppercase mb-3 tracking-wider">Bukti Transfer</p>
                                <a href="{{ asset('storage/' . $order->payment_proof) }}" target="_blank" class="block group relative overflow-hidden rounded-2xl border border-gray-200">
                                    <img src="{{ asset('storage/' . $order->payment_proof) }}" alt="Bukti Pembayaran" class="w-full transition-transform duration-700 group-hover:scale-105">
                                    <div class="absolute inset-0 bg-brand-dark/0 group-hover:bg-brand-dark/20 transition-colors flex items-center justify-center">
                                        <div class="bg-white px-4 py-2 rounded-full text-xs font-bold text-brand-dark shadow-lg opacity-0 group-hover:opacity-100 transition-all transform translate-y-4 group-hover:translate-y-0">
                                            Perbesar Gambar
                                        </div>
                                    </div>
                                </a>
                            </div>

                            @if($order->payment_status === 'unpaid' || $order->payment_status === 'waiting')
                                <button type="button" 
                                        onclick="document.getElementById('verify-payment-{{ $order->id }}').classList.remove('hidden')"
                                        class="w-full py-3.5 bg-brand-dark text-white font-bold rounded-xl hover:bg-brand-orange transition-all shadow-lg shadow-brand-dark/20 hover:shadow-brand-orange/30 transform hover:-translate-y-0.5 flex items-center justify-center gap-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    Verifikasi Pembayaran
                                </button>
                                <form id="verify-payment-{{ $order->id }}-form" action="{{ route('admin.orders.verify-payment', $order) }}" method="POST" class="hidden">
                                    @csrf
                                    @method('PATCH')
                                </form>
                                <x-confirmation-modal 
                                    id="verify-payment-{{ $order->id }}"
                                    type="info" 
                                    title="Verifikasi Pembayaran" 
                                    message="Pastikan bukti transfer valid. Verifikasi akan mengubah status menjadi Lunas."
                                    confirmText="Ya, Verifikasi Valid"
                                    cancelText="Batal">
                                </x-confirmation-modal>
                            @endif
                        @else
                            <div class="text-center py-8 bg-gray-50 rounded-2xl border border-dashed border-gray-200">
                                <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                                <p class="text-gray-500 text-sm font-medium">Belum ada bukti pembayaran</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
