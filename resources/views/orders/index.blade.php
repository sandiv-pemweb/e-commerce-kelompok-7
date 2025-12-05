<x-store-layout>
    <div class="py-6 md:py-12 bg-brand-gray min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-6 md:mb-8">
                <h2 class="font-serif font-bold text-2xl md:text-3xl text-brand-dark leading-tight">
                    Pesanan Saya
                </h2>
                <p class="mt-1 md:mt-2 text-sm md:text-base text-gray-500">Lacak dan kelola pembelian Anda.</p>
            </div>

            @if(session('success'))
                <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl flex items-center shadow-sm mb-6">
                    <svg class="w-5 h-5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span class="text-sm md:text-base">{{ session('success') }}</span>
                </div>
            @endif

            @if($orders->count() > 0)
                <div class="space-y-4 md:space-y-6">
                    @foreach($orders as $order)
                        <div class="bg-white rounded-xl md:rounded-2xl shadow-sm hover:shadow-lg transition-all duration-300 border border-gray-100 overflow-hidden group">
                            <!-- Order Header -->
                            <div class="bg-gray-50/50 px-4 md:px-6 py-4 border-b border-gray-100 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                                <div class="flex flex-wrap gap-x-6 gap-y-2 w-full md:w-auto">
                                    <div class="w-1/2 md:w-auto">
                                        <p class="text-[10px] md:text-xs text-gray-500 uppercase tracking-wider font-medium">Tanggal Pesan</p>
                                        <p class="text-sm font-semibold text-brand-dark">{{ $order->created_at->format('d M Y') }}</p>
                                    </div>
                                    <div class="w-1/2 md:w-auto">
                                        <p class="text-[10px] md:text-xs text-gray-500 uppercase tracking-wider font-medium">ID Pesanan</p>
                                        <p class="text-sm font-semibold text-brand-dark">#{{ $order->code }}</p>
                                    </div>
                                    <div class="w-full md:w-auto mt-2 md:mt-0">
                                        <p class="text-[10px] md:text-xs text-gray-500 uppercase tracking-wider font-medium">Toko</p>
                                        @if($order->store && $order->store->slug)
                                            <a href="{{ route('stores.show', $order->store) }}" class="text-sm font-semibold text-brand-orange truncate hover:underline">
                                                {{ $order->store->name }}
                                            </a>
                                        @else
                                            <span class="text-sm font-semibold text-gray-500 truncate">
                                                {{ $order->store->name ?? 'Toko Tidak Diketahui' }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="flex items-center gap-2 w-full md:w-auto mt-2 md:mt-0">
                                    <span class="px-2 md:px-3 py-1 rounded-full text-[10px] md:text-xs font-bold border
                                        @if($order->payment_status === 'paid') bg-green-50 text-green-700 border-green-200
                                        @else bg-yellow-50 text-yellow-700 border-yellow-200 @endif">
                                        {{ match($order->payment_status) {
                                            'paid' => 'Lunas',
                                            'unpaid' => 'Belum Dibayar',
                                            default => ucfirst($order->payment_status)
                                        } }}
                                    </span>
                                    <span class="px-2 md:px-3 py-1 rounded-full text-[10px] md:text-xs font-bold border
                                        @if($order->order_status === 'completed') bg-green-50 text-green-700 border-green-200
                                        @elseif($order->order_status === 'cancelled') bg-red-50 text-red-700 border-red-200
                                        @elseif($order->order_status === 'shipped') bg-blue-50 text-blue-700 border-blue-200
                                        @else bg-gray-50 text-gray-700 border-gray-200 @endif">
                                        {{ match($order->order_status) {
                                            'pending' => 'Menunggu',
                                            'processing' => 'Diproses',
                                            'shipped' => 'Dikirim',
                                            'completed' => 'Selesai',
                                            'cancelled' => 'Dibatalkan',
                                            default => ucfirst($order->order_status)
                                        } }}
                                    </span>
                                </div>
                            </div>

                            <!-- Order Items -->
                            <div class="p-4 md:p-6">
                                <div class="space-y-4 md:space-y-6">
                                    @foreach($order->transactionDetails as $detail)
                                        <div class="flex gap-4 md:gap-6 items-start md:items-center">
                                            <div class="w-16 h-16 md:w-20 md:h-20 bg-gray-100 rounded-lg md:rounded-xl overflow-hidden flex-shrink-0 border border-gray-200">
                                                @if($detail->product->productImages->count() > 0)
                                                    <img src="{{ $detail->product->productImages->first()->image_url }}" class="w-full h-full object-cover">
                                                @else
                                                    <div class="w-full h-full flex items-center justify-center text-gray-400">
                                                        <svg class="w-6 h-6 md:w-8 md:h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <h4 class="font-bold text-brand-dark text-sm md:text-lg truncate">{{ $detail->product->name }}</h4>
                                                <p class="text-xs md:text-sm text-gray-500 mt-1">Qty: {{ $detail->qty }}</p>
                                                <p class="font-bold text-brand-dark text-sm md:text-lg mt-1 md:hidden">Rp {{ number_format($detail->product->price, 0, ',', '.') }}</p>
                                            </div>
                                            <div class="text-right hidden md:block">
                                                <p class="font-bold text-brand-dark text-lg">Rp {{ number_format($detail->product->price, 0, ',', '.') }}</p>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Order Footer -->
                            <div class="bg-gray-50/50 px-4 md:px-6 py-4 border-t border-gray-100 flex flex-col md:flex-row justify-between items-center gap-4">
                                <div class="flex items-center justify-between w-full md:w-auto gap-2">
                                    <span class="text-sm text-gray-500">Total Pesanan:</span>
                                    <span class="font-bold text-lg md:text-xl text-brand-dark">Rp {{ number_format($order->grand_total, 0, ',', '.') }}</span>
                                </div>
                                <div class="flex flex-col md:flex-row gap-3 w-full md:w-auto">
                                    <a href="{{ route('orders.show', $order->id) }}" class="w-full md:w-auto text-center px-6 py-2.5 bg-white border border-gray-300 text-brand-dark font-semibold rounded-xl hover:bg-gray-50 hover:border-brand-orange hover:text-brand-orange transition-all duration-300 shadow-sm text-sm md:text-base">
                                        Lihat Detail
                                    </a>
                                    @if($order->payment_status === 'unpaid')
                                        <a href="{{ route('payment.show', $order->id) }}" class="w-full md:w-auto text-center px-6 py-2.5 bg-brand-dark text-white font-semibold rounded-xl hover:bg-brand-orange transition-all duration-300 shadow-md transform hover:-translate-y-0.5 text-sm md:text-base">
                                            Bayar Sekarang
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-8">
                    {{ $orders->links() }}
                </div>
            @else
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 md:p-16 text-center">
                    <div class="w-16 h-16 md:w-24 md:h-24 bg-orange-50 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-8 h-8 md:w-12 md:h-12 text-brand-orange" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl md:text-2xl font-serif font-bold text-brand-dark mb-2">Belum ada pesanan</h3>
                    <p class="text-sm md:text-base text-gray-500 mb-8 max-w-md mx-auto">Sepertinya Anda belum melakukan pemesanan apapun. Mulai jelajahi koleksi kami untuk menemukan buku favorit Anda selanjutnya!</p>
                    <a href="{{ route('products.index') }}" class="inline-flex items-center px-6 md:px-8 py-3 bg-brand-dark text-white font-bold rounded-xl hover:bg-brand-orange transition-all duration-300 shadow-lg transform hover:-translate-y-1 text-sm md:text-base">
                        Mulai Belanja
                        <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                    </a>
                </div>
            @endif
        </div>
    </div>
</x-store-layout>
