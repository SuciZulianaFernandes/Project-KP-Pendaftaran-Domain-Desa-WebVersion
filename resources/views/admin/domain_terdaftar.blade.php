@extends('layouts.admin')
@section('title', 'Daftar Domain Terdaftar')

@section('content')

@include('components.inv-styles')

<!-- WIDGET STATISTIK (Disesuaikan dengan tema minimalis) -->
<div class="container-fluid" style="padding:0 24px;max-width:1400px; margin-bottom: 20px;">
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        
        <!-- Widget 1: Total Domain -->
        <div class="bg-white p-4 rounded-lg shadow-sm border-l-4 border-blue-500 flex justify-between items-center">
            <div>
                <p class="text-gray-500 text-xs font-bold uppercase">Total Domain</p>
                <h3 class="text-xl font-bold text-gray-800 mt-1">{{ $totalDomain }}</h3>
            </div>
            <div class="text-blue-500"><i class="fas fa-globe fa-2x"></i></div>
        </div>

        <!-- Widget 2: Total Aktif -->
        <div class="bg-white p-4 rounded-lg shadow-sm border-l-4 border-green-500 flex justify-between items-center">
            <div>
                <p class="text-gray-500 text-xs font-bold uppercase">Domain Aktif</p>
                <h3 class="text-xl font-bold text-gray-800 mt-1">{{ $totalAktif }}</h3>
            </div>
            <div class="text-green-500"><i class="fas fa-check-circle fa-2x"></i></div>
        </div>

        <!-- Widget 3: Total Nonaktif -->
        <div class="bg-white p-4 rounded-lg shadow-sm border-l-4 border-gray-500 flex justify-between items-center">
            <div>
                <p class="text-gray-500 text-xs font-bold uppercase">Nonaktif</p>
                <h3 class="text-xl font-bold text-gray-800 mt-1">{{ $totalNonaktif }}</h3>
            </div>
            <div class="text-gray-500"><i class="fas fa-pause-circle fa-2x"></i></div>
        </div>

        <!-- Widget 4: Total Kadaluarsa -->
        <div class="bg-white p-4 rounded-lg shadow-sm border-l-4 border-red-500 flex justify-between items-center">
            <div>
                <p class="text-gray-500 text-xs font-bold uppercase">Kadaluarsa</p>
                <h3 class="text-xl font-bold text-gray-800 mt-1">{{ $totalKadaluarsa }}</h3>
            </div>
            <div class="text-red-500"><i class="fas fa-exclamation-circle fa-2x"></i></div>
        </div>

    </div>
</div>

