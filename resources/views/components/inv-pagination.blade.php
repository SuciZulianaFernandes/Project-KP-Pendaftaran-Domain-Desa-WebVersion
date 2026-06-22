@if ($paginator->hasPages())
<div class="flex flex-col sm:flex-row items-center justify-between gap-4 pt-5 pb-1">
    
    <!-- Info Kiri -->
    <div class="text-sm text-slate-500 order-2 sm:order-1">
        Menampilkan 
        <span class="font-semibold text-slate-700">{{ $paginator->firstItem() }}</span> - 
        <span class="font-semibold text-slate-700">{{ $paginator->lastItem() }}</span> 
        dari <span class="font-semibold text-slate-700">{{ $paginator->total() }}</span> data
    </div>

    <!-- Navigasi Kanan -->
    <div class="flex items-center gap-1 order-1 sm:order-2">
        
        @php
            // TAMBAHKAN INI: Untuk memaksa pagination membawa parameter status & kecamatan
            $paginator->appends(request()->except('page'));
        @endphp

        @if ($paginator->onFirstPage())
            <span class="w-9 h-9 flex items-center justify-center rounded-lg text-slate-300 bg-slate-50 cursor-not-allowed text-xs">
                <i class="fas fa-chevron-left"></i>
            </span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" 
               class="w-9 h-9 flex items-center justify-center rounded-lg text-slate-600 bg-white border border-slate-200 hover:bg-[#109696] hover:text-white hover:border-[#109696] transition-all duration-200 text-xs shadow-sm">
                <i class="fas fa-chevron-left"></i>
            </a>
        @endif

        @foreach ($paginator->getUrlRange(1, $paginator->lastPage()) as $page => $url)
            @if ($page == $paginator->currentPage())
                <span class="w-9 h-9 flex items-center justify-center rounded-lg text-xs font-bold text-white bg-[#109696] shadow-sm shadow-[#109696]/20">
                    {{ $page }}
                </span>
            @else
                <a href="{{ $url }}" 
                   class="w-9 h-9 flex items-center justify-center rounded-lg text-xs font-semibold text-slate-600 bg-white border border-slate-200 hover:bg-slate-50 hover:border-slate-300 transition-all duration-200">
                    {{ $page }}
                </a>
            @endif
        @endforeach

        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" 
               class="w-9 h-9 flex items-center justify-center rounded-lg text-slate-600 bg-white border border-slate-200 hover:bg-[#109696] hover:text-white hover:border-[#109696] transition-all duration-200 text-xs shadow-sm">
                <i class="fas fa-chevron-right"></i>
            </a>
        @else
            <span class="w-9 h-9 flex items-center justify-center rounded-lg text-slate-300 bg-slate-50 cursor-not-allowed text-xs">
                <i class="fas fa-chevron-right"></i>
            </span>
        @endif

    </div>
</div>
@endif