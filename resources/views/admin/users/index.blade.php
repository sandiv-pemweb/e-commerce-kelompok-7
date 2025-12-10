<x-app-layout>
    <x-slot name="header">
        <h2 class=" font-bold text-2xl text-brand-dark leading-tight">
            {{ __('Manajemen Pengguna') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="bg-white overflow-hidden shadow-sm rounded-3xl border border-gray-100 mb-8">
                <div class="p-8">
                    <form method="GET" action="{{ route('admin.users.index') }}"
                        class="flex flex-col md:flex-row gap-4 items-end">
                        <div class="w-full md:w-1/3">
                            <label for="search" class="block font-bold text-sm text-gray-700 mb-2">Cari Pengguna</label>
                            <input type="text" name="q" id="search" value="{{ request('q') }}"
                                placeholder="Nama atau email..."
                                class="w-full border-gray-200 focus:border-brand-orange focus:ring-brand-orange rounded-xl shadow-sm bg-gray-50/50">
                        </div>
                        <div class="w-full md:w-1/4">
                            <label for="role" class="block font-bold text-sm text-gray-700 mb-2">Role</label>
                            <select name="role" id="role"
                                class="w-full border-gray-200 focus:border-brand-orange focus:ring-brand-orange rounded-xl shadow-sm cursor-pointer bg-gray-50/50">
                                <option value="">Semua Peran</option>
                                <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Administrator
                                </option>
                                <option value="seller" {{ request('role') == 'seller' ? 'selected' : '' }}>Penjual
                                </option>
                                <option value="buyer" {{ request('role') == 'buyer' ? 'selected' : '' }}>Pembeli</option>
                            </select>
                        </div>
                        <div class="w-full md:w-auto flex gap-3">
                            <button type="submit"
                                class="px-6 py-2.5 bg-brand-dark text-white font-bold rounded-xl hover:bg-brand-orange transition-all shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                                Filter
                            </button>
                            <a href="{{ route('admin.users.index') }}"
                                class="px-6 py-2.5 bg-white border border-gray-200 text-gray-700 font-bold rounded-xl hover:bg-gray-50 transition-colors">
                                Reset
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm rounded-3xl border border-gray-100">
                <div class="p-8">
                    <div class="overflow-x-auto">
                        <table class="min-w-full">
                            <thead>
                                <tr class="border-b border-gray-100">
                                    <th
                                        class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-wider pl-4">
                                        Pengguna</th>
                                    <th
                                        class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">
                                        Role</th>
                                    <th
                                        class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">
                                        Toko</th>
                                    <th
                                        class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">
                                        Bergabung</th>
                                    <th
                                        class="px-6 py-4 text-right text-xs font-bold text-gray-400 uppercase tracking-wider pr-4">
                                        Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50">
                                @foreach ($users as $user)
                                    <tr class="hover:bg-blue-50/50 transition-colors duration-200 group">
                                        <td class="px-6 py-4 whitespace-nowrap pl-4">
                                            <div class="flex items-center gap-4">
                                                <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=random&color=fff&size=40"
                                                    class="w-10 h-10 rounded-full border border-white shadow-sm" alt="">
                                                <div>
                                                    <div class="text-sm font-bold text-gray-900">{{ $user->name }}</div>
                                                    <div class="text-sm text-gray-500">{{ $user->email }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-3 py-1 inline-flex items-center gap-1.5 text-xs font-bold rounded-full 
                                                    @if($user->role === 'admin')
                                                        bg-purple-50 text-purple-700 border border-purple-100
                                                    @elseif($user->role === 'seller' || $user->store)
                                                        @if($user->store && !$user->store->is_verified)
                                                            bg-yellow-50 text-yellow-700 border border-yellow-100
                                                        @else
                                                            bg-orange-50 text-orange-700 border border-orange-100
                                                        @endif
                                                    @else
                                                        bg-blue-50 text-blue-700 border border-blue-100
                                                    @endif">
                                                @if($user->role === 'admin')
                                                    Administrator
                                                @elseif($user->role === 'seller' || $user->store)
                                                    @if($user->store && !$user->store->is_verified)
                                                        Penjual (Menunggu)
                                                    @else
                                                        Penjual (Seller)
                                                    @endif
                                                @else
                                                    Pembeli (Buyer)
                                                @endif
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $user->store ? $user->store->name : '-' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $user->created_at->format('d M Y') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium pr-4">
                                            <a href="{{ route('admin.users.show', $user) }}"
                                                class="inline-flex items-center px-4 py-2 bg-white border border-gray-200 rounded-xl text-sm font-bold text-gray-700 hover:bg-brand-dark hover:text-white hover:border-brand-dark transition-all shadow-sm group-hover:shadow-md">
                                                Detail
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-8 px-4 border-t border-gray-100 pt-6">
                        {{ $users->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>