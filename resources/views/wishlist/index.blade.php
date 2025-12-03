<x-store-layout>
    <div class="bg-brand-gray min-h-screen py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-3xl font-serif font-bold text-brand-dark mb-8">My Wishlist</h1>

            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-6" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            @if($wishlists->count() > 0)
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                    @foreach($wishlists as $item)
                        <div class="bg-white rounded-xl shadow-sm hover:shadow-md transition-shadow overflow-hidden group">
                            <div class="relative aspect-[2/3] bg-gray-200 overflow-hidden">
                                @if($item->product->productImages->count() > 0)
                                    <img src="{{ $item->product->productImages->first()->image_url }}" alt="{{ $item->product->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-gray-400 bg-gray-100">
                                        <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                    </div>
                                @endif
                                <div class="absolute top-2 right-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                    <button onclick="removeFromWishlist(this, {{ $item->id }})" class="p-2 bg-white/90 backdrop-blur-sm rounded-full text-gray-400 hover:text-red-500 transition-colors shadow-sm">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                            <div class="p-4">
                                <div class="text-xs text-brand-orange font-medium mb-1">{{ $item->product->category->name ?? 'Uncategorized' }}</div>
                                <h3 class="font-bold text-brand-dark truncate mb-1">
                                    <a href="{{ route('products.show', ['store' => $item->product->store->slug, 'product' => $item->product->slug]) }}">{{ $item->product->name }}</a>
                                </h3>
                                <p class="text-xs text-gray-500 mb-3">{{ $item->product->store->name }}</p>
                                <div class="flex items-center justify-between">
                                    <span class="font-bold text-brand-dark">{{ $item->product->formatted_price }}</span>
                                    <form action="{{ route('cart.add') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="product_id" value="{{ $item->product->id }}">
                                        <input type="hidden" name="quantity" value="1">
                                        <button type="submit" class="p-2 bg-brand-dark text-white rounded-lg hover:bg-brand-orange transition-colors">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z" />
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="mt-8">
                    {{ $wishlists->links() }}
                </div>
            @else
                <div class="bg-white rounded-xl shadow-sm p-16 text-center">
                    <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="h-10 w-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900">Your wishlist is empty</h3>
                    <p class="mt-2 text-gray-500">Start adding books you love to your wishlist!</p>
                    <a href="{{ route('products.index') }}" class="mt-6 inline-block text-brand-orange font-medium hover:underline">Browse Products</a>
                </div>
            @endif
        </div>
    </div>
</x-store-layout>
