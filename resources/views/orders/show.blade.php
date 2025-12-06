<x-store-layout>
    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
                <div>
                    <div class="flex items-center gap-3 mb-1">
                        <a href="{{ route('orders.index') }}" class="text-gray-400 hover:text-brand-orange transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                        </a>
                        <h2 class="font-serif font-bold text-2xl text-brand-dark leading-tight flex items-center gap-2">
                            <span class="text-gray-400">#</span>{{ $order->code }}
                        </h2>
                    </div>
                    <p class="text-sm text-gray-500 ml-9">Dipesan pada {{ $order->created_at->format('d M Y, H:i') }}</p>
                </div>
                <div class="flex items-center gap-3 ml-9 md:ml-0">
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
                                <p class="text-sm text-blue-600 mt-2">Gunakan nomor ini untuk melacak pengiriman paket Anda.</p>
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
                                        
                                        @if($order->payment_status === 'paid' && $order->order_status === 'completed')
                                            @php
                                                $existingReview = \App\Models\ProductReview::where('transaction_id', $order->id)
                                                    ->where('product_id', $detail->product_id)
                                                    ->first();
                                            @endphp

                                            @if($existingReview)
                                                <span class="inline-flex items-center px-3 py-1 mt-2 rounded-lg text-xs font-bold bg-green-100 text-green-700">
                                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                                                    Diulas
                                                </span>
                                            @else
                                                <button onclick="openReviewModal('{{ $order->id }}', '{{ $detail->product_id }}', '{{ $detail->product->name }}')" class="mt-2 text-sm font-bold text-brand-orange hover:text-brand-dark transition-colors underline">
                                                    Beri Ulasan
                                                </button>
                                            @endif
                                        @endif
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
                    <!-- Actions Card -->
                    @if($order->payment_status === 'unpaid' || $order->order_status === 'shipped')
                        <div class="bg-white shadow-sm rounded-2xl border border-gray-100 p-6">
                            <h3 class="font-bold text-gray-800 mb-4 flex items-center gap-2">
                                <svg class="w-5 h-5 text-brand-orange" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                </svg>
                                Aksi Pesanan
                            </h3>
                            <div class="space-y-3">
                                @if($order->payment_status === 'unpaid')
                                    <a href="{{ route('payment.show', $order->id) }}" class="w-full px-6 py-3 bg-brand-dark text-white font-bold rounded-xl hover:bg-brand-orange transition-all duration-300 shadow-md transform hover:-translate-y-0.5 flex items-center justify-center gap-2">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                                        Bayar Sekarang
                                    </a>
                                @endif

                                @if($order->order_status === 'shipped')
                                    <form action="{{ route('orders.complete', $order) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin pesanan sudah diterima dengan baik? Saldo akan diteruskan ke penjual.')">
                                        @csrf
                                        <button type="submit" class="w-full px-6 py-3 bg-green-600 text-white font-bold rounded-xl hover:bg-green-700 transition-all duration-300 shadow-md transform hover:-translate-y-0.5 flex items-center justify-center gap-2">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                            Pesanan Diterima
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    @endif

                    <!-- Payment Status -->
                    <div class="bg-white shadow-sm rounded-2xl border border-gray-100 p-6">
                        <h3 class="font-bold text-gray-800 mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5 text-brand-orange" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            Status Pembayaran
                        </h3>
                        <div class="text-center">
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
                    </div>

                    <!-- Store Info -->
                    <div class="bg-white shadow-sm rounded-2xl border border-gray-100 p-6">
                        <h3 class="font-bold text-gray-800 mb-4 flex items-center gap-2">
                             <svg class="w-5 h-5 text-brand-orange" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                            Informasi Toko
                        </h3>
                         <div class="flex items-center gap-3">
                            <div class="relative w-12 h-12 rounded-full overflow-hidden shrink-0" x-data="{ imageError: false }">
                                <img 
                                    src="{{ str_starts_with($order->store->logo, 'http') ? $order->store->logo : asset('storage/' . $order->store->logo) }}" 
                                    alt="{{ $order->store->name }}" 
                                    class="w-full h-full object-cover"
                                    x-on:error="imageError = true"
                                    x-show="!imageError"
                                >
                                <div 
                                    x-show="imageError" 
                                    class="w-full h-full bg-gray-100 flex items-center justify-center text-gray-500 font-bold text-xl absolute inset-0"
                                    style="display: none;"
                                >
                                    {{ substr($order->store->name, 0, 1) }}
                                </div>
                            </div>
                            <div>
                                <h4 class="font-bold text-brand-dark text-lg">
                                    <a href="{{ route('stores.show', $order->store) }}" class="hover:text-brand-orange transition-colors">
                                        {{ $order->store->name }}
                                    </a>
                                </h4>
                                <a href="{{ route('stores.show', $order->store) }}" class="text-sm text-brand-orange hover:underline">Kunjungi Toko</a>
                            </div>
                        </div>
                    </div>

                    <!-- Address Info -->
                    <div class="bg-white shadow-sm rounded-2xl border border-gray-100 p-6">
                        <h3 class="font-bold text-gray-800 mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5 text-brand-orange" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            Alamat Pengiriman
                        </h3>
                         <p class="text-sm text-gray-700 leading-relaxed">
                            {{ $order->address }}<br>
                            {{ $order->city }}, {{ $order->postal_code }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Review Modal -->
    <div id="reviewModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" onclick="closeReviewModal()"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full">
                <form action="{{ route('reviews.store') }}" method="POST" class="p-6">
                    @csrf
                    <input type="hidden" name="transaction_id" id="review_transaction_id">
                    <input type="hidden" name="product_id" id="review_product_id">

                    <div class="mb-6">
                        <h3 class="text-lg font-bold text-brand-dark mb-2" id="modal-title">Beri Ulasan Produk</h3>
                        <p class="text-gray-500 text-sm" id="review_product_name"></p>
                    </div>

                    <div class="space-y-4">
                        <div>
                            <label class="block font-bold text-sm text-gray-700 mb-2">Rating</label>
                            <div class="flex gap-2" id="star-container">
                                <input type="hidden" name="rating" id="rating-input" required>
                                @for($i = 1; $i <= 5; $i++)
                                    <button type="button" 
                                            onclick="setRating({{ $i }})" 
                                            onmouseover="highlightStars({{ $i }})" 
                                            onmouseleave="resetStars()"
                                            class="focus:outline-none transition-transform hover:scale-110">
                                        <svg id="star-{{ $i }}" class="w-8 h-8 text-gray-300 transition-colors duration-200" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                        </svg>
                                    </button>
                                @endfor
                            </div>
                        </div>

                        <div>
                            <label for="review" class="block font-bold text-sm text-gray-700 mb-2">Ulasan Anda</label>
                            <textarea name="review" id="review" rows="4" class="w-full border-gray-300 focus:border-brand-orange focus:ring-brand-orange rounded-xl shadow-sm" required placeholder="Ceritakan pengalaman Anda menggunakan produk ini..."></textarea>
                        </div>
                    </div>

                    <div class="mt-6 flex justify-end gap-3">
                        <button type="button" onclick="closeReviewModal()" class="px-4 py-2 bg-gray-100 text-gray-700 font-bold rounded-xl hover:bg-gray-200 transition-colors">
                            Batal
                        </button>
                        <button type="submit" class="px-4 py-2 bg-brand-dark text-white font-bold rounded-xl hover:bg-brand-orange transition-colors shadow-md">
                            Kirim Ulasan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        let currentRating = 0;

        function setRating(rating) {
            currentRating = rating;
            document.getElementById('rating-input').value = rating;
            highlightStars(rating);
        }

        function highlightStars(rating) {
            for (let i = 1; i <= 5; i++) {
                const star = document.getElementById(`star-${i}`);
                if (i <= rating) {
                    star.classList.remove('text-gray-300');
                    star.classList.add('text-brand-orange');
                } else {
                    star.classList.remove('text-brand-orange');
                    star.classList.add('text-gray-300');
                }
            }
        }

        function resetStars() {
            highlightStars(currentRating);
        }

        function openReviewModal(transactionId, productId, productName) {
            document.getElementById('review_transaction_id').value = transactionId;
            document.getElementById('review_product_id').value = productId;
            document.getElementById('review_product_name').textContent = productName;
            
            // Reset form
            currentRating = 0;
            document.getElementById('rating-input').value = '';
            highlightStars(0);
            document.getElementById('review').value = '';
            
            document.getElementById('reviewModal').classList.remove('hidden');
        }

        function closeReviewModal() {
            document.getElementById('reviewModal').classList.add('hidden');
        }
    </script>
</x-store-layout>
