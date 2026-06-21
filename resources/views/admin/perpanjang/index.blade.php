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

        {{-- Form Search & Filter Global --}}
        <form action="{{ route('admin.perpanjang.list') }}" method="GET" style="padding: 16px; border-bottom: 1px solid #e2e8f0; display: flex; gap: 10px; align-items: center; flex-wrap: wrap;">
            <div style="position:relative; flex:1; min-width: 250px;">
                <input type="text" name="search" placeholder="Cari Nama Domain..." value="{{ request('search') }}"
                    style="width:100%;padding:10px 16px;padding-left:40px;border:1px solid #cbd5e1;border-radius:8px;outline:none;font-size:14px;transition:all .2s">
                <i class="fas fa-search" style="position:absolute;left:14px;top:13px;color:#94a3b8"></i>
            </div>
            
            <button type="submit" style="padding:10px 16px;background:#dc2626;color:white;border:none;border-radius:8px;font-weight:600;cursor:pointer;">
                Cari
            </button>

            <div style="width: 180px;">
                <select name="status" onchange="this.form.submit()" style="width:100%;padding:10px;border:1px solid #cbd5e1;border-radius:8px;background:white;cursor:pointer;">
                    <option value="">Semua Status</option>
                    <option value="belum_dibuat" {{ request('status') == 'belum_dibuat' ? 'selected' : '' }}>Belum Dibuat</option>
                    <option value="belum_bayar" {{ request('status') == 'belum_bayar' ? 'selected' : '' }}>Belum Bayar</option>
                    <option value="sudah_bayar" {{ request('status') == 'sudah_bayar' ? 'selected' : '' }}>Sudah Bayar</option>
                </select>
            </div>
        </form>

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
                    @php
                        $nomorUrut = $data->firstItem();
                    @endphp

                    @forelse($data as $indexPerpanjang => $row)
                        @if($row['type'] == 'belum_dibuat')
                            <tr data-status="belum_dibuat" style="animation-delay:{{$indexPerpanjang*0.05}}s">
                                <td>{{ ($data->currentPage() - 1) * $data->perPage() + $loop->iteration }}</td>
                                <td style="font-weight:500;color:#334155">{{ $row['pengajuan']->nama_domain }}.desa.id</td>
                                
                                <td style="white-space:nowrap">
                                    <span class="inv-badge" style="background:#dbeafe; color:#1e40af; border:1px solid #93c5fd">
                                        <span class="d" style="background:#3b82f6"></span>Diproses
                                    </span>
                                </td>

                                <td style="white-space:nowrap">
                                    <span class="px-2 py-1 rounded text-xs font-semibold bg-purple-100 text-purple-700 border border-purple-200">
                                        Perpanjangan
                                    </span>
                                </td>

                                <td><span class="inv-date">-</span></td>

                                <td style="white-space:nowrap">
                                    <span class="inv-badge" style="background:#f1f5f9; color:#475569; border:1px solid #cbd5e1">
                                        <span class="d" style="background:#94a3b8"></span>Belum Dibuat
                                    </span>
                                </td>

                                <td style="text-align:center">
                                    <div style="display:flex;justify-content:center;gap:8px;">
                                        <a href="{{ route('admin.perpanjang.show', $row['pengajuan']->id_pengajuan) }}" class="inv-btn-d" title="Lihat">
                                            <i class="fas fa-eye"></i> Detail
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        
                        @elseif($row['type'] == 'faktur')
                            @php
                                $fakturItem = $row['faktur'];
                                $pengajuanRow = $row['pengajuan'];
                                $statusAkhir = $pengajuanRow->status_pengajuan;
                                if ($fakturItem->status == 'belum_bayar') {
                                    $statusAkhir = 'diproses';
                                } elseif ($statusAkhir == 'aktif' && $pengajuanRow->aktivasi) {
                                    $statusAkhir = $pengajuanRow->aktivasi->status_akt;
                                }
                            @endphp

                            <tr data-status="{{ $fakturItem->status }}" style="animation-delay:{{$indexPerpanjang*0.05}}s">
                                <td>{{ ($data->currentPage() - 1) * $data->perPage() + $loop->iteration }}</td>
                                <td style="font-weight:500;color:#334155">{{ $pengajuanRow->nama_domain }}.desa.id</td>
                                
                                <td style="white-space:nowrap">
                                    @if($statusAkhir == 'menunggu_aktivasi')
                                        <span class="inv-badge" style="background:#ffedd5; color:#9a3412; border:1px solid #fed7aa">
                                            <span class="d" style="background:#f97316"></span>Menunggu Aktivasi
                                        </span>
                                    @elseif($statusAkhir == 'diproses')
                                        <span class="inv-badge" style="background:#dbeafe; color:#1e40af; border:1px solid #93c5fd">
                                            <span class="d" style="background:#3b82f6"></span>Diproses
                                        </span>
                                    @elseif($statusAkhir == 'aktif')
                                        <span class="inv-badge badge-green">
                                            <span class="d"></span>Aktif
                                        </span>
                                    @elseif($statusAkhir == 'kadaluarsa')
                                        <span class="inv-badge badge-red">
                                            <span class="d"></span>Kadaluarsa
                                        </span>
                                    @elseif($statusAkhir == 'nonaktif')
                                        <span class="inv-badge" style="background:#f3f4f6; color:#374151; border:1px solid #9ca3af">
                                            <span class="d" style="background:#6b7280"></span>Nonaktif
                                        </span>
                                    @else
                                        <span class="inv-badge" style="background:#f1f5f9; color:#475569; border:1px solid #cbd5e1">
                                            <span class="d" style="background:#94a3b8"></span>{{ ucfirst(str_replace('_', ' ', $statusAkhir)) }}
                                        </span>
                                    @endif
                                </td>

                                <td style="white-space:nowrap">
                                    <span class="px-2 py-1 rounded text-xs font-semibold bg-purple-100 text-purple-700 border border-purple-200">
                                        Perpanjangan
                                    </span>
                                </td>

                                <td>
                                    <span class="inv-date">{{ $fakturItem->created_at->format('d/m/Y') }}</span>
                                </td>

                                <td style="white-space:nowrap">
                                    @if($fakturItem->status == 'belum_bayar')
                                        <span class="inv-badge badge-red">
                                            <span class="d"></span>Belum Bayar
                                        </span>
                                    @elseif($fakturItem->status == 'sudah_bayar')
                                        <span class="inv-badge badge-green">
                                            <span class="d"></span>Sudah Bayar
                                        </span>
                                    @else
                                        <span class="inv-badge" style="background:#f1f5f9; color:#475569; border:1px solid #cbd5e1">
                                            <span class="d" style="background:#94a3b8"></span>Kadaluarsa
                                        </span>
                                    @endif
                                </td>

                                <td style="text-align:center">
                                    <div style="display:flex;justify-content:center;gap:8px;">
                                        <a href="{{ route('admin.perpanjang.show', $fakturItem->id) }}" class="inv-btn-d" title="Lihat">
                                            <i class="fas fa-eye"></i> Detail
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endif
                    @empty
                        <tr class="inv-empty"><td colspan="7" style="text-align: center; padding: 20px;"><i class="fas fa-inbox"></i> Belum ada pengajuan perpanjangan.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @include('components.inv-pagination', ['paginator' => $data])
    </div>
