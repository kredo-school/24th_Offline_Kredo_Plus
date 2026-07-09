<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-6 py-3 bg-brand-red border border-transparent rounded-full font-bold text-sm text-white hover:bg-red-600 active:scale-95 focus:outline-none focus:ring-2 focus:ring-red-400 focus:ring-offset-2 transition-all']) }}>
    {{ $slot }}
</button>
