@extends('layouts.desa')

@section('content')
@include('components.inv-styles')

<div class="container-fluid" style="padding:0 24px;max-width:1400px">
    <div style="display:flex;align-items:flex-end;justify-content:space-between;margin-bottom:22px;flex-wrap:wrap;gap:10px">
        <div>
            <h1 style="font-size:22px;font-weight:800;margin:0;letter-spacing:-.5px">Daftar Faktur</h1>
            <p style="font-size:14px;color:#64748b;margin:4px 0 0">Kelola semua faktur domain Anda</p>
        </div>
    </div>

    <div class="inv-card">
        @if(session('success'))
            <div class="alert inv-alert inv-alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if(session('error'))
            <div class="alert inv-alert inv-alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @include('components.inv-search-filter')

        <div style="overflow-x:auto">
            <table class="inv-table" id="invTable">
                <thead>
                    <tr>
                        <th>No. Invoice</th>
                        <th>Tanggal Invoice</th>
                        <th>Jatuh Tempo</th>
                        <th>Total</th>
                        <th style="text-align:center">Status Pembayaran</th>
                        <th style="text-align:center">Detail</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($fakturs as $i => $item)
                    <tr data-status="{{ $item->status }}" style="animation-delay:{{$i*0.05}}s">
                        <td><span class="inv-id">#{{ $item->no_invoice }}</span></td>
                        <td><span class="inv-date">{{ $item->created_at?->format('d/m/Y') ?? '-' }}</span></td>
                        <td><span class="inv-date">{{ $item->expired_at?->format('d/m/Y') ?? '-' }}</span></td>
                        <td><span class="inv-amount">Rp {{ number_format($item->total, 0, ',', '.') }}</span></td>
                        <td style="text-align:center">
                            @if($item->status == 'belum_bayar')
                                <span class="inv-badge badge-red"><span class="d"></span>Belum Dibayar</span>                            @elseif($item->status == 'sudah_bayar')
                                <span class="inv-badge badge-green"><span class="d"></span>Sudah Dibayar</span>
                            @else
                                <span class="inv-badge" style="background:#f1f5f9;color:#475569"><span class="d" style="background:#94a3b8"></span>{{ ucfirst($item->status) }}</span>
                            @endif
                        </td>
                        <td style="text-align:center">
                            <a href="{{ route('desa.faktur.show', $item->id) }}" class="inv-btn-d"><i class="fas fa-eye"></i> Lihat</a>
                        </td>
                    </tr>
                    @empty
                    <tr class="inv-empty"><td colspan="6"><i class="fas fa-inbox"></i>Tidak ada data faktur</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @include('components.inv-pagination')
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded',function(){
    var s=document.getElementById('invSearch'),
        f=document.getElementById('invFilter'),
        rows=Array.from(document.querySelectorAll('#invTable tbody tr[data-status]')),
        empty=document.querySelector('.inv-empty');

    function filter(){
        var q=s.value.trim().toLowerCase(), v=f.value, n=0;
        rows.forEach(function(r){
            var show=(!q||r.textContent.toLowerCase().includes(q))&&(!v||r.dataset.status===v);
            r.style.display=show?'':'none';
            if(show)n++;
        });
        if(empty)empty.style.display=n?'none':'';
    }
    s.addEventListener('input',filter);
    f.addEventListener('change',filter);
});
</script>
@endsection