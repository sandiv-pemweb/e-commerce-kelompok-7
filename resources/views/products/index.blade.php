<x-store-layout>
    <div class="bg-brand-gray min-h-screen py-12" x-data="{ showFilters: false }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
                <div>
                    <h2 class="text-3xl font-serif font-bold text-brand-dark">Koleksi Kami</h2>
                    <p class="mt-2 text-gray-500">Jelajahi berbagai macam buku dan produk kami.</p>
                </div>
                <div class="grid grid-cols-2 gap-3 w-full md:flex md:w-auto md:items-center">
                    <!-- Mobile Filter Toggle -->
                    <button @click="showFilters = !showFilters" class="lg:hidden w-full bg-white border border-gray-200 text-brand-dark font-medium py-2.5 px-4 rounded-xl flex items-center justify-between shadow-sm hover:shadow-md transition-all h-11">
                        <div class="flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-brand-orange">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6h9.75M10.5 6a1.5 1.5 0 11-3 0m3 0a1.5 1.5 0 10-3 0M3.75 6H7.5m3 12h9.75m-9.75 0a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m-3.75 0H7.5m9-6h3.75m-3.75 0a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m-9.75 0h9.75" />
                            </svg>
                            <span>Filter</span>
                        </div>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4 text-gray-400 transition-transform duration-200" :class="{'rotate-180': showFilters}">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                        </svg>
                    </button>

                    <form id="sortForm" method="GET" action="{{ route('products.index') }}" class="relative w-full md:w-auto">
                        <!-- Preserve existing filters -->
                        @if(request('search')) <input type="hidden" name="search" value="{{ request('search') }}"> @endif
                        @if(request('categories')) 
                            @foreach(request('categories') as $cat)
                                <input type="hidden" name="categories[]" value="{{ $cat }}">
                            @endforeach
                        @endif
                        @if(request('min_price')) <input type="hidden" name="min_price" value="{{ request('min_price') }}"> @endif
                        @if(request('max_price')) <input type="hidden" name="max_price" value="{{ request('max_price') }}"> @endif

                        <div class="relative group w-full">
                            <select name="sort" onchange="document.getElementById('sortForm').submit()" class="appearance-none w-full bg-white border border-gray-200 text-gray-700 py-2.5 pl-4 pr-10 rounded-xl leading-tight focus:outline-none focus:ring-2 focus:ring-brand-orange/20 focus:border-brand-orange transition-all cursor-pointer text-sm font-medium shadow-sm hover:border-brand-orange/50 h-11">
                                <option value="popularity" {{ request('sort') == 'popularity' ? 'selected' : '' }}>Urutkan: Popularitas</option>
                                <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Terbaru</option>
                                <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Harga: Rendah ke Tinggi</option>
                                <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Harga: Tinggi ke Rendah</option>
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-gray-400 group-hover:text-brand-orange transition-colors">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"/></svg>
                            </div>
                        </div>
                    </form>
                </div>
            </div>



            <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
                <!-- Sidebar Filters -->
                <div class="lg:col-span-1 lg:!block" x-show="showFilters" x-transition.origin.top x-cloak>
                    <div class="sticky top-24 space-y-6">
                        <form method="GET" action="{{ route('products.index') }}" id="filterForm">
                            @if(request('search')) <input type="hidden" name="search" value="{{ request('search') }}"> @endif
                            @if(request('sort')) <input type="hidden" name="sort" value="{{ request('sort') }}"> @endif

                            <!-- Categories -->
                            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                                <div class="p-6 border-b border-gray-100 bg-gray-50/50">
                                    <h3 class="font-bold text-brand-dark">Kategori</h3>
                                </div>
                                <div class="p-6 max-h-[400px] overflow-y-auto custom-scrollbar">
                                    <div class="space-y-4">
                                        @foreach($categories as $category)
                                            <div>
                                                <div class="flex items-center group">
                                                    <input type="checkbox" name="categories[]" value="{{ $category->id }}" 
                                                        {{ in_array($category->id, request('categories', [])) ? 'checked' : '' }}
                                                        class="rounded border-gray-300 text-brand-orange focus:ring-brand-orange cursor-pointer"
                                                        onchange="document.getElementById('filterForm').submit()">
                                                    <label class="ml-3 text-sm text-gray-600 group-hover:text-brand-orange transition-colors cursor-pointer flex-1">{{ $category->name }}</label>
                                                </div>
                                                @if($category->children->count() > 0)
                                                    <div class="ml-6 mt-3 space-y-3 border-l-2 border-gray-100 pl-3">
                                                        @foreach($category->children as $child)
                                                            <div class="flex items-center group">
                                                                <input type="checkbox" name="categories[]" value="{{ $child->id }}" 
                                                                    {{ in_array($child->id, request('categories', [])) ? 'checked' : '' }}
                                                                    class="rounded border-gray-300 text-brand-orange focus:ring-brand-orange cursor-pointer"
                                                                    onchange="document.getElementById('filterForm').submit()">
                                                                <label class="ml-3 text-sm text-gray-500 group-hover:text-brand-orange transition-colors cursor-pointer flex-1">{{ $child->name }}</label>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                            <!-- Price Range -->
                            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden mt-6">
                                <div class="p-6 border-b border-gray-100 bg-gray-50/50">
                                    <h3 class="font-bold text-brand-dark">Rentang Harga</h3>
                                </div>
                                <div class="p-6 space-y-6">
                                    <div class="grid grid-cols-2 gap-6">
                                        <div>
                                            <label class="text-xs font-medium text-gray-500 mb-1.5 block">Harga Minimum</label>
                                            <div class="relative">
                                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs">Rp</span>
                                                <input type="number" name="min_price" value="{{ request('min_price', $minPrice) }}" 
                                                    class="w-full rounded-lg border-gray-200 pl-8 text-sm focus:ring-brand-orange focus:border-brand-orange bg-gray-50 focus:bg-white transition-colors">
                                            </div>
                                        </div>
                                        <div>
                                            <label class="text-xs font-medium text-gray-500 mb-1.5 block">Harga Maksimum</label>
                                            <div class="relative">
                                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs">Rp</span>
                                                <input type="number" name="max_price" value="{{ request('max_price', $maxPrice) }}" 
                                                    class="w-full rounded-lg border-gray-200 pl-8 text-sm focus:ring-brand-orange focus:border-brand-orange bg-gray-50 focus:bg-white transition-colors">
                                            </div>
                                        </div>
                                    </div>
                                    <button type="submit" class="w-full bg-brand-dark text-white py-2.5 rounded-lg text-sm font-medium hover:bg-brand-orange transition-colors shadow-sm hover:shadow flex items-center justify-center gap-2">
                                        <span>Terapkan Filter</span>
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-3.5 h-3.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Product Grid -->
                <div class="lg:col-span-3">


                    @if($products->count() > 0)
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-6">
                            @foreach($products as $product)
                                <div class="bg-white rounded-xl shadow-sm hover:shadow-md transition-shadow overflow-hidden group">
                                    <div class="relative aspect-[2/3] bg-gray-200 overflow-hidden">
                                        @if($product->productImages->count() > 0)
                                            <img src="{{ $product->productImages->first()->image_url }}" alt="{{ $product->name }}"
                                                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center text-gray-400 bg-gray-100">
                                                <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                </svg>
                                            </div>
                                        @endif
                                        <div class="absolute top-2 right-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                            <button onclick="toggleWishlist(this, {{ $product->id }})" class="p-2 bg-white/90 backdrop-blur-sm rounded-full {{ Auth::user() && Auth::user()->wishlists->contains('product_id', $product->id) ? 'text-red-500' : 'text-gray-400 hover:text-red-500' }} transition-colors shadow-sm">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="{{ Auth::user() && Auth::user()->wishlists->contains('product_id', $product->id) ? 'currentColor' : 'none' }}" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z" />
                                                    </svg>
                                                </button>
                                        </div>
                                        @if($product->stock <= 0)
                                            <div class="absolute inset-0 bg-black/50 flex items-center justify-center z-10">
                                                <span class="px-3 py-1 bg-red-500 text-white text-xs font-bold rounded-full uppercase tracking-wider">Stok Habis</span>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="p-4">
                                        <h3 class="font-bold text-brand-dark truncate">
                                            <a
                                                href="{{ route('products.show', ['store' => $product->store->slug, 'product' => $product->slug]) }}">{{ $product->name }}</a>
                                        </h3>
                                        <p class="text-xs text-gray-500 mb-2">{{ $product->store->name ?? 'Toko Tidak Diketahui' }}</p>
                                        <div class="flex items-center gap-1 mb-3">
                                            <div class="flex text-brand-orange text-sm">
                                                @php
                                                    $rating = $product->productReviews->avg('rating') ?? 0;
                                                @endphp
                                                @for($i = 1; $i <= 5; $i++)
                                                    <svg class="w-4 h-4 {{ $i <= round($rating) ? 'fill-current' : 'text-gray-300' }}"
                                                        viewBox="0 0 20 20" fill="currentColor">
                                                        <path
                                                            d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                                    </svg>
                                                @endfor
                                            </div>
                                            <span class="text-xs text-gray-400">({{ number_format($rating, 1) }})</span>
                                            <span class="text-xs text-gray-300 mx-1 hidden sm:inline">&bull;</span>
                                            <span class="text-xs text-gray-500 hidden sm:inline">{{ $product->sold_count }} Terjual</span>
                                        </div>
                                        <div class="flex items-center justify-between">
                                            <span class="font-bold text-brand-dark">{{ $product->formatted_price }}</span>
                                            @auth
                                                <button onclick="addToCart({{ $product->id }})"
                                                    class="p-2 bg-brand-dark text-white rounded-lg hover:bg-brand-orange transition-colors">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                        stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z" />
                                                    </svg>
                                                </button>
                                            @else
                                                <a href="{{ route('login') }}"
                                                    class="p-2 bg-brand-dark text-white rounded-lg hover:bg-brand-orange transition-colors">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                        stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z" />
                                                    </svg>
                                                </a>
                                            @endauth
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Pagination -->
                        <div class="mt-12">
                            {{ $products->links() }}
                        </div>
                    @else
                        <div class="bg-white rounded-xl shadow-sm p-16 text-center">
                            <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                                <svg class="h-10 w-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <h3 class="text-lg font-bold text-gray-900">Produk tidak ditemukan</h3>
                            <p class="mt-2 text-gray-500">Kami tidak dapat menemukan produk yang sesuai dengan pencarian Anda.</p>
                            <a href="{{ route('products.index') }}" class="mt-6 inline-block text-brand-orange font-medium hover:underline">Hapus semua filter</a>
                        </div>
                    @endif
                </div>
            </div>
</x-store-layout>
