<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-serif font-bold text-2xl text-brand-dark leading-tight">
                {{ __('Manajemen Kategori') }}
            </h2>
            <a href="{{ route('seller.categories.create') }}" class="px-6 py-3 bg-brand-orange border border-transparent rounded-full font-bold text-sm text-white uppercase tracking-widest hover:bg-brand-dark transition-colors shadow-md hover:shadow-lg transform hover:-translate-y-0.5 flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                Tambah Kategori
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
            @if (session('success'))
                <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl flex items-center shadow-sm">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl flex items-center shadow-sm">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    {{ session('error') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-lg rounded-2xl border border-gray-100">
                <div class="p-8">
                    @if($categories->isEmpty())
                        <div class="text-center py-12">
                            <svg class="mx-auto h-16 w-16 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
                            <p class="text-lg font-medium text-gray-500 mb-4">Tidak ada kategori ditemukan.</p>
                            <a href="{{ route('seller.categories.create') }}" class="inline-flex items-center px-6 py-3 bg-brand-dark border border-transparent rounded-xl font-bold text-sm text-white uppercase tracking-widest hover:bg-brand-orange transition-colors shadow-md">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                                Tambah Kategori Pertama
                            </a>
                        </div>
                    @else
                        <div class="overflow-x-auto rounded-xl border border-gray-200">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-brand-dark text-white">
                                    <tr>
                                        <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider">Nama Kategori</th>
                                        <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider">Jumlah Produk</th>
                                        <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($categories as $category)
                                        <tr class="hover:bg-blue-50 transition-colors duration-150">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-brand-dark">{{ $category->name }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                <span class="px-3 py-1 bg-gray-100 rounded-full text-xs font-bold text-gray-600">
                                                    {{ $category->products_count }} produk
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium flex space-x-2">
                                                <a href="{{ route('seller.categories.edit', $category) }}" class="flex-1 text-center px-4 py-2 bg-gray-100 text-gray-700 font-bold rounded-xl hover:bg-brand-dark hover:text-white transition-colors">
                                                    Edit
                                                </a>
                                                <button type="button"
                                                        onclick="document.getElementById('delete-category-{{ $category->id }}').classList.remove('hidden')"
                                                        class="flex-1 text-center px-4 py-2 bg-red-50 text-red-600 font-bold rounded-xl hover:bg-red-600 hover:text-white transition-colors">
                                                    Hapus
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-6">
                            {{ $categories->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Category Modals -->
    @foreach($categories as $category)
    <x-confirmation-modal 
        id="delete-category-{{ $category->id }}"
        type="danger" 
        title="Hapus Kategori?" 
        message="Apakah Anda yakin ingin menghapus kategori '{{ $category->name }}'? Kategori ini berisi {{ $category->products_count }} produk. Tindakan ini tidak dapat dibatalkan."
        confirmText="Ya, Hapus"
        cancelText="Batal">
    </x-confirmation-modal>
    <form id="delete-category-{{ $category->id }}-form" method="POST" action="{{ route('seller.categories.destroy', $category) }}" class="hidden">
        @csrf
        @method('DELETE')
    </form>
    @endforeach
</x-app-layout>
