@props(['responsive' => false])

@php
    $linkComponent = $responsive ? 'responsive-nav-link' : 'nav-link';
@endphp

<x-dynamic-component :component="$linkComponent" :href="route('dashboard')" :active="request()->routeIs('dashboard')">
    {{ __('Dashboard') }}
</x-dynamic-component>

@auth
    <!-- Admin Menu -->
    @if(Auth::user()->isAdmin())
        <x-dynamic-component :component="$linkComponent" :href="route('admin.stores.index')" :active="request()->routeIs('admin.stores.*')">
            {{ __('Kelola Toko') }}
        </x-dynamic-component>
        <x-dynamic-component :component="$linkComponent" :href="route('admin.users.index')" :active="request()->routeIs('admin.users.*')">
            {{ __('Kelola Pengguna') }}
        </x-dynamic-component>
        <x-dynamic-component :component="$linkComponent" :href="route('admin.withdrawals.index')" :active="request()->routeIs('admin.withdrawals.*')">
            {{ __('Penarikan Saldo') }}
        </x-dynamic-component>
    @endif

    <!-- Seller Menu -->
    @if(Auth::user()->store)
        @if(Auth::user()->store->is_verified)
            <x-dynamic-component :component="$linkComponent" :href="route('seller.orders.index')" :active="request()->routeIs('seller.orders.*')">
                {{ __('Pesanan') }}
            </x-dynamic-component>
            <x-dynamic-component :component="$linkComponent" :href="route('seller.products.index')" :active="request()->routeIs('seller.products.*')">
                {{ __('Produk') }}
            </x-dynamic-component>
            <x-dynamic-component :component="$linkComponent" :href="route('seller.categories.index')" :active="request()->routeIs('seller.categories.*')">
                {{ __('Kategori') }}
            </x-dynamic-component>
            <x-dynamic-component :component="$linkComponent" :href="route('seller.balance.index')" :active="request()->routeIs('seller.balance.*') || request()->routeIs('seller.withdrawals.*')">
                {{ __('Saldo') }}
            </x-dynamic-component>
            <x-dynamic-component :component="$linkComponent" :href="route('seller.stores.edit')" :active="request()->routeIs('seller.stores.*')">
                {{ __('Toko Saya') }}
            </x-dynamic-component>
        @else
            <x-dynamic-component :component="$linkComponent" :href="route('seller.stores.edit')" :active="request()->routeIs('seller.stores.*')">
                {{ __('Toko Saya') }}
            </x-dynamic-component>
        @endif
    @else
        @unless(Auth::user()->isAdmin())
            <x-dynamic-component :component="$linkComponent" :href="route('seller.stores.create')" :active="request()->routeIs('seller.stores.create')">
                {{ __('Daftar Toko') }}
            </x-dynamic-component>
        @endunless
    @endif
@endauth
