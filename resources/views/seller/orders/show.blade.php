<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h2 class=" font-bold text-2xl text-brand-dark leading-tight flex items-center gap-2">
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

    <div class="py-6 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-8 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl flex items-center shadow-sm">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    {{ session('success') }}
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <div class="lg:col-span-2 space-y-6">


                    @if($order->tracking_number)
                        <div class="bg-gradient-to-r from-blue-50 to-white border border-blue-100 rounded-3xl p-6 flex items-start gap-4">
                            <div class="p-3 bg-blue-100 rounded-2xl text-blue-600">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            </div>
                            <div>
                                <h4 class="font-bold text-blue-900 text-lg mb-1">Nomor Resi</h4>
                                <p class="text-blue-800 font-mono text-2xl tracking-wider">{{ $order->tracking_number }}</p>
                                <p class="text-sm text-blue-600 mt-2 font-medium">Pengiriman via Ekspedisi</p>
                            </div>
                        </div>
                    @endif


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


                <div class="space-y-6">

                    <div class="bg-white shadow-sm rounded-3xl border border-gray-100 p-8">
                        <h3 class=" font-bold text-lg text-brand-dark mb-6 flex items-center gap-2">
                            <svg class="w-5 h-5 text-brand-orange" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                            Perbarui Status
                        </h3>

                        <form method="POST" action="{{ route('seller.orders.update', $order) }}" class="space-y-6" x-data="{ status: '{{ $order->order_status }}' }">
                            @csrf
                            @method('PATCH')

                            <div>
                                <label class="block font-bold text-sm text-gray-700 mb-2">Status Pembayaran</label>
                                <div class="px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-600 font-medium flex items-center justify-between">
                                    <span>
                                        {{ match($order->payment_status) {
                                            'paid' => 'Lunas',
                                            'unpaid' => 'Belum Dibayar',
                                            'refunded' => 'Dikembalikan',
                                            'waiting' => 'Menunggu Verifikasi',
                                            default => ucfirst($order->payment_status)
                                        } }}
                                    </span>
                                    @if($order->payment_status === 'paid')
                                        <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                    @elseif($order->payment_status === 'refunded')
                                         <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                                    @elseif($order->payment_status === 'waiting')
                                        <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    @else
                                        <svg class="w-5 h-5 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    @endif
                                </div>
                                <p class="text-xs text-gray-500 mt-2">*Status pembayaran hanya dapat diverifikasi oleh Admin.</p>
                            </div>

                            <div>
                                <label for="order_status" class="block font-bold text-sm text-gray-700 mb-2">Status Pesanan</label>
                                
                                @if($order->order_status === 'pending')
                                    <div class="w-full px-4 py-3 bg-yellow-50 border border-yellow-200 text-yellow-700 rounded-xl font-bold flex items-center gap-2">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        Menunggu Pembayaran
                                    </div>
                                    <p class="text-xs text-gray-500 mt-2">*Menunggu konfirmasi pembayaran oleh Admin.</p>
                                    
                                @elseif($order->order_status === 'shipped')
                                    <div class="w-full px-4 py-3 bg-blue-50 border border-blue-200 text-blue-700 rounded-xl font-bold flex items-center gap-2">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                        Dalam Pengiriman
                                    </div>
                                    <p class="text-xs text-gray-500 mt-2">*Pesanan sedang dalam pengiriman.</p>
                                    
                                @elseif(in_array($order->order_status, ['cancelled', 'completed']))
                                    <div class="w-full px-4 py-3 rounded-xl border font-bold flex items-center gap-2 {{ $order->order_status == 'cancelled' ? 'bg-red-50 text-red-700 border-red-200' : 'bg-green-50 text-green-700 border-green-200' }}">
                                        @if($order->order_status == 'cancelled')
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                            Pesanan Dibatalkan
                                        @else
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                            Pesanan Selesai
                                        @endif
                                    </div>

                                @else

                                    <select x-model="status" name="order_status" class="w-full border-gray-300 focus:border-brand-orange focus:ring-brand-orange rounded-xl shadow-sm cursor-pointer bg-gray-50/50">
                                        <option value="processing" {{ $order->order_status == 'processing' ? 'selected' : '' }}>Diproses Penjual</option>
                                        <option value="shipped" {{ $order->order_status == 'shipped' ? 'selected' : '' }}>Dikirim</option>
                                        <option value="cancelled">Dibatalkan</option>
                                    </select>
                                    <p class="text-xs text-gray-500 mt-2">*Pastikan barang siap sebelum mengubah status menjadi "Dikirim".</p>
                                @endif
                                
                                @error('order_status')
                                    <span class="text-red-600 text-sm mt-1 block">{{ $message }}</span>
                                @enderror
                            </div>

                            <div x-show="status == 'shipped'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform -translate-y-2" x-transition:enter-end="opacity-100 transform translate-y-0">
                                <label for="tracking_number" class="block font-bold text-sm text-gray-700 mb-2">Nomor Resi <span class="text-gray-400 font-normal">(Wajib)</span></label>
                                <input id="tracking_number" type="text" name="tracking_number" value="{{ old('tracking_number', $order->tracking_number) }}" class="w-full border-gray-300 focus:border-brand-orange focus:ring-brand-orange rounded-xl shadow-sm bg-gray-50/50" placeholder="Masukkan nomor resi" {{ $order->order_status == 'shipped' ? 'readonly' : '' }} :required="status == 'shipped'">
                                @error('tracking_number')
                                    <span class="text-red-600 text-sm mt-1 block">{{ $message }}</span>
                                @enderror
                            </div>


                            @if($order->order_status === 'processing')
                                <button type="submit" class="w-full px-6 py-3 bg-brand-dark text-white font-bold rounded-xl hover:bg-brand-orange transition-all shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                                    Perbarui Status
                                </button>
                            @endif
                        </form>
                    </div>


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
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
