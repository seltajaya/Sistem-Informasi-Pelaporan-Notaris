<x-guest-layout>
    <x-auth-card :title="'Masuk ke Akun' . ($region ? ' — ' . $region->name : '')"
        subtitle="Sistem Informasi Pelaporan Notaris — Kanwil Kemenkum Bengkulu">

        <x-auth-session-status class="mb-4" :status="session('status')" />

        @if ($region ?? null)
            <div class="mb-4 inline-flex items-center gap-2 rounded-full bg-emas-100 px-3 py-1 text-xs font-bold uppercase tracking-wider text-kumham-800">
                Wilayah: {{ $region->name }}
            </div>
        @endif

        <form method="POST" action="{{ route('login', ['slug' => $region?->slug]) }}">
            @csrf

            <div>
                <x-input-label for="email" :value="__('Email')" />

                <x-text-input
                    id="email"
                    class="mt-2 block w-full"
                    type="email"
                    name="email"
                    :value="old('email')"
                    required
                    autofocus
                    autocomplete="username"
                    placeholder="nama@email.com"
                />

                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <div class="mt-4">
                <x-input-label for="password" :value="__('Password')" />

                <div style="position: relative; margin-top: 8px;">

                    <x-text-input
                        id="password"
                        class="block w-full"
                        style="padding-right: 48px;"
                        type="password"
                        name="password"
                        required
                        autocomplete="current-password"
                        placeholder="••••••••"
                    />

                    {{-- TOMBOL LIHAT PASSWORD --}}
                    <button
                        type="button"
                        onclick="togglePassword()"
                        aria-label="Tampilkan atau sembunyikan password"
                        style="
                            position: absolute;
                            top: 0;
                            right: 0;
                            height: 100%;
                            width: 48px;
                            display: flex;
                            align-items: center;
                            justify-content: center;
                            color: #9ca3af;
                            background: transparent;
                            border: none;
                            cursor: pointer;
                        "
                    >

                        {{-- ICON MATA TERBUKA --}}
                        <svg
                            id="eye-open"
                            xmlns="http://www.w3.org/2000/svg"
                            width="20"
                            height="20"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                        >
                            <path d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            <circle cx="12" cy="12" r="3"></circle>
                        </svg>

                        {{-- ICON MATA TERTUTUP --}}
                        <svg
                            id="eye-closed"
                            xmlns="http://www.w3.org/2000/svg"
                            width="20"
                            height="20"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            style="display: none;"
                        >
                            <path d="M3 3l18 18"></path>
                            <path d="M10.58 10.58a2 2 0 0 0 2.83 2.83"></path>
                            <path d="M9.88 4.24A9.5 9.5 0 0 1 12 4c5 0 8.5 4 9.5 8a10.9 10.9 0 0 1-3.03 4.94"></path>
                            <path d="M6.53 6.53A10.9 10.9 0 0 0 2.5 12c1 4 4.5 8 9.5 8 1.61 0 3.08-.39 4.37-1.05"></path>
                        </svg>

                    </button>
                </div>

                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <div class="mt-4 flex items-center justify-between">
                <label for="remember_me" class="inline-flex items-center">
                    <input
                        id="remember_me"
                        type="checkbox"
                        class="rounded border-gray-300 text-kumham-700 shadow-sm focus:ring-kumham-500"
                        name="remember"
                    >

                    <span class="ms-2 text-sm text-gray-600">
                        Ingat saya
                    </span>
                </label>

                @if (Route::has('password.request'))
                    <a
                        class="text-sm font-semibold text-kumham-700 hover:text-kumham-500"
                        href="{{ route('password.request') }}"
                    >
                        Lupa password?
                    </a>
                @endif
            </div>

            <div class="mt-6">
                <x-primary-button class="w-full">
                    Masuk
                </x-primary-button>
            </div>

        </form>

    </x-auth-card>
</x-guest-layout>

<script>
    function togglePassword() {
        const password = document.getElementById('password');
        const eyeOpen = document.getElementById('eye-open');
        const eyeClosed = document.getElementById('eye-closed');

        if (password.type === 'password') {
            password.type = 'text';

            eyeOpen.style.display = 'none';
            eyeClosed.style.display = 'block';
        } else {
            password.type = 'password';

            eyeOpen.style.display = 'block';
            eyeClosed.style.display = 'none';
        }
    }
</script>