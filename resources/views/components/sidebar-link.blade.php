@php
    $active = request()->routeIs($route . '*');
@endphp
<a href="{{ route($route) }}"
   class="group flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium transition-all duration-150
          {{ $active ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-900/50' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
    <svg class="h-5 w-5 shrink-0 {{ $active ? 'text-white' : 'text-slate-500 group-hover:text-slate-300' }}"
         fill="none" viewBox="0 0 24 24" stroke="currentColor">
        {!! $icon !!}
    </svg>
    {{ $label }}
</a>
