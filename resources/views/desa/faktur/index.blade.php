@extends('layouts.desa')

@section('content')
@include('components.inv-styles')

<div class="container-fluid" style="padding:0 24px;max-width:1400px">

    <div style="display:flex;align-items:flex-end;justify-content:space-between;margin-bottom:22px;flex-wrap:wrap;gap:10px">

        <div>
            <h1 style="font-size:22px;font-weight:800;margin:0;letter-spacing:-.5px">
                Daftar Faktur
            </h1>

            <p style="font-size:14px;color:#64748b;margin:4px 0 0">
                Kelola semua faktur domain Anda
            </p>
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

        {{-- PENCARIAN DENGAN FORM GET --}}
        <div style="padding: 16px; border-bottom: 1px solid #e2e8f0; display: flex; gap: 10px; align-items: center;">
            <form action="{{ route('desa.faktur.index') }}" method="GET" style="display:flex; flex:1; gap:10px;">
                <div style="position:relative;flex:1">
                    <input type="text" name="search" id="invSearch" placeholder="Cari No Invoice atau Domain..." value="{{ request('search') }}"
                        style="width:100%;padding:10px 16px;padding-left:40px;border:1px solid #cbd5e1;border-radius:8px;outline:none;font-size:14px;transition:all .2s">
                    <i class="fas fa-search" style="position:absolute;left:14px;top:13px;color:#94a3b8"></i>
                </div>
                <button type="submit" class="btn btn-primary" style="padding: 0 20px; border-radius: 8px;">Cari</button>
            </form>
            
            <div style="width: 180px;">
                <select id="invFilter" style="width:100%;padding:10px;border:1px solid #cbd5e1;border-radius:8px;background:white;cursor:pointer;">
                    <option value="">Semua Status</option>
                    <option value="belum_bayar">Belum Dibayar</option>
                    <option value="sudah_bayar">Sudah Dibayar</option>
                </select>
            </div>
        </div>

        <div style="overflow-x:auto">

            <table class="inv-table" id="invTable">

                <thead>

                    <tr>

                        <th>No</th>
                        <th>No. Invoice</th>
                        <th>Nama Desa</th>
                        <th>Domain</th>
                        <th style="text-align:center">Tipe</th>
                        <th>Tanggal Invoice</th>
                        <th>Jatuh Tempo</th>
                        <th style="text-align:center">Status Pembayaran</th>
                        <th style="text-align:center">Detail</th>

                    </tr>

                </thead>

                <tbody>

                    @forelse($fakturs as $fakturDesa)

                    <tr
                        data-status="{{ $fakturDesa->status }}"
                        style="animation-delay:{{$loop->index * 0.05}}s"
                    >

                        <td>
                            {{ $fakturs->firstItem() + $loop->index }}
                        </td>

                        <td>

                            <span class="inv-id">
                                #{{ $fakturDesa->no_invoice }}
                            </span>

                        </td>

                        <td>
                            {{ $fakturDesa->pengajuan->nama_desa ?? '-' }}
                        </td>

                        <td>

                            <span class="inv-date">
                                {{ $fakturDesa->pengajuan->nama_domain ?? '-' }}.desa.id
                            </span>

                        </td>

                        <td style="text-align:center">

                            @if($fakturDesa->tipe == 'perpanjangan')

                                <span class="px-2 py-0.5 rounded text-xs bg-purple-100 text-purple-700 font-medium">
                                    Perpanjangan
                                </span>

                            @else

                                <span class="px-2 py-0.5 rounded text-xs bg-blue-100 text-blue-700 font-medium">
                                    Baru
                                </span>

                            @endif

                        </td>

                        <td>

                            <span class="inv-date">
                                {{ $fakturDesa->created_at?->format('d/m/Y') ?? '-' }}
                            </span>

                        </td>

                        <td>

                            <span class="inv-date">
                                {{ $fakturDesa->expired_at?->format('d/m/Y') ?? '-' }}
                            </span>

                        </td>

                        <td style="text-align:center">

                            @if($fakturDesa->status == 'belum_bayar')

                                <span class="inv-badge badge-red">

                                    <span class="d"></span>

                                    Belum Dibayar

                                </span>

                            @elseif($fakturDesa->status == 'sudah_bayar')

                                <span class="inv-badge badge-green">

                                    <span class="d"></span>

                                    Sudah Dibayar

                                </span>

                            @else

                                <span class="inv-badge" style="background:#f1f5f9;color:#475569">

                                    <span class="d" style="background:#94a3b8"></span>

                                    {{ ucfirst($fakturDesa->status) }}

                                </span>

                            @endif

                        </td>

                        <td style="text-align:center">

                            <a
                                href="{{ route('desa.faktur.show', $fakturDesa->id) }}"
                                class="inv-btn-d"
                            >

                                <i class="fas fa-eye"></i> Detail

                            </a>

                        </td>

                    </tr>

                    @empty

                    <tr class="inv-empty">

                        <td colspan="9">

                            <i class="fas fa-inbox"></i>

                            Tidak ada data faktur

                        </td>

                    </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

        {{-- PASTIKAN MENGIRIM VARIABEL PAGINATOR --}}
        @include('components.inv-pagination', ['paginator' => $fakturs])

    </div>

</div>

<script>
document.addEventListener('DOMContentLoaded', function(){

    // Filter Dropdown Status (Client Side Only)
    var filterInvoice = document.getElementById('invFilter'),
        rowsInvoice = Array.from(document.querySelectorAll('#invTable tbody tr[data-status]')),
        emptyInvoice = document.querySelector('.inv-empty');

    function filterStatusOnly(){
        var statusInvoice = filterInvoice.value,
            totalVisibleInvoice = 0;

        rowsInvoice.forEach(function(rowInvoice){
            var statusMatch = (!statusInvoice || rowInvoice.dataset.status === statusInvoice);
            
            rowInvoice.style.display = statusMatch ? '' : 'none';

            if(statusMatch){
                totalVisibleInvoice++;
            }
        });

        if(emptyInvoice){
            emptyInvoice.style.display = totalVisibleInvoice ? 'none' : '';
        }
    }
    
    if(filterInvoice) filterInvoice.addEventListener('change', filterStatusOnly);

});
</script>

@endsection