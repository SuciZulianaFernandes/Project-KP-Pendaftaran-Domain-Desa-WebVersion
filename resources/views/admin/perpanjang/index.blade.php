@extends('layouts.admin')

@section('title', 'Pengajuan Perpanjang Domain')

@section('content')

@include('components.inv-styles')

<div class="container-fluid" style="padding:0 24px;max-width:1400px">
    <div style="display:flex;align-items:flex-end;justify-content:space-between;margin-bottom:22px;flex-wrap:wrap;gap:10px">
        <div>
            <h1 style="font-size:22px;font-weight:800;margin:0;letter-spacing:-.5px">Pengajuan Perpanjang Domain</h1>
            <p style="font-size:14px;color:#64748b;margin:4px 0 0">Kelola perpanjangan domain dan status pembayaran</p>
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

        {{-- Search & Filter --}}
        <div style="padding: 16px; border-bottom: 1px solid #e2e8f0; display: flex; gap: 10px; align-items: center;">
            <div style="position:relative;flex:1">
                <input type="text" id="invSearch" placeholder="Cari Nama Domain..." 
                    style="width:100%;padding:10px 16px;padding-left:40px;border:1px solid #cbd5e1;border-radius:8px;outline:none;font-size:14px;transition:all .2s">
                <i class="fas fa-search" style="position:absolute;left:14px;top:13px;color:#94a3b8"></i>
            </div>
            <div style="width: 180px;">
                <select id="invFilter" style="width:100%;padding:10px;border:1px solid #cbd5e1;border-radius:8px;background:white;cursor:pointer;">
                    <option value="">Semua Status</option>
                    <option value="belum_bayar">Belum Bayar</option>
                    <option value="sudah_bayar">Sudah Bayar</option>
                </select>
            </div>
        </div>

        <div style="overflow-x:auto">
            <table class="inv-table" id="invTable">
                <thead>
                    <tr>
                        <th>No</th>
                        <th data-type="string" class="sortable">Domain <i class="sort-icon"></i></th>
                        <th data-type="string" class="sortable">Status Domain <i class="sort-icon"></i></th>
                        <th data-type="string" class="sortable">Tipe <i class="sort-icon"></i></th>
                        <th data-type="string" class="sortable">Tgl Faktur <i class="sort-icon"></i></th>
                        <th data-type="string" class="sortable">Status Faktur <i class="sort-icon"></i></th>
                        <th style="text-align:center; cursor: default;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($fakturs as $indexPerpanjang => $item)
                        @php
                            // Ambil data relasi untuk pengecekan null
                            $pengajuan = $item->pengajuan;
                            $pengajuanStatus = $pengajuan ? $pengajuan->status_pengajuan : 'unknown';
                        @endphp

                        <tr data-status="{{ $item->status }}" style="animation-delay:{{$indexPerpanjang*0.05}}s">
                            
                            {{-- NOMOR URUT --}}
                            <td>{{ method_exists($fakturs, 'firstItem') ? $fakturs->firstItem() + $indexPerpanjang : $indexPerpanjang + 1 }}</td>
                            
                            {{-- DOMAIN --}}
                            <td style="font-weight:500;color:#334155">{{ $item->nama_domain }}.desa.id</td>
                            
                            {{-- STATUS DOMAIN --}}
                            <td>
                                @if($pengajuanStatus == 'menunggu_aktivasi')
                                    <span class="px-2 py-1 rounded text-xs font-semibold bg-orange-100 text-orange-700 border border-orange-200">
                                        Menunggu Aktivasi
                                    </span>
                                @elseif($pengajuanStatus == 'diproses')
                                    <span class="px-2 py-1 rounded text-xs font-semibold bg-blue-100 text-blue-700 border border-blue-200">
                                        Diproses
                                    </span>
                                @elseif($pengajuanStatus == 'aktif')
                                    <span class="px-2 py-1 rounded text-xs font-semibold bg-green-100 text-green-700 border border-green-200">
                                        Aktif
                                    </span>
                                @else
                                    <span class="px-2 py-1 rounded text-xs font-semibold bg-gray-100 text-gray-700 border border-gray-200">
                                        {{ ucfirst(str_replace('_', ' ', $pengajuanStatus)) }}
                                    </span>
                                @endif
                            </td>

                            {{-- TIPE --}}
                            <td>
                                <span class="px-2 py-1 rounded text-xs font-semibold bg-purple-100 text-purple-700 border border-purple-200">
                                    Perpanjangan
                                </span>
                            </td>

                            {{-- TGL FAKTUR --}}
                            <td>
                                <span class="inv-date">{{ $item->created_at->format('d/m/Y') }}</span>
                            </td>

                            {{-- STATUS FAKTUR --}}
                            <td>
                                @if($item->status == 'belum_bayar')
                                    <span class="px-2 py-1 rounded text-xs font-semibold bg-yellow-100 text-yellow-700 border border-yellow-200">
                                        Belum Bayar
                                    </span>
                                @elseif($item->status == 'sudah_bayar')
                                    <span class="px-2 py-1 rounded text-xs font-semibold bg-green-100 text-green-700 border border-green-200">
                                        Sudah Bayar
                                    </span>
                                @else
                                    <span class="px-2 py-1 rounded text-xs font-semibold bg-red-100 text-red-700 border border-red-200">
                                        Kadaluarsa
                                    </span>
                                @endif
                            </td>

                            {{-- AKSI --}}
                            <td style="text-align:center">
                                <div style="display:flex;justify-content:center;gap:8px;">
                                    <a href="{{ route('admin.perpanjang.show', $item->id) }}" class="inv-btn-d" title="Lihat Detail">
                                        <i class="fas fa-eye"></i> Detail
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr class="inv-empty"><td colspan="7"><i class="fas fa-inbox"></i> Belum ada pengajuan perpanjangan.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @include('components.inv-pagination', ['paginator' => $fakturs])
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded',function(){
    // --- LOGIC SEARCH & FILTER (Adapted for Perpanjangan) ---
    var s=document.getElementById('invSearch'),
        f=document.getElementById('invFilter'),
        rows=Array.from(document.querySelectorAll('#invTable tbody tr[data-status]')),
        empty=document.querySelector('.inv-empty');

    function filter(){
        var q=s.value.trim().toLowerCase(), v=f.value, n=0;
        rows.forEach(function(r){
            var textMatch = (!q || r.textContent.toLowerCase().includes(q));
            // Menggunakan data-status (status_faktur)
            var statusMatch = (!v || r.dataset.status === v);
            var show = textMatch && statusMatch;
            r.style.display=show?'':'none';
            if(show)n++;
        });
        if(empty)empty.style.display=n?'none':'';
    }
    if(s) s.addEventListener('input',filter);
    if(f) f.addEventListener('change',filter);

    // --- LOGIC SORTING (New: Client Side Instant Sort) ---
    const sortHeaders = document.querySelectorAll('th.sortable');
    
    sortHeaders.forEach(header => {
        header.style.cursor = 'pointer';
        
        // Tambahkan hover effect sederhana
        header.addEventListener('mouseenter', () => header.style.backgroundColor = '#f8fafc');
        header.addEventListener('mouseleave', () => header.style.backgroundColor = '');

        header.addEventListener('click', () => {
            const table = header.closest('table');
            const tbody = table.querySelector('tbody');
            const allRows = Array.from(tbody.querySelectorAll('tr'));
            
            const type = header.dataset.type;
            const icon = header.querySelector('.sort-icon');
            // colIndex dikurangi 1 karena kolom pertama (No) tidak sortable
            const colIndex = Array.from(header.parentNode.children).indexOf(header);

            document.querySelectorAll('th.sortable .sort-icon').forEach(i => i.textContent = '');
            
            let isAsc = !header.classList.contains('asc');
            sortHeaders.forEach(h => h.classList.remove('asc', 'desc'));
            
            header.classList.add(isAsc ? 'asc' : 'desc');
            icon.textContent = isAsc ? ' ▲' : ' ▼';

            allRows.sort((a, b) => {
                let aVal = a.cells[colIndex].textContent.trim();
                let bVal = b.cells[colIndex].textContent.trim();

                return isAsc ? aVal.localeCompare(bVal) : bVal.localeCompare(aVal);
            });

            allRows.forEach(row => tbody.appendChild(row));
        });
    });
});
</script>
@endsection