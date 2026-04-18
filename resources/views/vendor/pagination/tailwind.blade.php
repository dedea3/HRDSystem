@if ($paginator->hasPages())
<nav class="flex items-center justify-between" aria-label="Pagination">
    <div class="text-sm text-slate-400">
        Menampilkan <span class="font-medium text-white">{{ $paginator->firstItem() }}</span>
        –<span class="font-medium text-white">{{ $paginator->lastItem() }}</span>
        dari <span class="font-medium text-white">{{ $paginator->total() }}</span> data
    </div>
    <div class="flex gap-1">
        {{-- Previous --}}
        @if ($paginator->onFirstPage())
            <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-slate-800/50 text-slate-600 cursor-not-allowed">
                ‹
            </span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}"
               class="flex h-9 w-9 items-center justify-center rounded-xl bg-slate-800 text-slate-300 hover:bg-slate-700 transition-colors">
                ‹
            </a>
        @endif

        {{-- Page numbers --}}
        @foreach ($elements as $element)
            @if (is_string($element))
                <span class="flex h-9 w-9 items-center justify-center rounded-xl text-slate-500">…</span>
            @endif
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-indigo-600 text-white font-semibold">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}"
                           class="flex h-9 w-9 items-center justify-center rounded-xl bg-slate-800 text-slate-300 hover:bg-slate-700 transition-colors">
                            {{ $page }}
                        </a>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Next --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}"
               class="flex h-9 w-9 items-center justify-center rounded-xl bg-slate-800 text-slate-300 hover:bg-slate-700 transition-colors">
                ›
            </a>
        @else
            <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-slate-800/50 text-slate-600 cursor-not-allowed">
                ›
            </span>
        @endif
    </div>
</nav>
@endif
