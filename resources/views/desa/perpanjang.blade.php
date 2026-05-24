@extends('layouts.desa')

@section('title', 'Perpanjang Domain')

@section('content')

@include('components.inv-styles')

<div class="container-fluid" style="padding:0 24px;max-width:1400px">
    <!-- JUDUL DI LUAR CARD -->
    <div style="display:flex;align-items:flex-end;justify-content:space-between;margin-bottom:22px;flex-wrap:wrap;gap:10px">
        <div>
            <h1 style="font-size:22px;font-weight:800;margin:0;letter-spacing:-.5px">Domain Aktif</h1>
            <p style="font-size:14px;color:#64748b;margin:4px 0 0">Pantau masa berlaku dan ajukan perpanjangan domain</p>
        </div>
    </div>

    <div class="inv-card">
        <!-- ALERT SESSION DI DALAM CARD -->
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

        {{-- Search & Filter (DI DALAM CARD) --}}
        <div style="padding: 16px; border-bottom: 1px solid #e2e8f0; display: flex; gap: 10px; align-items: center;">
            <div style="position:relative;flex:1">
                <input type="text" id="invSearch" placeholder="Cari Nama Domain..." 
                    style="width:100%;padding:10px 16px;padding-left:40px;border:1px solid #cbd5e1;border-radius:8px;outline:none;font-size:14px;transition:all .2s">
                <i class="fas fa-search" style="position:absolute;left:14px;top:13px;color:#94a3b8"></i>
            </div>
            <div style="width: 180px;">
                <select id="invFilter" style="width:100%;padding:10px;border:1px solid #cbd5e1;border-radius:8px;background:white;cursor:pointer;">
                    <option value="">Semua Status</option>
                    <option value="kadaluarsa">Kadaluarsa</option>
                    <!-- <option value="faktur_tersedia">Faktur Tersedia</option>
                    <option value="menunggu_faktur">Menunggu Faktur</option>
                    <option value="siap_diperpanjang">Siap Diperpanjang</option> -->
                    <option value="aktif">Aktif</option>
                </select>
            </div>
        </div>

        <div style="overflow-x:auto">
            <table class="inv-table" id="invTable">
                <thead>
                    <tr>
                        <th>No</th>
                        <th data-type="string" class="sortable">Domain <i class="sort-icon"></i></th>
                        <th data-type="string" class="sortable">Tgl Aktivasi <i class="sort-icon"></i></th>
                        <th data-type="string" class="sortable">Masa Berlaku <i class="sort-icon"></i></th>
                        <th data-type="string" class="sortable">Status <i class="sort-icon"></i></th>
                        <th style="text-align:center; cursor: default;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                                            @php
                            // Inisialisasi variabel counter di luar loop
                            $numDaftarDomain = 0;
                        @endphp

                        @forelse($data as $row)
                            @php
                                // --- FILTER STATUS: HANYA TAMPILKAN AKTIF & KADALUARSA ---
                                $allowedStatuses = ['aktif', 'kadaluarsa']; 
                                if (!in_array($row->status_pengajuan, $allowedStatuses)) {
                                    continue; 
                                }
                                // --------------------------------------

                                $numDaftarDomain++;
                                $nomorTampil = method_exists($data, 'firstItem') 
                                                ? $data->firstItem() + ($numDaftarDomain - 1) 
                                                : $numDaftarDomain;

                                $aktivasi = $row->aktivasi_terakhir;
                                // Cek status kadaluarsa
                                $kadaluarsa = ($aktivasi && $aktivasi->masa_berlaku) ? $aktivasi->is_kadaluarsa : false;
                                
                                $bisaPerpanjang = false;

                                // --- LOGIKA PERPANJANGAN BARU ---
                                // 1. Jika sudah kadaluarsa, matikan tombol perpanjang (wajib false)
                                if ($kadaluarsa) {
                                    $bisaPerpanjang = false;
                                } 
                                // 2. Jika belum kadaluarsa, cek waktunya
                                elseif ($aktivasi && $aktivasi->tgl_aktivasi) {
                                    // Target: Muncul "Siap Diperpanjang" 27 menit sebelum masa berlaku habis.
                                    // Masa berlaku total = 30 menit.
                                    // 27 menit sebelum habis = 3 menit SETELAH tgl aktivasi.
                                    
                                    // Batas waktu tombol bisa diklik (3 menit setelah aktivasi)
                                    $batasAwal = $aktivasi->tgl_aktivasi->copy()->addMinutes(3);
                                    
                                    // Cek apakah waktu sekarang sudah melewati batas awal tersebut
                                    $bisaPerpanjang = \Carbon\Carbon::now() >= $batasAwal;
                                }
                                // -----------------------------------

                                // Ambil ID Faktur Belum Bayar
                                $fakturBelumBayar = $row->faktur->where('status', 'belum_bayar')->first();
                                $idFakturBelumBayar = $fakturBelumBayar ? $fakturBelumBayar->id : null;

                                // Tentukan Data Status untuk Filter JS
                                $dataStatus = 'aktif';
                                if($kadaluarsa) $dataStatus = 'kadaluarsa';
                                elseif($row->ada_faktur_belum_bayar) $dataStatus = 'faktur_tersedia';
                                elseif($row->menunggu_faktur) $dataStatus = 'menunggu_faktur';
                                elseif($bisaPerpanjang) $dataStatus = 'siap_diperpanjang';
                                else $dataStatus = 'aktif';
                            @endphp

                    <tr data-status="{{ $dataStatus }}" style="animation-delay:{{$numDaftarDomain*0.05}}s">
                        
                        <!-- Nomor Urut -->
                        <td>{{ $nomorTampil }}</td>
                        
                        <td style="font-weight:500;color:#334155">{{ $row->nama_domain }}.desa.id</td>
                        
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
                        <td>
                            @if($kadaluarsa)
                                <span class="inv-badge badge-red"><span class="d"></span>Kadaluarsa</span>
                            
                            @elseif($row->ada_faktur_belum_bayar)
                                <span class="inv-badge" style="background:#dbeafe;color:#1e40af"><span class="d" style="background:#3b82f6"></span>Faktur Tersedia</span>
                            
                            @elseif($row->menunggu_faktur)
                                <span class="inv-badge" style="background:#ffedd5;color:#9a3412"><span class="d" style="background:#f97316"></span>Menunggu Faktur</span>
                            
                            @elseif($bisaPerpanjang)
                                <span class="inv-badge badge-green"><span class="d"></span>Aktif</span>
                            
                            @else
                                <span class="inv-badge badge-green"><span class="d"></span>Aktif</span>
                            @endif
                        </td>

                       {{-- KOLOM AKSI --}}
