@if ($model->hasPages())
    <ul class="pagination">
        {{-- Previous Page Link --}}
        @php
            $prevPageUrl = ($model->currentPage() > 1) ? str_replace('?page=', '/trang-', $model->previousPageUrl()).config('apps.general.suffix') : null;
        @endphp
        @if ($prevPageUrl)
            <li class="page-item"><a class="page-link" href="{{ $prevPageUrl }}">Previous</a></li>
        @else
            <li class="page-item disabled"><span class="page-link">Previous</span></li>
        @endif

        {{-- Pagination Links --}}
        @foreach ($model->getUrlRange(max(1, $model->currentPage() - 2), min($model->lastPage(), $model->currentPage() + 2)) as $page => $url)
            @php
                $paginationUrl = str_replace('?page=', '/trang-', $url).config('apps.general.suffix');
                $paginationUrl = ($page == 1) ? str_replace('/trang-'.$page, '', $paginationUrl) : $paginationUrl;
            @endphp
            <li class="page-item {{ ($page == $model->currentPage()) ? 'active' : '' }}"><a class="page-link" href="{{ $paginationUrl }}">{{ $page }}</a></li>
        @endforeach

        {{-- Next Page Link --}}
        @php
            $nextPageUrl = ($model->hasMorePages()) ? str_replace('?page=', '/trang-', $model->nextPageUrl()).config('apps.general.suffix') : null;
        @endphp
        @if ($nextPageUrl)
            <li class="page-item"><a class="page-link" href="{{ $nextPageUrl }}">Next</a></li>
        @else
            <li class="page-item disabled"><span class="page-link">Next</span></li>
        @endif
    </ul>
@endif
