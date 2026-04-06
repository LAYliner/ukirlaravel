@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Pagination Navigation">
        <div style="display: flex; gap: 0.5rem; align-items: center;">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <span style="padding: 0.5rem 1rem; border: 1px solid #ddd; border-radius: 4px; color: #adb5bd; cursor: not-allowed;">
                    &laquo; Previous
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" 
                   style="padding: 0.5rem 1rem; border: 1px solid #ddd; border-radius: 4px; color: #3498db; text-decoration: none;">
                    &laquo; Previous
                </a>
            @endif

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" 
                   style="padding: 0.5rem 1rem; border: 1px solid #ddd; border-radius: 4px; color: #3498db; text-decoration: none;">
                    Next &raquo;
                </a>
            @else
                <span style="padding: 0.5rem 1rem; border: 1px solid #ddd; border-radius: 4px; color: #adb5bd; cursor: not-allowed;">
                    Next &raquo;
                </span>
            @endif
        </div>
        
        <div style="margin-top: 0.5rem; color: #6c757d; font-size: 0.875rem;">
            Showing {{ $paginator->firstItem() }} to {{ $paginator->lastItem() }} of {{ $paginator->total() }} results
        </div>
    </nav>
@endif