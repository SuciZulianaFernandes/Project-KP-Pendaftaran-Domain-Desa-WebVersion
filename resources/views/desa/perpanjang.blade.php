@extends('layouts.desa')
@section('title', 'Perpanjang Domain')

@section('content')
@include('components.inv-styles')

<div class="container-fluid" style="padding:0 24px;max-width:1400px">
    <div class="inv-card">
        
        <!-- Header -->
        <div style="display:flex;align-items:flex-end;justify-content:space-between;margin-bottom:22px;flex-wrap:wrap;gap:10px">
            <div>
                <h1 style="font-size:22px;font-weight:800;margin:0;letter-spacing:-.5px">Domain Aktif</h1>
                <p style="font-size:14px;color:#64748b;margin:4px 0 0">Pantau masa berlaku dan ajukan perpanjangan domain</p>
            </div>
        </div>

        <!-- Alert Session -->
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

        <!-- Search Bar -->
        <div style="margin-bottom:20px; position:relative;">
            <input type="text" id="invSearch" placeholder="Cari Nama Domain..." 
                   style="width:100%; padding:10px 15px; border:1px solid #e2e8f0; border-radius:6px; outline:none; font-size:14px;">
            <i class="fas fa-search" style="position:absolute; right:15px; top:12px; color:#94a3b8;"></i>
        </div>

        <div style="overflow-x:auto">
            <table class="inv-table" id="invTable">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Domain</th>
                        <th>Tgl Aktivasi</th>
                        <th>Masa Berlaku</th>
                        <th style="text-align:center">Status</th>
                        <th style="text-align:center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($data as $i => $row)
                        @php
                            // GUNAKAN AKTIVASI TERAKHIR
                            $aktivasi = $row->aktivasi_terakhir;
                            
                            $kadaluarsa = ($aktivasi && $aktivasi->masa_berlaku) ? $aktivasi->is_kadaluarsa : false;
                            
                            $bisaPerpanjang = false;
                            if ($aktivasi && $aktivasi->tgl_aktivasi) {
                                // TESTING: 30 detik SETELAH tanggal aktivasi
                                $batasAwal = $aktivasi->tgl_aktivasi->copy()->addSeconds(30);
                                
                                // PRODUCTION: 60 hari SEBELUM masa berlaku habis
                                // $batasAwal = $aktivasi->masa_berlaku->copy()->subDays(60);
                                
                                $bisaPerpanjang = \Carbon\Carbon::now() >= $batasAwal;
                            }
                        @endphp

                    <tr data-status="{{ $row->nama_domain }}" style="animation-delay:{{$i*0.05}}s">
                        <td>{{ $i+1 }}</td>
                        <td><span class="inv-id">{{ $row->nama_domain }}.desa.id</span></td>
                        
                        <td>
                            @if($aktivasi && $aktivasi->tgl_aktivasi)
                                <span class="inv-date">{{ $aktivasi->tgl_aktivasi->format('d/m/Y H:i') }}</span>
                            @else
                                <span class="inv-date">-</span>
                            @endif
                        </td>
                        
                        <td>
                            @if($aktivasi && $aktivasi->masa_berlaku)
                                <span class="inv-date {{ $kadaluarsa ? 'text-red-600 font-bold' : 'text-gray-700' }}">
                                    {{ $aktivasi->masa_berlaku->format('d/m/Y H:i') }}
                                </span>
                            @else
                                <span class="inv-date text-gray-400">-</span>
                            @endif
                        </td>

                        {{-- KOLOM STATUS --}}
                        <td style="text-align:center">
                            @if($kadaluarsa)
                                <span class="inv-badge badge-red"><span class="d"></span>Kadaluarsa</span>
                            
                            @elseif($row->ada_faktur_belum_bayar)
                                <span class="inv-badge" style="background:#dbeafe;color:#1e40af"><span class="d" style="background:#3b82f6"></span>Faktur Tersedia</span>
                            
                            @elseif($row->menunggu_faktur)
                                <span class="inv-badge" style="background:#ffedd5;color:#9a3412"><span class="d" style="background:#f97316"></span>Menunggu Faktur</span>
                            
                            @elseif($bisaPerpanjang)
                                <span class="inv-badge badge-green"><span class="d"></span>Siap Diperpanjang</span>
                            
                            @else
                                <span class="inv-badge badge-green"><span class="d"></span>Aktif</span>
                            @endif
                        </td>

                        {{-- KOLOM AKSI --}}
                        <td style="text-align:center">
                            @if($row->ada_faktur_belum_bayar)
                                {{-- PRIORITAS 1: ADA TAGIHAN --}}
                                <a href="{{ route('desa.faktur.index') }}" 
                                   class="inv-btn-d">
                                   <i class="fas fa-file-invoice-dollar"></i> Faktur
                                </a>

                            @elseif($row->menunggu_faktur)
                                {{-- PRIORITAS 2: SUDAH AJUKAN --}}
                                <span class="text-gray-400 text-xs"><i class="fas fa-hourglass-half"></i> Menunggu Admin</span>

                            @elseif($bisaPerpanjang)
                                {{-- PRIORITAS 3: BOLEH AJUKAN --}}
                                <a href="{{ url('/desa/perpanjang/ajukan/' . $row->id_pengajuan) }}" 
                                   class="inv-btn-d"
                                   onclick="return confirm('Apakah anda ingin perpanjang domain?')">
                                   <i class="fas fa-redo"></i> Ajukan Perpanjang
                                </a>

                            @else
                                {{-- PRIORITAS 4: BELUM WAKTUNYA --}}
                                <button disabled class="inv-btn-d" style="background:#e2e8f0; border-color:#e2e8f0; color:#94a3b8; cursor:not-allowed">
                                    Perpanjang
                                </button>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr class="inv-empty"><td colspan="6"><i class="fas fa-inbox"></i>Tidak ada domain aktif.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded',function(){
    var s=document.getElementById('invSearch'),
        rows=Array.from(document.querySelectorAll('#invTable tbody tr[data-status]')),
        empty=document.querySelector('.inv-empty');

    function filterSearch(){
        var q=s.value.trim().toLowerCase(), n=0;
        rows.forEach(function(r){
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