</div>

<div id="printConfirmationModal" class="hidden fixed inset-0 bg-gray-900 bg-opacity-50 overflow-y-auto h-full w-full z-50 flex items-center justify-center">
    <div class="relative mx-auto p-5 border w-96 shadow-lg rounded-xl bg-white">
        <div class="mt-3 text-center">
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-blue-100 mb-4">
                <i class="fas fa-print text-blue-600 text-xl"></i>
            </div>
            <h3 class="text-lg leading-6 font-medium text-gray-900">Konfirmasi</h3>
            <div class="mt-2 px-7 py-3">
                <p class="text-sm text-gray-500">Apakah Anda yakin ingin membuat faktur?</p>
            </div>
        </div>
        <div class="items-center px-4 py-3 flex justify-center gap-3">
            <button id="printModalNoBtn" class="px-4 py-2 bg-gray-200 text-gray-800 text-base font-medium rounded-md shadow-sm hover:bg-gray-300">Batal</button>
            <button id="printModalYesBtn" class="px-4 py-2 bg-blue-600 text-white text-base font-medium rounded-md shadow-sm hover:bg-blue-700">Ya, Lanjutkan</button>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded',function(){
    // Logic Sorting (Client Side)
    const sortHeaders = document.querySelectorAll('th.sortable');
    sortHeaders.forEach(header => {
        header.style.cursor = 'pointer';
        header.addEventListener('click', () => {
            const table = header.closest('table');
            const tbody = table.querySelector('tbody');
            const allRows = Array.from(tbody.querySelectorAll('tr:not(.inv-empty)'));
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

    // Modal Logic
    const modal = document.getElementById('printConfirmationModal');
    const yesBtn = document.getElementById('printModalYesBtn');
    const noBtn = document.getElementById('printModalNoBtn');
    const confirmBtns = document.querySelectorAll('.js-confirm-print');
    let formToSubmit = null;

    confirmBtns.forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            formToSubmit = this.closest('form');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        });
    });

    if(yesBtn) yesBtn.addEventListener('click', function() { if (formToSubmit) formToSubmit.submit(); closeModal(); });
    if(noBtn) noBtn.addEventListener('click', closeModal);
    if(modal) modal.addEventListener('click', function(e) { if (e.target === modal) closeModal(); });

    function closeModal() {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        formToSubmit = null;
    }
});
</script>
@endsection