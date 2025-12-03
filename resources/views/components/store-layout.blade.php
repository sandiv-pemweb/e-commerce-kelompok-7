<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'BookLand') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400..900;1,400..900&family=Plus+Jakarta+Sans:ital,wght@0,200..800;1,200..800&display=swap"
        rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans text-brand-dark antialiased bg-brand-gray">
    <div class="min-h-screen flex flex-col">
        <!-- Header -->
        <header class="bg-white shadow-sm sticky top-0 z-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-20">
                    <!-- Logo -->
                    <div class="flex-shrink-0 flex items-center gap-3">
                        <a href="{{ route('home') }}" class="flex items-center gap-2">
                            <div
                                class="w-10 h-10 bg-brand-orange rounded-lg flex items-center justify-center text-white">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                                    stroke="currentColor" class="w-6 h-6">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" />
                                </svg>
                            </div>
                            <div class="flex flex-col">
                                <span class="font-serif text-2xl font-bold text-brand-dark leading-none">BookLand</span>
                                <span class="text-xs text-brand-muted font-medium">Book Store Website</span>
                            </div>
                        </a>
                    </div>

                    <!-- Search Bar -->
                    <div class="hidden md:flex flex-1 max-w-lg mx-8">
                        <div class="relative w-full" x-data="{ menuOpen: false }">
                            <!-- Dropdown Button -->
                            <button @click="menuOpen = !menuOpen" type="button"
                                class="absolute inset-y-0 left-0 pl-3 flex items-center hover:text-brand-orange transition-colors z-10">
                                <span class="text-gray-600 text-sm font-medium">Menus</span>
                                <svg class="h-4 w-4 text-gray-600 ml-1" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor" :class="menuOpen ? 'rotate-180' : ''">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>

                            <!-- Dropdown Menu -->
                            <div x-show="menuOpen" @click.away="menuOpen = false"
                                x-transition:enter="transition ease-out duration-200"
                                x-transition:enter-start="opacity-0 scale-95"
                                x-transition:enter-end="opacity-100 scale-100"
                                x-transition:leave="transition ease-in duration-150"
                                x-transition:leave-start="opacity-100 scale-100"
                                x-transition:leave-end="opacity-0 scale-95"
                                class="absolute left-0 top-full mt-2 w-56 bg-white rounded-lg shadow-lg border border-gray-100 py-2 z-50"
                                style="display: none;">
                                <!-- Products (Always visible) -->
                                <a href="{{ route('products.index') }}"
                                    class="flex items-center px-4 py-2.5 text-sm text-gray-700 hover:bg-orange-50 hover:text-brand-orange transition-colors {{ request()->routeIs('products.*') ? 'bg-orange-50 text-brand-orange font-semibold' : '' }}">
                                    <svg class="w-5 h-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" />
                                    </svg>
                                    Products
                                </a>

                                @auth
                                    <!-- Dashboard -->
                                    <a href="{{ route('dashboard') }}"
                                        class="flex items-center px-4 py-2.5 text-sm text-gray-700 hover:bg-orange-50 hover:text-brand-orange transition-colors {{ request()->routeIs('dashboard') ? 'bg-orange-50 text-brand-orange font-semibold' : '' }}">
                                        <svg class="w-5 h-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                        </svg>
                                        Dashboard
                                    </a>

                                    <!-- Cart -->
                                    <a href="{{ route('cart.index') }}"
                                        class="flex items-center px-4 py-2.5 text-sm text-gray-700 hover:bg-orange-50 hover:text-brand-orange transition-colors {{ request()->routeIs('cart.*') ? 'bg-orange-50 text-brand-orange font-semibold' : '' }}">
                                        <svg class="w-5 h-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z" />
                                        </svg>
                                        Cart
                                        @if($cartCount > 0)
                                            <span
                                                class="ml-auto px-2 py-0.5 text-xs font-bold text-white bg-brand-orange rounded-full">{{ $cartCount }}</span>
                                        @endif
                                    </a>

                                    <!-- My Orders -->
                                    <a href="{{ route('orders.index') }}"
                                        class="flex items-center px-4 py-2.5 text-sm text-gray-700 hover:bg-orange-50 hover:text-brand-orange transition-colors {{ request()->routeIs('orders.*') ? 'bg-orange-50 text-brand-orange font-semibold' : '' }}">
                                        <svg class="w-5 h-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                        My Orders
                                    </a>

                                    @if(!Auth::user()->store && !Auth::user()->isAdmin())
                                        <!-- Register Store -->
                                        <div class="border-t border-gray-100 my-2"></div>
                                        <a href="{{ route('seller.stores.create') }}"
                                            class="flex items-center px-4 py-2.5 text-sm text-gray-700 hover:bg-orange-50 hover:text-brand-orange transition-colors {{ request()->routeIs('seller.stores.create') ? 'bg-orange-50 text-brand-orange font-semibold' : '' }}">
                                            <svg class="w-5 h-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                            </svg>
                                            Register Store
                                        </a>
                                    @endif
                                @endauth
                            </div>

                            <input type="text"
                                class="block w-full pl-24 pr-10 py-2.5 border-none bg-gray-100 rounded-lg text-sm focus:ring-2 focus:ring-brand-orange focus:bg-white transition-colors placeholder-gray-400"
                                placeholder="Search books here...">
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Right Navigation -->
                    <div class="flex items-center gap-6">
                        <a href="{{ route('wishlist.index') }}"
                            class="relative p-2 text-brand-dark hover:text-brand-orange transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z" />
                            </svg>
                            <span id="wishlist-count"
                                class="absolute top-1 right-0 inline-flex items-center justify-center px-1.5 py-0.5 text-xs font-bold leading-none text-white transform translate-x-1/4 -translate-y-1/4 bg-brand-orange rounded-full"
                                style="{{ $wishlistCount > 0 ? '' : 'display: none;' }}">{{ $wishlistCount }}</span>
                        </a>

                        <a href="{{ route('cart.index') }}"
                            class="relative p-2 text-brand-dark hover:text-brand-orange transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z" />
                            </svg>
                            <span id="cart-count"
                                class="absolute top-1 right-0 inline-flex items-center justify-center px-1.5 py-0.5 text-xs font-bold leading-none text-white transform translate-x-1/4 -translate-y-1/4 bg-brand-orange rounded-full"
                                style="{{ $cartCount > 0 ? '' : 'display: none;' }}">{{ $cartCount }}</span>
                        </a>

                        @auth
                            <div class="flex items-center sm:ms-6">
                                <x-dropdown align="right" width="48">
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
                                        <x-dropdown-link :href="route('dashboard')"
                                            class="hover:text-brand-orange hover:bg-orange-50 flex items-center gap-3">
                                            <svg class="w-5 h-5 text-gray-500" fill="none" viewBox="0 0 24 24"
                                                stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                            </svg>
                                            {{ __('Dashboard') }}
                                        </x-dropdown-link>

                                        <x-dropdown-link :href="route('profile.edit')"
                                            class="hover:text-brand-orange hover:bg-orange-50 flex items-center gap-3">
                                            <svg class="w-5 h-5 text-gray-500" fill="none" viewBox="0 0 24 24"
                                                stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                            </svg>
                                            {{ __('Profile') }}
                                        </x-dropdown-link>

                                        <!-- Authentication -->
                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf

                                            <x-dropdown-link :href="route('logout')"
                                                class="hover:text-brand-orange hover:bg-orange-50 flex items-center gap-3"
                                                onclick="event.preventDefault();
                                                                        this.closest('form').submit();">
                                                <svg class="w-5 h-5 text-gray-500" fill="none" viewBox="0 0 24 24"
                                                    stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                                </svg>
                                                {{ __('Log Out') }}
                                            </x-dropdown-link>
                                        </form>
                                    </x-slot>
                                </x-dropdown>
                            </div>
                        @else
                            <div class="flex items-center gap-3 pl-4 border-l border-gray-200">
                                <a href="{{ route('login') }}"
                                    class="text-sm font-semibold text-brand-dark hover:text-brand-orange">Log in</a>
                                <a href="{{ route('register') }}"
                                    class="px-4 py-2 text-sm font-semibold text-white bg-brand-dark rounded-lg hover:bg-brand-orange transition-colors">Register</a>
                            </div>
                        @endauth
                    </div>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="flex-grow">
            {{ $slot }}
        </main>

        <!-- Footer -->
        <x-footer />
    </div>
</body>

</html>