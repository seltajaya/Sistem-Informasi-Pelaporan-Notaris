@props(['value'])

<label {{ $attributes->merge(['class' => 'block font-semibold text-sm text-kumham-900']) }}>
    {{ $value ?? $slot }}
</label>
