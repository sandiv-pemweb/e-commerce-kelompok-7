<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Manajemen Produk') }}
            </h2>
            <a href="{{ route('seller.products.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700">
                Tambah Produk
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if (session('success'))
                        <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if($products->isEmpty())
                        <p class="text-center text-gray-500 py-8">Belum ada produk. <a href="{{ route('seller.products.create') }}" class="text-indigo-600 hover:underline">Tambahkan produk pertama Anda</a></p>
                    @else
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach($products as $product)
                                <div class="border rounded-lg overflow-hidden shadow hover:shadow-lg transition">
                                    @if($product->productImages->isNotEmpty())
                                        <img src="{{ Str::startsWith($product->productImages->first()->image, 'http') ? $product->productImages->first()->image : asset('storage/' . $product->productImages->first()->image) }}" alt="{{ $product->name }}" class="w-full h-48 object-cover">
                                    @else
                                        <div class="w-full h-48 bg-gray-200 flex items-center justify-center">
                                            <span class="text-gray-400">Tidak ada gambar</span>
                                        </div>
                                    @endif
                                    
                                    <div class="p-4">
                                        <h3 class="font-semibold text-lg mb-2">{{ $product->name }}</h3>
                                        <p class="text-sm text-gray-600 mb-2">{{ $product->productCategory->name }}</p>
                                        <p class="text-indigo-600 font-bold text-lg mb-2">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                                        <p class="text-sm text-gray-500 mb-4">Stok: {{ $product->stock }}</p>
                                        
                                        <div class="flex gap-2">
                                            <a href="{{ route('seller.products.edit', $product) }}" class="flex-1 text-center px-3 py-2 bg-indigo-600 text-white text-sm rounded hover:bg-indigo-700">
                                                Edit
                                            </a>
                                            <button type="button" 
                                                    onclick="document.getElementById('delete-product-{{ $product->id }}').classList.remove('hidden')"
                                                    class="flex-1 px-3 py-2 bg-red-600 text-white text-sm rounded hover:bg-red-700">
                                                Hapus
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="mt-6">
                            {{ $products->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Product Modals -->
    @foreach($products as $product)
    <x-confirmation-modal 
        id="delete-product-{{ $product->id }}"
        type="danger" 
        title="Hapus Produk" 
        message="Apakah Anda yakin ingin menghapus produk {{ $product->name }}? Tindakan ini tidak dapat dibatalkan."
        confirmText="Ya, Hapus"
        cancelText="Batal">
    </x-confirmation-modal>
    <form id="delete-product-{{ $product->id }}-form" method="POST" action="{{ route('seller.products.destroy', $product) }}" class="hidden">
        @csrf
        @method('DELETE')
    </form>
    @endforeach
</x-app-layout>
