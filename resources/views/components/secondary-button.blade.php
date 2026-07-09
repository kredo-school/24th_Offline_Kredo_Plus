<button {{ $attributes->merge(['type' => 'button', 'class' => 'inline-flex items-center px-6 py-3 bg-white border border-slate-200 rounded-full font-bold text-sm text-slate-600 shadow-sm hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-brand-blue/30 focus:ring-offset-2 disabled:opacity-25 transition-all']) }}>
    {{ $slot }}
</button>
