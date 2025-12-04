<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-serif font-bold text-2xl text-brand-dark leading-tight">
                {{ __('Product Management') }}
            </h2>
            <a href="{{ route('seller.products.create') }}" class="inline-flex items-center px-6 py-3 bg-brand-orange border border-transparent rounded-full font-bold text-sm text-white uppercase tracking-widest hover:bg-brand-dark transition-colors shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                Add Product
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-8 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl flex items-center shadow-sm">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    {{ session('success') }}
                </div>
            @endif

            @if($products->isEmpty())
                <div class="bg-white overflow-hidden shadow-lg rounded-2xl border border-gray-100 p-12 text-center">
                    <svg class="mx-auto h-16 w-16 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">No products yet</h3>
                    <p class="text-gray-500 mb-6">Start selling by adding your first product.</p>
                    <a href="{{ route('seller.products.create') }}" class="inline-flex items-center px-6 py-3 bg-brand-dark border border-transparent rounded-xl font-bold text-white hover:bg-brand-orange transition-colors">
                        Add First Product
                    </a>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach($products as $product)
                        <div class="bg-white rounded-2xl overflow-hidden shadow-lg border border-gray-100 hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                            <div class="relative">
                                @if($product->productImages->isNotEmpty())
                                    <img src="{{ Str::startsWith($product->productImages->first()->image, 'http') ? $product->productImages->first()->image : asset('storage/' . $product->productImages->first()->image) }}" alt="{{ $product->name }}" class="w-full h-56 object-cover">
                                @else
                                    <div class="w-full h-56 bg-gray-100 flex items-center justify-center">
                                        <svg class="w-12 h-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    </div>
                                @endif
                                <div class="absolute top-4 right-4 bg-white/90 backdrop-blur-sm px-3 py-1 rounded-full text-xs font-bold text-brand-dark shadow-sm">
                                    Stock: {{ $product->stock }}
                                </div>
                            </div>
                            
                            <div class="p-6">
                                <div class="mb-4">
                                    <p class="text-xs font-bold text-brand-orange uppercase tracking-wider mb-1">{{ $product->productCategory->name }}</p>
                                    <h3 class="font-serif font-bold text-xl text-brand-dark mb-2 line-clamp-1">{{ $product->name }}</h3>
                                    <p class="text-2xl font-bold text-gray-900">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                                </div>
                                
                                <div class="flex gap-3 pt-4 border-t border-gray-100">
                                    @if($product->slug)
                                        <a href="{{ route('seller.products.edit', $product) }}" class="flex-1 text-center px-4 py-2 bg-gray-100 text-gray-700 font-bold rounded-xl hover:bg-brand-dark hover:text-white transition-colors">
                                            Edit
                                        </a>
                                    @else
                                        <button disabled class="flex-1 text-center px-4 py-2 bg-gray-100 text-gray-400 font-bold rounded-xl cursor-not-allowed" title="Product data incomplete (missing slug)">
                                            Edit
                                        </button>
                                    @endif
                                    @if($product->slug)
                                        <button type="button" 
                                                onclick="document.getElementById('delete-product-{{ $product->id }}').classList.remove('hidden')"
                                                class="flex-1 px-4 py-2 bg-red-50 text-red-600 font-bold rounded-xl hover:bg-red-600 hover:text-white transition-colors">
                                            Delete
                                        </button>
                                    @else
                                        <button disabled class="flex-1 px-4 py-2 bg-red-50 text-red-300 font-bold rounded-xl cursor-not-allowed" title="Product data incomplete (missing slug)">
                                            Delete
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-8">
                    {{ $products->links() }}
                </div>
            @endif
        </div>
    </div>

    <!-- Delete Product Modals -->
    @foreach($products as $product)
        @if($product->slug)
            <x-confirmation-modal 
                id="delete-product-{{ $product->id }}"
                type="danger" 
                title="Delete Product" 
                message="Are you sure you want to delete {{ $product->name }}? This action cannot be undone."
                confirmText="Yes, Delete"
                cancelText="Cancel">
            </x-confirmation-modal>
            <form id="delete-product-{{ $product->id }}-form" method="POST" action="{{ route('seller.products.destroy', $product) }}" class="hidden">
                @csrf
                @method('DELETE')
            </form>
        @endif
    @endforeach
</x-app-layout>