<td style="text-align:center">
    <div style="display:flex;justify-content:center;gap:8px;flex-wrap:wrap">

        <!-- DETAIL -->
        <a href="{{ route('desa.verifikasi.detail', $row->id_pengajuan) }}" 
           class="inv-btn-d" 
           title="Lihat">
            <i class="fas fa-eye"></i> Lihat
        </a>

        {{-- JIKA STATUS KADALUARSA → HANYA TAMPILKAN DETAIL --}}
        @if($kadaluarsa)

        {{-- TIDAK ADA TOMBOL LAIN --}}

        @elseif($row->ada_faktur_belum_bayar)

            {{-- LINK MENUJU DETAIL FAKTUR --}}
            @if($idFakturBelumBayar)
                <a href="{{ route('desa.faktur.show', $idFakturBelumBayar) }}" class="inv-btn-d">
                   <i class="fas fa-file-invoice-dollar"></i> Faktur
                </a>
            @else
                <span class="text-xs text-gray-400">
                    Data Faktur Error
                </span>
            @endif

        @elseif($row->menunggu_faktur)

            <span class="text-gray-400 text-xs">
                <i class="fas fa-hourglass-half"></i> Menunggu Faktur
            </span>

        @elseif($bisaPerpanjang)

            <a href="{{ url('/desa/perpanjang/ajukan/' . $row->id_pengajuan) }}" 
               class="inv-btn-d"
               onclick="event.preventDefault(); openPerpanjangModal('{{ url('/desa/perpanjang/ajukan/' . $row->id_pengajuan) }}')">
               <i class="fas fa-redo"></i> Ajukan Perpanjang
            </a>

        @else

            <button disabled class="inv-btn-d" style="background:#e2e8f0; border-color:#e2e8f0; color:#94a3b8; cursor:not-allowed">
                <i class="fas fa-redo"></i> Ajukan Perpanjang
            </button>

        @endif

    </div>
