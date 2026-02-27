@if ($paginator->hasPages())
    <nav class="techno-pagination" role="navigation" aria-label="Навігація по сторінках">
        <ul class="pagination-list">
            {{-- Previous --}}
            @if ($paginator->onFirstPage())
                <li class="pagination-item pagination-item--disabled" aria-disabled="true">
                    <span>{{ __('pagination.previous') }}</span>
                </li>
            @else
                <li class="pagination-item">
                    <a class="pagination-link" href="{{ $paginator->previousPageUrl() }}" rel="prev">
                        {{ __('pagination.previous') }}
                    </a>
                </li>
            @endif

            {{-- Pagination Elements --}}
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
                                <a class="pagination-link" href="{{ $url }}" aria-label="{{ __('Перейти на сторінку :page', ['page' => $page]) }}">
                                    {{ $page }}
                                </a>
                            </li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next --}}
            @if ($paginator->hasMorePages())
                <li class="pagination-item">
                    <a class="pagination-link" href="{{ $paginator->nextPageUrl() }}" rel="next">
                        {{ __('pagination.next') }}
                    </a>
                </li>
            @else
                <li class="pagination-item pagination-item--disabled" aria-disabled="true">
                    <span>{{ __('pagination.next') }}</span>
                </li>
            @endif
        </ul>
    </nav>
@endif
