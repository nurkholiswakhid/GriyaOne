@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Pagination Navigation">

        {{-- Mobile View --}}
        <div class="flex items-center justify-between sm:hidden">
            <div class="flex items-center gap-2">
                @if ($paginator->onFirstPage())
                    <span class="inline-flex items-center gap-1.5 px-3.5 py-2 text-sm font-medium text-gray-400 bg-gray-100 cursor-not-allowed rounded-lg">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                        Sebelumnya
                    </span>
                @else
                    <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="inline-flex items-center gap-1.5 px-3.5 py-2 text-sm font-medium text-red-600 bg-white border border-gray-200 rounded-lg hover:bg-red-50 hover:border-red-200 active:bg-red-100 transition duration-200 shadow-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                        Sebelumnya
                    </a>
                @endif
            </div>

            <span class="inline-flex items-center px-3 py-1.5 text-sm font-semibold text-gray-700 bg-white border border-gray-200 rounded-lg shadow-sm">
                {{ $paginator->currentPage() }} <span class="text-gray-400 mx-1">/</span> {{ $paginator->lastPage() }}
            </span>

            <div class="flex items-center gap-2">
                @if ($paginator->hasMorePages())
                    <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="inline-flex items-center gap-1.5 px-3.5 py-2 text-sm font-medium text-red-600 bg-white border border-gray-200 rounded-lg hover:bg-red-50 hover:border-red-200 active:bg-red-100 transition duration-200 shadow-sm">
                        Berikutnya
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                    </a>
                @else
                    <span class="inline-flex items-center gap-1.5 px-3.5 py-2 text-sm font-medium text-gray-400 bg-gray-100 cursor-not-allowed rounded-lg">
                        Berikutnya
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                    </span>
                @endif
            </div>
        </div>

        {{-- Desktop View --}}
        <div class="hidden sm:flex sm:items-center sm:justify-between">
            <div>
                <p class="text-sm text-gray-500">
                    Menampilkan
                    @if ($paginator->firstItem())
                        <span class="font-semibold text-gray-800">{{ $paginator->firstItem() }}</span>
                        <span class="mx-0.5">–</span>
                        <span class="font-semibold text-gray-800">{{ $paginator->lastItem() }}</span>
                    @else
                        <span class="font-semibold text-gray-800">0</span>
                    @endif
                    dari
                    <span class="font-semibold text-gray-800">{{ $paginator->total() }}</span>
                    video
                </p>
            </div>

            <div class="flex items-center gap-1.5">

                {{-- Previous Page Link --}}
                @if ($paginator->onFirstPage())
                    <span aria-disabled="true">
                        <span class="inline-flex items-center gap-1 px-3 py-2 text-sm font-medium text-gray-300 bg-gray-50 border border-gray-200 cursor-not-allowed rounded-lg" aria-hidden="true">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                        </span>
                    </span>
                @else
                    <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="inline-flex items-center gap-1 px-3 py-2 text-sm font-medium text-gray-600 bg-white border border-gray-200 rounded-lg hover:bg-red-50 hover:text-red-600 hover:border-red-300 active:bg-red-100 focus:outline-none focus:ring-2 focus:ring-red-500/20 transition duration-200 shadow-sm" aria-label="Sebelumnya">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                    </a>
                @endif

                {{-- Page Number Separator (left side) --}}
                <span class="w-px h-5 bg-gray-200 mx-0.5"></span>

                {{-- Pagination Elements --}}
                @foreach ($elements as $element)
                    {{-- "Three Dots" Separator --}}
                    @if (is_string($element))
                        <span aria-disabled="true">
                            <span class="inline-flex items-center justify-center w-10 h-10 text-sm text-gray-400 font-medium cursor-default select-none">•••</span>
                        </span>
                    @endif

                    {{-- Array Of Links --}}
                    @if (is_array($element))
                        @foreach ($element as $page => $url)
                            @if ($page == $paginator->currentPage())
                                <span aria-current="page">
                                    <span class="inline-flex items-center justify-center w-10 h-10 text-sm font-bold text-white bg-gradient-to-br from-red-600 to-red-700 rounded-lg shadow-md shadow-red-200 cursor-default select-none">{{ $page }}</span>
                                </span>
                            @else
                                <a href="{{ $url }}" class="inline-flex items-center justify-center w-10 h-10 text-sm font-medium text-gray-600 bg-white border border-gray-200 rounded-lg hover:bg-red-50 hover:text-red-600 hover:border-red-300 active:bg-red-100 focus:outline-none focus:ring-2 focus:ring-red-500/20 transition duration-200 shadow-sm" aria-label="Halaman {{ $page }}">
                                    {{ $page }}
                                </a>
                            @endif
                        @endforeach
                    @endif
                @endforeach

                {{-- Page Number Separator (right side) --}}
                <span class="w-px h-5 bg-gray-200 mx-0.5"></span>

                {{-- Next Page Link --}}
                @if ($paginator->hasMorePages())
                    <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="inline-flex items-center gap-1 px-3 py-2 text-sm font-medium text-gray-600 bg-white border border-gray-200 rounded-lg hover:bg-red-50 hover:text-red-600 hover:border-red-300 active:bg-red-100 focus:outline-none focus:ring-2 focus:ring-red-500/20 transition duration-200 shadow-sm" aria-label="Berikutnya">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                    </a>
                @else
                    <span aria-disabled="true">
                        <span class="inline-flex items-center gap-1 px-3 py-2 text-sm font-medium text-gray-300 bg-gray-50 border border-gray-200 cursor-not-allowed rounded-lg" aria-hidden="true">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                        </span>
                    </span>
                @endif
            </div>
        </div>
    </nav>
@endif


