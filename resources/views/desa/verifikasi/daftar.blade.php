@extends('layouts.desa')

@section('title', 'Daftar Pengajuan Verifikasi Dokumen')

@section('content')

@include('components.inv-styles')

<div class="container-fluid" style="padding:0 24px;max-width:1400px">
    <div style="display:flex;align-items:flex-end;justify-content:space-between;margin-bottom:22px;flex-wrap:wrap;gap:10px">
        <div>
            <h1 style="font-size:22px;font-weight:800;margin:0;letter-spacing:-.5px">Daftar Pengajuan Domain</h1>
            <p style="font-size:14px;color:#64748b;margin:4px 0 0">Kelola dan verifikasi status domain desa</p>
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

        {{-- Search & Filter (PERBAIKAN: Menggunakan Form GET) --}}
        <div style="padding: 16px; border-bottom: 1px solid #e2e8f0; display: flex; gap: 10px; align-items: center;">
            {{-- FORM PENCARIAN --}}
            <form action="{{ route('desa.verifikasi.daftar') }}" method="GET" style="display:flex; flex:1; gap:10px;">
                <div style="position:relative;flex:1">
                    <input type="text" name="search" placeholder="Cari berdasarkan nama domain..." value="{{ request('search') }}"
                        style="width:100%;padding:10px 16px;padding-left:40px;border:1px solid #cbd5e1;border-radius:8px;outline:none;font-size:14px;transition:all .2s">
                    <i class="fas fa-search" style="position:absolute;left:14px;top:13px;color:#94a3b8"></i>
                </div>
                <button type="submit" class="btn btn-primary" style="padding: 0 20px; border-radius: 8px;">
                    Cari
                </button>
            </form>
            
            <div style="width: 180px;">
                <select id="invFilter" style="width:100%;padding:10px;border:1px solid #cbd5e1;border-radius:8px;background:white;cursor:pointer;">
                    <option value="">Semua Status</option>
                    <option value="ditinjau">Ditinjau</option>
                    <option value="perlu_perbaikan">Perlu Perbaikan</option>
                    <option value="diproses">Diproses</option>
                    <option value="menunggu_aktivasi">Menunggu Aktivasi</option>
                    <option value="aktif">Aktif</option>
                </select>
            </div>
        </div>

        <div style="overflow-x:auto">
            <table class="inv-table" id="invTable">
                <thead>
                    <tr>
                        <th>No</th>
                        <th data-type="string" class="sortable">Nama Domain <i class="sort-icon"></i></th>
                        <th data-type="string" class="sortable">Tanggal Pengajuan <i class="sort-icon"></i></th>
                        <th data-type="string" class="sortable">Status <i class="sort-icon"></i></th>
                        <th data-type="string" class="sortable">Catatan <i class="sort-icon"></i></th>
                        <th style="text-align:center; cursor: default;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($data as $indexVerifikasi => $row)
                        <tr data-status="{{ $row->status_pengajuan }}" style="animation-delay:{{$indexVerifikasi*0.05}}s">
                            
                            <td>{{ method_exists($data, 'firstItem') ? $data->firstItem() + $indexVerifikasi : $indexVerifikasi + 1 }}</td>
                            
                            <td style="font-weight:500;color:#334155">{{ $row->nama_domain }}.desa.id</td>
                            
                            <td><span class="inv-date">{{ $row->tgl_pengajuan }}</span></td>
                            
                            <td style="white-space:nowrap">
                                @if($row->status_pengajuan == 'ditinjau')
                                    <span class="inv-badge" style="background:#fef9c3; color:#854d0e; border:1px solid #fde047">
                                        <span class="d" style="background:#eab308"></span>Ditinjau
                                    </span>
                                @elseif($row->status_pengajuan == 'perlu_perbaikan')
                                    <span class="inv-badge badge-red">
                                        <span class="d"></span>Perlu Perbaikan
                                    </span>
                                @elseif($row->status_pengajuan == 'diproses')
                                    <span class="inv-badge" style="background:#dbeafe; color:#1e40af; border:1px solid #93c5fd">
                                        <span class="d" style="background:#3b82f6"></span>Diproses
                                    </span>
                                @elseif($row->status_pengajuan == 'menunggu_aktivasi')
                                    <span class="inv-badge" style="background:#ffedd5; color:#9a3412; border:1px solid #fed7aa">
                                        <span class="d" style="background:#f97316"></span>Menunggu Aktivasi
                                    </span>
                                @elseif($row->status_pengajuan == 'aktif')
                                    <span class="inv-badge badge-green">
                                        <span class="d"></span>Aktif
                                    </span>
                                @else
                                    <span class="inv-badge" style="background:#f1f5f9; color:#475569; border:1px solid #cbd5e1">
                                        <span class="d" style="background:#94a3b8"></span>Draft
                                    </span>
                                @endif
                            </td>

                            <td style="white-space:nowrap">
                                <span style="font-size:13px; color:#64748b;">
                                    {{ $row->catatan_umum ?? '-' }}
                                </span>
                            </td>

                            <td style="text-align:center">
                                <div style="display:flex;justify-content:center;gap:8px;">
                                    <a href="{{ route('desa.verifikasi.detail', $row->id_pengajuan) }}" class="inv-btn-d" title="Lihat">
                                        <i class="fas fa-eye"></i> Lihat
                                    </a>    
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr class="inv-empty"><td colspan="6"><i class="fas fa-inbox"></i> Tidak ada data pengajuan</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @include('components.inv-pagination', ['paginator' => $data])
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded',function(){
    // Filter Dropdown Status (Client Side Only - untuk mempercepat jika data sudah diload)
    var f=document.getElementById('invFilter'),
        rows=Array.from(document.querySelectorAll('#invTable tbody tr[data-status]')),
        empty=document.querySelector('.inv-empty');

    // Hapus listener search yang lama agar tidak bentrok dengan form GET
    // var s=document.getElementById('invSearch'); -> Tidak dipakai lagi karena sudah jadi form

    if(f) {
        f.addEventListener('change',function(){
            var v=f.value, n=0;
            rows.forEach(function(r){
                var statusMatch = (!v || r.dataset.status === v);
                r.style.display=statusMatch?'':'none';
                if(statusMatch)n++;
            });
            if(empty)empty.style.display=n?'none':'';
        });
        
        // Trigger filter saat halaman dimuat jika ada value di dropdown (opsional)
        // f.dispatchEvent(new Event('change'));
    }

    // --- LOGIC SORTING ---
    const sortHeaders = document.querySelectorAll('th.sortable');
    
    sortHeaders.forEach(header => {
        header.style.cursor = 'pointer';
        header.addEventListener('mouseenter', () => header.style.backgroundColor = '#f8fafc');
        header.addEventListener('mouseleave', () => header.style.backgroundColor = '');

        header.addEventListener('click', () => {
            const table = header.closest('table');
            const tbody = table.querySelector('tbody');
            const allRows = Array.from(tbody.querySelectorAll('tr'));
            
            // Kolom No di-skip karena tidak ada data-type
            const type = header.dataset.type;
            if(!type) return;

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
</script>
@endsection