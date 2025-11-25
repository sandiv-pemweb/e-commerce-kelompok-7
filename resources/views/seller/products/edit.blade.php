<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Produk: ') . $product->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                    {{ session('success') }}
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Product Form -->
                <div class="lg:col-span-2">
                    <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                        <div class="p-6 text-gray-900">
                            <h3 class="text-lg font-semibold mb-4">Informasi Produk</h3>
                            
                            <form method="POST" action="{{ route('seller.products.update', $product) }}" class="space-y-6">
                                @csrf
                                @method('PATCH')

                                <div>
                                    <label for="product_category_id" class="block font-medium text-sm text-gray-700">Kategori</label>
                                    <select id="product_category_id" name="product_category_id" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}" {{ old('product_category_id', $product->product_category_id) == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('product_category_id')
                                        <span class="text-red-600 text-sm">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div>
                                    <label for="name" class="block font-medium text-sm text-gray-700">Nama Produk</label>
                                    <input id="name" type="text" name="name" value="{{ old('name', $product->name) }}" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                    @error('name')
                                        <span class="text-red-600 text-sm">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div>
                                    <label for="description" class="block font-medium text-sm text-gray-700">Deskripsi</label>
                                    <textarea id="description" name="description" rows="4" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>{{ old('description', $product->description) }}</textarea>
                                    @error('description')
                                        <span class="text-red-600 text-sm">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div>
                                    <label for="price" class="block font-medium text-sm text-gray-700">Harga</label>
                                    <input id="price" type="number" name="price" value="{{ old('price', $product->price) }}" min="0" step="100" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                    @error('price')
                                        <span class="text-red-600 text-sm">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div>
                                    <label for="stock" class="block font-medium text-sm text-gray-700">Stok</label>
                                    <input id="stock" type="number" name="stock" value="{{ old('stock', $product->stock) }}" min="0" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                    @error('stock')
                                        <span class="text-red-600 text-sm">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div>
                                    <label for="weight" class="block font-medium text-sm text-gray-700">Berat (gram)</label>
                                    <input id="weight" type="number" name="weight" value="{{ old('weight', $product->weight) }}" min="0" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                    @error('weight')
                                        <span class="text-red-600 text-sm">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div>
                                    <button type="submit" class="w-full px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">Perbarui Produk</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Product Images -->
                <div>
                    <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                        <div class="p-6 text-gray-900">
                            <h3 class="text-lg font-semibold mb-4">Gambar Produk</h3>
                            
                            <!-- Upload Form -->
                            <form method="POST" action="{{ route('seller.product-images.store', $product) }}" enctype="multipart/form-data" class="mb-6">
                                @csrf
                                <div>
                                    <label for="image" class="block font-medium text-sm text-gray-700 mb-2">Tambah Gambar</label>
                                    <input id="image" type="file" name="image" accept="image/*" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100" required>
                                    @error('image')
                                        <span class="text-red-600 text-sm">{{ $message }}</span>
                                    @enderror
                                </div>
                                <button type="submit" class="mt-2 w-full px-4 py-2 bg-indigo-600 text-white text-sm rounded hover:bg-indigo-700">Upload</button>
                            </form>

                            <!-- Image List -->
                            <div class="space-y-3">
                                @forelse($product->productImages as $image)
                                    <div class="relative group">
                                        <img src="{{ Str::startsWith($image->image, 'http') ? $image->image : asset('storage/' . $image->image) }}" alt="Product Image" class="w-full h-32 object-cover rounded">
                                        <button type="button"
                                                onclick="document.getElementById('delete-image-{{ $image->id }}').classList.remove('hidden')"
                                                class="absolute top-2 right-2 bg-red-600 text-white p-2 rounded-full shadow hover:bg-red-700 opacity-0 group-hover:opacity-100 transition">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                        </button>
                                    </div>
                                @empty
                                    <p class="text-sm text-gray-500 text-center py-4">Belum ada gambar</p>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Image Modals -->
    @foreach($product->productImages as $image)
    <x-confirmation-modal 
        id="delete-image-{{ $image->id }}"
        type="danger" 
        title="Hapus Gambar Produk" 
        message="Apakah Anda yakin ingin menghapus gambar ini? Tindakan ini tidak dapat dibatalkan."
        confirmText="Ya, Hapus"
        cancelText="Batal">
    </x-confirmation-modal>
    <form id="delete-image-{{ $image->id }}-form" method="POST" action="{{ route('seller.product-images.destroy', [$product, $image]) }}" class="hidden">
        @csrf
        @method('DELETE')
    </form>
    @endforeach
</x-app-layout>
