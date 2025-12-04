<x-store-layout>
    <!-- Hero Section -->
    <section class="relative bg-white overflow-hidden flex flex-col-reverse lg:block">
        <div class="max-w-7xl mx-auto">
            <div class="relative z-10 pb-8 bg-white sm:pb-16 md:pb-20 lg:max-w-2xl lg:w-full lg:pb-28 xl:pb-32">
                <main class="mt-10 mx-auto max-w-7xl px-4 sm:mt-12 sm:px-6 md:mt-16 lg:mt-20 lg:px-8 xl:mt-28">
                    <div class="sm:text-center lg:text-left">
                        <div
                            class="inline-flex items-center px-3 py-1 rounded-full border border-brand-orange/30 bg-brand-orange/5 text-brand-orange text-xs font-bold tracking-widest uppercase mb-6">
                            <span class="w-2 h-2 bg-brand-orange rounded-full mr-2"></span>
                            Best Seller Book
                        </div>
                        @if($heroProduct)
                            <h1
                                class="text-4xl tracking-tight font-extrabold text-brand-dark sm:text-5xl md:text-6xl font-serif leading-tight">
                                <span class="block xl:inline">{{ $heroProduct->name }}</span>
                            </h1>
                            <p class="mt-4 text-lg text-brand-dark font-medium">
                                {{ $heroProduct->author }} &nbsp; <span class="text-gray-300">|</span> &nbsp;
                                {{ $heroProduct->productCategory->name ?? 'General' }}
                            </p>
                            <p class="mt-4 text-gray-500 text-base leading-relaxed max-w-lg">
                                {{ Str::limit($heroProduct->description, 200) }}
                            </p>

                            <div class="mt-8 flex flex-wrap items-center gap-6">
                                <div>
                                    @if($heroProduct->discount_price > 0)
                                        <span
                                            class="text-2xl md:text-4xl font-bold text-brand-dark">{{ $heroProduct->formatted_discount_price }}</span>
                                        <div class="flex items-center gap-2 mt-1">
                                            <span
                                                class="text-md text-gray-400 line-through">{{ $heroProduct->formatted_price }}</span>
                                            <span
                                                class="px-2 py-0.5 bg-red-100 text-red-600 text-xs font-bold rounded">-{{ $heroProduct->discount_percentage }}%</span>
                                        </div>
                                    @else
                                        <span
                                            class="text-2xl md:text-4xl font-bold text-brand-dark">{{ $heroProduct->formatted_price }}</span>
                                    @endif
                                </div>
                                <div class="h-10 w-px bg-gray-200"></div>
                                <div class="flex flex-col">
                                    <div class="flex text-brand-orange text-sm">
                                        @php
                                            $rating = $heroProduct->productReviews->avg('rating') ?? 0;
                                        @endphp
                                        @for($i = 1; $i <= 5; $i++)
                                            <svg class="w-5 h-5 {{ $i <= round($rating) ? 'fill-current' : 'text-gray-300' }}"
                                                viewBox="0 0 20 20" fill="currentColor">
                                                <path
                                                    d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                            </svg>
                                        @endfor
                                    </div>
                                    <div class="flex items-center gap-3 text-xs text-gray-500 mt-1">
                                        <span>{{ number_format($rating, 1) }} ({{ $heroProduct->productReviews->count() }}
                                            Reviews)</span>
                                        <span>&bull;</span>
                                        <span>{{ $heroProduct->wishlist_count }}+ Likes</span>
                                        <span>&bull;</span>
                                        <span>{{ $heroProduct->sold_count }} Sold</span>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-10 flex gap-4 sm:justify-center lg:justify-start">
                                <div class="rounded-xl shadow-lg shadow-brand-orange/20">
                                    <a href="{{ route('products.show', ['store' => $heroProduct->store->slug, 'product' => $heroProduct->slug]) }}"
                                        class="w-full flex items-center justify-center px-8 py-4 border border-transparent text-base font-bold rounded-xl text-white bg-brand-dark hover:bg-brand-orange transition-all transform hover:-translate-y-1 md:text-lg md:px-10">
                                        Buy Now
                                    </a>
                                </div>
                                <div>
                                    <a href="{{ route('products.show', ['store' => $heroProduct->store->slug, 'product' => $heroProduct->slug]) }}"
                                        class="w-full flex items-center justify-center px-8 py-4 border border-gray-200 text-base font-bold rounded-xl text-brand-dark bg-white hover:bg-gray-50 hover:border-gray-300 transition-all md:text-lg md:px-10">
                                        See Details
                                    </a>
                                </div>
                            </div>
                        @else
                            <h1
                                class="text-4xl tracking-tight font-extrabold text-brand-dark sm:text-5xl md:text-6xl font-serif">
                                <span class="block xl:inline">BookLand Store</span>
                            </h1>
                            <p class="mt-4 text-gray-500 text-base leading-relaxed max-w-lg">
                                Discover the best books for your journey.
                            </p>
                        @endif

                        <!-- Partners -->
                        <div class="mt-12 pt-8 border-t border-gray-100">
                            <p class="text-sm text-gray-400 mb-4 font-medium">Our Trusted Partners</p>
                            <div class="flex flex-nowrap overflow-x-auto gap-8 md:gap-12 items-center pb-2 no-scrollbar">
                                <a href="#" class="flex-shrink-0 group transition-all duration-300 opacity-70 hover:opacity-100">
                                    <img src="https://www.kompasgramedia.com/assets/photo/2018/09/06/822036325.png" 
                                         alt="Gramedia" 
                                         class="h-6 md:h-8 w-auto object-contain grayscale group-hover:grayscale-0 transition-all duration-300">
                                </a>
                                <a href="#" class="flex-shrink-0 group transition-all duration-300 opacity-70 hover:opacity-100">
                                    <img src="https://emir.co.id/wp-content/uploads/2016/11/RevisiOpsiLogo_10feb22-fix.png" 
                                         alt="Erlangga" 
                                         class="h-6 md:h-8 w-auto object-contain grayscale group-hover:grayscale-0 transition-all duration-300">
                                </a>
                                <a href="#" class="flex-shrink-0 group transition-all duration-300 opacity-70 hover:opacity-100">
                                    <img src="https://static.mizanstore.com/f/img/penerbit/2f8f39438e69753f6e600900d60a3b5a.png" 
                                         alt="Mizan" 
                                         class="h-6 md:h-8 w-auto object-contain grayscale group-hover:grayscale-0 transition-all duration-300">
                                </a>
                            </div>
                        </div>
                    </div>
                </main>
            </div>
        </div>
        <div class="lg:absolute lg:inset-y-0 lg:right-0 lg:w-1/2 bg-white mb-8 lg:mb-0">
            <div class="relative h-[30rem] w-full sm:h-[32rem] md:h-[40rem] lg:h-full flex items-center justify-center">
                <!-- Decorative Elements Removed -->

                <!-- Hero Image -->
                <div class="relative z-10 h-full w-full flex items-center justify-center p-4 pt-12 sm:p-8 lg:p-16">
                    @if($heroProduct && $heroProduct->productImages->count() > 0)
                        <img class="max-h-full w-auto object-contain rounded-2xl shadow-2xl transform hover:scale-105 transition-transform duration-700"
                            src="{{ $heroProduct->productImages->first()->image_url }}" alt="{{ $heroProduct->name }}">
                    @else
                        <img class="max-h-full w-auto object-contain rounded-2xl shadow-2xl transform hover:scale-105 transition-transform duration-700"
                            src="https://images.unsplash.com/photo-1544716278-ca5e3f4abd8c?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=1000&q=80"
                            alt="Book Cover">
                    @endif
                </div>

                <!-- Floating Card 1 -->
                <div class="absolute top-20 left-4 scale-75 origin-top-left lg:origin-center lg:scale-100 lg:top-24 lg:left-10 bg-white p-4 rounded-2xl shadow-[0_20px_50px_rgba(0,0,0,0.1)] z-20 flex items-center gap-4 max-w-xs animate-bounce"
                    style="animation-duration: 3s;">
                    <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center text-green-600">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                            stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <h4 class="font-bold text-sm text-brand-dark">Best Seller</h4>
                        <p class="text-xs text-gray-500">#1 in {{ $heroProduct->productCategory->name ?? 'General' }}
                        </p>
                    </div>
                </div>

                <!-- Floating Card 2 -->
                <div class="absolute top-32 right-4 scale-75 origin-top-right lg:origin-center lg:scale-100 lg:top-40 lg:right-20 bg-white p-3 rounded-2xl shadow-[0_20px_50px_rgba(0,0,0,0.1)] z-20 flex flex-col items-center gap-1 animate-bounce"
                    style="animation-duration: 4s; animation-delay: 1s;">
                    <div class="flex -space-x-2">
                        <img class="w-8 h-8 rounded-full border-2 border-white"
                            src="https://ui-avatars.com/api/?name=John+Doe&background=random" alt="">
                        <img class="w-8 h-8 rounded-full border-2 border-white"
                            src="https://ui-avatars.com/api/?name=Jane+Doe&background=random" alt="">
                        <img class="w-8 h-8 rounded-full border-2 border-white"
                            src="https://ui-avatars.com/api/?name=Bob+Smith&background=random" alt="">
                    </div>
                    <p class="text-xs font-bold text-brand-dark mt-1">
                        {{ $heroProduct->transaction_details_count ?? 0 }}+ Sold
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Popular Section -->
    <section class="py-16 bg-brand-gray">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-serif font-bold text-brand-dark">Popular This Week</h2>
                <p class="mt-4 text-gray-500 max-w-2xl mx-auto">
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore
                    et dolore magna aliqua.
                </p>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-6">
                <!-- Product Card Loop -->
                @foreach($featuredProducts as $product)
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
                                @auth
                                    @php
                                        $inWishlist = \App\Models\Wishlist::where('user_id', Auth::id())->where('product_id', $product->id)->exists();
                                    @endphp
                                    <button onclick="toggleWishlist(this, {{ $product->id }})"
                                        class="p-2 bg-white/90 backdrop-blur-sm rounded-full transition-colors shadow-sm {{ $inWishlist ? 'text-red-500' : 'text-gray-400 hover:text-red-500' }}">
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                            fill="{{ $inWishlist ? 'currentColor' : 'none' }}" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z" />
                                        </svg>
                                    </button>
                                @else
                                    <a href="{{ route('login') }}"
                                        class="p-2 bg-white/90 backdrop-blur-sm rounded-full text-gray-400 hover:text-red-500 transition-colors shadow-sm">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z" />
                                        </svg>
                                    </a>
                                @endauth
                            </div>
                        </div>
                        <div class="p-4">
                            <h3 class="font-bold text-brand-dark truncate">
                                <a
                                    href="{{ route('products.show', ['store' => $product->store->slug, 'product' => $product->slug]) }}">{{ $product->name }}</a>
                            </h3>
                            <p class="text-xs text-gray-500 mb-2">{{ $product->store->name ?? 'Unknown Store' }}</p>
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
                                <span class="text-xs text-gray-300 mx-1">&bull;</span>
                                <span class="text-xs text-gray-500">{{ $product->sold_count }} Sold</span>
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

            <div class="mt-12 text-center">
                <a href="{{ route('products.index') }}"
                    class="inline-block px-8 py-3 border border-brand-dark text-brand-dark font-medium rounded-md hover:bg-brand-dark hover:text-white transition-colors">
                    View All Products
                </a>
            </div>
        </div>
    </section>

</x-store-layout>