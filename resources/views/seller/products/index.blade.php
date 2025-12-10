<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class=" font-bold text-2xl text-brand-dark leading-tight">
                {{ __('Manajemen Produk') }}
            </h2>
            <a href="{{ route('seller.products.create') }}" class="inline-flex items-center px-6 py-3 bg-brand-orange border border-transparent rounded-full font-bold text-sm text-white uppercase tracking-widest hover:bg-brand-dark transition-colors shadow-sm hover:shadow-md transform hover:-translate-y-0.5">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                Tambah Produk
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-8 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-2xl flex items-center shadow-sm">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    {{ session('success') }}
                </div>
            @endif

            @if($products->isEmpty())
                <div class="bg-white overflow-hidden shadow-sm rounded-3xl border border-gray-100 p-12 text-center">
                    <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Belum ada produk</h3>
                    <p class="text-gray-500 mb-8">Mulai berjualan dengan menambahkan produk pertama Anda.</p>
                    <a href="{{ route('seller.products.create') }}" class="inline-flex items-center px-6 py-3 bg-brand-dark border border-transparent rounded-xl font-bold text-white hover:bg-brand-orange transition-colors shadow-sm hover:shadow-md">
                        Tambah Produk Pertama
                    </a>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach($products as $product)
                        <div class="bg-white rounded-3xl overflow-hidden shadow-sm border border-gray-100 hover:shadow-md transition-all duration-300 transform hover:-translate-y-1 block h-full flex flex-col">
                            <div class="relative">
                                @if($product->productImages->isNotEmpty())
                                    <img src="{{ $product->productImages->first()->image_url }}" alt="{{ $product->name }}" class="w-full h-56 object-cover">
                                @else
                                    <div class="w-full h-56 bg-gray-50 flex items-center justify-center">
                                        <svg class="w-12 h-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    </div>
                                @endif
                                <div class="absolute top-4 right-4 bg-white/95 backdrop-blur-sm px-3 py-1.5 rounded-full text-xs font-bold text-brand-dark shadow-sm border border-gray-100">
                                    Stok: {{ $product->stock }}
                                </div>
                            </div>
                            
                            <div class="p-6 flex flex-col flex-1">
                                <div class="mb-4 flex-1">
                                    <p class="text-xs font-bold text-brand-orange uppercase tracking-wider mb-2">{{ $product->productCategory->name }}</p>
                                    <h3 class="font-bold text-lg text-brand-dark mb-2 line-clamp-2 leading-snug">{{ $product->name }}</h3>
                                    <p class="text-xl font-bold text-gray-900">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                                </div>
                                
                                <div class="flex gap-3 pt-5 border-t border-gray-50 mt-auto">
                                    @if($product->slug)
                                        <a href="{{ route('seller.products.edit', $product) }}" class="flex-1 text-center px-4 py-2.5 bg-white border border-gray-200 text-gray-700 font-bold rounded-xl hover:bg-brand-dark hover:text-white hover:border-brand-dark transition-all shadow-sm">
                                            Edit
                                        </a>
                                    @else
                                        <button disabled class="flex-1 text-center px-4 py-2.5 bg-gray-50 text-gray-400 font-bold rounded-xl cursor-not-allowed border border-gray-100">
                                            Edit
                                        </button>
                                    @endif
                                    @if($product->slug)
                                        <button type="button" 
                                                onclick="document.getElementById('delete-product-{{ $product->id }}').classList.remove('hidden')"
                                                class="flex-1 px-4 py-2.5 bg-red-50 border border-red-50 text-red-600 font-bold rounded-xl hover:bg-red-600 hover:text-white hover:border-red-600 transition-all">
                                            Hapus
                                        </button>
                                    @else
                                        <button disabled class="flex-1 px-4 py-2.5 bg-gray-50 border border-gray-100 text-gray-300 font-bold rounded-xl cursor-not-allowed">
                                            Hapus
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-10">
                    {{ $products->links() }}
                </div>
            @endif
        </div>
    </div>


    @foreach($products as $product)
        @if($product->slug)
            <x-confirmation-modal 
                id="delete-product-{{ $product->id }}"
                type="danger" 
                title="Hapus Produk" 
                message="Apakah Anda yakin ingin menghapus {{ $product->name }}? Tindakan ini tidak dapat dibatalkan."
                confirmText="Ya, Hapus"
                cancelText="Batal">
            </x-confirmation-modal>
            <form id="delete-product-{{ $product->id }}-form" method="POST" action="{{ route('seller.products.destroy', $product) }}" class="hidden">
                @csrf
                @method('DELETE')
            </form>
        @endif
    @endforeach
</x-app-layout>
