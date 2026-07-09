@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'border-slate-200 focus:border-brand-blue focus:ring-2 focus:ring-brand-blue/40 rounded-xl shadow-sm text-sm py-3']) }}>
