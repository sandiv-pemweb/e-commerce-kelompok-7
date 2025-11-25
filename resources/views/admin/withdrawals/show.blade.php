<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detail Penarikan Saldo') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                    {{ session('error') }}
                </div>
            @endif

            <!-- Withdrawal Information -->
            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-semibold mb-4">Informasi Penarikan</h3>
                    
                    <div class="grid grid-cols-2 gap-6">
                        <div class="space-y-3">
                            <div>
                                <p class="text-sm text-gray-600">Tanggal Pengajuan</p>
                                <p class="font-medium">{{ $withdrawal->created_at->format('d M Y H:i') }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Nama Toko</p>
                                <p class="font-medium">{{ $withdrawal->storeBalance->store->name }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Pemilik Toko</p>
                                <p class="font-medium">{{ $withdrawal->storeBalance->store->user->name }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Email</p>
                                <p class="font-medium">{{ $withdrawal->storeBalance->store->user->email }}</p>
                            </div>
                        </div>

                        <div class="space-y-3">
                            <div>
                                <p class="text-sm text-gray-600">Jumlah Penarikan</p>
                                <p class="font-bold text-2xl text-indigo-600">Rp {{ number_format($withdrawal->amount, 0, ',', '.') }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Status</p>
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    @if($withdrawal->status == 'approved') bg-green-100 text-green-800
                                    @elseif($withdrawal->status == 'rejected') bg-red-100 text-red-800
                                    @else bg-yellow-100 text-yellow-800
                                    @endif">
                                    @if($withdrawal->status == 'approved') Disetujui
                                    @elseif($withdrawal->status == 'rejected') Ditolak
                                    @else Pending
                                    @endif
                                </span>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Saldo Toko Saat Ini</p>
                                <p class="font-medium">Rp {{ number_format($withdrawal->storeBalance->balance, 0, ',', '.') }}</p>
                            </div>
                        </div>
                    </div>

                    <hr class="my-6">

                    <h4 class="font-semibold mb-3">Informasi Rekening Bank</h4>
                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <p class="text-sm text-gray-600">Nama Bank</p>
                            <p class="font-medium">{{ $withdrawal->bank_name }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Nama Pemilik Rekening</p>
                            <p class="font-medium">{{ $withdrawal->bank_account_name }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Nomor Rekening</p>
                            <p class="font-medium">{{ $withdrawal->bank_account_number }}</p>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    @if($withdrawal->status == 'pending')
                        <div class="mt-6 flex gap-3">
                            <button type="button" 
                                    onclick="document.getElementById('approve-modal').classList.remove('hidden')"
                                    class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">
                                Setujui Penarikan
                            </button>

                            <button type="button" 
                                    onclick="document.getElementById('reject-modal').classList.remove('hidden')"
                                    class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">
                                Tolak Penarikan
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Approve Modal -->
    @if($withdrawal->status == 'pending')
    <x-confirmation-modal 
        id="approve-modal"
        type="success" 
        title="Setujui Penarikan Saldo" 
        message="Apakah Anda yakin ingin menyetujui penarikan saldo sebesar Rp {{ number_format($withdrawal->amount, 0, ',', '.') }} untuk toko {{ $withdrawal->storeBalance->store->name }}? Saldo akan dikurangi dari toko."
        confirmText="Ya, Setujui"
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
        title="Tolak Penarikan Saldo" 
        message="Apakah Anda yakin ingin menolak penarikan saldo sebesar Rp {{ number_format($withdrawal->amount, 0, ',', '.') }} dari toko {{ $withdrawal->storeBalance->store->name }}?"
        confirmText="Ya, Tolak"
        cancelText="Batal">
    </x-confirmation-modal>
    <form id="reject-modal-form" method="POST" action="{{ route('admin.withdrawals.reject', $withdrawal) }}" class="hidden">
        @csrf
        @method('PATCH')
    </form>
    @endif
</x-app-layout>
