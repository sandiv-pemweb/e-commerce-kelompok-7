<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class=" font-bold text-2xl text-brand-dark leading-tight">
                {{ __('Detail Penarikan') }}
            </h2>
            <a href="{{ route('admin.withdrawals.index') }}" 
               class="px-4 py-2 bg-white border border-gray-300 rounded-xl text-sm font-bold text-gray-700 hover:bg-gray-50 transition-colors shadow-sm">
                &larr; Kembali
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl flex items-center shadow-sm">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl flex items-center shadow-sm">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    {{ session('error') }}
                </div>
            @endif

            <!-- Main Invoice Card -->
            <div class="bg-white overflow-hidden shadow-sm rounded-3xl border border-gray-100 relative">
                <!-- Status Ribbon -->
                <div class="absolute top-0 right-0 mt-6 mr-6">
                    <span class="px-4 py-2 inline-flex items-center gap-1.5 text-sm leading-5 font-bold rounded-full 
                        @if($withdrawal->status == 'approved') bg-green-50 text-green-700 border border-green-100
                        @elseif($withdrawal->status == 'rejected') bg-red-50 text-red-700 border border-red-100
                        @else bg-yellow-50 text-yellow-700 border border-yellow-100 animate-pulse
                        @endif">
                        @if($withdrawal->status == 'approved')
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            Disetujui
                        @elseif($withdrawal->status == 'rejected')
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            Ditolak
                        @else
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            Menunggu Konfirmasi
                        @endif
                    </span>
                </div>

                <div class="p-8 md:p-12">
                     <!-- Header -->
                    <div class="text-center mb-10">
                        <div class="inline-flex items-center justify-center p-3 bg-brand-orange/10 rounded-full mb-4">
                            <svg class="w-10 h-10 text-brand-orange" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        </div>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Permintaan Penarikan</p>
                        <h1 class="text-5xl font-extrabold text-brand-dark tracking-tight mb-2">
                            Rp {{ number_format($withdrawal->amount, 0, ',', '.') }}
                        </h1>
                        <p class="text-gray-500 font-medium">{{ $withdrawal->created_at->format('d F Y, H:i') }}</p>
                    </div>

                    <!-- Divider -->
                    <div class="relative flex py-5 items-center">
                        <div class="flex-grow border-t border-dashed border-gray-300"></div>
                        <span class="flex-shrink-0 mx-4 text-gray-400 text-xs uppercase font-bold">Detail Toko</span>
                        <div class="flex-grow border-t border-dashed border-gray-300"></div>
                    </div>

                    <!-- Store Details -->
                    <div class="flex items-center gap-6 mb-8 bg-gray-50/50 p-6 rounded-3xl border border-gray-100">
                        @if($withdrawal->storeBalance->store->logo)
                            <img class="h-16 w-16 rounded-2xl object-cover border border-white shadow-sm" src="{{ $withdrawal->storeBalance->store->logo_url }}" alt="{{ $withdrawal->storeBalance->store->name }}">
                        @else
                            <div class="h-16 w-16 rounded-2xl bg-white flex items-center justify-center text-xl font-bold text-gray-400 border border-gray-200">
                                {{ substr($withdrawal->storeBalance->store->name, 0, 1) }}
                            </div>
                        @endif
                        <div>
                            <h4 class="text-xl font-bold text-brand-dark mb-1">{{ $withdrawal->storeBalance->store->name }}</h4>
                            <p class="text-gray-500 text-sm mb-2">{{ $withdrawal->storeBalance->store->user->name }} • {{ $withdrawal->storeBalance->store->user->email }}</p>
                            <div class="mt-1 text-xs font-bold text-green-700 bg-green-50 inline-block px-3 py-1 rounded-full border border-green-100">
                                Saldo Tersedia: Rp {{ number_format($withdrawal->storeBalance->balance, 0, ',', '.') }}
                            </div>
                        </div>
                    </div>

                    <!-- Bank Details -->
                    <div class="bg-gray-50/50 rounded-3xl p-8 border border-gray-100 mb-8">
                        <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-6">Informasi Rekening Tujuan</h4>
                        <div class="grid grid-cols-2 gap-8">
                            <div>
                                <p class="text-xs text-gray-400 mb-1 uppercase tracking-wider font-bold">Bank</p>
                                <p class="font-bold text-gray-900 text-lg">{{ $withdrawal->bank_name }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-400 mb-1 uppercase tracking-wider font-bold">Atas Nama</p>
                                <p class="font-bold text-gray-900 text-lg">{{ $withdrawal->bank_account_name }}</p>
                            </div>
                            <div class="col-span-2 pt-6 border-t border-gray-200/50 mt-2">
                                <p class="text-xs text-gray-400 mb-2 uppercase tracking-wider font-bold">Nomor Rekening</p>
                                <p class="font-mono font-bold text-brand-dark text-2xl tracking-wider">{{ $withdrawal->bank_account_number }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    @if($withdrawal->status == 'pending')
                         <div class="grid grid-cols-2 gap-4">
                            <button onclick="document.getElementById('reject-modal').classList.remove('hidden')"
                                    class="w-full py-4 text-red-600 font-bold bg-white hover:bg-red-50 rounded-2xl transition-all border border-red-100 hover:border-red-200 shadow-sm hover:shadow-md">
                                Tolak Permintaan
                            </button>
                            <button onclick="document.getElementById('approve-modal').classList.remove('hidden')"
                                    class="w-full py-4 text-white font-bold bg-brand-dark hover:bg-brand-orange rounded-2xl transition-all shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                                Setujui & Transfer
                            </button>
                        </div>
                    @endif
                </div>

                <!-- Footer Decor -->
                <div class="bg-gray-50/50 px-8 py-4 border-t border-gray-100 text-center">
                    <p class="text-xs text-gray-400 font-mono">REF ID: {{ str_pad($withdrawal->id, 8, '0', STR_PAD_LEFT) }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Modals -->
    @if($withdrawal->status == 'pending')
    <!-- Approve Modal -->
    <x-confirmation-modal 
        id="approve-modal"
        type="success" 
        title="Konfirmasi Transfer" 
        message="Anda akan menyetujui penarikan sebesar <b>Rp {{ number_format($withdrawal->amount, 0, ',', '.') }}</b>. Pastikan Anda telah melakukan transfer ke rekening tujuan. Tindakan ini tidak dapat dibatalkan."
        confirmText="Ya, Saya Sudah Transfer"
        cancelText="Batal">
    </x-confirmation-modal>
    <form id="approve-modal-form" method="POST" action="{{ route('admin.withdrawals.approve', $withdrawal) }}" class="hidden">
        @csrf
        @method('PATCH')
    </form>

    <!-- Reject Modal -->
    <x-confirmation-modal 
        id="reject-modal"
        type="danger" 
        title="Tolak Penarikan" 
        message="Apakah Anda yakin ingin menolak permintaan penarikan ini? Saldo akan dikembalikan ke akun toko."
        confirmText="Tolak Permintaan"
        cancelText="Batal">
    </x-confirmation-modal>
    <form id="reject-modal-form" method="POST" action="{{ route('admin.withdrawals.reject', $withdrawal) }}" class="hidden">
        @csrf
        @method('PATCH')
    </form>
    @endif
</x-app-layout>