</td>

    </div>
</td>
                    </tr>
                    @empty
                    <tr class="inv-empty"><td colspan="6"><i class="fas fa-inbox"></i>Tidak ada domain aktif.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- PAGINATION -->
        @include('components.inv-pagination', ['paginator' => $data])
    </div>
</div>

<!-- MODAL PERPANJANG -->
<div id="modalPerpanjang" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm hidden">
    <div class="bg-white rounded-2xl shadow-2xl p-8 mx-4 w-full max-w-sm text-center transform transition-all duration-300 scale-95 opacity-0" id="modalPerpanjangContent">
        <div class="mx-auto mb-5 w-20 h-20 bg-orange-100 rounded-full flex items-center justify-center">
    <svg class="w-12 h-12 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
    </svg>
</div>
        <h2 class="text-2xl font-bold text-gray-800 mb-3">Ajukan Perpanjang?</h2>
        <p class="text-gray-600 text-sm leading-relaxed mb-8">Apakah anda yakin ingin mengajukan perpanjangan domain ini? Admin akan membuatkan faktur untuk pembayaran.</p>
        <div class="flex gap-3">
            <button onclick="closePerpanjangModal()" class="w-1/2 py-3 border border-gray-300 text-gray-700 font-semibold rounded-lg hover:bg-gray-50 transition duration-200 text-base">
                Batal
            </button>
            <button id="btnConfirmPerpanjang" class="w-1/2 py-3 bg-orange-500 hover:bg-orange-600 text-white font-semibold rounded-lg transition duration-200 text-base">
                Ya, Ajukan
            </button>
        </div>
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
            var textMatch = (!q || r.textContent.toLowerCase().includes(q));
            var statusMatch = (!v || r.dataset.status === v);
            var show = textMatch && statusMatch;
            r.style.display=show?'':'none';
            if(show)n++;
        });
        if(empty)empty.style.display=n?'none':'';
    }
    if(s) s.addEventListener('input',filter);
    if(f) f.addEventListener('change',filter);

    const sortHeaders = document.querySelectorAll('th.sortable');
    sortHeaders.forEach(header => {
        header.style.cursor = 'pointer';
        header.addEventListener('mouseenter', () => header.style.backgroundColor = '#f8fafc');
        header.addEventListener('mouseleave', () => header.style.backgroundColor = '');
        header.addEventListener('click', () => {
            const table = header.closest('table');
            const tbody = table.querySelector('tbody');
            const allRows = Array.from(tbody.querySelectorAll('tr'));
            const type = header.dataset.type;
            const icon = header.querySelector('.sort-icon');
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

function openPerpanjangModal(url) {
    const modal = document.getElementById('modalPerpanjang');
    const content = document.getElementById('modalPerpanjangContent');
    const btnConfirm = document.getElementById('btnConfirmPerpanjang');
    btnConfirm.onclick = function() { window.location.href = url; };
    modal.classList.remove('hidden');
    setTimeout(() => { content.classList.remove('scale-95', 'opacity-0'); content.classList.add('scale-100', 'opacity-100'); }, 10);
}

function closePerpanjangModal() {
    const modal = document.getElementById('modalPerpanjang');
    const content = document.getElementById('modalPerpanjangContent');
    content.classList.remove('scale-100', 'opacity-100');
    content.classList.add('scale-95', 'opacity-0');
    setTimeout(() => { modal.classList.add('hidden'); }, 300);
}

document.getElementById('modalPerpanjang').addEventListener('click', function(e) {
    if (e.target === this) closePerpanjangModal();
});
</script>
@endsection