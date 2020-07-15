@if ($paginator->hasPages())
    <div class="nav-links">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())	
			<div class="disabled prev page-numbers"><span class="lnr lnr-arrow-left"></span></div>
			{{-- <li class="disabled"><span>&laquo;</span></li> --}}
        @else
			<a class="prev page-numbers" href="{{ $paginator->previousPageUrl() }}" rel="prev"><span class="lnr lnr-arrow-left"></span></a>
            {{-- <li><a href="{{ $paginator->previousPageUrl() }}" rel="prev">&laquo;</a></li> --}}
        @endif

        {{-- Pagination Elements --}}
        @foreach ($elements as $element)
            {{-- "Three Dots" Separator --}}
            @if (is_string($element))
                <div class="disabled"><span>{{ $element }}</span></div>
            @endif

            {{-- Array Of Links --}}
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
						<span class="page-numbers current">{{ $page }}</span>
                        {{-- <li class="active"><span>{{ $page }}</span></li> --}}
                    @else
						<a class="page-numbers" href="{{ $url }}">{{ $page }}</a>
                        {{-- <li><a href="{{ $url }}">{{ $page }}</a></li> --}}
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <a class="next page-numbers" href="{{ $paginator->nextPageUrl() }}" rel="next"><span class="lnr lnr-arrow-right"></span></a>
			{{-- <li><a href="{{ $paginator->nextPageUrl() }}" rel="next">&raquo;</a></li> --}}
        @else
			{{-- <span class="disabled next page-numbers gggggg"><span class="lnr lnr-arrow-righ"></span></span> --}}
			<div class="disabled next page-numbers" href="#"><span class="lnr lnr-arrow-right"></span></div>
            {{-- <li class="disabled"><span>&raquo;</span></li> --}}
        @endif
    </div>
@endif