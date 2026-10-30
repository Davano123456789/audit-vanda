{{-- Pagination berbahasa Indonesia (menggantikan tampilan bawaan Laravel) --}}
@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Navigasi halaman" class="flex flex-col items-center justify-between gap-3 sm:flex-row">
        <p class="text-sm text-gray-500">
            Menampilkan <span class="font-medium text-ink">{{ $paginator->firstItem() }}</span>
            &ndash; <span class="font-medium text-ink">{{ $paginator->lastItem() }}</span>
            dari <span class="font-medium text-ink">{{ $paginator->total() }}</span> data
        </p>

        <div class="inline-flex overflow-hidden rounded-lg border border-gray-300 text-sm">
            {{-- Sebelumnya --}}
            @if ($paginator->onFirstPage())
                <span class="px-3 py-1.5 text-gray-300" aria-disabled="true"><i data-lucide="chevron-left" class="h-4 w-4"></i></span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="px-3 py-1.5 text-gray-600 hover:bg-gray-50" aria-label="Sebelumnya">
                    <i data-lucide="chevron-left" class="h-4 w-4"></i>
                </a>
            @endif

            @foreach ($elements as $element)
                @if (is_string($element))
                    <span class="border-l border-gray-300 px-3 py-1.5 text-gray-400">{{ $element }}</span>
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span aria-current="page" class="border-l border-gray-300 bg-brand-600 px-3 py-1.5 font-medium text-white">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}" class="border-l border-gray-300 px-3 py-1.5 text-gray-600 hover:bg-gray-50">{{ $page }}</a>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Berikutnya --}}
            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="border-l border-gray-300 px-3 py-1.5 text-gray-600 hover:bg-gray-50" aria-label="Berikutnya">
                    <i data-lucide="chevron-right" class="h-4 w-4"></i>
                </a>
            @else
                <span class="border-l border-gray-300 px-3 py-1.5 text-gray-300" aria-disabled="true"><i data-lucide="chevron-right" class="h-4 w-4"></i></span>
            @endif
        </div>
    </nav>
@endif
