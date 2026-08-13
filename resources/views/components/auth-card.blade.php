@props(['title', 'subtitle'])

<div class="card-panel overflow-hidden">
    <div
        class="border-b border-gray-200 bg-kumham-50/50 px-8 pt-6 pb-5"
        style="position: relative;"
    >

        {{-- HEADER --}}
        <div style="padding-right: 80px;">
            <h2 class="text-xl font-extrabold tracking-tight text-kumham-950">
                {{ $title }}
            </h2>

            @isset($subtitle)
                <p class="mt-1 text-sm text-gray-500">
                    {{ $subtitle }}
                </p>
            @endisset
        </div>

        {{-- LOGO KEMENKUM - POJOK KANAN HEADER --}}
        <div
            style="
                position: absolute;
                top: 18px;
                right: 24px;
                width: 52px;
                height: 52px;
                display: flex;
                align-items: center;
                justify-content: center;
            "
        >
            <img
                src="{{ asset('images/logo-kemenkum.PNG') }}"
                alt="Logo Kementerian Hukum dan HAM"
                style="
                    width: 52px;
                    height: 52px;
                    object-fit: contain;
                "
            >
        </div>

    </div>

    <div class="px-8 py-6">
        {{ $slot }}
    </div>
</div>