@if ($paginator->hasPages())
<nav role="navigation" aria-label="Pagination" class="flex flex-col sm:flex-row justify-between items-center gap-4 mt-4">
    <div class="flex items-center gap-2">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <span class="inline-flex items-center px-4 py-2 border border-gray-200 text-sm font-medium rounded-md text-gray-400 bg-gray-50 cursor-not-allowed" aria-disabled="true">
                &laquo; Previous
            </span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" 
               class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-primary bg-white hover:bg-gray-50 focus:outline-none focus-visible:ring-2 focus-visible:ring-primary/50 transition-colors"
               aria-label="Previous page">
                &laquo; Previous
            </a>
        @endif

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" 
               class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-primary bg-white hover:bg-gray-50 focus:outline-none focus-visible:ring-2 focus-visible:ring-primary/50 transition-colors"
               aria-label="Next page">
                Next &raquo;
            </a>
        @else
            <span class="inline-flex items-center px-4 py-2 border border-gray-200 text-sm font-medium rounded-md text-gray-400 bg-gray-50 cursor-not-allowed" aria-disabled="true">
                Next &raquo;
            </span>
        @endif
    </div>

    <p class="text-sm text-gray-500">
        Menampilkan {{ $paginator->firstItem() ?? 0 }} - {{ $paginator->lastItem() ?? 0 }} dari {{ $paginator->total() ?? 0 }} data
    </p>
</nav>
@endif