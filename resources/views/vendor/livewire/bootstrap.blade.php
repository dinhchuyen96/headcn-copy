@php
$end = $paginator->perPage() * ($paginator->currentPage() - 1) + $paginator->count();
@endphp

<nav class="row mt-3">
    <div class="col-md-6 d-flex align-items-center">
        <span>{{ $paginator->firstItem() }} - {{ $end }} / {{ $paginator->total() }} item</span>
    </div>
    <div class="col-md-6 text-right">
        <ul class="pagination m-0 justify-content-end">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <li class="page-item disabled" aria-disabled="true" aria-label="@lang('pagination.previous')">
                    <span class="page-link" aria-hidden="true"><</span>
                </li>
            @else

                <li class="page-item d-flex">
                    <button type="button" dusk="firstPage" class="page-link" wire:click="gotoPage(1)"
                        wire:loading.attr="disabled" rel="first"
                        aria-label="@lang('pagination.first')"><<</button>
                    <button type="button" dusk="previousPage" class="page-link" wire:click="previousPage"
                        wire:loading.attr="disabled" rel="prev"
                        aria-label="@lang('pagination.previous')"><</button>

                </li>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <li class="page-item disabled" aria-disabled="true"><span
                            class="page-link">{{ $element }}</span></li>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li class="page-item active" wire:key="paginator-page-{{ $page }}"
                                aria-current="page"><span class="page-link">{{ $page }}</span></li>
                        @else
                            <li class="page-item" wire:key="paginator-page-{{ $page }}"><button
                                    type="button" class="page-link"
                                    wire:click="gotoPage({{ $page }})">{{ $page }}</button></li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <li class="page-item d-flex">
                    <button type="button" dusk="nextPage" class="page-link" wire:click="nextPage"
                        wire:loading.attr="disabled" rel="next" aria-label="@lang('pagination.next')">></button>
                    <button type="button" dusk="firstPage" class="page-link" wire:click="gotoPage({{$paginator->lastPage()}})"
                        wire:loading.attr="disabled" rel="first"
                        aria-label="@lang('pagination.first')">>></button>
                </li>
            @else
                <li class="page-item disabled" aria-disabled="true" aria-label="@lang('pagination.next')">
                    <span class="page-link" aria-hidden="true">></span>
                </li>
            @endif
        </ul>
        {{-- <ul class="pagination m-0 justify-content-end">
            @if (!$paginator->onFirstPage())
                <li class="page-item">
                    <a class="page-link" href="{{ $paginator->url(1) }}" rel="prev" aria-label="@lang('pagination.previous')"><<</a>
                </li>
                <li class="page-item">
                    <a class="page-link" href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="@lang('pagination.previous')"><</a>
                </li>
            @endif
            @foreach (range(1, $paginator->lastPage()) as $i)
                @if ($paginator->currentPage() == 1)
                    @if ($i <= $paginator->currentPage() + 4)
                        @if ($i == $paginator->currentPage())
                            <li class="page-item active" aria-current="page"><span class="page-link">{{ $i }}</span></li>
                        @else
                            <li class="page-item"><a class="page-link" href="{{ $paginator->url($i) }}">{{ $i }}</a></li>
                        @endif
                    @endif
                @elseif ($paginator->currentPage() == 2)
                    @if ($i <= $paginator->currentPage() + 3)
                        @if ($i == $paginator->currentPage())
                            <li class="page-item active" aria-current="page"><span class="page-link">{{ $i }}</span></li>
                        @else
                            <li class="page-item"><a class="page-link" href="{{ $paginator->url($i) }}">{{ $i }}</a></li>
                        @endif
                    @endif
                @elseif ($paginator->currentPage() == $paginator->lastPage())
                    @if ($i >= $paginator->currentPage() - 4)
                        @if ($i == $paginator->currentPage())
                            <li class="page-item active" aria-current="page"><span class="page-link">{{ $i }}</span></li>
                        @else
                            <li class="page-item"><a class="page-link" href="{{ $paginator->url($i) }}">{{ $i }}</a></li>
                        @endif
                    @endif
                @elseif ($paginator->currentPage() == $paginator->lastPage()-1)
                    @if ($i >= $paginator->currentPage() - 3)
                        @if ($i == $paginator->currentPage())
                            <li class="page-item active" aria-current="page"><span class="page-link">{{ $i }}</span></li>
                        @else
                            <li class="page-item"><a class="page-link" href="{{ $paginator->url($i) }}">{{ $i }}</a></li>
                        @endif
                    @endif
                @else
                    @if ($i >= $paginator->currentPage() - 2 && $i <= $paginator->currentPage() + 2)
                        @if ($i == $paginator->currentPage())
                            <li class="page-item active" aria-current="page"><span class="page-link">{{ $i }}</span></li>
                        @else
                            <li class="page-item"><a class="page-link" href="{{ $paginator->url($i) }}">{{ $i }}</a></li>
                        @endif
                    @endif
                @endif
            @endforeach
            @if ($paginator->hasMorePages())
                <li class="page-item">
                    <a class="page-link" href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="@lang('pagination.next')">></a>
                </li>
                <li class="page-item">
                    <a class="page-link" href="{{ $paginator->url($paginator->lastPage()) }}" rel="next" aria-label="@lang('pagination.next')">>></a>
                </li>
            @endif
        </ul> --}}
    </div>
</nav>
