<x-app-layout>
    <x-slot name="header">
        <h2 class=" font-bold text-2xl text-brand-dark leading-tight">
            {{ __('Tambah Produk Baru') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm rounded-3xl border border-gray-100">
                <div class="p-8">
                    @if($categories->isEmpty())
                        <div class="mb-6 p-4 bg-yellow-50 border border-yellow-200 rounded-2xl flex items-center">
                            <svg class="w-5 h-5 text-yellow-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                            <p class="text-yellow-800 font-medium">Anda harus membuat kategori produk terlebih dahulu. <a href="{{ route('seller.categories.create') }}" class="underline hover:text-yellow-900 font-bold">Buat Kategori</a></p>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('seller.products.store') }}" class="space-y-6" enctype="multipart/form-data">
                        @csrf

                        <div>
                            <label for="product_category_id" class="block font-bold text-sm text-gray-700 mb-2">Kategori</label>
                            <select id="product_category_id" name="product_category_id" class="w-full border-gray-300 focus:border-brand-orange focus:ring-brand-orange rounded-xl shadow-sm bg-gray-50/50" required>
                                <option value="">Pilih Kategori</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('product_category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                @endforeach
                            </select>
                            @error('product_category_id')
                                <span class="text-red-600 text-sm mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <label for="name" class="block font-bold text-sm text-gray-700 mb-2">Nama Produk</label>
                            <input id="name" type="text" name="name" value="{{ old('name') }}" class="w-full border-gray-300 focus:border-brand-orange focus:ring-brand-orange rounded-xl shadow-sm bg-gray-50/50" required placeholder="e.g. Wireless Headphones">
                            @error('name')
                                <span class="text-red-600 text-sm mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="author" class="block font-bold text-sm text-gray-700 mb-2">Penulis</label>
                                <input id="author" type="text" name="author" value="{{ old('author') }}" class="w-full border-gray-300 focus:border-brand-orange focus:ring-brand-orange rounded-xl shadow-sm bg-gray-50/50" placeholder="e.g. J.K. Rowling">
                                @error('author')
                                    <span class="text-red-600 text-sm mt-1 block">{{ $message }}</span>
                                @enderror
                            </div>

                            <div>
                                <label for="publisher" class="block font-bold text-sm text-gray-700 mb-2">Penerbit</label>
                                <input id="publisher" type="text" name="publisher" value="{{ old('publisher') }}" class="w-full border-gray-300 focus:border-brand-orange focus:ring-brand-orange rounded-xl shadow-sm bg-gray-50/50" placeholder="e.g. Bloomsbury">
                                @error('publisher')
                                    <span class="text-red-600 text-sm mt-1 block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="published_year" class="block font-bold text-sm text-gray-700 mb-2">Tahun Terbit</label>
                                <input id="published_year" type="number" name="published_year" value="{{ old('published_year') }}" min="1900" max="{{ date('Y') + 1 }}" class="w-full border-gray-300 focus:border-brand-orange focus:ring-brand-orange rounded-xl shadow-sm bg-gray-50/50" placeholder="e.g. 2023">
                                @error('published_year')
                                    <span class="text-red-600 text-sm mt-1 block">{{ $message }}</span>
                                @enderror
                            </div>

                            <div>
                                <label for="condition" class="block font-bold text-sm text-gray-700 mb-2">Kondisi</label>
                                <select id="condition" name="condition" class="w-full border-gray-300 focus:border-brand-orange focus:ring-brand-orange rounded-xl shadow-sm bg-gray-50/50" required>
                                    <option value="new" {{ old('condition') == 'new' ? 'selected' : '' }}>Baru</option>
                                    <option value="second" {{ old('condition') == 'second' ? 'selected' : '' }}>Bekas</option>
                                </select>
                                @error('condition')
                                    <span class="text-red-600 text-sm mt-1 block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div>
                            <label for="description" class="block font-bold text-sm text-gray-700 mb-2">Deskripsi</label>
                            <textarea id="description" name="description" rows="4" class="w-full border-gray-300 focus:border-brand-orange focus:ring-brand-orange rounded-xl shadow-sm bg-gray-50/50" required placeholder="Deskripsikan produk Anda...">{{ old('description') }}</textarea>
                            @error('description')
                                <span class="text-red-600 text-sm mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="price" class="block font-bold text-sm text-gray-700 mb-2">Harga (Rp)</label>
                                <input id="price" type="number" name="price" value="{{ old('price') }}" min="0" step="100" class="w-full border-gray-300 focus:border-brand-orange focus:ring-brand-orange rounded-xl shadow-sm bg-gray-50/50" required placeholder="0">
                                @error('price')
                                    <span class="text-red-600 text-sm mt-1 block">{{ $message }}</span>
                                @enderror
                            </div>

                            <div>
                                <label for="discount_price" class="block font-bold text-sm text-gray-700 mb-2">Harga Diskon (Rp) <span class="text-gray-400 font-normal text-xs">(Opsional)</span></label>
                                <input id="discount_price" type="number" name="discount_price" value="{{ old('discount_price') }}" min="0" step="100" class="w-full border-gray-300 focus:border-brand-orange focus:ring-brand-orange rounded-xl shadow-sm bg-gray-50/50" placeholder="0">
                                @error('discount_price')
                                    <span class="text-red-600 text-sm mt-1 block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="stock" class="block font-bold text-sm text-gray-700 mb-2">Stok</label>
                                <input id="stock" type="number" name="stock" value="{{ old('stock', 0) }}" min="0" class="w-full border-gray-300 focus:border-brand-orange focus:ring-brand-orange rounded-xl shadow-sm bg-gray-50/50" required>
                                @error('stock')
                                    <span class="text-red-600 text-sm mt-1 block">{{ $message }}</span>
                                @enderror
                            </div>

                            <div>
                                <label for="weight" class="block font-bold text-sm text-gray-700 mb-2">Berat (gram)</label>
                                <input id="weight" type="number" name="weight" value="{{ old('weight', 0) }}" min="0" class="w-full border-gray-300 focus:border-brand-orange focus:ring-brand-orange rounded-xl shadow-sm bg-gray-50/50" required>
                                @error('weight')
                                    <span class="text-red-600 text-sm mt-1 block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div>
                            <label for="images" class="block font-bold text-sm text-gray-700 mb-2">Gambar Produk</label>
                            <input id="images" type="file" name="images[]" multiple accept="image/*" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-bold file:bg-brand-orange file:text-white hover:file:bg-brand-dark transition-colors cursor-pointer">
                            <p class="text-xs text-gray-500 mt-1">Anda dapat memilih lebih dari satu gambar.</p>
                            @error('images')
                                <span class="text-red-600 text-sm mt-1 block">{{ $message }}</span>
                            @enderror
                            @error('images.*')
                                <span class="text-red-600 text-sm mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="flex items-center justify-end gap-4 pt-6 border-t border-gray-100">
                            <a href="{{ route('seller.products.index') }}" class="px-6 py-3 bg-gray-100 text-gray-700 font-bold rounded-xl hover:bg-gray-200 transition-colors">Batal</a>
                            <button type="submit" class="px-8 py-3 bg-brand-dark text-white font-bold rounded-xl hover:bg-brand-orange transition-colors shadow-sm hover:shadow-md transform hover:-translate-y-0.5">Buat Produk</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
