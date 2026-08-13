<x-guest-layout>
    <x-auth-card title="Lupa Password" subtitle="Masukkan email Anda untuk menerima tautan reset password.">
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <form method="POST" action="{{ route('password.email') }}">
            @csrf

            <div>
                <x-input-label for="email" :value="__('Email')" />
                <x-text-input id="email" class="mt-2 block w-full" type="email" name="email" :value="old('email')" required autofocus placeholder="nama@email.com" />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <div class="mt-6">
                <x-primary-button class="w-full">
                    Kirim Tautan Reset
                </x-primary-button>
            </div>

            <div class="mt-4 text-center">
                <a class="text-sm font-semibold text-kumham-700 hover:text-kumham-500" href="{{ route('login') }}">
                    &larr; Kembali ke halaman masuk
                </a>
            </div>
        </form>
    </x-auth-card>
</x-guest-layout>