<!-- MAIN CONTENT CARD (Gaya Faktur) -->
<div class="container-fluid" style="padding:0 24px;max-width:1400px">
    <div class="inv-card">
        
        <!-- Header & Filter -->
        <div style="display:flex;align-items:flex-end;justify-content:space-between;margin-bottom:22px;flex-wrap:wrap;gap:10px">
            <div>
                <h1 style="font-size:22px;font-weight:800;margin:0;letter-spacing:-.5px">Daftar Domain Terdaftar</h1>
                <p style="font-size:14px;color:#64748b;margin:4px 0 0">Kelola status aktivasi dan masa berlaku domain desa</p>
            </div>
            
            <!-- Filter Buttons (Gaya Faktur) -->
            <div style="display:flex; background:#f8fafc; padding:4px; border-radius:6px;">
                <a href="{{ route('admin.domain_terdaftar', ['status' => 'all']) }}" 
                   class="px-4 py-1.5 text-xs font-medium rounded transition-all {{ $statusFilter == 'all' || empty($statusFilter) ? 'bg-white shadow-sm text-blue-600 font-bold' : 'text-gray-500 hover:text-gray-700' }}">
                   Semua
                </a>
                <a href="{{ route('admin.domain_terdaftar', ['status' => 'aktif']) }}" 
                   class="px-4 py-1.5 text-xs font-medium rounded transition-all {{ $statusFilter == 'aktif' ? 'bg-white shadow-sm text-green-600 font-bold' : 'text-gray-500 hover:text-gray-700' }}">
                   Aktif
                </a>
                <a href="{{ route('admin.domain_terdaftar', ['status' => 'kadaluarsa']) }}" 
                   class="px-4 py-1.5 text-xs font-medium rounded transition-all {{ $statusFilter == 'kadaluarsa' ? 'bg-white shadow-sm text-red-600 font-bold' : 'text-gray-500 hover:text-gray-700' }}">
                   Kadaluarsa
                </a>
            </div>
        </div>

        <!-- Search Bar (Opsional, ala Faktur) -->
        <div style="margin-bottom:20px; position:relative;">
            <input type="text" id="invSearch" placeholder="Cari Nama Desa atau Domain..." 
                   style="width:100%; padding:10px 15px; border:1px solid #e2e8f0; border-radius:6px; outline:none; font-size:14px;">
            <i class="fas fa-search" style="position:absolute; right:15px; top:12px; color:#94a3b8;"></i>
        </div>

        <div style="overflow-x:auto">
            <table class="inv-table" id="invTable">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Desa</th>
                        <th>Domain</th>
                        <th>Tgl Aktivasi</th>
                        <th>Masa Berlaku Hingga</th>
                        <th style="text-align:center">Status</th>
                        <th style="text-align:center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($data as $i => $row)
                    <tr data-status="{{ $row->aktivasi->status_akt ?? '' }}" style="animation-delay:{{$i*0.05}}s">
                        {{-- DIUBAH: Menambahkan firstItem() agar nomor berlanjut --}}
                        <td>{{ $data->firstItem() + $i }}</td>
                        <td><span class="inv-date">{{ $row->nama_desa }}</span></td>
                        <td><span class="inv-id">{{ $row->nama_domain }}.desa.id</span></td>
                        <td><span class="inv-date">{{ $row->aktivasi ? $row->aktivasi->tgl_aktivasi->format('d/m/Y') : '-' }}</span></td>
                        
                        {{-- Kolom Expired --}}
                        <td>
                            @if($row->aktivasi)
                                <span class="inv-date {{ $row->aktivasi->status_akt == 'kadaluarsa' ? 'text-red-600 font-bold' : '' }}">
                                    {{ $row->aktivasi->masa_berlaku->format('d/m/Y H:i') }}
                                </span>
                            @else
                                <span class="inv-date">-</span>
                            @endif
                        </td>

                        {{-- Kolom Status (Badge Style Faktur) --}}
                        <td style="text-align:center">
                            @if($row->aktivasi->status_akt == 'aktif')
                                <span class="inv-badge badge-green"><span class="d"></span>Aktif</span>
                            @elseif($row->aktivasi->status_akt == 'kadaluarsa')
                                <span class="inv-badge badge-red"><span class="d"></span>Kadaluarsa</span>
                            @else
                                <span class="inv-badge" style="background:#f1f5f9;color:#475569"><span class="d" style="background:#94a3b8"></span>{{ ucfirst($row->aktivasi->status_akt) }}</span>
                            @endif
                        </td>

                        {{-- Kolom Aksi --}}
                        <td style="text-align:center">
                            <a href="{{ route('admin.pengajuan.detail', $row->id_pengajuan) }}" class="inv-btn-d"><i class="fas fa-eye"></i> Lihat Detail</a>
                        </td>
                    </tr>
                    @empty
                    <tr class="inv-empty"><td colspan="7"><i class="fas fa-inbox"></i>Tidak ada data domain</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- DITAMBAHKAN: PAGINATION --}}
        @include('components.inv-pagination', ['paginator' => $data])
    </div>
</div>

{{-- Script Pencarian Sederhana (Hanya Search Text, Filter Status pakai Backend) --}}
<script>
document.addEventListener('DOMContentLoaded',function(){
    // Hanya mengaktifkan fitur search text
    var s=document.getElementById('invSearch'),
        rows=Array.from(document.querySelectorAll('#invTable tbody tr[data-status]')),
        empty=document.querySelector('.inv-empty');

    function filterSearch(){
        var q=s.value.trim().toLowerCase(), n=0;
        rows.forEach(function(r){
            // Cari teks di seluruh baris
            var show=(!q||r.textContent.toLowerCase().includes(q));
            r.style.display=show?'':'none';
            if(show)n++;
        });
        if(empty)empty.style.display=n?'none':'';
    }
    s.addEventListener('input',filterSearch);
});
</script>
@endsection