<x-app-layout>
    <x-slot name="header">
        <h2 class="font-serif font-bold text-2xl text-brand-dark leading-tight">
            {{ __('Add New Product') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-lg rounded-2xl border border-gray-100">
                <div class="p-8">
                    @if($categories->isEmpty())
                        <div class="mb-6 p-4 bg-yellow-50 border border-yellow-200 rounded-xl flex items-center">
                            <svg class="w-5 h-5 text-yellow-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                            <p class="text-yellow-800 font-medium">You must create a product category first. <a href="{{ route('seller.categories.create') }}" class="underline hover:text-yellow-900 font-bold">Create Category</a></p>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('seller.products.store') }}" class="space-y-6">
                        @csrf

                        <div>
                            <label for="product_category_id" class="block font-bold text-sm text-gray-700 mb-2">Category</label>
                            <select id="product_category_id" name="product_category_id" class="w-full border-gray-300 focus:border-brand-orange focus:ring-brand-orange rounded-xl shadow-sm" required>
                                <option value="">Select Category</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('product_category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                @endforeach
                            </select>
                            @error('product_category_id')
                                <span class="text-red-600 text-sm mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <label for="name" class="block font-bold text-sm text-gray-700 mb-2">Product Name</label>
                            <input id="name" type="text" name="name" value="{{ old('name') }}" class="w-full border-gray-300 focus:border-brand-orange focus:ring-brand-orange rounded-xl shadow-sm" required placeholder="e.g. Wireless Headphones">
                            @error('name')
                                <span class="text-red-600 text-sm mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <label for="description" class="block font-bold text-sm text-gray-700 mb-2">Description</label>
                            <textarea id="description" name="description" rows="4" class="w-full border-gray-300 focus:border-brand-orange focus:ring-brand-orange rounded-xl shadow-sm" required placeholder="Describe your product...">{{ old('description') }}</textarea>
                            @error('description')
                                <span class="text-red-600 text-sm mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <label for="price" class="block font-bold text-sm text-gray-700 mb-2">Price (Rp)</label>
                                <input id="price" type="number" name="price" value="{{ old('price') }}" min="0" step="100" class="w-full border-gray-300 focus:border-brand-orange focus:ring-brand-orange rounded-xl shadow-sm" required placeholder="0">
                                @error('price')
                                    <span class="text-red-600 text-sm mt-1 block">{{ $message }}</span>
                                @enderror
                            </div>

                            <div>
                                <label for="stock" class="block font-bold text-sm text-gray-700 mb-2">Stock</label>
                                <input id="stock" type="number" name="stock" value="{{ old('stock', 0) }}" min="0" class="w-full border-gray-300 focus:border-brand-orange focus:ring-brand-orange rounded-xl shadow-sm" required>
                                @error('stock')
                                    <span class="text-red-600 text-sm mt-1 block">{{ $message }}</span>
                                @enderror
                            </div>

                            <div>
                                <label for="weight" class="block font-bold text-sm text-gray-700 mb-2">Weight (gram)</label>
                                <input id="weight" type="number" name="weight" value="{{ old('weight', 0) }}" min="0" class="w-full border-gray-300 focus:border-brand-orange focus:ring-brand-orange rounded-xl shadow-sm" required>
                                @error('weight')
                                    <span class="text-red-600 text-sm mt-1 block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="flex items-center justify-end gap-4 pt-6 border-t border-gray-100">
                            <a href="{{ route('seller.products.index') }}" class="px-6 py-2 bg-gray-100 text-gray-700 font-bold rounded-xl hover:bg-gray-200 transition-colors">Cancel</a>
                            <button type="submit" class="px-6 py-2 bg-brand-dark text-white font-bold rounded-xl hover:bg-brand-orange transition-colors shadow-md">Create Product</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
