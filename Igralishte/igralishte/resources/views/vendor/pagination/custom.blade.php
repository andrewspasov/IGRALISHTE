<link href="{{ asset('css/app.css') }}" rel="stylesheet">

@if ($paginator->hasPages())
    <nav role="navigation" aria-label="{{ __('Pagination Navigation') }}" class="flex justify-between items-center mt-4">
        <div>
            @if ($paginator->onFirstPage())
                <span class="pagination-arrow disabled" aria-disabled="true" aria-label="{{ __('pagination.previous') }}" style="font-family: 'Cormorant Garamond', serif;">
                    <svg width="20" height="20" viewBox="0 0 7 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M5.61445 0.773562L5.61429 0.773722L0.530399 5.84253C0.530365 5.84256 0.530332 5.8426 0.530298 5.84263C0.483749 5.8892 0.454657 5.93571 0.438308 5.98186L0.438047 5.98258C0.419088 6.03525 0.409026 6.09311 0.409026 6.15733C0.409026 6.22154 0.419088 6.27941 0.438048 6.33207L0.438306 6.3328C0.454669 6.37898 0.483792 6.42552 0.530399 6.47213L5.61445 11.5562C5.72485 11.6666 5.86129 11.7228 6.03485 11.7228C6.20524 11.7228 6.3477 11.6637 6.47034 11.5411C6.59458 11.4169 6.65206 11.2788 6.65206 11.1207C6.65206 10.9625 6.59458 10.8245 6.47034 10.7003L2.035 6.26494L1.92738 6.15733L2.035 6.04971L6.47034 1.61437C6.58153 1.50318 6.63697 1.36897 6.63697 1.20181C6.63697 1.03729 6.57865 0.89696 6.45526 0.773562C6.33101 0.64932 6.193 0.591845 6.03485 0.591845C5.87671 0.591845 5.73869 0.64932 5.61445 0.773562Z" fill="#000"/></svg>
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" class="pagination-arrow" rel="prev" aria-label="{{ __('pagination.previous') }}" style="font-family: 'Cormorant Garamond', serif;">
                    <svg width="20" height="20" viewBox="0 0 7 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M5.61445 0.773562L5.61429 0.773722L0.530399 5.84253C0.530365 5.84256 0.530332 5.8426 0.530298 5.84263C0.483749 5.8892 0.454657 5.93571 0.438308 5.98186L0.438047 5.98258C0.419088 6.03525 0.409026 6.09311 0.409026 6.15733C0.409026 6.22154 0.419088 6.27941 0.438048 6.33207L0.438306 6.3328C0.454669 6.37898 0.483792 6.42552 0.530399 6.47213L5.61445 11.5562C5.72485 11.6666 5.86129 11.7228 6.03485 11.7228C6.20524 11.7228 6.3477 11.6637 6.47034 11.5411C6.59458 11.4169 6.65206 11.2788 6.65206 11.1207C6.65206 10.9625 6.59458 10.8245 6.47034 10.7003L2.035 6.26494L1.92738 6.15733L2.035 6.04971L6.47034 1.61437C6.58153 1.50318 6.63697 1.36897 6.63697 1.20181C6.63697 1.03729 6.57865 0.89696 6.45526 0.773562C6.33101 0.64932 6.193 0.591845 6.03485 0.591845C5.87671 0.591845 5.73869 0.64932 5.61445 0.773562Z" fill="#000"/></svg>
                </a>
            @endif
        </div>


<div class="flex flex-wrap justify-center items-end">
    @if ($paginator->currentPage() > 3)
        <a href="{{ $paginator->url(1) }}" class="pagination-link mx-2">1</a>
        @if ($paginator->currentPage() > 4)
            <span class="mx-2 dot">•</span>
            <span class="pagination-dots mx-2" style="position: relative; top: 0px;">...</span>
        @else
            <span class="mx-2 dot">•</span>
        @endif
    @endif

    @foreach(range(1, $paginator->lastPage()) as $i)
        @if ($i >= $paginator->currentPage() - 2 && $i <= $paginator->currentPage() + 2)
            @if ($i == $paginator->currentPage())
                <span class="pagination-active mx-2">{{ $i }}</span>
            @else
                <a href="{{ $paginator->url($i) }}" class="pagination-link mx-2">{{ $i }}</a>
            @endif
            @if (!($i == $paginator->lastPage() || ($paginator->currentPage() < $paginator->lastPage() - 3 && $i == $paginator->currentPage() + 2)))
                <span class="mx-2 dot">•</span>
            @endif
        @endif
    @endforeach

    @if ($paginator->currentPage() < $paginator->lastPage() - 2)
        @if ($paginator->currentPage() < $paginator->lastPage() - 3)
            <span class="pagination-dots mx-2" style="position: relative; top: 0px;">...</span>
            <span class="mx-2 dot">•</span>

        @endif
        <a href="{{ $paginator->url($paginator->lastPage()) }}" class="pagination-link mx-2">{{ $paginator->lastPage() }}</a>
    @endif
