<x-store-layout>
    <div class="bg-brand-gray min-h-screen py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
                <div>
                    <h2 class="text-3xl font-serif font-bold text-brand-dark">Our Collection</h2>
                    <p class="mt-2 text-gray-500">Explore our wide range of books and products.</p>
                </div>
                <div class="flex items-center gap-4">
                    <form id="sortForm" method="GET" action="{{ route('products.index') }}" class="relative">
                        <!-- Preserve existing filters -->
                        @if(request('search')) <input type="hidden" name="search" value="{{ request('search') }}"> @endif
                        @if(request('categories')) 
                            @foreach(request('categories') as $cat)
                                <input type="hidden" name="categories[]" value="{{ $cat }}">
                            @endforeach
                        @endif
                        @if(request('min_price')) <input type="hidden" name="min_price" value="{{ request('min_price') }}"> @endif
                        @if(request('max_price')) <input type="hidden" name="max_price" value="{{ request('max_price') }}"> @endif

                        <select name="sort" onchange="document.getElementById('sortForm').submit()" class="appearance-none bg-white border border-gray-200 text-gray-700 py-2.5 pl-4 pr-10 rounded-xl leading-tight focus:outline-none focus:ring-2 focus:ring-brand-orange/20 focus:border-brand-orange transition-all cursor-pointer text-sm font-medium">
                            <option value="popularity" {{ request('sort') == 'popularity' ? 'selected' : '' }}>Sort by: Popularity</option>
                            <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Newest Arrivals</option>
                            <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Price: Low to High</option>
                            <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Price: High to Low</option>
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-gray-500">
                            <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"/></svg>
                        </div>
                    </form>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
                <!-- Sidebar Filters -->
                <div class="lg:col-span-1 space-y-8">
                    <form method="GET" action="{{ route('products.index') }}" id="filterForm">
                        @if(request('search')) <input type="hidden" name="search" value="{{ request('search') }}"> @endif
                        @if(request('sort')) <input type="hidden" name="sort" value="{{ request('sort') }}"> @endif

                        <!-- Categories -->
                        <div class="bg-white rounded-xl shadow-sm p-6">
                            <h3 class="font-bold text-brand-dark mb-4">Shop by Category</h3>
                            <div class="space-y-3">
                                @foreach($categories as $category)
                                    <div class="flex items-center">
                                        <input type="checkbox" name="categories[]" value="{{ $category->id }}" 
                                            {{ in_array($category->id, request('categories', [])) ? 'checked' : '' }}
                                            class="rounded border-gray-300 text-brand-orange focus:ring-brand-orange"
                                            onchange="document.getElementById('filterForm').submit()">
                                        <label class="ml-2 text-sm text-gray-600">{{ $category->name }}</label>
                                    </div>
                                    @foreach($category->children as $child)
                                        <div class="flex items-center ml-4">
                                            <input type="checkbox" name="categories[]" value="{{ $child->id }}" 
                                                {{ in_array($child->id, request('categories', [])) ? 'checked' : '' }}
                                                class="rounded border-gray-300 text-brand-orange focus:ring-brand-orange"
                                                onchange="document.getElementById('filterForm').submit()">
                                            <label class="ml-2 text-sm text-gray-500">{{ $child->name }}</label>
                                        </div>
                                    @endforeach
                                @endforeach
                            </div>
                        </div>

                        <!-- Price Range -->
                        <div class="bg-white rounded-xl shadow-sm p-6 mt-6">
                            <h3 class="font-bold text-brand-dark mb-4">Price Range</h3>
                            <div class="space-y-4">
                                <div>
                                    <label class="text-xs text-gray-500">Min Price</label>
                                    <input type="number" name="min_price" value="{{ request('min_price', $minPrice) }}" 
                                        class="w-full rounded-lg border-gray-200 text-sm focus:ring-brand-orange focus:border-brand-orange">
                                </div>
                                <div>
                                    <label class="text-xs text-gray-500">Max Price</label>
                                    <input type="number" name="max_price" value="{{ request('max_price', $maxPrice) }}" 
                                        class="w-full rounded-lg border-gray-200 text-sm focus:ring-brand-orange focus:border-brand-orange">
                                </div>
                                <button type="submit" class="w-full bg-brand-dark text-white py-2 rounded-lg text-sm hover:bg-brand-orange transition-colors">
                                    Apply Price
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Product Grid -->
                <div class="lg:col-span-3">
                    <!-- Search Bar (Mobile/Desktop) -->
                    <div class="bg-white rounded-xl shadow-sm p-4 mb-6">
                        <form method="GET" action="{{ route('products.index') }}" class="relative">
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search books..." class="w-full pl-10 pr-4 py-2 border border-gray-200 rounded-lg focus:ring-brand-orange focus:border-brand-orange">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </div>
                        </form>
                    </div>

                    @if($products->count() > 0)
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-6">
                            @foreach($products as $product)
                                <div class="bg-white rounded-xl shadow-sm hover:shadow-md transition-shadow overflow-hidden group">
                                    <div class="relative aspect-[2/3] bg-gray-200 overflow-hidden">
                                        @if($product->productImages->count() > 0)
                                            <img src="{{ $product->productImages->first()->image_url }}" alt="{{ $product->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center text-gray-400 bg-gray-100">
                                                <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
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
                                    </div>
                                    <div class="p-4">
                                        <div class="text-xs text-brand-orange font-medium mb-1">{{ $product->category->name ?? 'Uncategorized' }}</div>
                                        <h3 class="font-bold text-brand-dark truncate mb-1">
                                            <a href="{{ route('products.show', ['store' => $product->store->slug, 'product' => $product->slug]) }}">{{ $product->name }}</a>
                                        </h3>
                                        <p class="text-xs text-gray-500 mb-3">{{ $product->store->name }}</p>
                                        <div class="flex items-center justify-between">
                                            <span class="font-bold text-lg text-brand-dark">{{ $product->formatted_price }}</span>
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
                            <h3 class="text-lg font-bold text-gray-900">No products found</h3>
                            <p class="mt-2 text-gray-500">We couldn't find any products matching your search.</p>
                            <a href="{{ route('products.index') }}" class="mt-6 inline-block text-brand-orange font-medium hover:underline">Clear all filters</a>
                        </div>
                    @endif
                </div>
            </div>
</x-store-layout>
