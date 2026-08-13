<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-kumham-700 border border-transparent rounded-lg font-semibold text-sm text-white uppercase tracking-wider hover:bg-kumham-600 active:bg-kumham-800 active:scale-[0.98] focus:outline-none focus:ring-2 focus:ring-kumham-500 focus:ring-offset-2 transition duration-200']) }}>
    {{ $slot }}
</button>
