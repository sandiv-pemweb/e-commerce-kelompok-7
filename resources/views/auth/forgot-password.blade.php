<x-guest-layout>

    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-900 text-center">
            Lupa Password
        </h2>
        <p class="mt-2 text-sm text-gray-600 text-center">
            Masukkan email Anda dan kami akan mengirimkan link untuk reset password.
        </p>
    </div>


    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}">
        @csrf


        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required
                autofocus />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-6">
            <x-primary-button class="w-full sm:w-auto justify-center">
                Kirim Link Reset Password
            </x-primary-button>
        </div>
    </form>


    <div class="mt-6 text-center border-t pt-4">
        <p class="text-sm text-gray-600">
            Ingat password Anda?
            <a href="{{ route('login') }}"
                class="font-semibold text-indigo-600 hover:text-indigo-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 rounded-md">
                Kembali ke Login
            </a>
        </p>
    </div>
</x-guest-layout>