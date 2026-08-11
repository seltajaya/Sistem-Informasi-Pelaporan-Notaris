<button {{ $attributes->merge(['type' => 'button', 'class' => 'inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-white border border-gray-300 rounded-lg font-semibold text-sm text-kumham-800 uppercase tracking-wider shadow-sm hover:bg-gray-50 active:scale-[0.98] focus:outline-none focus:ring-2 focus:ring-kumham-500 focus:ring-offset-2 disabled:opacity-25 transition duration-200']) }}>
    {{ $slot }}
</button>
