@if ($paginator->hasPages())
    <nav class="techno-pagination" role="navigation" aria-label="{{ __('ui.pagination.aria') }}">
        <ul class="pagination-list">
            @if ($paginator->onFirstPage())
                <li class="pagination-item pagination-item--prev pagination-item--disabled" aria-disabled="true">
                    <span class="pagination-link pagination-link--arrow" aria-hidden="true">
                        <svg class="pagination-icon" viewBox="0 0 24 24" fill="none">
                            <path d="M14.5 6.5L9 12l5.5 5.5" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </span>
                </li>
            @else
                <li class="pagination-item pagination-item--prev">
                    <a class="pagination-link pagination-link--arrow" href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="{{ __('pagination.previous') }}">
                        <svg class="pagination-icon" viewBox="0 0 24 24" fill="none">
                            <path d="M14.5 6.5L9 12l5.5 5.5" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </a>
                </li>
            @endif

            @foreach ($elements as $element)
                @if (is_string($element))
                    <li class="pagination-item pagination-item--dots" aria-disabled="true">
                        <span>{{ $element }}</span>
                    </li>
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page === $paginator->currentPage())
                            <li class="pagination-item pagination-item--active" aria-current="page">
                                <span>{{ $page }}</span>
                            </li>
                        @else
                            <li class="pagination-item">
                                <a class="pagination-link" href="{{ $url }}" aria-label="{{ __('ui.pagination.goto', ['page' => $page]) }}">
                                    {{ $page }}
                                </a>
                            </li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            @if ($paginator->hasMorePages())
                <li class="pagination-item pagination-item--next">
                    <a class="pagination-link pagination-link--arrow" href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="{{ __('pagination.next') }}">
                        <svg class="pagination-icon" viewBox="0 0 24 24" fill="none">
                            <path d="M9.5 6.5L15 12l-5.5 5.5" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </a>
                </li>
            @else
                <li class="pagination-item pagination-item--next pagination-item--disabled" aria-disabled="true">
                    <span class="pagination-link pagination-link--arrow" aria-hidden="true">
                        <svg class="pagination-icon" viewBox="0 0 24 24" fill="none">
                            <path d="M9.5 6.5L15 12l-5.5 5.5" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </span>
                </li>
            @endif
        </ul>
    </nav>
@endif

