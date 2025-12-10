<x-app-layout>
    <x-slot name="header">
        <h2 class=" font-bold text-2xl text-brand-dark leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-6 bg-brand-gray min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="bg-white overflow-hidden shadow-sm rounded-3xl mb-10 border border-gray-100 relative">
                <!-- Decorative Blob -->
                <div
                    class="absolute top-0 right-0 -mt-10 -mr-10 w-40 h-40 bg-brand-orange rounded-full opacity-10 blur-2xl">
                </div>

                <div class="p-8 md:flex md:items-center md:justify-between relative z-10">
                    <div>
                        <h3 class="text-3xl  font-bold text-brand-dark mb-2">Selamat datang kembali,
                            {{ Auth::user()->name }}! 👋
                        </h3>
                        <p class="text-gray-500 text-lg">
                            @if(Auth::user()->isAdmin())
                                Anda masuk sebagai <span class="font-bold text-brand-orange">Administrator</span>
                            @elseif(Auth::user()->store && Auth::user()->store->is_verified)
                                Anda masuk sebagai <span class="font-bold text-green-600">Penjual Terverifikasi</span>
                            @elseif(Auth::user()->store)
                                Anda masuk sebagai <span class="font-bold text-yellow-600">Penjual (Menunggu
                                    Verifikasi)</span>
                            @else
                                You are logged in as <span class="font-bold text-blue-600">Member</span>
                            @endif
                        </p>
                    </div>
                    <div class="mt-6 md:mt-0">
                        <span
                            class="inline-flex items-center px-4 py-2 rounded-full border border-gray-200 bg-gray-50 text-sm font-medium text-gray-600">
                            {{ now()->format('l, d F Y') }}
                        </span>
                    </div>
                </div>
            </div>

            @if(Auth::user()->isAdmin())
                <!-- Admin Dashboard -->
                <h3 class="text-xl font-bold text-brand-dark mb-6">Ringkasan Admin</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 mb-10">
                    <!-- Store Management Card -->
                    <div
                        class="bg-white rounded-3xl shadow-sm hover:shadow-md transition-shadow duration-300 overflow-hidden group border border-gray-100">
                        <div class="p-6">
                            <div class="flex items-center justify-between mb-6">
                                <div class="p-3 bg-indigo-50 rounded-2xl group-hover:bg-indigo-100 transition-colors">
                                    <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                                        </path>
                                    </svg>
                                </div>
                                <span class="text-xs font-bold uppercase tracking-wider text-gray-400">Manajemen</span>
                            </div>
                            <h3 class="text-xl font-bold text-brand-dark mb-2">Kelola Toko</h3>
                            <p class="text-gray-500 mb-6 text-sm">Verifikasi toko baru dan kelola status penjual.</p>
                            <a href="{{ route('admin.stores.index') }}"
                                class="inline-flex items-center text-indigo-600 font-bold hover:text-indigo-800 transition-colors">
                                Lihat Semua Toko <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                                </svg>
                            </a>
                        </div>
                    </div>

                    <!-- User Management Card -->
                    <div
                        class="bg-white rounded-3xl shadow-sm hover:shadow-md transition-shadow duration-300 overflow-hidden group border border-gray-100">
                        <div class="p-6">
                            <div class="flex items-center justify-between mb-6">
                                <div class="p-3 bg-purple-50 rounded-2xl group-hover:bg-purple-100 transition-colors">
                                    <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                                        </path>
                                    </svg>
                                </div>
                                <span class="text-xs font-bold uppercase tracking-wider text-gray-400">Pengguna</span>
                            </div>
                            <h3 class="text-xl font-bold text-brand-dark mb-2">Kelola Pengguna</h3>
                            <p class="text-gray-500 mb-6 text-sm">Atur hak akses dan pantau aktivitas pengguna.</p>
                            <a href="{{ route('admin.users.index') }}"
                                class="inline-flex items-center text-purple-600 font-bold hover:text-purple-800 transition-colors">
                                Lihat Semua Pengguna <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                                </svg>
                            </a>
                        </div>
                    </div>

                    <!-- Order Management Card -->
                    <div
                        class="bg-white rounded-3xl shadow-sm hover:shadow-md transition-shadow duration-300 overflow-hidden group border border-gray-100">
                        <div class="p-6">
                            <div class="flex items-center justify-between mb-6">
                                <div class="p-3 bg-red-50 rounded-2xl group-hover:bg-red-100 transition-colors">
                                    <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z">
                                        </path>
                                    </svg>
                                </div>
                                <span class="text-xs font-bold uppercase tracking-wider text-gray-400">Pesanan</span>
                            </div>
                            <h3 class="text-xl font-bold text-brand-dark mb-2">Kelola Pesanan</h3>
                            <p class="text-gray-500 mb-6 text-sm">Cek pembayaran dan pantau status pengiriman.</p>
                            <a href="{{ route('admin.orders.index') }}"
                                class="inline-flex items-center text-red-600 font-bold hover:text-red-800 transition-colors">
                                Lihat Semua Pesanan <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                                </svg>
                            </a>
                        </div>
                    </div>

                    <!-- Withdrawal Management Card -->
                    <div
                        class="bg-white rounded-3xl shadow-sm hover:shadow-md transition-shadow duration-300 overflow-hidden group border border-gray-100">
                        <div class="p-6">
                            <div class="flex items-center justify-between mb-6">
                                <div class="p-3 bg-green-50 rounded-2xl group-hover:bg-green-100 transition-colors">
                                    <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z">
                                        </path>
                                    </svg>
                                </div>
                                <span class="text-xs font-bold uppercase tracking-wider text-gray-400">Keuangan</span>
                            </div>
                            <h3 class="text-xl font-bold text-brand-dark mb-2">Penarikan Dana</h3>
                            <p class="text-gray-500 mb-6 text-sm">
                                @if(isset($pendingWithdrawalsCount) && $pendingWithdrawalsCount > 0)
                                    <span class="text-red-500 font-bold">{{ $pendingWithdrawalsCount }} Permintaan</span>
                                    penarikan dana menunggu persetujuan.
                                @else
                                    Belum ada permintaan penarikan dana baru.
                                @endif
                            </p>
                            <a href="{{ route('admin.withdrawals.index') }}"
                                class="inline-flex items-center text-green-600 font-bold hover:text-green-800 transition-colors">
                                Kelola Penarikan Dana <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>

                @if(isset($adminSalesChartData) && isset($adminOrderStatusChartData))
                    <h3 class="text-xl font-bold text-brand-dark mb-6">Analisis Platform</h3>
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-10">
                        <!-- Admin Sales Trend Chart -->
                        <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 lg:col-span-2">
                            <h4 class="font-bold text-gray-700 mb-4">Total Transaksi (30 Hari Terakhir)</h4>
                            <div class="relative h-64">
                                <canvas id="adminSalesChart"></canvas>
                            </div>
                        </div>

                        <!-- Admin Order Status Chart -->
                        <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100">
                            <h4 class="font-bold text-gray-700 mb-4">Distribusi Status Pesanan</h4>
                            <div class="relative h-64">
                                <canvas id="adminOrderStatusChart"></canvas>
                            </div>
                        </div>
                    </div>

                    <!-- Chart.js Logic for Admin -->
                    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            // Admin Sales Chart
                            if (document.getElementById('adminSalesChart')) {
                                const adminSalesCtx = document.getElementById('adminSalesChart').getContext('2d');
                                new Chart(adminSalesCtx, {
                                    type: 'line',
                                    data: {
                                        labels: @json($adminSalesChartData['labels']),
                                        datasets: [{
                                            label: 'Total Transaksi',
                                            data: @json($adminSalesChartData['data']),
                                            borderColor: '#4F46E5', // Indigo-600
                                            backgroundColor: 'rgba(79, 70, 229, 0.1)',
                                            borderWidth: 2,
                                            fill: true,
                                            tension: 0.4,
                                            pointBackgroundColor: '#fff',
                                            pointBorderColor: '#4F46E5',
                                            pointBorderWidth: 2,
                                            pointRadius: 4,
                                            pointHoverRadius: 6
                                        }]
                                    },
                                    options: {
                                        responsive: true,
                                        maintainAspectRatio: false,
                                        plugins: {
                                            legend: { display: false },
                                            tooltip: {
                                                callbacks: {
                                                    label: function(context) {
                                                        let label = context.dataset.label || '';
                                                        if (label) { label += ': '; }
                                                        if (context.parsed.y !== null) {
                                                            label += new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(context.parsed.y);
                                                        }
                                                        return label;
                                                    }
                                                }
                                            }
                                        },
                                        scales: {
                                            y: {
                                                beginAtZero: true,
                                                grid: { borderDash: [2, 4], color: '#f3f4f6' },
                                                ticks: {
                                                    callback: function(value) { return 'Rp ' + (value / 1000) + 'k'; }
                                                }
                                            },
                                            x: { grid: { display: false } }
                                        },
                                        interaction: { intersect: false, mode: 'index' }
                                    }
                                });
                            }

                            // Admin Order Status Chart
                            if (document.getElementById('adminOrderStatusChart')) {
                                const adminStatusCtx = document.getElementById('adminOrderStatusChart').getContext('2d');
                                new Chart(adminStatusCtx, {
                                    type: 'doughnut',
                                    data: {
                                        labels: @json($adminOrderStatusChartData['labels']),
                                        datasets: [{
                                            data: @json($adminOrderStatusChartData['data']),
                                            backgroundColor: [
                                                '#FCD34D', // Amber-300 (Pending)
                                                '#3B82F6', // Blue-500 (Processing)
                                                '#8B5CF6', // Purple-500 (Shipped)
                                                '#10B981', // Emerald-500 (Completed)
                                                '#EF4444', // Red-500 (Cancelled)
                                            ],
                                            borderWidth: 0,
                                            hoverOffset: 4
                                        }]
                                    },
                                    options: {
                                        responsive: true,
                                        maintainAspectRatio: false,
                                        plugins: {
                                            legend: { position: 'right' }
                                        }
                                    }
                                });
                            }
                        });
                    </script>
                @endif

            @elseif(Auth::user()->store)
                @if(Auth::user()->store->is_verified)
                    <!-- Verified Seller Dashboard -->
                    <h3 class="text-xl font-bold text-brand-dark mb-6">Ringkasan Toko</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-10">
                        <!-- Total Orders -->
                        <div class="bg-white rounded-3xl shadow-sm p-6 border border-gray-100 relative overflow-hidden group">
                            <div
                                class="absolute top-0 right-0 -mt-4 -mr-4 w-24 h-24 bg-blue-50 rounded-full opacity-50 group-hover:scale-110 transition-transform">
                            </div>
                            <div class="relative z-10">
                                <p class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-1">Total Pesanan</p>
                                <h4 class="text-4xl font-bold text-brand-dark mb-2">
                                    {{ Auth::user()->store->transactions_count ?? 0 }}
                                </h4>
                                <a href="{{ route('seller.orders.index') }}"
                                    class="text-blue-600 text-sm font-bold hover:underline flex items-center">
                                    Lihat Pesanan <svg class="w-3 h-3 ml-1" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7">
                                        </path>
                                    </svg>
                                </a>
                            </div>
                        </div>

                        <!-- Total Products -->
                        <div class="bg-white rounded-3xl shadow-sm p-6 border border-gray-100 relative overflow-hidden group">
                            <div
                                class="absolute top-0 right-0 -mt-4 -mr-4 w-24 h-24 bg-brand-orange/10 rounded-full opacity-50 group-hover:scale-110 transition-transform">
                            </div>
                            <div class="relative z-10">
                                <p class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-1">Total Produk</p>
                                <h4 class="text-4xl font-bold text-brand-dark mb-2">
                                    {{ Auth::user()->store->products_count ?? 0 }}
                                </h4>
                                <a href="{{ route('seller.products.index') }}"
                                    class="text-brand-orange text-sm font-bold hover:underline flex items-center">
                                    Kelola Produk <svg class="w-3 h-3 ml-1" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7">
                                        </path>
                                    </svg>
                                </a>
                            </div>
                        </div>

                        <!-- Total Balance -->
                        <div class="bg-white rounded-3xl shadow-sm p-6 border border-gray-100 relative overflow-hidden group">
                            <div
                                class="absolute top-0 right-0 -mt-4 -mr-4 w-24 h-24 bg-green-50 rounded-full opacity-50 group-hover:scale-110 transition-transform">
                            </div>
                            <div class="relative z-10">
                                <p class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-1">Saldo Aktif</p>
                                <h4 class="text-4xl font-bold text-brand-dark mb-2">Rp
                                    {{ number_format(Auth::user()->store->storeBalance->balance ?? 0, 0, ',', '.') }}
                                </h4>
                                <a href="{{ route('seller.balance.index') }}"
                                    class="text-green-600 text-sm font-bold hover:underline flex items-center">
                                    Lihat Detail <svg class="w-3 h-3 ml-1" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7">
                                        </path>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>

                    @if(isset($salesChartData) && isset($topProductsChartData))
                        <h3 class="text-xl font-bold text-brand-dark mb-6">Analisis Penjualan</h3>
                        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-10">
                            <!-- Sales Trend Chart -->
                            <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 lg:col-span-2">
                                <h4 class="font-bold text-gray-700 mb-4">Pendapatan 30 Hari Terakhir</h4>
                                <div class="relative h-64">
                                    <canvas id="salesChart"></canvas>
                                </div>
                            </div>

                            <!-- Top Products Chart -->
                            <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100">
                                <h4 class="font-bold text-gray-700 mb-4">5 Produk Terlaris</h4>
                                <div class="relative h-64">
                                    <canvas id="topProductsChart"></canvas>
                                </div>
                            </div>
                        </div>

                        <!-- Chart.js Logic -->
                        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
                        <script>
                            document.addEventListener('DOMContentLoaded', function() {
                                // Sales Chart
                                const salesCtx = document.getElementById('salesChart').getContext('2d');
                                new Chart(salesCtx, {
                                    type: 'line',
                                    data: {
                                        labels: @json($salesChartData['labels']),
                                        datasets: [{
                                            label: 'Pendapatan',
                                            data: @json($salesChartData['data']),
                                            borderColor: '#FF6B00',
                                            backgroundColor: 'rgba(255, 107, 0, 0.1)',
                                            borderWidth: 2,
                                            fill: true,
                                            tension: 0.4,
                                            pointBackgroundColor: '#fff',
                                            pointBorderColor: '#FF6B00',
                                            pointBorderWidth: 2,
                                            pointRadius: 4,
                                            pointHoverRadius: 6
                                        }]
                                    },
                                    options: {
                                        responsive: true,
                                        maintainAspectRatio: false,
                                        plugins: {
                                            legend: { display: false },
                                            tooltip: {
                                                callbacks: {
                                                    label: function(context) {
                                                        let label = context.dataset.label || '';
                                                        if (label) {
                                                            label += ': ';
                                                        }
                                                        if (context.parsed.y !== null) {
                                                            label += new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(context.parsed.y);
                                                        }
                                                        return label;
                                                    }
                                                }
                                            }
                                        },
                                        scales: {
                                            y: {
                                                beginAtZero: true,
                                                grid: { borderDash: [2, 4], color: '#f3f4f6' },
                                                ticks: {
                                                    callback: function(value, index, values) {
                                                        return 'Rp ' + (value / 1000) + 'k';
                                                    }
                                                }
                                            },
                                            x: {
                                                grid: { display: false }
                                            }
                                        },
                                        interaction: {
                                            intersect: false,
                                            mode: 'index',
                                        },
                                    }
                                });

                                // Top Products Chart
                                const topProductsCtx = document.getElementById('topProductsChart').getContext('2d');
                                new Chart(topProductsCtx, {
                                    type: 'bar',
                                    data: {
                                        labels: @json($topProductsChartData['labels']),
                                        datasets: [{
                                            label: 'Terjual',
                                            data: @json($topProductsChartData['data']),
                                            backgroundColor: [
                                                '#FF6B00', '#10B981', '#3B82F6', '#8B5CF6', '#EF4444'
                                            ],
                                            borderRadius: 8,
                                            barThickness: 30
                                        }]
                                    },
                                    options: {
                                        responsive: true,
                                        maintainAspectRatio: false,
                                        plugins: {
                                            legend: { display: false }
                                        },
                                        scales: {
                                            y: {
                                                beginAtZero: true,
                                                grid: { borderDash: [2, 4], color: '#f3f4f6' },
                                                ticks: { stepSize: 1 }
                                            },
                                            x: {
                                                grid: { display: false }
                                            }
                                        }
                                    }
                                });
                            });
                        </script>
                    @endif

                    <!-- Quick Actions -->
                    <h3 class="text-xl  font-bold text-brand-dark mb-6">Aksi Cepat</h3>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                        <a href="{{ route('seller.products.create') }}"
                            class="flex flex-col items-center justify-center p-6 bg-white border border-gray-200 rounded-3xl hover:border-brand-orange hover:shadow-md transition-all group">
                            <div
                                class="w-12 h-12 bg-gray-50 rounded-full flex items-center justify-center mb-3 group-hover:bg-brand-orange group-hover:text-white transition-colors text-gray-400">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4">
                                    </path>
                                </svg>
                            </div>
                            <span class="font-bold text-gray-700 group-hover:text-brand-orange">Tambah Produk</span>
                        </a>
                        <a href="{{ route('seller.categories.create') }}"
                            class="flex flex-col items-center justify-center p-6 bg-white border border-gray-200 rounded-3xl hover:border-brand-orange hover:shadow-md transition-all group">
                            <div
                                class="w-12 h-12 bg-gray-50 rounded-full flex items-center justify-center mb-3 group-hover:bg-brand-orange group-hover:text-white transition-colors text-gray-400">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z">
                                    </path>
                                </svg>
                            </div>
                            <span class="font-bold text-gray-700 group-hover:text-brand-orange">Tambah Kategori</span>
                        </a>
                        <a href="{{ route('seller.withdrawals.create') }}"
                            class="flex flex-col items-center justify-center p-6 bg-white border border-gray-200 rounded-3xl hover:border-brand-orange hover:shadow-md transition-all group">
                            <div
                                class="w-12 h-12 bg-gray-50 rounded-full flex items-center justify-center mb-3 group-hover:bg-brand-orange group-hover:text-white transition-colors text-gray-400">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z">
                                    </path>
                                </svg>
                            </div>
                            <span class="font-bold text-gray-700 group-hover:text-brand-orange">Tarik Dana</span>
                        </a>
                        <a href="{{ route('seller.stores.edit') }}"
                            class="flex flex-col items-center justify-center p-6 bg-white border border-gray-200 rounded-3xl hover:border-brand-orange hover:shadow-md transition-all group">
                            <div
                                class="w-12 h-12 bg-gray-50 rounded-full flex items-center justify-center mb-3 group-hover:bg-brand-orange group-hover:text-white transition-colors text-gray-400">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z">
                                    </path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                            </div>
                            <span class="font-bold text-gray-700 group-hover:text-brand-orange">Pengaturan Toko</span>
                        </a>
                    </div>

                @else
                    <!-- Unverified Seller Dashboard -->
                    <div class="bg-yellow-50 border border-yellow-200 rounded-3xl p-8 text-center">
                        <div
                            class="inline-flex items-center justify-center w-16 h-16 bg-yellow-100 rounded-full mb-4 text-yellow-600">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <h3 class="text-2xl  font-bold text-brand-dark mb-2">Toko Menunggu Verifikasi</h3>
                        <p class="text-gray-600 max-w-lg mx-auto mb-6">Toko Anda saat ini sedang ditinjau oleh administrator
                            kami. Anda akan mendapatkan akses penuh setelah toko terverifikasi.</p>
                        <a href="{{ route('seller.stores.edit') }}"
                            class="inline-flex items-center px-6 py-3 bg-brand-dark text-white font-bold rounded-xl hover:bg-brand-orange transition-colors">
                            Lihat Detail Toko
                        </a>
                    </div>
                @endif

            @else
                <!-- Member Dashboard (No Store) -->
                @unless(Auth::user()->isAdmin())
                    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-10 text-center">
                        <div
                            class="inline-flex items-center justify-center w-20 h-20 bg-indigo-50 rounded-full mb-6 text-indigo-600">
                            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                        </div>
                        <h3 class="text-3xl  font-bold text-brand-dark mb-4">Mulai Berjualan Hari Ini!</h3>
                        <p class="text-gray-500 max-w-xl mx-auto mb-8 text-lg">Bergabunglah dengan komunitas penjual kami dan
                            jangkau ribuan pencinta buku. Mudah untuk memulai.</p>
                        <a href="{{ route('seller.stores.create') }}"
                            class="inline-flex items-center px-8 py-4 bg-brand-orange text-white font-bold text-lg rounded-xl hover:bg-brand-dark transition-colors shadow-lg shadow-brand-orange/20">
                            Buat Toko Anda Sekarang
                        </a>
                    </div>
                @endunless
            @endif
        </div>
    </div>
</x-app-layout>
