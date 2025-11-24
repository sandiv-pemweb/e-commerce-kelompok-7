<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Manajemen Kategori Produk') }}
            </h2>
            <a href="{{ route('seller.categories.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700">
                Tambah Kategori
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

                    @if (session('error'))
                        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                            {{ session('error') }}
                        </div>
                    @endif

                    @if($categories->isEmpty())
                        <p class="text-center text-gray-500 py-8">Belum ada kategori. <a href="{{ route('seller.categories.create') }}" class="text-indigo-600 hover:underline">Tambahkan kategori pertama Anda</a></p>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Kategori</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah Produk</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($categories as $category)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $category->name }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $category->products_count }} produk</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                                <a href="{{ route('seller.categories.edit', $category) }}" class="text-indigo-600 hover:text-indigo-900">Edit</a>
                                                <button type="button"
                                                        onclick="document.getElementById('delete-category-{{ $category->id }}').classList.remove('hidden')"
                                                        class="text-red-600 hover:text-red-900">
                                                    Hapus
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-4">
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
        title="Hapus Kategori" 
        message="Apakah Anda yakin ingin menghapus kategori {{ $category->name }}? Kategori dengan {{ $category->products_count }} produk akan dihapus. Tindakan ini tidak dapat dibatalkan."
        confirmText="Ya, Hapus"
        cancelText="Batal">
    </x-confirmation-modal>
    <form id="delete-category-{{ $category->id }}-form" method="POST" action="{{ route('seller.categories.destroy', $category) }}" class="hidden">
        @csrf
        @method('DELETE')
    </form>
    @endforeach
</x-app-layout>
