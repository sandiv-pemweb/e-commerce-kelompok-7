<x-store-layout>
    <div class="py-12 bg-brand-gray min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Store Header -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden mb-8">
                <div class="h-40 bg-gradient-to-r from-brand-dark to-brand-orange"></div>
                <div class="px-8 pb-8">
                    <div class="flex flex-col md:flex-row items-center md:items-center -mt-12 mb-6 gap-4 md:gap-6 relative z-10 text-center md:text-left">
                        <div class="w-32 h-32 bg-white rounded-full shadow-lg p-1.5 border-4 border-white ring-1 ring-black/5 relative overflow-hidden shrink-0" x-data="{ imageError: false }">
                            @if($store->logo)
                                <img 
                                    src="{{ str_starts_with($store->logo, 'http') ? $store->logo : asset('storage/' . $store->logo) }}" 
                                    alt="{{ $store->name }}" 
                                    class="w-full h-full object-cover rounded-full"
                                    x-on:error="imageError = true"
                                    x-show="!imageError"
                                >
                                <div 
                                    x-show="imageError" 
                                    class="w-full h-full bg-gray-100 rounded-full flex items-center justify-center text-5xl font-serif font-bold text-brand-dark absolute inset-0 m-1.5"
                                    style="display: none;"
                                >
                                    {{ strtoupper(substr($store->name, 0, 1)) }}
                                </div>
                            @else
                                <div class="w-full h-full bg-gray-100 rounded-full flex items-center justify-center text-5xl font-serif font-bold text-brand-dark">
                                    {{ strtoupper(substr($store->name, 0, 1)) }}
                                </div>
                            @endif
                        </div>
                        <div class="mb-1 md:mt-12">
                            <h1 class="text-3xl font-serif font-bold text-brand-dark mb-1 leading-tight">{{ $store->name }}</h1>
                            <p class="text-gray-500 text-sm flex items-center justify-center md:justify-start gap-2">
                                <svg class="w-4 h-4 text-brand-orange" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                Member since {{ $store->created_at->format('F Y') }}
                            </p>
                        </div>
                    </div>
                    @if($store->about)
                        <div class="mt-6 pt-6 border-t border-gray-100">
                            <h3 class="font-serif font-bold text-lg text-brand-dark mb-2">Tentang Toko</h3>
                            <p class="text-gray-600 max-w-3xl leading-relaxed">{{ $store->about }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Store Products -->
            <div class="mb-6">
                <h2 class="font-serif font-bold text-2xl text-brand-dark mb-4">Products</h2>
                @if($products->count() > 0)
                    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 md:gap-6">
                        @foreach($products as $product)
                            <div class="bg-white rounded-xl shadow-sm hover:shadow-lg transition-all duration-300 border border-gray-100 overflow-hidden group flex flex-col h-full">
                                <div class="relative aspect-[3/4] overflow-hidden bg-gray-100">
                                    @if($product->productImages->count() > 0)
                                        <img src="{{ $product->productImages->first()->image_url }}" alt="{{ $product->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center text-gray-400">
                                            <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                        </div>
                                    @endif
                                    @if($product->stock <= 0)
                                        <div class="absolute inset-0 bg-black/50 flex items-center justify-center">
                                            <span class="px-3 py-1 bg-red-500 text-white text-xs font-bold rounded-full uppercase tracking-wider">Out of Stock</span>
                                        </div>
                                    @endif
                                    <div class="absolute top-2 right-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                        <button onclick="toggleWishlist(this, {{ $product->id }})" class="p-2 bg-white/90 backdrop-blur-sm rounded-full {{ Auth::user() && Auth::user()->wishlists->contains('product_id', $product->id) ? 'text-red-500' : 'text-gray-400 hover:text-red-500' }} transition-colors shadow-sm">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="{{ Auth::user() && Auth::user()->wishlists->contains('product_id', $product->id) ? 'currentColor' : 'none' }}" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                                <div class="p-4 flex flex-col flex-grow">
                                    <div class="mb-2">
                                        <span class="text-xs font-bold text-brand-orange uppercase tracking-wider">{{ $product->productCategory->name ?? 'Uncategorized' }}</span>
                                    </div>
                                    <h3 class="font-bold text-brand-dark text-lg mb-1 line-clamp-2 group-hover:text-brand-orange transition-colors">
                                        <a href="{{ route('products.show', [$store->slug, $product->slug]) }}">
                                            {{ $product->name }}
                                        </a>
                                    </h3>
                                    <div class="mt-auto pt-3 flex items-center justify-between">
                                        <span class="font-bold text-lg text-brand-dark">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                                        <button onclick="addToCart({{ $product->id }})" class="p-2 bg-brand-dark text-white rounded-lg hover:bg-brand-orange transition-colors">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="mt-8">
                        {{ $products->links() }}
                    </div>
                @else
                    <div class="bg-white rounded-xl shadow-sm p-12 text-center border border-gray-100">
                        <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4 text-gray-400">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900">No products found</h3>
                        <p class="text-gray-500 mt-1">This store hasn't added any products yet.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-store-layout>
