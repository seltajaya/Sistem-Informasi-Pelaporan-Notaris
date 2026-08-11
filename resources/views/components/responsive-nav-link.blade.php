@props(['active'])

@php
$classes = ($active ?? false)
            ? 'block w-full ps-3 pe-4 py-2 border-l-4 border-emas-500 text-start text-base font-bold text-kumham-800 bg-kumham-50 focus:outline-none focus:text-kumham-800 focus:bg-kumham-100 transition duration-150 ease-in-out'
            : 'block w-full ps-3 pe-4 py-2 border-l-4 border-transparent text-start text-base font-medium text-gray-600 hover:text-kumham-700 hover:bg-kumham-50 hover:border-kumham-200 focus:outline-none focus:text-kumham-700 focus:bg-kumham-50 focus:border-kumham-200 transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
