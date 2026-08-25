<x-guest-layout>
    <x-auth-card :title="'Masuk ke Akun' . ($region ? ' — ' . $region->name : '')"
        subtitle="Sistem Informasi Pelaporan Notaris — Kanwil Kemenkum Bengkulu">

        <x-auth-session-status class="mb-4" :status="session('status')" />

        {{-- DESIGN ENHANCEMENT --}}
        <div class="login-modern-wrapper">

            {{-- Intro kecil --}}
            <div class="login-intro">
                <div class="login-intro-icon">
                    <svg xmlns="http://www.w3.org/2000/svg"
                        width="18"
                        height="18"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                        stroke-linecap="round"
                        stroke-linejoin="round">
                        <path d="M12 2L3 7l9 5 9-5-9-5z"></path>
                        <path d="M3 7v10l9 5 9-5V7"></path>
                        <path d="M12 12v10"></path>
                    </svg>
                </div>

                <div>
                    <div class="login-intro-title">
                        Portal Pelaporan Notaris
                    </div>

                    <div class="login-intro-text">
                        Silakan masuk menggunakan akun wilayah Anda
                    </div>
                </div>
            </div>

            @if ($region ?? null)
                <div class="mb-5 flex items-center gap-2 rounded-xl border border-yellow-200 bg-gradient-to-r from-yellow-50 to-amber-50 px-4 py-3">
                    <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-yellow-100 text-kumham-800">
                        <svg xmlns="http://www.w3.org/2000/svg"
                            width="17"
                            height="17"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                            stroke-linecap="round"
                            stroke-linejoin="round">
                            <path d="M12 2l9 4v6c0 5-3.8 9.7-9 11-5.2-1.3-9-6-9-11V6l9-4z"></path>
                            <path d="M9 12l2 2 4-4"></path>
                        </svg>
                    </div>

                    <div>
                        <div class="text-[10px] font-bold uppercase tracking-[0.15em] text-gray-500">
                            Wilayah Portal
                        </div>

                        <div class="text-sm font-bold text-kumham-800">
                            {{ $region->name }}
                        </div>
                    </div>
                </div>
            @endif

            <form method="POST" action="{{ route('login', ['slug' => $region?->slug]) }}">
                @csrf

                <div>
                    <x-input-label
                        for="email"
                        :value="__('Email')"
                        class="font-semibold text-gray-700"
                    />

                    <div class="login-input-wrapper mt-2">
                        <div class="login-input-icon">
                            <svg xmlns="http://www.w3.org/2000/svg"
                                width="18"
                                height="18"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="2"
                                stroke-linecap="round"
                                stroke-linejoin="round">
                                <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                                <polyline points="22,6 12,13 2,6"></polyline>
                            </svg>
                        </div>

                        <x-text-input
                            id="email"
                            class="login-enhanced-input block w-full"
                            type="email"
                            name="email"
                            :value="old('email')"
                            required
                            autofocus
                            autocomplete="username"
                            placeholder="nama@email.com"
                        />
                    </div>

                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <div class="mt-5">
                    <x-input-label
                        for="password"
                        :value="__('Password')"
                        class="font-semibold text-gray-700"
                    />

                    <div class="login-input-wrapper mt-2">

                        <div class="login-input-icon">
                            <svg xmlns="http://www.w3.org/2000/svg"
                                width="18"
                                height="18"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="2"
                                stroke-linecap="round"
                                stroke-linejoin="round">
                                <rect x="3" y="11" width="18" height="10" rx="2"></rect>
                                <path d="M7 11V7a5 5 0 0110 0v4"></path>
                            </svg>
                        </div>

                        <x-text-input
                            id="password"
                            class="login-enhanced-input block w-full"
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
                            class="login-password-toggle"
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

                <div class="mt-5 flex items-center justify-between gap-3">
                    <label for="remember_me" class="inline-flex cursor-pointer items-center">
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
                            class="text-sm font-semibold text-kumham-700 transition hover:text-kumham-500"
                            href="{{ route('password.request') }}"
                        >
                            Lupa password?
                        </a>
                    @endif
                </div>

                <div class="mt-6">
                    <x-primary-button class="login-submit-button w-full">
                        <span>Masuk</span>

                        <svg xmlns="http://www.w3.org/2000/svg"
                            width="18"
                            height="18"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                            stroke-linecap="round"
                            stroke-linejoin="round">
                            <line x1="5" y1="12" x2="19" y2="12"></line>
                            <polyline points="12 5 19 12 12 19"></polyline>
                        </svg>
                    </x-primary-button>
                </div>

                {{-- Security information --}}
                <div class="login-security">
                    <div class="login-security-icon">
                        <svg xmlns="http://www.w3.org/2000/svg"
                            width="16"
                            height="16"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                            stroke-linecap="round"
                            stroke-linejoin="round">
                            <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                            <polyline points="9 12 11 14 15 10"></polyline>
                        </svg>
                    </div>

                    <div>
                        <div class="font-semibold text-gray-700">
                            Akses aman & terpercaya
                        </div>

                        <div class="mt-0.5 text-xs text-gray-500">
                            Data pelaporan Anda dilindungi oleh sistem keamanan portal.
                        </div>
                    </div>
                </div>

            </form>
        </div>

        {{-- DESIGN CSS --}}
        <style>
            .login-modern-wrapper {
                position: relative;
            }

            .login-intro {
                display: flex;
                align-items: center;
                gap: 12px;
                margin-bottom: 20px;
                padding: 13px 15px;
                border: 1px solid #e5e7eb;
                border-radius: 14px;
                background: linear-gradient(135deg, #f8fafc 0%, #ffffff 100%);
            }

            .login-intro-icon {
                display: flex;
                align-items: center;
                justify-content: center;
                width: 38px;
                height: 38px;
                flex-shrink: 0;
                border-radius: 11px;
                color: #facc15;
                background: #172d70;
                box-shadow: 0 4px 12px rgba(23, 45, 112, 0.16);
            }

            .login-intro-title {
                color: #172d70;
                font-size: 14px;
                font-weight: 800;
                line-height: 1.2;
            }

            .login-intro-text {
                margin-top: 3px;
                color: #6b7280;
                font-size: 11px;
                line-height: 1.4;
            }

            .login-input-wrapper {
                position: relative;
            }

            .login-input-icon {
                position: absolute;
                top: 50%;
                left: 14px;
                z-index: 2;
                display: flex;
                align-items: center;
                justify-content: center;
                color: #64748b;
                pointer-events: none;
                transform: translateY(-50%);
            }

            .login-enhanced-input {
                min-height: 48px !important;
                padding-left: 44px !important;
                border: 1px solid #d5dbe5 !important;
                border-radius: 11px !important;
                background: #ffffff !important;
                color: #1f2937 !important;
                font-size: 14px !important;
                transition: all 0.2s ease !important;
            }

            .login-enhanced-input:hover {
                border-color: #b8c1d1 !important;
            }

            .login-enhanced-input:focus {
                border-color: #29458f !important;
                box-shadow: 0 0 0 3px rgba(41, 69, 143, 0.10) !important;
            }

            .login-password-toggle {
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
                transition: color 0.2s ease;
            }

            .login-password-toggle:hover {
                color: #172d70;
            }

            .login-submit-button {
                min-height: 50px !important;
                display: flex !important;
                align-items: center !important;
                justify-content: center !important;
                gap: 10px !important;
                border-radius: 11px !important;
                font-weight: 800 !important;
                letter-spacing: 0.02em !important;
                box-shadow: 0 7px 16px rgba(23, 45, 112, 0.18) !important;
                transition: all 0.2s ease !important;
            }

            .login-submit-button:hover {
                transform: translateY(-1px);
                box-shadow: 0 10px 22px rgba(23, 45, 112, 0.24) !important;
            }

            .login-security {
                display: flex;
                align-items: flex-start;
                gap: 10px;
                margin-top: 20px;
                padding: 12px 14px;
                border-radius: 11px;
                background: #f8fafc;
                border: 1px solid #edf0f4;
            }

            .login-security-icon {
                display: flex;
                align-items: center;
                justify-content: center;
                width: 28px;
                height: 28px;
                flex-shrink: 0;
                border-radius: 8px;
                color: #29458f;
                background: #e9eefb;
            }

            @media (max-width: 640px) {
                .login-intro {
                    padding: 11px 12px;
                }

                .login-intro-title {
                    font-size: 13px;
                }

                .login-security {
                    padding: 11px 12px;
                }
            }
        </style>

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
