<x-app-layout>
    <x-slot name="header">
        <h2 class=" font-bold text-2xl text-brand-dark leading-tight">
            {{ __('Kelola Pesanan') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header & Filter -->
            <div class="bg-white overflow-hidden shadow-sm rounded-3xl border border-gray-100 mb-8">
                <div class="p-8">
                    <form method="GET" action="{{ route('admin.orders.index') }}" class="flex flex-col md:flex-row gap-4 items-end">
                        <div class="w-full md:w-1/3">
                            <label for="status" class="block font-bold text-sm text-gray-700 mb-2">Status Pesanan</label>
                            <select name="status" id="status" class="w-full border-gray-200 focus:border-brand-orange focus:ring-brand-orange rounded-xl shadow-sm cursor-pointer bg-gray-50/50">
                                <option value="">Semua Status Pesanan</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Menunggu Pembayaran</option>
                                <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>Diproses</option>
                                <option value="shipped" {{ request('status') == 'shipped' ? 'selected' : '' }}>Dikirim</option>
                                <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Selesai</option>
                                <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                            </select>
                        </div>

                        <div class="w-full md:w-1/3">
                            <label for="payment_status" class="block font-bold text-sm text-gray-700 mb-2">Status Pembayaran</label>
                            <select name="payment_status" id="payment_status" class="w-full border-gray-200 focus:border-brand-orange focus:ring-brand-orange rounded-xl shadow-sm cursor-pointer bg-gray-50/50">
                                <option value="">Semua Status Pembayaran</option>
                                <option value="waiting" {{ request('payment_status') == 'waiting' ? 'selected' : '' }}>Menunggu Verifikasi</option>
                                <option value="unpaid" {{ request('payment_status') == 'unpaid' ? 'selected' : '' }}>Belum Dibayar</option>
                                <option value="paid" {{ request('payment_status') == 'paid' ? 'selected' : '' }}>Lunas</option>
                                <option value="refunded" {{ request('payment_status') == 'refunded' ? 'selected' : '' }}>Dikembalikan</option>
                            </select>
                        </div>

                        <div class="w-full md:w-auto flex gap-3">
                            <button type="submit" class="px-6 py-2.5 bg-brand-dark text-white font-bold rounded-xl hover:bg-brand-orange transition-all shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                                Filter
                            </button>
                            <a href="{{ route('admin.orders.index') }}" class="px-6 py-2.5 bg-white border border-gray-200 text-gray-700 font-bold rounded-xl hover:bg-gray-50 transition-colors">
                                Reset
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Orders Table -->
            <div class="bg-white overflow-hidden shadow-sm rounded-3xl border border-gray-100">
                <div class="p-8">
                    @if($orders->isEmpty())
                        <div class="text-center py-16">
                            <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-6">
                                <svg class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                            </div>
                            <h3 class="text-xl  font-bold text-gray-900 mb-2">Tidak ada pesanan ditemukan</h3>
                            <p class="text-gray-500">Coba sesuaikan filter pencarian Anda atau cek kembali nanti.</p>
                        </div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full">
                                <thead>
                                    <tr class="border-b border-gray-100">
                                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-wider pl-4">ID Pesanan</th>
                                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Pembeli / Toko</th>
                                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Total</th>
                                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Status</th>
                                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Tanggal</th>
                                        <th class="px-6 py-4 text-right text-xs font-bold text-gray-400 uppercase tracking-wider pr-4">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-50">
                                    @foreach ($orders as $order)
                                        <tr class="hover:bg-blue-50/50 transition-colors duration-200 group">
                                            <td class="px-6 py-6 whitespace-nowrap pl-4">
                                                <span class="font-mono text-sm font-bold text-brand-dark bg-gray-100 px-2 py-1 rounded-md">#{{ $order->code }}</span>
                                            </td>
                                            <td class="px-6 py-6">
                                                <div class="flex flex-col gap-3">
                                                    <!-- Buyer -->
                                                    <div class="flex items-center gap-3">
                                                        <img src="https://ui-avatars.com/api/?name={{ urlencode($order->buyer->user->name ?? 'User') }}&background=random&color=fff&size=32" 
                                                             class="w-8 h-8 rounded-full border border-white shadow-sm" alt="">
                                                        <div>
                                                            <p class="text-sm font-bold text-gray-900">{{ $order->buyer->user->name ?? 'N/A' }}</p>
                                                            <p class="text-xs text-brand-orange font-bold">Pembeli</p>
                                                        </div>
                                                    </div>
                                                    <!-- Store -->
                                                    <div class="flex items-center gap-3">
                                                         @if($order->store && $order->store->logo)
                                                            <img src="{{ $order->store->logo_url }}" 
                                                                 class="w-8 h-8 rounded-full border border-white shadow-sm object-cover" alt="">
                                                        @else
                                                            <div class="w-8 h-8 rounded-full bg-gray-100 border border-white shadow-sm flex items-center justify-center text-xs font-bold text-gray-500">
                                                                {{ substr($order->store->name ?? 'S', 0, 1) }}
                                                            </div>
                                                        @endif
                                                        <div>
                                                            <p class="text-sm font-medium text-gray-600">{{ $order->store->name ?? 'N/A' }}</p>
                                                             <p class="text-xs text-gray-400 font-bold">Toko</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-6 whitespace-nowrap">
                                                <span class="text-lg font-bold text-brand-dark">Rp {{ number_format($order->grand_total, 0, ',', '.') }}</span>
                                            </td>
                                            <td class="px-6 py-6 whitespace-nowrap">
                                                <div class="flex flex-col gap-2 items-start">
                                                    <!-- Order Status -->
                                                    <span class="px-3 py-1 inline-flex items-center text-xs font-bold rounded-full 
                                                        {{ match($order->order_status) {
                                                            'completed' => 'bg-green-50 text-green-700 border border-green-100',
                                                            'cancelled' => 'bg-red-50 text-red-700 border border-red-100',
                                                            'shipped' => 'bg-blue-50 text-blue-700 border border-blue-100',
                                                            'processing' => 'bg-orange-50 text-orange-700 border border-orange-100',
                                                            default => 'bg-yellow-50 text-yellow-700 border border-yellow-100'
                                                        } }}">
                                                        {{ match($order->order_status) {
                                                            'pending' => 'Menunggu Pembayaran',
                                                            'processing' => 'Diproses',
                                                            'shipped' => 'Dikirim',
                                                            'completed' => 'Selesai',
                                                            'cancelled' => 'Dibatalkan',
                                                            default => ucfirst($order->order_status)
                                                        } }}
                                                    </span>
                                                    
                                                    <!-- Payment Status -->
                                                    <span class="px-3 py-1 inline-flex items-center gap-1.5 text-xs font-bold rounded-full 
                                                        {{ match($order->payment_status) {
                                                            'paid' => 'bg-green-50 text-green-600',
                                                            'refunded' => 'bg-red-50 text-red-600',
                                                            'waiting' => 'bg-purple-50 text-purple-600',
                                                            default => 'bg-gray-100 text-gray-600'
                                                        } }}">
                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                                                        {{ match($order->payment_status) {
                                                            'paid' => 'Lunas',
                                                            'unpaid' => 'Belum Lunas',
                                                            'refunded' => 'Dikembalikan',
                                                            'waiting' => 'Verifikasi',
                                                            default => ucfirst($order->payment_status)
                                                        } }}
                                                    </span>
                                                </div>
                                            </td>
                                            <td class="px-6 py-6 whitespace-nowrap text-sm text-gray-400 font-medium">
                                                {{ $order->created_at->format('d M Y') }}
                                                <div class="text-xs text-gray-300">{{ $order->created_at->format('H:i') }} WIB</div>
                                            </td>
                                            <td class="px-6 py-6 whitespace-nowrap text-right text-sm font-medium pr-4">
                                                <a href="{{ route('admin.orders.show', $order) }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-200 rounded-xl text-sm font-bold text-gray-700 hover:bg-brand-dark hover:text-white hover:border-brand-dark transition-all shadow-sm group-hover:shadow-md">
                                                    Detail
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-8 px-4 border-t border-gray-100 pt-6">
                            {{ $orders->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
