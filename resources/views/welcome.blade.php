<x-store-layout>
    <!-- Hero Section -->
    <section class="relative bg-white overflow-hidden">
        <div class="max-w-7xl mx-auto">
            <div class="relative z-10 pb-8 bg-white sm:pb-16 md:pb-20 lg:max-w-2xl lg:w-full lg:pb-28 xl:pb-32">
                <main class="mt-10 mx-auto max-w-7xl px-4 sm:mt-12 sm:px-6 md:mt-16 lg:mt-20 lg:px-8 xl:mt-28">
                    <div class="sm:text-center lg:text-left">
                        <span class="text-brand-orange font-bold tracking-widest uppercase text-sm mb-4 block">Best Seller</span>
                        <h1 class="text-4xl tracking-tight font-extrabold text-brand-dark sm:text-5xl md:text-6xl font-serif">
                            <span class="block xl:inline">Think and Grow Rich</span>
                        </h1>
                        <p class="mt-2 text-base text-gray-500 sm:mt-5 sm:text-lg sm:max-w-xl sm:mx-auto md:mt-5 md:text-xl lg:mx-0">
                            Napoleon Hill &nbsp; | &nbsp; Business & Strategy
                        </p>
                        <p class="mt-4 text-gray-500 text-sm leading-relaxed max-w-lg">
                            Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris.
                        </p>
                        
                        <div class="mt-6 flex items-center gap-4">
                            <span class="text-3xl font-bold text-brand-dark">$9.5</span>
                            <span class="text-lg text-gray-400 line-through">$12.0</span>
                            <span class="px-2 py-1 bg-pink-100 text-pink-600 text-xs font-bold rounded">-20%</span>
                        </div>

                        <div class="mt-8 flex gap-4 sm:justify-center lg:justify-start">
                            <div class="rounded-md shadow">
                                <a href="#" class="w-full flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-md text-white bg-brand-dark hover:bg-brand-orange transition-colors md:py-4 md:text-lg md:px-10">
                                    Buy Now
                                </a>
                            </div>
                            <div class="rounded-md shadow">
                                <a href="#" class="w-full flex items-center justify-center px-8 py-3 border border-gray-300 text-base font-medium rounded-md text-brand-dark bg-white hover:bg-gray-50 md:py-4 md:text-lg md:px-10">
                                    See Details
                                </a>
                            </div>
                        </div>
                        
                        <!-- Partners (Mobile/Desktop inline) -->
                        <div class="mt-10">
                            <p class="text-sm text-gray-400 mb-4">Our partner</p>
                            <div class="flex gap-6 opacity-50 grayscale">
                                <span class="font-bold text-xl">Highlow</span>
                                <span class="font-bold text-xl">emajine</span>
                                <span class="font-bold text-xl">GlowUP</span>
                            </div>
                        </div>
                    </div>
                </main>
            </div>
        </div>
        <div class="lg:absolute lg:inset-y-0 lg:right-0 lg:w-1/2 bg-brand-orange/10 rounded-bl-[100px] overflow-hidden">
            <div class="relative h-56 w-full sm:h-72 md:h-96 lg:h-full flex items-center justify-center">
                 <!-- Decorative Circles -->
                 <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-[500px] h-[500px] bg-brand-orange rounded-full opacity-20 blur-3xl"></div>
                 <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-[300px] h-[300px] bg-brand-orange rounded-full opacity-40"></div>
                 
                 <!-- Hero Image Placeholder -->
                 <img class="relative z-10 h-full w-full object-cover object-center lg:object-contain p-10" src="https://images.unsplash.com/photo-1544716278-ca5e3f4abd8c?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=1000&q=80" alt="Book Cover">
                 
                 <!-- Floating Card -->
                 <div class="absolute bottom-20 right-20 bg-white p-4 rounded-xl shadow-lg z-20 hidden lg:flex items-center gap-4 max-w-xs">
                    <div class="w-12 h-16 bg-brand-dark rounded shadow-sm flex-shrink-0"></div>
                    <div>
                        <h4 class="font-bold text-sm text-brand-dark">Think and Grow Rich</h4>
                        <p class="text-xs text-gray-500">By Napoleon Hill</p>
                        <div class="flex items-center gap-1 mt-1">
                            <span class="text-brand-orange text-xs">★★★★★</span>
                        </div>
                        <div class="font-bold text-brand-dark mt-1">$9.5</div>
                    </div>
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
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.
                </p>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-6">
                <!-- Product Card Loop -->
                @foreach($products as $product)
                <div class="bg-white rounded-2xl shadow-sm hover:shadow-md transition-shadow overflow-hidden group">
                    <div class="relative aspect-[2/3] bg-gray-200 overflow-hidden">
                        <img src="{{ $product->image_url ?? 'https://via.placeholder.com/300x450' }}" alt="{{ $product->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                        <div class="absolute top-2 right-2">
                            @auth
                                @php
                                    $inWishlist = \App\Models\Wishlist::where('user_id', Auth::id())->where('product_id', $product->id)->exists();
                                @endphp
                                <button onclick="toggleWishlist(this, {{ $product->id }})" 
                                        class="p-2 bg-white/80 backdrop-blur-sm rounded-full transition-colors {{ $inWishlist ? 'text-red-500' : 'text-gray-400 hover:text-red-500' }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="{{ $inWishlist ? 'currentColor' : 'none' }}" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z" />
                                    </svg>
                                </button>
                            @else
                                <a href="{{ route('login') }}" class="p-2 bg-white/80 backdrop-blur-sm rounded-full text-gray-400 hover:text-red-500 transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z" />
                                    </svg>
                                </a>
                            @endauth
                        </div>
                    </div>
                    <div class="p-4">
                        <h3 class="font-bold text-brand-dark truncate">{{ $product->name }}</h3>
                        <p class="text-xs text-gray-500 mb-2">{{ $product->author ?? 'Unknown Author' }}</p>
                        <div class="flex items-center gap-1 mb-3">
                            <span class="text-brand-orange text-xs">★★★★★</span>
                            <span class="text-xs text-gray-400">(4.0)</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="font-bold text-brand-dark">${{ number_format($product->price, 2) }}</span>
                            @auth
                                <button onclick="addToCart({{ $product->id }})" class="p-2 bg-brand-dark text-white rounded-lg hover:bg-brand-orange transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z" />
                                    </svg>
                                </button>
                            @else
                                <a href="{{ route('login') }}" class="p-2 bg-brand-dark text-white rounded-lg hover:bg-brand-orange transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z" />
                                    </svg>
                                </a>
                            @endauth
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            
            <div class="mt-12 text-center">
                <a href="{{ route('products.index') }}" class="inline-block px-8 py-3 border border-brand-dark text-brand-dark font-medium rounded-md hover:bg-brand-dark hover:text-white transition-colors">
                    View All Products
                </a>
            </div>
        </div>
    </section>

</x-store-layout>
