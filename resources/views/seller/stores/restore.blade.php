<x-app-layout>


    <div class="py-12 flex flex-col justify-center min-h-[80vh]">
        <div class="max-w-xl mx-auto px-4 sm:px-6 lg:px-8 w-full">
            <div class="bg-white overflow-hidden shadow-lg rounded-2xl border border-gray-100">
                <div class="p-8 text-center">
                    <div class="mb-8">
                        <div class="mx-auto h-20 w-20 bg-yellow-50 rounded-full flex items-center justify-center mb-6">
                            <svg class="h-10 w-10 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                            </svg>
                        </div>
                        <h3 class="text-2xl font-serif font-bold text-brand-dark mb-4">Store Deactivated</h3>
                        <p class="text-gray-600 leading-relaxed">
                            Your store <strong>{{ $store->name }}</strong> is currently deactivated. 
                            You can restore it to start selling again. All your products and data are safe.
                        </p>
                    </div>

                    <form action="{{ route('seller.stores.restore') }}" method="POST">
                        @csrf
                        @method('PATCH')
                        
                        <div class="flex flex-col sm:flex-row justify-center gap-4">
                            <a href="{{ route('dashboard') }}" class="px-6 py-3 bg-gray-100 text-gray-700 font-bold rounded-xl hover:bg-gray-200 transition-colors">
                                Back to Dashboard
                            </a>
                            <button type="submit" class="px-6 py-3 bg-brand-dark text-white font-bold rounded-xl hover:bg-brand-orange transition-colors shadow-md transform hover:-translate-y-0.5">
                                Restore My Store
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
