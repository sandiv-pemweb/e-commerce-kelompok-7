@props(['responsive' => false])

@php
    $linkComponent = $responsive ? 'responsive-nav-link' : 'nav-link';
@endphp

{{-- Public links always visible --}}
<x-dynamic-component :component="$linkComponent" :href="route('products.index')" :active="request()->routeIs('products.*')">
    {{ __('Products') }}
</x-dynamic-component>

@auth
    {{-- Customer links --}}
    <x-dynamic-component :component="$linkComponent" :href="route('dashboard')" :active="request()->routeIs('dashboard')">
        {{ __('Dashboard') }}
    </x-dynamic-component>
    
    <x-dynamic-component :component="$linkComponent" :href="route('cart.index')" :active="request()->routeIs('cart.*')">
        {{ __('Cart') }}
    </x-dynamic-component>
    
    <x-dynamic-component :component="$linkComponent" :href="route('orders.index')" :active="request()->routeIs('orders.*')">
        {{ __('My Orders') }}
    </x-dynamic-component>

    {{-- Admin Menu --}}
    @if(Auth::user()->isAdmin())
        <x-dynamic-component :component="$linkComponent" :href="route('admin.stores.index')" :active="request()->routeIs('admin.stores.*')">
            {{ __('Manage Stores') }}
        </x-dynamic-component>
        <x-dynamic-component :component="$linkComponent" :href="route('admin.users.index')" :active="request()->routeIs('admin.users.*')">
            {{ __('Manage Users') }}
        </x-dynamic-component>
        <x-dynamic-component :component="$linkComponent" :href="route('admin.withdrawals.index')" :active="request()->routeIs('admin.withdrawals.*')">
            {{ __('Withdrawals') }}
        </x-dynamic-component>
    @endif

    {{-- Seller Menu --}}
    @if(Auth::user()->store)
        @if(Auth::user()->store->is_verified)
            <x-dynamic-component :component="$linkComponent" :href="route('seller.orders.index')" :active="request()->routeIs('seller.orders.*')">
                {{ __('Orders') }}
            </x-dynamic-component>
            <x-dynamic-component :component="$linkComponent" :href="route('seller.products.index')" :active="request()->routeIs('seller.products.*')">
                {{ __('Products') }}
            </x-dynamic-component>
            <x-dynamic-component :component="$linkComponent" :href="route('seller.categories.index')" :active="request()->routeIs('seller.categories.*')">
                {{ __('Categories') }}
            </x-dynamic-component>
            <x-dynamic-component :component="$linkComponent" :href="route('seller.balance.index')" :active="request()->routeIs('seller.balance.*') || request()->routeIs('seller.withdrawals.*')">
                {{ __('Balance') }}
            </x-dynamic-component>
            <x-dynamic-component :component="$linkComponent" :href="route('seller.stores.edit')" :active="request()->routeIs('seller.stores.*')">
                {{ __('My Store') }}
            </x-dynamic-component>
        @else
            <x-dynamic-component :component="$linkComponent" :href="route('seller.stores.edit')" :active="request()->routeIs('seller.stores.*')">
                {{ __('My Store') }}
            </x-dynamic-component>
        @endif
    @else
        @unless(Auth::user()->isAdmin())
            <x-dynamic-component :component="$linkComponent" :href="route('seller.stores.create')" :active="request()->routeIs('seller.stores.create')">
                {{ __('Register Store') }}
            </x-dynamic-component>
        @endunless
    @endif
@endauth

