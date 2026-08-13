@props(['active'])

@php
$classes = ($active ?? false)
            ? 'inline-flex items-center px-3 pt-1 border-b-[3px] border-emas-500 text-sm font-bold leading-5 text-kumham-800 focus:outline-none transition duration-150 ease-in-out'
            : 'inline-flex items-center px-3 pt-1 border-b-[3px] border-transparent text-sm font-semibold leading-5 text-gray-600 hover:text-kumham-700 hover:border-kumham-200 focus:outline-none focus:text-kumham-700 transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
