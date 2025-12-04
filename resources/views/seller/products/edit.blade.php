<x-app-layout>
    <x-slot name="header">
        <h2 class="font-serif font-bold text-2xl text-brand-dark leading-tight">
            {{ __('Edit Product: ') . $product->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            @if (session('success'))
                <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl flex items-center shadow-sm">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    {{ session('success') }}
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Product Form -->
                <div class="lg:col-span-2">
                    <div class="bg-white overflow-hidden shadow-lg rounded-2xl border border-gray-100">
                        <div class="p-8">
                            <h3 class="text-xl font-serif font-bold text-brand-dark mb-6 border-b border-gray-100 pb-4">Product Information</h3>
                            
                            <form method="POST" action="{{ route('seller.products.update', $product) }}" class="space-y-6">
                                @csrf
                                @method('PATCH')

                                <div>
                                    <label for="product_category_id" class="block font-bold text-sm text-gray-700 mb-2">Category</label>
                                    <select id="product_category_id" name="product_category_id" class="w-full border-gray-300 focus:border-brand-orange focus:ring-brand-orange rounded-xl shadow-sm" required>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}" {{ old('product_category_id', $product->product_category_id) == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('product_category_id')
                                        <span class="text-red-600 text-sm mt-1 block">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div>
                                    <label for="name" class="block font-bold text-sm text-gray-700 mb-2">Product Name</label>
                                    <input id="name" type="text" name="name" value="{{ old('name', $product->name) }}" class="w-full border-gray-300 focus:border-brand-orange focus:ring-brand-orange rounded-xl shadow-sm" required>
                                    @error('name')
                                        <span class="text-red-600 text-sm mt-1 block">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label for="author" class="block font-bold text-sm text-gray-700 mb-2">Author</label>
                                        <input id="author" type="text" name="author" value="{{ old('author', $product->author) }}" class="w-full border-gray-300 focus:border-brand-orange focus:ring-brand-orange rounded-xl shadow-sm" placeholder="e.g. J.K. Rowling">
                                        @error('author')
                                            <span class="text-red-600 text-sm mt-1 block">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="publisher" class="block font-bold text-sm text-gray-700 mb-2">Publisher</label>
                                        <input id="publisher" type="text" name="publisher" value="{{ old('publisher', $product->publisher) }}" class="w-full border-gray-300 focus:border-brand-orange focus:ring-brand-orange rounded-xl shadow-sm" placeholder="e.g. Bloomsbury">
                                        @error('publisher')
                                            <span class="text-red-600 text-sm mt-1 block">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label for="published_year" class="block font-bold text-sm text-gray-700 mb-2">Published Year</label>
                                        <input id="published_year" type="number" name="published_year" value="{{ old('published_year', $product->published_year) }}" min="1900" max="{{ date('Y') + 1 }}" class="w-full border-gray-300 focus:border-brand-orange focus:ring-brand-orange rounded-xl shadow-sm" placeholder="e.g. 2023">
                                        @error('published_year')
                                            <span class="text-red-600 text-sm mt-1 block">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="condition" class="block font-bold text-sm text-gray-700 mb-2">Condition</label>
                                        <select id="condition" name="condition" class="w-full border-gray-300 focus:border-brand-orange focus:ring-brand-orange rounded-xl shadow-sm" required>
                                            <option value="new" {{ old('condition', $product->condition) == 'new' ? 'selected' : '' }}>New</option>
                                            <option value="used" {{ old('condition', $product->condition) == 'used' ? 'selected' : '' }}>Used</option>
                                        </select>
                                        @error('condition')
                                            <span class="text-red-600 text-sm mt-1 block">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div>
                                    <label for="description" class="block font-bold text-sm text-gray-700 mb-2">Description</label>
                                    <textarea id="description" name="description" rows="4" class="w-full border-gray-300 focus:border-brand-orange focus:ring-brand-orange rounded-xl shadow-sm" required>{{ old('description', $product->description) }}</textarea>
                                    @error('description')
                                        <span class="text-red-600 text-sm mt-1 block">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label for="price" class="block font-bold text-sm text-gray-700 mb-2">Price (Rp)</label>
                                        <input id="price" type="number" name="price" value="{{ old('price', $product->price) }}" min="0" step="100" class="w-full border-gray-300 focus:border-brand-orange focus:ring-brand-orange rounded-xl shadow-sm" required>
                                        @error('price')
                                            <span class="text-red-600 text-sm mt-1 block">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="discount_price" class="block font-bold text-sm text-gray-700 mb-2">Discount Price (Rp) <span class="text-gray-400 font-normal text-xs">(Optional)</span></label>
                                        <input id="discount_price" type="number" name="discount_price" value="{{ old('discount_price', $product->discount_price) }}" min="0" step="100" class="w-full border-gray-300 focus:border-brand-orange focus:ring-brand-orange rounded-xl shadow-sm">
                                        @error('discount_price')
                                            <span class="text-red-600 text-sm mt-1 block">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                                    <div>
                                        <label for="stock" class="block font-bold text-sm text-gray-700 mb-2">Stock</label>
                                        <input id="stock" type="number" name="stock" value="{{ old('stock', $product->stock) }}" min="0" class="w-full border-gray-300 focus:border-brand-orange focus:ring-brand-orange rounded-xl shadow-sm" required>
                                        @error('stock')
                                            <span class="text-red-600 text-sm mt-1 block">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="weight" class="block font-bold text-sm text-gray-700 mb-2">Weight (gram)</label>
                                        <input id="weight" type="number" name="weight" value="{{ old('weight', $product->weight) }}" min="0" class="w-full border-gray-300 focus:border-brand-orange focus:ring-brand-orange rounded-xl shadow-sm" required>
                                        @error('weight')
                                            <span class="text-red-600 text-sm mt-1 block">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="pt-6 border-t border-gray-100">
                                    <button type="submit" class="w-full px-6 py-3 bg-brand-dark text-white font-bold rounded-xl hover:bg-brand-orange transition-colors shadow-md">Update Product</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Product Images -->
                <div>
                    <div class="bg-white overflow-hidden shadow-lg rounded-2xl border border-gray-100">
                        <div class="p-8">
                            <h3 class="text-xl font-serif font-bold text-brand-dark mb-6 border-b border-gray-100 pb-4">Product Images</h3>
                            
                            <!-- Upload Form -->
                            <form method="POST" action="{{ route('seller.product-images.store', $product) }}" enctype="multipart/form-data" class="mb-8">
                                @csrf
                                <div class="mb-4">
                                    <label for="image" class="block font-bold text-sm text-gray-700 mb-2">Add Image</label>
                                    <input id="image" type="file" name="image" accept="image/*" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-bold file:bg-brand-orange file:text-white hover:file:bg-brand-dark transition-colors" required>
                                    @error('image')
                                        <span class="text-red-600 text-sm mt-1 block">{{ $message }}</span>
                                    @enderror
                                </div>
                                <button type="submit" class="w-full px-4 py-2 bg-brand-dark text-white font-bold rounded-xl hover:bg-brand-orange transition-colors shadow-sm">Upload Image</button>
                            </form>

                            <!-- Image List -->
                            <div class="space-y-4">
                                @forelse($product->productImages as $image)
                                    <div class="relative group rounded-xl overflow-hidden shadow-sm border border-gray-100">
                                        <img src="{{ Str::startsWith($image->image, 'http') ? $image->image : asset('storage/' . $image->image) }}" alt="Product Image" class="w-full h-40 object-cover">
                                        <button type="button"
                                                onclick="document.getElementById('delete-image-{{ $image->id }}').classList.remove('hidden')"
                                                class="absolute top-2 right-2 bg-red-600 text-white p-2 rounded-full shadow-md hover:bg-red-700 opacity-0 group-hover:opacity-100 transition-all transform hover:scale-110">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                        </button>
                                    </div>
                                @empty
                                    <div class="text-center py-8 bg-gray-50 rounded-xl border border-dashed border-gray-300">
                                        <svg class="mx-auto h-12 w-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                        <p class="text-sm text-gray-500 mt-2">No images yet</p>
                                    </div>
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
        title="Delete Image" 
        message="Are you sure you want to delete this image? This action cannot be undone."
        confirmText="Yes, Delete"
        cancelText="Cancel">
    </x-confirmation-modal>
    <form id="delete-image-{{ $image->id }}-form" method="POST" action="{{ route('seller.product-images.destroy', [$product, $image]) }}" class="hidden">
        @csrf
        @method('DELETE')
    </form>
    @endforeach
</x-app-layout>
