<x-store-layout>
    <div class="bg-gray-50 min-h-screen py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Top Section: Image & Main Info -->
            <div class="bg-white rounded-3xl shadow-sm p-8 mb-8">
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-12">
                    <!-- Product Image (Left) -->
                    <div class="lg:col-span-4">
                        <div class="relative aspect-[3/4] rounded-2xl overflow-hidden bg-gray-100 shadow-lg group">
                            @if($product->productImages->count() > 0)
                                <img id="mainImage" src="{{ $product->productImages->first()->image_url }}" alt="{{ $product->name }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-gray-300">
                                    <svg class="w-24 h-24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                            @endif
                        </div>
                        <!-- Thumbnails -->
                        @if($product->productImages->count() > 1)
                            <div class="flex gap-3 mt-4 overflow-x-auto pb-2">
                                @foreach($product->productImages as $image)
                                    <button class="flex-shrink-0 w-16 h-16 rounded-lg overflow-hidden border-2 border-transparent hover:border-brand-orange transition-all focus:outline-none focus:border-brand-orange" onclick="document.getElementById('mainImage').src = '{{ $image->image_url }}'">
                                        <img src="{{ $image->image_url }}" alt="" class="w-full h-full object-cover">
                                    </button>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    <!-- Main Info (Right) -->
                    <div class="lg:col-span-8 flex flex-col">
                        <div class="flex justify-between items-start mb-4">
                            <h1 class="text-4xl font-serif font-bold text-brand-dark leading-tight">{{ $product->name }}</h1>
                            <!-- Wishlist Button -->
                            <button onclick="toggleWishlist(this, {{ $product->id }})" class="p-3 bg-gray-50 rounded-full {{ Auth::user() && Auth::user()->wishlists->contains('product_id', $product->id) ? 'text-red-500' : 'text-gray-400 hover:text-red-500' }} hover:bg-red-50 transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="{{ Auth::user() && Auth::user()->wishlists->contains('product_id', $product->id) ? 'currentColor' : 'none' }}" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z" />
                                    </svg>
                                </button>
                        </div>

                        <!-- Rating & Social -->
                        <div class="flex items-center gap-6 mb-8">
                            <div class="flex items-center gap-2">
                                <div class="flex text-brand-orange">
                                    @for($i = 1; $i <= 5; $i++)
                                        <svg class="w-5 h-5 {{ $i <= round($averageRating) ? 'fill-current' : 'text-gray-300' }}" viewBox="0 0 20 20" fill="currentColor">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                        </svg>
                                    @endfor
                                </div>
                                <span class="font-bold text-brand-dark">{{ number_format($averageRating, 1) }}</span>
                            </div>
                            <div class="flex items-center gap-4 text-sm text-gray-500">
                                <span class="flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path></svg>
                                    {{ $totalReviews }} Reviews
                                </span>
                                <span class="flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                                    {{ $product->wishlist_count }}+ Likes
                                </span>
                                <span class="flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                    {{ $product->sold_count }} Sold
                                </span>
                            </div>
                        </div>

                        <!-- Meta Info -->
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8 border-y border-gray-100 py-6">
                            <div>
                                <p class="text-xs text-gray-400 uppercase tracking-wider font-bold mb-1">Written by</p>
                                <div class="flex items-center gap-2">
                                    <div class="w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center text-xs font-bold text-gray-600">
                                        {{ substr($product->author ?? $product->store->name, 0, 1) }}
                                    </div>
                                    <span class="font-bold text-brand-dark text-sm">{{ $product->author ?? $product->store->name }}</span>
                                </div>
                            </div>
                            <div>
                                <p class="text-xs text-gray-400 uppercase tracking-wider font-bold mb-1">Publisher</p>
                                <span class="font-bold text-brand-dark text-sm">{{ $product->publisher ?? $product->store->name }}</span>
                            </div>
                            <div>
                                <p class="text-xs text-gray-400 uppercase tracking-wider font-bold mb-1">Year</p>
                                <span class="font-bold text-brand-dark text-sm">{{ $product->published_year ?? $product->created_at->format('Y') }}</span>
                            </div>
                            <div>
                                <p class="text-xs text-gray-400 uppercase tracking-wider font-bold mb-1">Store</p>
                                <div class="flex items-center gap-2">
                                    <img src="{{ $product->store->logo }}" alt="{{ $product->store->name }}" class="w-6 h-6 rounded-full object-cover">
                                    <span class="font-bold text-brand-dark text-sm">{{ $product->store->name }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Description -->
                        <div class="prose prose-sm text-gray-500 mb-8 max-w-none">
                            <p class="line-clamp-3">{{ $product->description }}</p>
                        </div>

                        <!-- Price & Actions -->
                        <div class="mt-auto">
                            <div class="flex items-center gap-4 mb-8">
                                @if($product->discount_price > 0)
                                    <span class="text-4xl font-bold text-brand-dark">{{ $product->formatted_discount_price }}</span>
                                    <span class="text-lg text-gray-400 line-through decoration-red-500 decoration-2">
                                        {{ $product->formatted_price }}
                                    </span>
                                    <span class="px-2 py-1 bg-red-100 text-red-600 text-xs font-bold rounded">-{{ $product->discount_percentage }}%</span>
                                @else
                                    <span class="text-4xl font-bold text-brand-dark">{{ $product->formatted_price }}</span>
                                @endif
                            </div>

                            <div class="flex items-center gap-6">
                                @auth
                                    @if($product->stock > 0)
                                        <!-- Quantity -->
                                            <div class="flex items-center border border-gray-200 rounded-lg">
                                                <button type="button" id="decrement-button" class="px-4 py-2 text-brand-orange text-xl font-bold hover:bg-gray-50 rounded-l-lg">-</button>
                                                <input type="number" name="quantity" id="quantity-input" value="1" min="1" max="{{ $product->stock }}" class="w-12 text-center border-0 focus:ring-0 font-bold text-brand-dark p-0">
                                                <button type="button" id="increment-button" class="px-4 py-2 text-brand-orange text-xl font-bold hover:bg-gray-50 rounded-r-lg">+</button>
                                            </div>

                                            <button type="button" onclick="addToCart({{ $product->id }}, document.getElementById('quantity-input').value)" class="flex-1 bg-brand-orange text-white px-8 py-3 rounded-xl hover:bg-brand-dark transition-all font-bold text-lg shadow-lg shadow-brand-orange/20 flex items-center justify-center gap-2">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z" />
                                                </svg>
                                                Add to cart
                                            </button>
                                    @else
                                        <button disabled class="w-full bg-gray-100 text-gray-400 px-8 py-4 rounded-xl font-bold text-lg cursor-not-allowed border border-gray-200">
                                            Out of Stock
                                        </button>
                                    @endif
                                @else
                                    <a href="{{ route('login') }}" class="block w-full bg-brand-orange text-white text-center px-8 py-4 rounded-xl hover:bg-brand-dark transition-all font-bold text-lg shadow-lg shadow-brand-orange/20">
                                        Login to Buy
                                    </a>
                                @endauth
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bottom Section: Details & Related -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                <!-- Left: Tabs & Content -->
                <div class="lg:col-span-8">
                    <div class="bg-white rounded-3xl shadow-sm p-8 min-h-[500px]" x-data="{ activeTab: 'details' }">
                        <!-- Tabs -->
                        <div class="flex items-center gap-8 border-b border-gray-100 mb-8">
                            <button @click="activeTab = 'details'" 
                                :class="{ 'border-brand-orange text-brand-dark': activeTab === 'details', 'border-transparent text-gray-400 hover:text-gray-600': activeTab !== 'details' }"
                                class="pb-4 font-bold text-lg border-b-2 transition-colors">
                                Details Product
                            </button>
                            <button @click="activeTab = 'reviews'" 
                                :class="{ 'border-brand-orange text-brand-dark': activeTab === 'reviews', 'border-transparent text-gray-400 hover:text-gray-600': activeTab !== 'reviews' }"
                                class="pb-4 font-bold text-lg border-b-2 transition-colors">
                                Customer Reviews
                            </button>
                        </div>

                        <!-- Details Content -->
                        <div x-show="activeTab === 'details'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform scale-95" x-transition:enter-end="opacity-100 transform scale-100">
                            <div class="grid grid-cols-1 gap-y-4">
                                <div class="grid grid-cols-3 py-3 border-b border-gray-50">
                                    <span class="font-bold text-brand-dark">Book Title</span>
                                    <span class="col-span-2 text-gray-500">{{ $product->name }}</span>
                                </div>
                                <div class="grid grid-cols-3 py-3 border-b border-gray-50">
                                    <span class="font-bold text-brand-dark">Author</span>
                                    <span class="col-span-2 text-gray-500">{{ $product->author ?? $product->store->name }}</span>
                                </div>
                                <div class="grid grid-cols-3 py-3 border-b border-gray-50">
                                    <span class="font-bold text-brand-dark">ISBN</span>
                                    <span class="col-span-2 text-gray-500">121341381648 (ISBN13: 121341381648)</span>
                                </div>
                                <div class="grid grid-cols-3 py-3 border-b border-gray-50">
                                    <span class="font-bold text-brand-dark">Edition Language</span>
                                    <span class="col-span-2 text-gray-500">English</span>
                                </div>
                                <div class="grid grid-cols-3 py-3 border-b border-gray-50">
                                    <span class="font-bold text-brand-dark">Book Format</span>
                                    <span class="col-span-2 text-gray-500">Paperback, {{ $product->weight }}g</span>
                                </div>
                                <div class="grid grid-cols-3 py-3 border-b border-gray-50">
                                    <span class="font-bold text-brand-dark">Date Published</span>
                                    <span class="col-span-2 text-gray-500">{{ $product->published_year ?? $product->created_at->format('Y') }}</span>
                                </div>
                                <div class="grid grid-cols-3 py-3 border-b border-gray-50">
                                    <span class="font-bold text-brand-dark">Publisher</span>
                                    <span class="col-span-2 text-gray-500">{{ $product->publisher ?? $product->store->name }}</span>
                                </div>
                                <div class="grid grid-cols-3 py-3 border-b border-gray-50">
                                    <span class="font-bold text-brand-dark">Store</span>
                                    <span class="col-span-2 text-gray-500">{{ $product->store->name }}</span>
                                </div>
                                <div class="grid grid-cols-3 py-3 border-b border-gray-50">
                                    <span class="font-bold text-brand-dark">Tags</span>
                                    <div class="col-span-2 flex flex-wrap gap-2">
                                        <span class="px-3 py-1 bg-brand-orange/10 text-brand-orange text-xs font-bold rounded-full">{{ $product->productCategory->name ?? 'General' }}</span>
                                        <span class="px-3 py-1 bg-brand-orange text-white text-xs font-bold rounded-full">Bestseller</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Reviews Content -->
                        <div x-show="activeTab === 'reviews'" x-cloak x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform scale-95" x-transition:enter-end="opacity-100 transform scale-100">
                            <div class="space-y-8">
                                @forelse($product->productReviews as $review)
                                    <div class="flex gap-4 border-b border-gray-50 pb-8 last:border-0 last:pb-0">
                                        <div class="flex-shrink-0">
                                            <img src="https://ui-avatars.com/api/?name={{ $review->transaction->buyer->user->name ?? 'User' }}&background=random" alt="" class="w-12 h-12 rounded-full">
                                        </div>
                                        <div class="flex-1">
                                            <div class="flex justify-between items-start mb-2">
                                                <div>
                                                    <h4 class="font-bold text-brand-dark">{{ $review->transaction->buyer->user->name ?? 'Anonymous' }}</h4>
                                                    <div class="flex items-center gap-2 text-xs text-gray-400">
                                                        <span>{{ $review->created_at->diffForHumans() }}</span>
                                                        <span>•</span>
                                                        <span>Verified Purchase</span>
                                                    </div>
                                                </div>
                                                <div class="flex text-brand-orange">
                                                    @for($i = 1; $i <= 5; $i++)
                                                        <svg class="w-4 h-4 {{ $i <= $review->rating ? 'fill-current' : 'text-gray-300' }}" viewBox="0 0 20 20" fill="currentColor">
                                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                                        </svg>
                                                    @endfor
                                                </div>
                                            </div>
                                            <p class="text-gray-600 leading-relaxed">{{ $review->review }}</p>
                                        </div>
                                    </div>
                                @empty
                                    <div class="text-center py-12">
                                        <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4 text-gray-400">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-8 h-8">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 8.25h9m-9 3H12m-9.75 1.51c0 1.6 1.123 2.994 2.707 3.227 1.129.166 2.27.293 3.423.379.35.026.67.21.865.501L12 21l2.755-4.133a1.14 1.14 0 01.865-.501 48.172 48.172 0 003.423-.379c1.584-.233 2.707-1.626 2.707-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0012 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018z" />
                                            </svg>
                                        </div>
                                        <h3 class="text-lg font-bold text-brand-dark mb-2">No Reviews Yet</h3>
                                        <p class="text-gray-500">Be the first to review this product!</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right: Related Books -->
                <div class="lg:col-span-4">
                    <div class="bg-white rounded-3xl shadow-sm p-6">
                        <h3 class="font-bold text-xl text-brand-dark mb-6">Related Books</h3>
                        <div class="space-y-6">
                            @foreach($relatedProducts->take(4) as $related)
                                <div class="flex gap-4 group">
                                    <div class="w-20 h-28 flex-shrink-0 bg-gray-100 rounded-lg overflow-hidden">
                                        @if($related->productImages->count() > 0)
                                            <img src="{{ $related->productImages->first()->image_url }}" alt="{{ $related->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform">
                                        @endif
                                    </div>
                                    <div class="flex flex-col justify-between py-1">
                                        <div>
                                            <h4 class="font-bold text-brand-dark leading-tight mb-1">
                                                <a href="{{ route('products.show', ['store' => $related->store->slug, 'product' => $related->slug]) }}" class="hover:text-brand-orange transition-colors">{{ $related->name }}</a>
                                            </h4>
                                            <p class="text-xs text-brand-orange uppercase font-bold tracking-wider mb-2">{{ $related->productCategory->name ?? 'Category' }}</p>
                                            <p class="font-bold text-brand-dark">{{ $related->formatted_price }}</p>
                                        </div>
                                        @auth
                                            <button onclick="addToCart({{ $related->id }})" class="text-xs font-bold text-brand-orange border border-brand-orange rounded-lg px-3 py-1.5 hover:bg-brand-orange hover:text-white transition-colors flex items-center gap-1 w-max">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-3 h-3">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z" />
                                                </svg>
                                                Add to cart
                                            </button>
                                        @else
                                            <a href="{{ route('login') }}" class="text-xs font-bold text-brand-orange border border-brand-orange rounded-lg px-3 py-1.5 hover:bg-brand-orange hover:text-white transition-colors flex items-center gap-1 w-max">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-3 h-3">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z" />
                                                </svg>
                                                Add to cart
                                            </a>
                                        @endauth
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const decrementBtn = document.getElementById('decrement-button');
            const incrementBtn = document.getElementById('increment-button');
            const quantityInput = document.getElementById('quantity-input');

            if (decrementBtn && incrementBtn && quantityInput) {
                decrementBtn.addEventListener('click', function() {
                    let currentValue = parseInt(quantityInput.value);
                    if (currentValue > 1) {
                        quantityInput.value = currentValue - 1;
                    }
                });

                incrementBtn.addEventListener('click', function() {
                    let currentValue = parseInt(quantityInput.value);
                    let maxValue = parseInt(quantityInput.getAttribute('max'));
                    if (currentValue < maxValue) {
                        quantityInput.value = currentValue + 1;
                    }
                });
            }
        });
    </script>
</x-store-layout>
