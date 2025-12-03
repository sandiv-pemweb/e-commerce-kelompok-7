<x-app-layout>
    <x-slot name="header">
        <h2 class="font-serif font-bold text-2xl text-brand-dark leading-tight">
            {{ __('Withdrawal Details') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
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

            <!-- Withdrawal Information -->
            <div class="bg-white overflow-hidden shadow-lg rounded-2xl border border-gray-100">
                <div class="p-8">
                    <div class="flex items-center justify-between mb-8 border-b border-gray-100 pb-6">
                        <h3 class="text-2xl font-serif font-bold text-brand-dark">Withdrawal Information</h3>
                        <span class="px-4 py-2 inline-flex text-sm leading-5 font-bold rounded-full 
                            @if($withdrawal->status == 'approved') bg-green-100 text-green-800
                            @elseif($withdrawal->status == 'rejected') bg-red-100 text-red-800
                            @else bg-yellow-100 text-yellow-800
                            @endif">
                            @if($withdrawal->status == 'approved') Approved
                            @elseif($withdrawal->status == 'rejected') Rejected
                            @else Pending
                            @endif
                        </span>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                        <div class="space-y-6">
                            <div>
                                <p class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Date Requested</p>
                                <p class="text-lg font-medium text-gray-900">{{ $withdrawal->created_at->format('d M Y H:i') }}</p>
                            </div>
                            <div>
                                <p class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Store Name</p>
                                <p class="text-lg font-bold text-brand-dark">{{ $withdrawal->storeBalance->store->name }}</p>
                            </div>
                            <div>
                                <p class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Store Owner</p>
                                <p class="text-lg font-medium text-gray-900">{{ $withdrawal->storeBalance->store->user->name }}</p>
                                <p class="text-sm text-gray-500">{{ $withdrawal->storeBalance->store->user->email }}</p>
                            </div>
                        </div>

                        <div class="space-y-6">
                            <div>
                                <p class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Withdrawal Amount</p>
                                <p class="font-bold text-3xl text-brand-orange">Rp {{ number_format($withdrawal->amount, 0, ',', '.') }}</p>
                            </div>
                            <div>
                                <p class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Current Store Balance</p>
                                <p class="text-lg font-medium text-green-600">Rp {{ number_format($withdrawal->storeBalance->balance, 0, ',', '.') }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="border-t border-gray-100 pt-8 mb-8">
                        <h4 class="font-serif font-bold text-lg text-brand-dark mb-6">Bank Account Details</h4>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div class="bg-gray-50 p-4 rounded-xl border border-gray-100">
                                <p class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Bank Name</p>
                                <p class="text-lg font-bold text-gray-800">{{ $withdrawal->bank_name }}</p>
                            </div>
                            <div class="bg-gray-50 p-4 rounded-xl border border-gray-100">
                                <p class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Account Holder</p>
                                <p class="text-lg font-bold text-gray-800">{{ $withdrawal->bank_account_name }}</p>
                            </div>
                            <div class="bg-gray-50 p-4 rounded-xl border border-gray-100">
                                <p class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Account Number</p>
                                <p class="text-lg font-bold text-gray-800 font-mono">{{ $withdrawal->bank_account_number }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    @if($withdrawal->status == 'pending')
                        <div class="border-t border-gray-100 pt-8 flex flex-wrap gap-4">
                            <button type="button" 
                                    onclick="document.getElementById('approve-modal').classList.remove('hidden')"
                                    class="px-6 py-3 bg-green-600 text-white font-bold rounded-xl hover:bg-green-700 shadow-md hover:shadow-lg transition-all transform hover:-translate-y-0.5">
                                Approve Withdrawal
                            </button>

                            <button type="button" 
                                    onclick="document.getElementById('reject-modal').classList.remove('hidden')"
                                    class="px-6 py-3 bg-red-600 text-white font-bold rounded-xl hover:bg-red-700 shadow-md hover:shadow-lg transition-all transform hover:-translate-y-0.5">
                                Reject Withdrawal
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
        title="Approve Withdrawal" 
        message="Are you sure you want to approve the withdrawal of Rp {{ number_format($withdrawal->amount, 0, ',', '.') }} for {{ $withdrawal->storeBalance->store->name }}? The amount will be deducted from the store balance."
        confirmText="Yes, Approve"
        cancelText="Cancel">
    </x-confirmation-modal>
    <form id="approve-modal-form" method="POST" action="{{ route('admin.withdrawals.approve', $withdrawal) }}" class="hidden">
        @csrf
        @method('PATCH')
    </form>

    <!-- Reject Modal -->
    <x-confirmation-modal 
        id="reject-modal"
        type="danger" 
        title="Reject Withdrawal" 
        message="Are you sure you want to reject the withdrawal of Rp {{ number_format($withdrawal->amount, 0, ',', '.') }} from {{ $withdrawal->storeBalance->store->name }}?"
        confirmText="Yes, Reject"
        cancelText="Cancel">
    </x-confirmation-modal>
    <form id="reject-modal-form" method="POST" action="{{ route('admin.withdrawals.reject', $withdrawal) }}" class="hidden">
        @csrf
        @method('PATCH')
    </form>
    @endif
</x-app-layout>
