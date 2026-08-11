@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'border-gray-300 focus:border-kumham-500 focus:ring-kumham-500 rounded-lg shadow-sm']) }}>