</div>


        
            <div>
                @if ($paginator->hasMorePages())
                    <a href="{{ $paginator->nextPageUrl() }}" class="pagination-arrow" rel="next" aria-label="{{ __('pagination.next') }}" style="font-family: 'Cormorant Garamond', serif;">

                        <svg width="20" height="20" fill="none" viewBox="0 0 7 12" xmlns="http://www.w3.org/2000/svg">
                            <path d="M1.38555 11.4139L1.38571 11.4138L6.4696 6.34497C6.46963 6.34494 6.46967 6.3449 6.4697 6.34487C6.51625 6.2983 6.54534 6.25179 6.56169 6.20564L6.56195 6.20492C6.58091 6.15225 6.59097 6.09439 6.59097 6.03017C6.59097 5.96596 6.58091 5.90809 6.56195 5.85543L6.56169 5.8547C6.54533 5.80852 6.51621 5.76198 6.4696 5.71537L1.38555 0.631321C1.27515 0.520924 1.13871 0.46469 0.965147 0.46469C0.794761 0.46469 0.652304 0.523761 0.529658 0.646407C0.405415 0.77065 0.34794 0.908663 0.34794 1.06681C0.34794 1.22496 0.405415 1.36297 0.529658 1.48721L4.965 5.92256L5.07262 6.03017L4.965 6.13779L0.529658 10.5731C0.418472 10.6843 0.363026 10.8185 0.363026 10.9857C0.363026 11.1502 0.421345 11.2905 0.544743 11.4139C0.668986 11.5382 0.806999 11.5957 0.965147 11.5957C1.12329 11.5957 1.26131 11.5382 1.38555 11.4139Z" fill="#000"/></svg>
                    </a>
                @else
                    <span class="pagination-arrow disabled" aria-disabled="true" aria-label="{{ __('pagination.next') }}" style="font-family: 'Cormorant Garamond', serif;">
                        <svg width="20" height="20" fill="none" viewBox="0 0 7 12" xmlns="http://www.w3.org/2000/svg">
                            <path d="M1.38555 11.4139L1.38571 11.4138L6.4696 6.34497C6.46963 6.34494 6.46967 6.3449 6.4697 6.34487C6.51625 6.2983 6.54534 6.25179 6.56169 6.20564L6.56195 6.20492C6.58091 6.15225 6.59097 6.09439 6.59097 6.03017C6.59097 5.96596 6.58091 5.90809 6.56195 5.85543L6.56169 5.8547C6.54533 5.80852 6.51621 5.76198 6.4696 5.71537L1.38555 0.631321C1.27515 0.520924 1.13871 0.46469 0.965147 0.46469C0.794761 0.46469 0.652304 0.523761 0.529658 0.646407C0.405415 0.77065 0.34794 0.908663 0.34794 1.06681C0.34794 1.22496 0.405415 1.36297 0.529658 1.48721L4.965 5.92256L5.07262 6.03017L4.965 6.13779L0.529658 10.5731C0.418472 10.6843 0.363026 10.8185 0.363026 10.9857C0.363026 11.1502 0.421345 11.2905 0.544743 11.4139C0.668986 11.5382 0.806999 11.5957 0.965147 11.5957C1.12329 11.5957 1.26131 11.5382 1.38555 11.4139Z" fill="#000"/></svg>
                    </span>
                @endif
            </div>
        </nav>
@endif

<style>
.pagination-arrow, .pagination-link, .pagination-active {
    color: #232221;
    text-decoration: none;
    font-size: 1rem;
    font-family: 'Cormorant Garamond', serif;
}


.pagination-active {
    color: #FF5B29;
    font-weight: normal;
}

.pagination-arrow:hover {
    color: #FF5B29;
}

.pagination-dots {
    padding: 0 10px;
    font-family: 'Cormorant Garamond', serif;
}



@media (min-width: 768px) {
        .pagination-arrow, .pagination-link, .pagination-active {
            color: #232221;
            text-decoration: none;
            font-size: 2rem;
            font-family: 'Cormorant Garamond', serif;
            margin: 0 3px;
        }

        .pagination-active {
            color: #FF5B29;
            font-weight: normal;
        }

        .pagination-arrow:hover, .pagination-link:hover {
            color: #FF5B29;
        }

        .pagination-dots {
            font-size: 2rem;
            position: relative;
            top: -0.25em;
        }

        .pagination-dots, .pagination-link, .pagination-active {
            margin-left: 10px;
            margin-right: 10px;
        }

        .dot {
            position: relative;
            bottom: 10px;
        }
    }

</style>
