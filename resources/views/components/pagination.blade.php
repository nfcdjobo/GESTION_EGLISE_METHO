@if ($paginator->hasPages())
    <nav class="flex items-center justify-between border-t border-gray-200 bg-white px-4 py-3 sm:px-6" role="navigation" aria-label="{{ __('Pagination Navigation') }}">
        <div class="flex flex-1 justify-between sm:hidden">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <span class="relative inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-500 cursor-not-allowed">
                    <i class="fas fa-chevron-left mr-2"></i>
                    Précédent
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" class="relative inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
                    <i class="fas fa-chevron-left mr-2"></i>
                    Précédent
                </a>
            @endif

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" class="relative ml-3 inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
                    Suivant
                    <i class="fas fa-chevron-right ml-2"></i>
                </a>
            @else
                <span class="relative ml-3 inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-500 cursor-not-allowed">
                    Suivant
                    <i class="fas fa-chevron-right ml-2"></i>
                </span>
            @endif
        </div>

        <div class="hidden sm:flex sm:flex-1 sm:items-center sm:justify-between">
            <div>
                <p class="text-sm text-gray-700">
                    Affichage de
                    <span class="font-medium">{{ $paginator->firstItem() ?? 0 }}</span>
                    à
                    <span class="font-medium">{{ $paginator->lastItem() ?? 0 }}</span>
                    sur
                    <span class="font-medium">{{ $paginator->total() }}</span>
                    résultats
                </p>
            </div>
            <div>
                <nav class="isolate inline-flex -space-x-px rounded-md shadow-sm" aria-label="Pagination">
                    {{-- Previous Page Link --}}
                    @if ($paginator->onFirstPage())
                        <span aria-disabled="true" aria-label="{{ __('pagination.previous') }}">
                            <span class="relative inline-flex items-center rounded-l-md border border-gray-300 bg-white px-2 py-2 text-sm font-medium text-gray-500 cursor-not-allowed" aria-hidden="true">
                                <i class="fas fa-chevron-left"></i>
                            </span>
                        </span>
                    @else
                        <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="relative inline-flex items-center rounded-l-md border border-gray-300 bg-white px-2 py-2 text-sm font-medium text-gray-500 hover:bg-gray-50" aria-label="{{ __('pagination.previous') }}">
                            <i class="fas fa-chevron-left"></i>
                        </a>
                    @endif

                    {{-- Pagination Elements --}}
                    @foreach ($elements as $element)
                        {{-- "Three Dots" Separator --}}
                        @if (is_string($element))
                            <span aria-disabled="true">
                                <span class="relative inline-flex items-center border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700">{{ $element }}</span>
                            </span>
                        @endif

                        {{-- Array Of Links --}}
                        @if (is_array($element))
                            @foreach ($element as $page => $url)
                                @if ($page == $paginator->currentPage())
                                    <span aria-current="page">
                                        <span class="relative z-10 inline-flex items-center border border-indigo-500 bg-indigo-50 px-4 py-2 text-sm font-medium text-indigo-600">{{ $page }}</span>
                                    </span>
                                @else
                                    <a href="{{ $url }}" class="relative inline-flex items-center border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-500 hover:bg-gray-50" aria-label="{{ __('Aller à la page :page', ['page' => $page]) }}">
                                        {{ $page }}
                                    </a>
                                @endif
                            @endforeach
                        @endif
                    @endforeach

                    {{-- Next Page Link --}}
                    @if ($paginator->hasMorePages())
                        <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="relative inline-flex items-center rounded-r-md border border-gray-300 bg-white px-2 py-2 text-sm font-medium text-gray-500 hover:bg-gray-50" aria-label="{{ __('pagination.next') }}">
                            <i class="fas fa-chevron-right"></i>
                        </a>
                    @else
                        <span aria-disabled="true" aria-label="{{ __('pagination.next') }}">
                            <span class="relative inline-flex items-center rounded-r-md border border-gray-300 bg-white px-2 py-2 text-sm font-medium text-gray-500 cursor-not-allowed" aria-hidden="true">
                                <i class="fas fa-chevron-right"></i>
                            </span>
                        </span>
                    @endif
                </nav>
            </div>
        </div>
    </nav>
@endif
