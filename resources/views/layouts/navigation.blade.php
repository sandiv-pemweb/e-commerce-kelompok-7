<nav x-data="{ open: false }" class="bg-white border-b border-gray-100 shadow-sm sticky top-0 z-50">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-20">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center gap-3">
                    <a href="{{ route('home') }}" class="flex items-center gap-2">
                        <div
                            class="hidden md:flex w-10 h-10 bg-brand-orange rounded-lg items-center justify-center text-white">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                                stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" />
                            </svg>
                        </div>
                        <div class="flex flex-col">
                            <span class="font-serif text-2xl font-bold text-brand-dark leading-none">BookLand</span>
                            <span class="text-xs text-brand-muted font-medium hidden md:block">Book Store Website</span>
                        </div>
                    </a>
                </div>
            </div>

            <!-- Search Bar -->
            <div class="flex flex-1 items-center max-w-lg mx-4 md:mx-8 hidden md:flex">
                <form action="{{ route('products.index') }}" method="GET" class="relative w-full">
                    <input type="text" name="search" value="{{ request('search') }}"
                        class="block w-full pl-10 pr-10 py-2.5 border-none bg-gray-100 rounded-lg text-sm focus:ring-2 focus:ring-brand-orange focus:bg-white transition-colors placeholder-gray-400"
                        placeholder="Cari buku di sini...">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                </form>
            </div>

            <!-- Right Navigation -->
            <div class="flex items-center gap-2 md:gap-6">
                <a href="{{ route('products.index') }}"
                    class="flex items-center text-sm font-medium text-gray-700 hover:text-brand-orange transition-colors {{ request()->routeIs('products.*') ? 'text-brand-orange font-bold' : '' }}">
                    <svg class="w-5 h-5 mr-0 md:mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" />
                    </svg>
                    <span class="hidden md:inline">Produk</span>
                </a>

                @auth
                    <!-- Wishlist & Cart -->
                    <a href="{{ route('wishlist.index') }}"
                        class="relative p-2 text-brand-dark hover:text-brand-orange transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z" />
                        </svg>
                        <span
                            class="wishlist-count absolute top-1 right-0 inline-flex items-center justify-center px-1.5 py-0.5 text-xs font-bold leading-none text-white transform translate-x-1/4 -translate-y-1/4 bg-brand-orange rounded-full"
                            style="{{ $wishlistCount > 0 ? '' : 'display: none;' }}">{{ $wishlistCount }}</span>
                    </a>

                    <a href="{{ route('cart.index') }}"
                        class="relative p-2 text-brand-dark hover:text-brand-orange transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z" />
                        </svg>
                        <span
                            class="cart-count absolute top-1 right-0 inline-flex items-center justify-center px-1.5 py-0.5 text-xs font-bold leading-none text-white transform translate-x-1/4 -translate-y-1/4 bg-brand-orange rounded-full"
                            style="{{ $cartCount > 0 ? '' : 'display: none;' }}">{{ $cartCount }}</span>
                    </a>

                    <div class="flex items-center sm:ms-6">
                        <x-dropdown align="right" width="60">
                            <x-slot name="trigger">
                                <button
                                    class="flex items-center gap-3 pl-4 border-l border-gray-200 focus:outline-none transition duration-150 ease-in-out group">
                                    <div
                                        class="w-10 h-10 rounded-full bg-gray-200 overflow-hidden ring-2 ring-transparent group-hover:ring-brand-orange transition-all">
                                        <img src="https://ui-avatars.com/api/?name={{ Auth::user()->name }}&background=random"
                                            alt="{{ Auth::user()->name }}" class="w-full h-full object-cover">
                                    </div>
                                    <div class="hidden sm:block text-left">
                                        <div
                                            class="text-sm font-bold text-brand-dark group-hover:text-brand-orange transition-colors">
                                            {{ Auth::user()->name }}
                                        </div>
                                        <div class="text-xs text-gray-500">{{ Auth::user()->email }}</div>
                                    </div>
                                    <div class="ml-1">
                                        <svg class="fill-current h-4 w-4 text-gray-400 group-hover:text-brand-orange transition-colors"
                                            xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                </button>
                            </x-slot>

                            <x-slot name="content">
                                <!-- Account Management -->
                                <div class="block px-4 py-2 text-xs text-gray-400">
                                    Akun
                                </div>

                                <x-dropdown-link :href="route('profile.edit')"
                                    class="hover:text-brand-orange hover:bg-orange-50 flex items-center gap-3">
                                    <svg class="w-5 h-5 text-gray-500" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                    Profil
                                </x-dropdown-link>

                                <x-dropdown-link :href="route('orders.index')"
                                    class="hover:text-brand-orange hover:bg-orange-50 flex items-center gap-3">
                                    <svg class="w-5 h-5 text-gray-500" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                                    </svg>
                                    Pembelian Saya
                                </x-dropdown-link>

                                <!-- Store Management -->
                                @if(Auth::user()->store)
                                    <div class="border-t border-gray-100 my-1"></div>
                                    <div class="block px-4 py-2 text-xs text-gray-400">
                                        Manajemen Toko
                                    </div>

                                    <x-dropdown-link :href="route('dashboard')"
                                        class="hover:text-brand-orange hover:bg-orange-50 flex items-center gap-3">
                                        <svg class="w-5 h-5 text-gray-500" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                                        </svg>
                                        Dashboard
                                    </x-dropdown-link>

                                    @if(Auth::user()->store->is_verified)
                                        <x-dropdown-link :href="route('seller.orders.index')"
                                            class="hover:text-brand-orange hover:bg-orange-50 flex items-center gap-3">
                                            <svg class="w-5 h-5 text-gray-500" fill="none" viewBox="0 0 24 24"
                                                stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                            </svg>
                                            Pesanan Toko
                                        </x-dropdown-link>

                                        <x-dropdown-link :href="route('seller.products.index')"
                                            class="hover:text-brand-orange hover:bg-orange-50 flex items-center gap-3">
                                            <svg class="w-5 h-5 text-gray-500" fill="none" viewBox="0 0 24 24"
                                                stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                            </svg>
                                            Produk Saya
                                        </x-dropdown-link>

                                        <x-dropdown-link :href="route('seller.balance.index')"
                                            class="hover:text-brand-orange hover:bg-orange-50 flex items-center gap-3">
                                            <svg class="w-5 h-5 text-gray-500" fill="none" viewBox="0 0 24 24"
                                                stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            Keuangan
                                        </x-dropdown-link>
                                    @endif

                                    <x-dropdown-link :href="route('seller.stores.edit')"
                                        class="hover:text-brand-orange hover:bg-orange-50 flex items-center gap-3">
                                        <svg class="w-5 h-5 text-gray-500" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                        Pengaturan Toko
                                    </x-dropdown-link>

                                @elseif(Auth::user()->isAdmin())
                                    <div class="border-t border-gray-100 my-1"></div>
                                    <div class="block px-4 py-2 text-xs text-gray-400">
                                        Administrasi
                                    </div>

                                    <x-dropdown-link :href="route('dashboard')"
                                        class="hover:text-brand-orange hover:bg-orange-50 flex items-center gap-3">
                                        <svg class="w-5 h-5 text-gray-500" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                                        </svg>
                                        {{ __('Dashboard') }}
                                    </x-dropdown-link>

                                    <x-dropdown-link :href="route('admin.stores.index')"
                                        class="hover:text-brand-orange hover:bg-orange-50 flex items-center gap-3">
                                        <svg class="w-5 h-5 text-gray-500" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                        </svg>
                                        Kelola Toko
                                    </x-dropdown-link>

                                    <x-dropdown-link :href="route('admin.users.index')"
                                        class="hover:text-brand-orange hover:bg-orange-50 flex items-center gap-3">
                                        <svg class="w-5 h-5 text-gray-500" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                        </svg>
                                        Kelola Pengguna
                                    </x-dropdown-link>

                                    <x-dropdown-link :href="route('admin.orders.index')"
                                        class="hover:text-brand-orange hover:bg-orange-50 flex items-center gap-3">
                                        <svg class="w-5 h-5 text-gray-500" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                                        </svg>
                                        Kelola Pesanan
                                    </x-dropdown-link>

                                    <x-dropdown-link :href="route('admin.withdrawals.index')"
                                        class="hover:text-brand-orange hover:bg-orange-50 flex items-center gap-3">
                                        <svg class="w-5 h-5 text-gray-500" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                                        </svg>
                                        Penarikan Dana
                                    </x-dropdown-link>

                                @else
                                    <div class="border-t border-gray-100 my-1"></div>
                                    <x-dropdown-link :href="route('seller.stores.create')"
                                        class="hover:text-brand-orange hover:bg-orange-50 flex items-center gap-3">
                                        <svg class="w-5 h-5 text-gray-500" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M13 10V3L4 14h7v7l9-11h-7z" />
                                        </svg>
                                        Mulai Berjualan
                                    </x-dropdown-link>
                                @endif

                                <!-- System -->
                                <div class="border-t border-gray-100 my-1"></div>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf

                                    <x-dropdown-link :href="route('logout')"
                                        class="hover:text-brand-orange hover:bg-orange-50 flex items-center gap-3" onclick="event.preventDefault();
                                                                this.closest('form').submit();">
                                        <svg class="w-5 h-5 text-gray-500" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                        </svg>
                                        Keluar
                                    </x-dropdown-link>
                                </form>
                            </x-slot>
                        </x-dropdown>
                    </div>
                @else
                    <div class="flex items-center gap-3 pl-4 border-l border-gray-200">
                        <!-- Desktop: Log in text -->
                        <a href="{{ route('login') }}"
                            class="hidden md:inline text-sm font-semibold text-brand-dark hover:text-brand-orange">Masuk</a>

                        <!-- Mobile: Log in button -->
                        <a href="{{ route('login') }}"
                            class="md:hidden px-4 py-2 text-sm font-bold text-white bg-brand-dark rounded-lg hover:bg-brand-orange transition-colors shadow-sm">Masuk</a>

                        <!-- Register: Hidden on mobile -->
                        <a href="{{ route('register') }}"
                            class="hidden md:inline-flex px-4 py-2 text-sm font-semibold text-white bg-brand-dark rounded-lg hover:bg-brand-orange transition-colors">Daftar</a>
                    </div>
                @endauth
            </div>
        </div>
    </div>
</nav>