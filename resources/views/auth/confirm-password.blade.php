<x-guest-layout>
    <x-auth-card title="Konfirmasi Password" subtitle="Area ini aman. Konfirmasi password Anda sebelum melanjutkan.">
        <form method="POST" action="{{ route('password.confirm') }}">
            @csrf

            <div>
                <x-input-label for="password" :value="__('Password')" />
                <x-text-input id="password" class="mt-2 block w-full" type="password" name="password" required autocomplete="current-password" placeholder="••••••••" />
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <div class="mt-6 flex justify-end">
                <x-primary-button>Konfirmasi</x-primary-button>
            </div>
        </form>
    </x-auth-card>
</x-guest-layout>
