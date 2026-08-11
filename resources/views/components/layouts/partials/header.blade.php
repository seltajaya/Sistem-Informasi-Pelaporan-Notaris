@props(['title' => config('app.name')])

<div class="institutional-header relative overflow-hidden text-white">
    {{-- Red-white accent strip (identitas negara) --}}
    <div class="absolute inset-x-0 top-0 h-1.5 flex" aria-hidden="true">
        <span class="flex-1 bg-red-600"></span>
        <span class="flex-1 bg-white"></span>
        <span class="flex-1 bg-red-600"></span>
        <span class="flex-1 bg-white"></span>
    </div>

    <div class="mx-auto max-w-container px-4 sm:px-6 lg:px-8">
        <div class="flex flex-wrap items-center justify-between gap-6 py-6">
            <x-layouts.partials.logo dark />

            <div class="hidden md:block text-right">
                <p class="text-sm font-semibold text-white/90">{{ $title }}</p>
                <p class="text-xs text-white/60">{{ now()->locale('id')->translatedFormat('l, d F Y') }}</p>
            </div>
        </div>
    </div>
</div>
