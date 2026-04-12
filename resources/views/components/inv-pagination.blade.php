@isset($paginator)
<div class="inv-footer">
    <span>Halaman {{ $paginator->currentPage() }} dari {{ $paginator->lastPage() }}</span>
    <div class="inv-pg">
        <button @if(!$paginator->onFirstPage()) onclick="window.location='{{ $paginator->previousPageUrl() }}'" @else disabled @endif><i class="fas fa-chevron-left" style="font-size:11px"></i></button>
        @for($i = 1; $i <= $paginator->lastPage(); $i++)
            <button class="{{ $i == $paginator->currentPage() ? 'active' : '' }}" onclick="window.location='{{ $paginator->url($i) }}'">{{ $i }}</button>
        @endfor
        <button @if(!$paginator->onLastPage()) onclick="window.location='{{ $paginator->nextPageUrl() }}'" @else disabled @endif><i class="fas fa-chevron-right" style="font-size:11px"></i></button>
    </div>
</div>
@else
<div class="inv-footer">
    <span>Halaman 1 dari 1</span>
    <div class="inv-pg">
        <button disabled><i class="fas fa-chevron-left" style="font-size:11px"></i></button>
        <button class="active">1</button>
        <button disabled><i class="fas fa-chevron-right" style="font-size:11px"></i></button>
    </div>
</div>
@endisset