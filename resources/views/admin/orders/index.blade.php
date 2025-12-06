<x-app-layout>
    <x-slot name="header">
        <h2 class="font-serif font-bold text-2xl text-brand-dark leading-tight">
            {{ __('Kelola Pesanan') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header & Filter -->
            <div class="bg-white overflow-hidden shadow-sm rounded-2xl border border-gray-100 mb-8">
                <div class="p-8">
                    <form method="GET" action="{{ route('admin.orders.index') }}" class="flex flex-col md:flex-row gap-4 items-end">
                        <div class="w-full md:w-1/3">
                            <label for="status" class="block font-bold text-sm text-gray-700 mb-2">Status Pesanan</label>
                            <select name="status" id="status" class="w-full border-gray-300 focus:border-brand-orange focus:ring-brand-orange rounded-xl shadow-sm cursor-pointer">
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
                            <select name="payment_status" id="payment_status" class="w-full border-gray-300 focus:border-brand-orange focus:ring-brand-orange rounded-xl shadow-sm cursor-pointer">
                                <option value="">Semua Status Pembayaran</option>
                                <option value="waiting" {{ request('payment_status') == 'waiting' ? 'selected' : '' }}>Menunggu Verifikasi</option>
                                <option value="unpaid" {{ request('payment_status') == 'unpaid' ? 'selected' : '' }}>Belum Dibayar</option>
                                <option value="paid" {{ request('payment_status') == 'paid' ? 'selected' : '' }}>Lunas</option>
                                <option value="refunded" {{ request('payment_status') == 'refunded' ? 'selected' : '' }}>Dikembalikan</option>
                            </select>
                        </div>

                        <div class="w-full md:w-auto flex gap-3">
                            <button type="submit" class="px-6 py-2.5 bg-brand-dark text-white font-bold rounded-xl hover:bg-brand-orange transition-colors shadow-md">
                                Filter
                            </button>
                            <a href="{{ route('admin.orders.index') }}" class="px-6 py-2.5 bg-gray-100 text-gray-700 font-bold rounded-xl hover:bg-gray-200 transition-colors">
                                Reset
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Orders Table -->
            <div class="bg-white overflow-hidden shadow-sm rounded-2xl border border-gray-100">
                <div class="p-8">
                    @if($orders->isEmpty())
                        <div class="text-center py-12">
                            <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                            </div>
                            <h3 class="text-lg font-bold text-gray-900 mb-1">Tidak ada pesanan ditemukan</h3>
                            <p class="text-gray-500 text-sm">Coba sesuaikan filter pencarian Anda.</p>
                        </div>
                    @else
                        <div class="overflow-x-auto rounded-xl border border-gray-200">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">ID Pesanan</th>
                                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Pembeli / Toko</th>
                                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Total</th>
                                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Status Pesanan</th>
                                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Status Pembayaran</th>
                                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Tanggal</th>
                                        <th class="px-6 py-4 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach ($orders as $order)
                                        <tr class="hover:bg-gray-50 transition-colors duration-150 group">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="text-sm font-bold text-brand-dark">#{{ $order->code }}</span>
                                            </td>
                                            <td class="px-6 py-4">
                                                <div class="flex flex-col">
                                                    <span class="text-sm font-bold text-gray-900">{{ $order->buyer->user->name ?? 'N/A' }}</span>
                                                    <span class="text-xs text-gray-500 flex items-center gap-1">
                                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                                                        {{ $order->store->name }}
                                                    </span>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="text-sm font-bold text-brand-orange">Rp {{ number_format($order->grand_total, 0, ',', '.') }}</span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-bold rounded-full 
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
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-bold rounded-full 
                                                    {{ match($order->payment_status) {
                                                        'paid' => 'bg-green-100 text-green-700 border border-green-200',
                                                        'refunded' => 'bg-red-100 text-red-700 border border-red-200',
                                                        'waiting' => 'bg-blue-100 text-blue-700 border-blue-200',
                                                        default => 'bg-yellow-100 text-yellow-700 border border-yellow-200'
                                                    } }}">
                                                    {{ match($order->payment_status) {
                                                        'paid' => 'Lunas',
                                                        'unpaid' => 'Belum Dibayar',
                                                        'refunded' => 'Dikembalikan',
                                                        'waiting' => 'Menunggu Verifikasi',
                                                        default => ucfirst($order->payment_status)
                                                    } }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $order->created_at->format('d M Y') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                <a href="{{ route('admin.orders.show', $order) }}" class="inline-flex items-center text-gray-400 hover:text-brand-orange transition-colors">
                                                    <span class="sr-only">Detail</span>
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-8">
                            {{ $orders->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
