<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center gap-2 px-6 py-3 bg-brand-blue border border-transparent rounded-full font-bold text-sm text-white shadow-soft hover:bg-indigo-700 active:scale-95 focus:outline-none focus:ring-2 focus:ring-brand-blue/40 focus:ring-offset-2 transition-all']) }}>
    {{ $slot }}
</button>
