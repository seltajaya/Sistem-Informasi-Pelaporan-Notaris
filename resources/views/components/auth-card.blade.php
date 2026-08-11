@props(['title', 'subtitle'])

<div class="card-panel overflow-hidden">
    <div class="border-b border-gray-200 bg-kumham-50/50 px-8 pt-6 pb-5">
        <h2 class="text-xl font-extrabold tracking-tight text-kumham-950">{{ $title }}</h2>
        @isset($subtitle)
            <p class="mt-1 text-sm text-gray-500">{{ $subtitle }}</p>
        @endisset
    </div>
    <div class="px-8 py-6">
        {{ $slot }}
    </div>
</div>
