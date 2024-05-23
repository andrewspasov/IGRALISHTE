
        <div>

     {{-- Pagination Elements --}}
     <div class="flex flex-wrap justify-center items-end">
            {{-- First Page Link --}}
            @if ($paginator->currentPage() > 3)
                <a href="{{ $paginator->url(1) }}" class="pagination-link mx-2">1</a>
                @if ($paginator->currentPage() > 4)
                    {{-- "Three Dots" Separator --}}
                    <span class="pagination-dots mx-2" style="position: relative; top: 0px;">. . .</span>
                @endif
            @endif

            {{-- Array Of Links --}}
            @foreach(range(1, $paginator->lastPage()) as $i)
                @if ($i >= $paginator->currentPage() - 2 && $i <= $paginator->currentPage() + 2)
                    @if ($i == $paginator->currentPage())
                        <span class="pagination-active mx-2">{{ $i }}</span>
                    @else
                        <a href="{{ $paginator->url($i) }}" class="pagination-link mx-2">{{ $i }}</a>
                    @endif
                    @if (!$loop->last)
                            <span class="mx-2" style="position: relative; bottom: 12px;">•</span> <!-- Dot added here -->
                        @endif
                @endif
            @endforeach

            {{-- Last Page Link --}}
            @if ($paginator->currentPage() < $paginator->lastPage() - 2)
                @if ($paginator->currentPage() < $paginator->lastPage() - 3)
                    {{-- "Three Dots" Separator --}}
                    <span class="pagination-dots mx-2" style="position: relative; top: 0px;">...</span>
                @endif
                <a href="{{ $paginator->url($paginator->lastPage()) }}" class="pagination-link mx-2">{{ $paginator->lastPage() }}</a>
            @endif
        </div>

            </div>
