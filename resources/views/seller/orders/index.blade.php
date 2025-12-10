<x-app-layout>
    <x-slot name="header">
        <h2 class=" font-bold text-2xl text-brand-dark leading-tight">
            {{ __('Manajemen Pesanan') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

            <div class="bg-white overflow-hidden shadow-sm rounded-3xl border border-gray-100">
                <div class="p-8">
                    <form method="GET" action="{{ route('seller.orders.index') }}"
                        class="flex flex-col md:flex-row gap-4 items-end">
                        <div class="w-full md:w-1/3">
                            <label for="status" class="block font-bold text-sm text-gray-700 mb-2">Status
                                Pesanan</label>
                            <select name="status" id="status"
                                class="w-full border-gray-300 focus:border-brand-orange focus:ring-brand-orange rounded-xl shadow-sm py-2.5">
                                <option value="">Semua Status Pesanan</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Menunggu
                                </option>
                                <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>
                                    Diproses</option>
                                <option value="shipped" {{ request('status') == 'shipped' ? 'selected' : '' }}>Dikirim
                                </option>
                                <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Selesai
                                </option>
                                <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>
                                    Dibatalkan</option>
                            </select>
                        </div>

                        <div class="w-full md:w-1/3">
                            <label for="payment_status" class="block font-bold text-sm text-gray-700 mb-2">Status
                                Pembayaran</label>
                            <select name="payment_status" id="payment_status"
                                class="w-full border-gray-300 focus:border-brand-orange focus:ring-brand-orange rounded-xl shadow-sm py-2.5">
                                <option value="">Semua Status Pembayaran</option>
                                <option value="unpaid" {{ request('payment_status') == 'unpaid' ? 'selected' : '' }}>Belum
                                    Dibayar</option>
                                <option value="paid" {{ request('payment_status') == 'paid' ? 'selected' : '' }}>Dibayar
                                </option>
                            </select>
                        </div>

                        <div class="w-full md:w-auto flex gap-3">
                            <button type="submit"
                                class="px-6 py-2.5 bg-brand-dark text-white font-bold rounded-xl hover:bg-brand-orange transition-colors shadow-sm hover:shadow-md">Filter</button>
                            <a href="{{ route('seller.orders.index') }}"
                                class="px-6 py-2.5 bg-gray-100 text-gray-700 font-bold rounded-xl hover:bg-gray-200 transition-colors">Reset</a>
                        </div>
                    </form>
                </div>
            </div>


            <div class="bg-white overflow-hidden shadow-sm rounded-3xl border border-gray-100">
                <div class="p-8">
                    @if($orders->isEmpty())
                        <div class="text-center py-12">
                            <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01">
                                    </path>
                                </svg>
                            </div>
                            <h3 class="text-lg font-bold text-gray-900 mb-1">Tidak ada pesanan ditemukan</h3>
                            <p class="text-gray-500 text-sm">Belum ada pesanan yang masuk.</p>
                        </div>
                    @else
                        <div class="overflow-x-auto rounded-2xl border border-gray-100">
                            <table class="min-w-full divide-y divide-gray-100">
                                <thead class="bg-gray-50/50">
                                    <tr>
                                        <th
                                            class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-wider pl-8">
                                            ID Pesanan</th>
                                        <th
                                            class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">
                                            Pembeli</th>
                                        <th
                                            class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">
                                            Total</th>
                                        <th
                                            class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">
                                            Status Pesanan</th>
                                        <th
                                            class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">
                                            Status Pembayaran</th>
                                        <th
                                            class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">
                                            Tanggal</th>
                                        <th
                                            class="px-6 py-4 text-right text-xs font-bold text-gray-400 uppercase tracking-wider pr-8">
                                            Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-50">
                                    @foreach($orders as $order)
                                                                <tr class="hover:bg-blue-50/50 transition-colors duration-200 group">
                                                                    <td class="px-6 py-4 whitespace-nowrap pl-8">
                                                                        <div class="text-sm font-bold text-brand-dark">#{{ $order->code }}</div>
                                                                    </td>
                                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                                        <div class="flex items-center">
                                                                            <div class="flex-shrink-0 h-9 w-9">
                                                                                <img class="h-9 w-9 rounded-full border border-gray-100 object-cover"
                                                                                    src="https://ui-avatars.com/api/?name={{ urlencode($order->buyer->user->name) }}&background=random"
                                                                                    alt="">
                                                                            </div>
                                                                            <div class="ml-3">
                                                                                <div class="text-sm font-bold text-gray-900">
                                                                                    {{ $order->buyer->user->name }}</div>
                                                                                <div class="text-xs text-gray-500">{{ $order->buyer->user->email }}
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </td>
                                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                                        <div class="text-sm font-bold text-brand-orange">Rp
                                                                            {{ number_format($order->grand_total, 0, ',', '.') }}</div>
                                                                    </td>
                                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-bold rounded-full 
                                                                                    {{ match ($order->order_status) {
                                            'completed' => 'bg-green-50 text-green-700 border border-green-100',
                                            'cancelled' => 'bg-red-50 text-red-700 border border-red-100',
                                            'shipped' => 'bg-blue-50 text-blue-700 border border-blue-100',
                                            default => 'bg-yellow-50 text-yellow-700 border border-yellow-100'
                                        } }}">
                                                                            {{ match ($order->order_status) {
                                            'pending' => 'Menunggu',
                                            'processing' => 'Diproses',
                                            'shipped' => 'Dikirim',
                                            'completed' => 'Selesai',
                                            'cancelled' => 'Dibatalkan',
                                            default => ucfirst($order->order_status)
                                        } }}
                                                                        </span>
                                                                    </td>
                                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-bold rounded-full 
                                                                                    {{ match ($order->payment_status) {
                                            'paid' => 'bg-green-50 text-green-700 border border-green-100',
                                            'refunded' => 'bg-red-50 text-red-700 border border-red-100',
                                            'waiting' => 'bg-purple-50 text-purple-700 border-purple-100',
                                            default => 'bg-yellow-50 text-yellow-700 border border-yellow-100'
                                        } }}">
                                                                            {{ match ($order->payment_status) {
                                            'paid' => 'Lunas',
                                            'unpaid' => 'Belum Lunas',
                                            'refunded' => 'Dikembalikan',
                                            'waiting' => 'Menunggu Verifikasi',
                                            default => ucfirst($order->payment_status)
                                        } }}
                                                                        </span>
                                                                    </td>
                                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                                        {{ $order->created_at->format('d M Y') }}</td>
                                                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium pr-8">
                                                                        <a href="{{ route('seller.orders.show', $order) }}"
                                                                            class="inline-flex items-center px-4 py-2 bg-white border border-gray-200 rounded-xl text-xs font-bold text-gray-700 hover:bg-brand-dark hover:text-white hover:border-brand-dark transition-all shadow-sm group-hover:shadow-md">
                                                                            Detail
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