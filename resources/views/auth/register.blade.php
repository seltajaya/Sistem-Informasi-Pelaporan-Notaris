<x-guest-layout>
    <x-auth-card title="Daftar Akun Notaris" subtitle="Pilih wilayah penempatan Anda saat mendaftar">
        <form method="POST" action="{{ route('register') }}">
            @csrf

            <div>
                <x-input-label for="name" :value="__('Nama Lengkap')" />
                <x-text-input id="name" class="mt-2 block w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
                <x-input-error :messages="$errors->get('name')" class="mt-2" />
            </div>

            <div class="mt-4">
                <x-input-label for="email" :value="__('Email')" />
                <x-text-input id="email" class="mt-2 block w-full" type="email" name="email" :value="old('email')" required autocomplete="username" placeholder="nama@email.com" />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <div class="mt-4">
                <x-input-label for="region_id" :value="__('Wilayah Penempatan')" />
                <select id="region_id" name="region_id" class="mt-2 block w-full rounded-lg border-gray-300 shadow-sm focus:border-kumham-500 focus:ring-kumham-500" required>
                    <option value="">-- Pilih Wilayah --</option>
                    @foreach ($regions as $region)
                        <option value="{{ $region->id }}" @selected(old('region_id') == $region->id)>{{ $region->name }}</option>
                    @endforeach
                </select>
                <x-input-error :messages="$errors->get('region_id')" class="mt-2" />
            </div>

            <div class="mt-4">
                <x-input-label for="password" :value="__('Password')" />
                <x-text-input id="password" class="mt-2 block w-full" type="password" name="password" required autocomplete="new-password" placeholder="Minimal 8 karakter" />
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <div class="mt-4">
                <x-input-label for="password_confirmation" :value="__('Konfirmasi Password')" />
                <x-text-input id="password_confirmation" class="mt-2 block w-full" type="password" name="password_confirmation" required autocomplete="new-password" />
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
            </div>

            <div class="mt-6 flex items-center justify-end gap-4">
                <a class="text-sm font-semibold text-gray-600 hover:text-kumham-700" href="{{ route('login') }}">
                    Sudah punya akun?
                </a>
                <x-primary-button>
                    Daftar
                </x-primary-button>
            </div>
        </form>
    </x-auth-card>
</x-guest-layout>
