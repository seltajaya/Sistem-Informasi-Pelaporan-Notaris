@props(['dark' => false, 'compact' => false])

@php
    $hasLogo = file_exists(public_path('images/logo-kemenkumham.svg'))
        || file_exists(public_path('images/logo-kemenkumham.png'));
    $text = $dark ? 'text-white' : 'text-kumham-900';
    $sub = $dark ? 'text-emas-300' : 'text-emas-600';
@endphp

<div class="flex items-center gap-3">
    @if ($hasLogo)
        <img src="{{ asset('images/logo-kemenkumham.svg') }}" onerror="this.src='{{ asset('images/logo-kemenkumham.png') }}'"
            alt="Logo Kementerian Hukum dan HAM RI"
            class="{{ $compact ? 'h-12 w-12' : 'h-14 w-14' }} shrink-0 object-contain">
    @else
        {{-- Placeholder mark; drop the official logo at public/images/logo-kemenkumham.{svg,png} --}}
        <div class="{{ $compact ? 'h-12 w-12' : 'h-14 w-14' }} shrink-0 rounded-xl bg-kumham-800 ring-1 ring-white/20 flex items-center justify-center shadow-card">
            <svg class="{{ $compact ? 'h-7 w-7' : 'h-8 w-8' }}" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                <path d="M12 3v3m0 0c-.8 0-1.6.4-2.2 1-.6.6-1 1.4-1 2.2v1h6.4v-1c0-.8-.4-1.6-1-2.2-.6-.6-1.4-1-2.2-1Zm0 0V5.5m-3.2 3.7h6.4M6.4 13l-1.2 2.4h13.6L17.6 13M7.2 15.4 12 21l4.8-5.6" stroke="#D4AF37" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </div>
    @endif

    <div class="leading-tight">
        <p class="{{ $compact ? 'text-[11px]' : 'text-xs' }} font-bold uppercase tracking-[0.16em] {{ $sub }}">
            Kementerian Hukum dan Hak Asasi Manusia RI
        </p>
        <p class="{{ $compact ? 'text-sm' : 'text-base' }} font-extrabold uppercase tracking-wide {{ $text }}">
            Kantor Wilayah Bengkulu
        </p>
        <p class="{{ $compact ? 'text-[10px]' : 'text-[11px]' }} font-medium {{ $dark ? 'text-white/70' : 'text-gray-500' }}">
            Sistem Informasi Pelaporan Notaris
        </p>
    </div>
</div>
