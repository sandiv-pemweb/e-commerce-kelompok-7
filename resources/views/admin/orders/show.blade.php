<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h2 class="font-serif font-bold text-2xl text-brand-dark leading-tight flex items-center gap-2">
                    <span class="text-gray-400">#</span>{{ $order->code }}
                </h2>
                <p class="text-sm text-gray-500 mt-1">Dipesan pada {{ $order->created_at->format('d M Y, H:i') }}</p>
            </div>
            <div class="flex items-center gap-3">
                <span class="px-3 py-1 text-sm font-bold rounded-full 
                    {{ match($order->order_status) {
                        'completed' => 'bg-green-100 text-green-700 border border-green-200',
                        'cancelled' => 'bg-red-100 text-red-700 border border-red-200',
                        'shipped' => 'bg-blue-100 text-blue-700 border border-blue-200',
                        default => 'bg-yellow-100 text-yellow-700 border border-yellow-200'
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

    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Left Column: Order Items & details -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Tracking Number -->
                    @if($order->tracking_number)
                        <div class="bg-blue-50 border border-blue-200 rounded-2xl p-6 flex items-start gap-4">
                            <div class="p-3 bg-blue-100 rounded-xl text-blue-600">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            </div>
                            <div>
                                <h4 class="font-bold text-blue-900 text-lg mb-1">Nomor Resi</h4>
                                <p class="text-blue-800 font-mono text-xl">{{ $order->tracking_number }}</p>
                                <p class="text-sm text-blue-600 mt-2">Dapat digunakan untuk melacak pengiriman paket.</p>
                            </div>
                        </div>
                    @endif

                    <!-- Products Card -->
                    <div class="bg-white overflow-hidden shadow-sm rounded-2xl border border-gray-100">
                        <div class="p-6 border-b border-gray-100 bg-white">
                            <h3 class="font-bold text-gray-800 flex items-center gap-2">
                                <svg class="w-5 h-5 text-brand-orange" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                                </svg>
                                Item Pesanan
                            </h3>
                        </div>
                        <div class="p-6 space-y-6">
                            @foreach($order->transactionDetails as $detail)
                                <div class="flex gap-4 items-start">
                                    <div class="w-20 h-20 flex-shrink-0 bg-gray-100 rounded-xl overflow-hidden border border-gray-200">
                                        @if($detail->product->productImages->first())
                                            <img src="{{ $detail->product->productImages->first()->image_url }}" 
                                                 alt="{{ $detail->product->name }}" 
                                                 class="w-full h-full object-cover">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center text-gray-400">
                                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <h4 class="font-bold text-gray-900 text-lg leading-tight mb-1">{{ $detail->product->name }}</h4>
                                        <p class="text-sm text-gray-500 mb-2">{{ $detail->qty }} x Rp {{ number_format($detail->product->price, 0, ',', '.') }}</p>
                                    </div>
                                    <div class="font-bold text-lg text-gray-900">
                                        Rp {{ number_format($detail->subtotal, 0, ',', '.') }}
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        
                        <div class="bg-gray-50 p-6 border-t border-gray-100 space-y-3">
                             <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Subtotal Produk</span>
                                <span class="font-medium">Rp {{ number_format($order->grand_total - $order->shipping_cost - $order->tax, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Biaya Pengiriman</span>
                                <span class="font-medium">Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</span>
                            </div>
                             <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Pajak (PPN)</span>
                                <span class="font-medium">Rp {{ number_format($order->tax, 0, ',', '.') }}</span>
                            </div>
                            <div class="pt-3 border-t border-gray-200 flex justify-between items-center">
                                <span class="font-bold text-brand-dark">Total Pembayaran</span>
                                <span class="font-bold text-2xl text-brand-orange">Rp {{ number_format($order->grand_total, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column: Info & Actions -->
                <div class="space-y-6">
                    <!-- Customer Info -->
                    <div class="bg-white shadow-sm rounded-2xl border border-gray-100 p-6">
                        <h3 class="font-bold text-gray-800 mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5 text-brand-orange" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            Informasi Pembeli
                        </h3>
                        <div class="space-y-3">
                            <div class="flex items-center gap-3">
                                <img src="https://ui-avatars.com/api/?name={{ $order->buyer->user->name }}&background=random" class="w-10 h-10 rounded-full">
                                <div>
                                    <p class="font-bold text-gray-900 text-sm">{{ $order->buyer->user->name }}</p>
                                    <p class="text-xs text-gray-500">{{ $order->buyer->user->email }}</p>
                                </div>
                            </div>
                            <div class="border-t border-gray-100 pt-3 mt-3">
                                <p class="text-xs font-bold text-gray-500 uppercase mb-1">Alamat Pengiriman</p>
                                <p class="text-sm text-gray-700 leading-relaxed">
                                    {{ $order->address }}<br>
                                    {{ $order->city }}, {{ $order->postal_code }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Proof & Verification -->
                    <div class="bg-white shadow-sm rounded-2xl border border-gray-100 p-6">
                        <h3 class="font-bold text-gray-800 mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5 text-brand-orange" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            Status Pembayaran
                        </h3>

                        <div class="text-center mb-6">
                            <span class="inline-flex items-center gap-1.5 px-4 py-2 rounded-full text-sm font-bold w-full justify-center
                                {{ match($order->payment_status) {
                                    'paid' => 'bg-green-50 text-green-700 border border-green-200',
                                    'refunded' => 'bg-red-50 text-red-700 border border-red-200',
                                    'waiting' => 'bg-blue-50 text-blue-700 border-blue-200',
                                    default => 'bg-yellow-50 text-yellow-700 border border-yellow-200'
                                } }}">
                                {{ match($order->payment_status) {
                                    'paid' => 'Lunas',
                                    'unpaid' => 'Belum Dibayar',
                                    'refunded' => 'Dikembalikan',
                                    'waiting' => 'Menunggu Verifikasi',
                                    default => ucfirst($order->payment_status)
                                } }}
                            </span>
                        </div>

                        @if($order->payment_proof)
                            <div class="mb-4">
                                <p class="text-xs font-bold text-gray-500 uppercase mb-2">Bukti Transfer</p>
                                <a href="{{ asset('storage/' . $order->payment_proof) }}" target="_blank" class="block group relative overflow-hidden rounded-xl border border-gray-200">
                                    <img src="{{ asset('storage/' . $order->payment_proof) }}" alt="Bukti Pembayaran" class="w-full transition-transform duration-500 group-hover:scale-110">
                                    <div class="absolute inset-0 bg-black/0 group-hover:bg-black/10 transition-colors flex items-center justify-center">
                                        <div class="bg-white/90 backdrop-blur-sm px-3 py-1 rounded-full text-xs font-bold shadow-sm opacity-0 group-hover:opacity-100 transition-opacity transform translate-y-2 group-hover:translate-y-0">
                                            Lihat Full
                                        </div>
                                    </div>
                                </a>
                            </div>

                            @if($order->payment_status === 'unpaid' || $order->payment_status === 'waiting')
                                <form action="{{ route('admin.orders.verify-payment', $order) }}" method="POST" onsubmit="return confirm('Verifikasi pembayaran ini? Status akan berubah menjadi Paid (Lunas).')">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="w-full py-3 bg-brand-dark text-white font-bold rounded-xl hover:bg-brand-orange transition-all shadow-lg hover:shadow-brand-orange/30 transform hover:-translate-y-0.5 flex items-center justify-center gap-2">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        Verifikasi Pembayaran
                                    </button>
                                </form>
                            @endif
                        @else
                            <div class="text-center py-8 bg-gray-50 rounded-xl border-2 border-dashed border-gray-200">
                                <svg class="w-10 h-10 text-gray-300 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                                <p class="text-gray-500 text-sm font-medium">Belum ada bukti pembayaran.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
