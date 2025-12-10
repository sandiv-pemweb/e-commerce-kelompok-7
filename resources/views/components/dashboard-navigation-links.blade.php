@props(['responsive' => false])

@php
    $linkComponent = $responsive ? 'responsive-nav-link' : 'nav-link';
@endphp

@auth

    @if(Auth::user()->isAdmin())
        <x-dynamic-component :component="$linkComponent" :href="route('dashboard')" :active="request()->routeIs('dashboard')">
            {{ __('Overview') }}
        </x-dynamic-component>
        <x-dynamic-component :component="$linkComponent" :href="route('admin.stores.index')"
            :active="request()->routeIs('admin.stores.*')">
            {{ __('Stores') }}
        </x-dynamic-component>
        <x-dynamic-component :component="$linkComponent" :href="route('admin.users.index')"
            :active="request()->routeIs('admin.users.*')">
            {{ __('Users') }}
        </x-dynamic-component>
        <x-dynamic-component :component="$linkComponent" :href="route('admin.withdrawals.index')"
            :active="request()->routeIs('admin.withdrawals.*')">
            {{ __('Withdrawals') }}
        </x-dynamic-component>
    @endif


    @if(Auth::user()->store)
        <x-dynamic-component :component="$linkComponent" :href="route('dashboard')" :active="request()->routeIs('dashboard')">
            {{ __('Overview') }}
        </x-dynamic-component>

        @if(Auth::user()->store->is_verified)
            <x-dynamic-component :component="$linkComponent" :href="route('seller.orders.index')"
                :active="request()->routeIs('seller.orders.*')">
                {{ __('Orders') }}
            </x-dynamic-component>
            <x-dynamic-component :component="$linkComponent" :href="route('seller.products.index')"
                :active="request()->routeIs('seller.products.*')">
                {{ __('Products') }}
            </x-dynamic-component>
            <x-dynamic-component :component="$linkComponent" :href="route('seller.categories.index')"
                :active="request()->routeIs('seller.categories.*')">
                {{ __('Categories') }}
            </x-dynamic-component>
            <x-dynamic-component :component="$linkComponent" :href="route('seller.balance.index')"
                :active="request()->routeIs('seller.balance.*') || request()->routeIs('seller.withdrawals.*')">
                {{ __('Balance') }}
            </x-dynamic-component>
            <x-dynamic-component :component="$linkComponent" :href="route('seller.stores.edit')"
                :active="request()->routeIs('seller.stores.*')">
                {{ __('Settings') }}
            </x-dynamic-component>
        @else
            <x-dynamic-component :component="$linkComponent" :href="route('seller.stores.edit')"
                :active="request()->routeIs('seller.stores.*')">
                {{ __('Store Settings') }}
            </x-dynamic-component>
        @endif
    @else
        @unless(Auth::user()->isAdmin())
            <x-dynamic-component :component="$linkComponent" :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Overview') }}
            </x-dynamic-component>
            <x-dynamic-component :component="$linkComponent" :href="route('seller.stores.create')"
                :active="request()->routeIs('seller.stores.create')">
                {{ __('Register Store') }}
            </x-dynamic-component>
        @endunless
    @endif
@endauth