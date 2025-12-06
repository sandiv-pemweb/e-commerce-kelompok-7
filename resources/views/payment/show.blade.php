<x-store-layout>
    <div class="py-12 bg-brand-gray min-h-screen">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
                <div>
                    <div class="flex items-center gap-3 mb-2">
                        <a href="{{ route('orders.show', $transaction->id) }}" class="text-gray-400 hover:text-brand-orange transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                        </a>
                        <h2 class="font-serif font-bold text-3xl text-brand-dark leading-tight">
                            Pembayaran
                        </h2>
                    </div>
                    <p class="text-gray-500 ml-9">ID Pesanan: <span class="font-mono font-bold text-brand-dark">#{{ $transaction->code }}</span></p>
                </div>
            </div>

            @if(session('success'))
                <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl flex items-center shadow-sm mb-6">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    {{ session('success') }}
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Left Column: Payment Status & Instructions -->
                <div class="lg:col-span-2 space-y-8">
                    <!-- Payment Status -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50">
                            <h3 class="font-bold text-lg text-brand-dark">Status Pembayaran</h3>
                        </div>
                        <div class="p-6">
                            @if($transaction->payment_status === 'waiting')
                                <div class="bg-blue-50 border border-blue-200 rounded-xl p-6 text-center">
                                    <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4 text-blue-600">
                                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    </div>
                                    <h4 class="text-xl font-bold text-blue-800 mb-2">Menunggu Konfirmasi Admin</h4>
                                    <p class="text-blue-700">Bukti pembayaran Anda sedang diverifikasi oleh Admin. Mohon tunggu 1x24 jam.</p>
                                </div>
                            @elseif($transaction->payment_status === 'unpaid')
                                <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-6 text-center">
                                    <div class="w-16 h-16 bg-yellow-100 rounded-full flex items-center justify-center mx-auto mb-4 text-yellow-600">
                                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    </div>
                                    <h4 class="text-xl font-bold text-yellow-800 mb-2">Pembayaran Diperlukan</h4>
                                    <p class="text-yellow-700">Silakan selesaikan pembayaran Anda untuk memproses pesanan.</p>
                                </div>
                            @else
                                <div class="bg-green-50 border border-green-200 rounded-xl p-6 text-center">
                                    <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4 text-green-600">
                                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                    </div>
                                    <h4 class="text-xl font-bold text-green-800 mb-2">Pembayaran Diterima</h4>
                                    <p class="text-green-700">Terima kasih! Pembayaran Anda telah diverifikasi.</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    @if($transaction->payment_status === 'unpaid')
                        <!-- Payment Instructions -->
                        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50">
                                <h3 class="font-bold text-lg text-brand-dark">Instruksi Pembayaran</h3>
                            </div>
                            <div class="p-6 space-y-6">
                                <div class="bg-blue-50 border border-blue-200 rounded-xl p-6">
                                    <div class="flex items-center gap-4 mb-4">
                                        <div class="w-12 h-12 bg-white rounded-lg flex items-center justify-center shadow-sm">
                                            <span class="font-bold text-blue-800">BCA</span>
                                        </div>
                                        <div>
                                            <h4 class="font-bold text-blue-900">Transfer Bank</h4>
                                            <p class="text-sm text-blue-700">Verifikasi manual diperlukan</p>
                                        </div>
                                    </div>
                                    <div class="space-y-2 pl-16">
                                        <div class="flex justify-between items-center border-b border-blue-200 pb-2">
                                            <span class="text-blue-800">Nomor Rekening</span>
                                            <span class="font-mono font-bold text-blue-900 text-lg">1234567890</span>
                                        </div>
                                        <div class="flex justify-between items-center pt-2">
                                            <span class="text-blue-800">Atas Nama</span>
                                            <span class="font-bold text-blue-900">E-Commerce Platform</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="bg-gray-50 border border-gray-200 rounded-xl p-6 opacity-60">
                                    <div class="flex items-center gap-4">
                                        <div class="w-12 h-12 bg-white rounded-lg flex items-center justify-center shadow-sm">
                                            <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path></svg>
                                        </div>
                                        <div>
                                            <h4 class="font-bold text-gray-900">QRIS / E-Wallet</h4>
                                            <p class="text-sm text-gray-600">Segera hadir - Pembayaran otomatis</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Upload Proof -->
                        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50">
                                <h3 class="font-bold text-lg text-brand-dark">Unggah Bukti Pembayaran</h3>
                            </div>
                            <div class="p-6">
                                @if($transaction->payment_proof)
                                    <div class="mb-6">
                                        <p class="text-sm font-bold text-gray-700 mb-2">Bukti Pembayaran Terkirim:</p>
                                        <div class="relative aspect-video w-full max-w-md rounded-xl overflow-hidden border border-gray-200 bg-gray-50">
                                            <img src="{{ asset('storage/' . $transaction->payment_proof) }}" class="w-full h-full object-contain">
                                        </div>
                                        <p class="text-xs text-gray-500 mt-2">Ingin mengganti bukti pembayaran? Silakan unggah file baru di bawah ini.</p>
                                    </div>
                                @endif

                                <form action="{{ route('payment.upload', $transaction->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                                    @csrf
                                    <div>
                                        <label class="block text-sm font-bold text-gray-700 mb-2">
                                            Gambar Bukti Pembayaran
                                        </label>
                                        <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-xl hover:border-brand-orange transition-colors group">
                                            <div class="space-y-1 text-center">
                                                <svg class="mx-auto h-12 w-12 text-gray-400 group-hover:text-brand-orange transition-colors" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                                </svg>
                                                <div class="flex text-sm text-gray-600">
                                                    <label for="payment_proof" class="relative cursor-pointer bg-white rounded-md font-medium text-brand-orange hover:text-brand-dark focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-brand-orange">
                                                        <span>Unggah file</span>
                                                        <input id="payment_proof" name="payment_proof" type="file" class="sr-only" accept="image/jpeg,image/png,image/jpg" required>
                                                    </label>
                                                    <p class="pl-1">atau seret dan lepas</p>
                                                </div>
                                                <p class="text-xs text-gray-500">
                                                    PNG, JPG, JPEG hingga 2MB
                                                </p>
                                            </div>
                                        </div>
                                        @error('payment_proof')
                                            <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <button type="submit" class="w-full bg-brand-dark text-white font-bold py-3 px-4 rounded-xl hover:bg-brand-orange transition-all duration-300 shadow-md transform hover:-translate-y-0.5">
                                        Kirim Bukti Pembayaran
                                    </button>
                                </form>

                                <div class="mt-6 bg-yellow-50 border border-yellow-200 rounded-xl p-4 flex gap-3">
                                    <svg class="w-5 h-5 text-yellow-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    <p class="text-sm text-yellow-700">
                                        <strong>Catatan:</strong> Setelah mengunggah, mohon tunggu konfirmasi Admin. Anda akan diberitahu melalui email setelah pembayaran Anda diverifikasi.
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Right Column: Order Summary -->
                <div class="space-y-8">
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50">
                            <h3 class="font-bold text-lg text-brand-dark">Ringkasan Pesanan</h3>
                        </div>
                        <div class="p-6">
                            <div class="space-y-4 mb-6">
                                @foreach($transaction->transactionDetails as $detail)
                                    <div class="flex gap-4 items-center">
                                        <div class="w-12 h-12 bg-gray-100 rounded-lg overflow-hidden flex-shrink-0 border border-gray-200">
                                            @if($detail->product->productImages->count() > 0)
                                                <img src="{{ $detail->product->productImages->first()->image_url }}" class="w-full h-full object-cover">
                                            @endif
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="font-bold text-sm text-brand-dark truncate">{{ $detail->product->name }}</p>
                                            <p class="text-xs text-gray-500">{{ $detail->qty }} x Rp {{ number_format($detail->product->price, 0, ',', '.') }}</p>
                                        </div>
                                        <div class="text-right">
                                            <p class="font-bold text-sm text-brand-dark">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <div class="border-t border-gray-100 pt-4 space-y-2">
                                <div class="flex justify-between text-sm text-gray-600">
                                    <span>Subtotal</span>
                                    <span>Rp {{ number_format($transaction->grand_total - $transaction->shipping_cost - $transaction->tax, 0, ',', '.') }}</span>
                                </div>
                                <div class="flex justify-between text-sm text-gray-600">
                                    <span>Pengiriman</span>
                                    <span>Rp {{ number_format($transaction->shipping_cost, 0, ',', '.') }}</span>
                                </div>
                                <div class="flex justify-between text-sm text-gray-600">
                                    <span>Pajak (11%)</span>
                                    <span>Rp {{ number_format($transaction->tax, 0, ',', '.') }}</span>
                                </div>
                                <div class="border-t border-dashed border-gray-200 pt-4 mt-2 flex justify-between items-center">
                                    <span class="font-bold text-lg text-brand-dark">Total</span>
                                    <span class="font-bold text-xl text-brand-orange">Rp {{ number_format($transaction->grand_total, 0, ',', '.') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-store-layout>
