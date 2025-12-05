<x-app-layout>
    <x-slot name="header">
        <h2 class="font-serif font-bold text-2xl text-brand-dark leading-tight">
            {{ __('Manajemen Pesanan') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
            <!-- Filter Form -->
            <div class="bg-white overflow-hidden shadow-lg rounded-2xl border border-gray-100">
                <div class="p-8">
                    <form method="GET" action="{{ route('seller.orders.index') }}" class="flex flex-col md:flex-row gap-4 items-end">
                        <div class="w-full md:w-1/3">
                            <label for="status" class="block font-bold text-sm text-gray-700 mb-2">Status Pesanan</label>
                            <select name="status" id="status" class="w-full border-gray-300 focus:border-brand-orange focus:ring-brand-orange rounded-xl shadow-sm">
                                <option value="">Semua Status Pesanan</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Menunggu</option>
                                <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>Diproses</option>
                                <option value="shipped" {{ request('status') == 'shipped' ? 'selected' : '' }}>Dikirim</option>
                                <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Selesai</option>
                                <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                            </select>
                        </div>

                        <div class="w-full md:w-1/3">
                            <label for="payment_status" class="block font-bold text-sm text-gray-700 mb-2">Status Pembayaran</label>
                            <select name="payment_status" id="payment_status" class="w-full border-gray-300 focus:border-brand-orange focus:ring-brand-orange rounded-xl shadow-sm">
                                <option value="">Semua Status Pembayaran</option>
                                <option value="unpaid" {{ request('payment_status') == 'unpaid' ? 'selected' : '' }}>Belum Dibayar</option>
                                <option value="paid" {{ request('payment_status') == 'paid' ? 'selected' : '' }}>Dibayar</option>
                            </select>
                        </div>

                        <div class="w-full md:w-auto flex gap-3">
                            <button type="submit" class="px-6 py-2.5 bg-brand-dark text-white font-bold rounded-xl hover:bg-brand-orange transition-colors shadow-md">Filter</button>
                            <a href="{{ route('seller.orders.index') }}" class="px-6 py-2.5 bg-gray-100 text-gray-700 font-bold rounded-xl hover:bg-gray-200 transition-colors">Reset</a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Orders List -->
            <div class="bg-white overflow-hidden shadow-lg rounded-2xl border border-gray-100">
                <div class="p-8">
                    @if($orders->isEmpty())
                        <div class="text-center py-12">
                            <svg class="mx-auto h-16 w-16 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                            <p class="text-lg font-medium text-gray-500">Tidak ada pesanan ditemukan.</p>
                        </div>
                    @else
                        <div class="overflow-x-auto rounded-xl border border-gray-200">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-brand-dark text-white">
                                    <tr>
                                        <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider">Kode Pesanan</th>
                                        <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider">Pembeli</th>
                                        <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider">Total</th>
                                        <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider">Pembayaran</th>
                                        <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider">Status</th>
                                        <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider">Tanggal</th>
                                        <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($orders as $order)
                                        <tr class="hover:bg-blue-50 transition-colors duration-150">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-brand-dark">#{{ $order->code }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $order->buyer->user->name }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-brand-orange">Rp {{ number_format($order->grand_total, 0, ',', '.') }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-bold rounded-full {{ $order->payment_status == 'paid' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                                    {{ match($order->payment_status) {
                                                        'paid' => 'Lunas',
                                                        'unpaid' => 'Belum Dibayar',
                                                        default => ucfirst($order->payment_status)
                                                    } }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-bold rounded-full 
                                                    @if($order->order_status == 'completed') bg-green-100 text-green-800
                                                    @elseif($order->order_status == 'shipped') bg-blue-100 text-blue-800
                                                    @elseif($order->order_status == 'processing') bg-yellow-100 text-yellow-800
                                                    @elseif($order->order_status == 'cancelled') bg-red-100 text-red-800
                                                    @else bg-gray-100 text-gray-800
                                                    @endif">
                                                    {{ match($order->order_status) {
                                                        'pending' => 'Menunggu',
                                                        'processing' => 'Diproses',
                                                        'shipped' => 'Dikirim',
                                                        'completed' => 'Selesai',
                                                        'cancelled' => 'Dibatalkan',
                                                        default => ucfirst($order->order_status)
                                                    } }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $order->created_at->format('d M Y') }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <a href="{{ route('seller.orders.show', $order) }}" class="text-brand-orange hover:text-brand-dark font-bold transition-colors flex items-center">
                                                    Lihat Detail
                                                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-6">
                            {{ $orders->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
