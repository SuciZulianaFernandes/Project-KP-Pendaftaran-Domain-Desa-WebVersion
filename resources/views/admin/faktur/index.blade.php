@extends('layouts.admin')
@section('title', 'Manajemen Faktur')

@section('content')
@include('components.inv-styles')

<div class="container-fluid" style="padding:0 24px;max-width:1400px">
    <div style="display:flex;align-items:flex-end;justify-content:space-between;margin-bottom:22px;flex-wrap:wrap;gap:10px">
        <div>
            <h1 style="font-size:22px;font-weight:800;margin:0;letter-spacing:-.5px">Manajemen Faktur</h1>
            <p style="font-size:14px;color:#64748b;margin:4px 0 0">Kelola semua faktur domain desa</p>
        </div>
    </div>

    {{-- WIDGET FAKTUR --}}
<div style="
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(220px,1fr));
    gap:16px;
    margin-bottom:22px;
">

    {{-- Total Faktur --}}
    <div class="inv-card" style="padding:20px;border-left:5px solid #0f172a">
        <div style="font-size:14px;color:#64748b">
            Total Faktur
        </div>

        <div style="
            font-size:28px;
            font-weight:800;
            color:#0f172a;
            margin-top:8px">
            {{ $totalFaktur }}
        </div>
    </div>

    {{-- Belum Dibayar --}}
    <div class="inv-card" style="padding:20px;border-left:5px solid #ef4444">
        <div style="font-size:14px;color:#64748b">
            Belum Dibayar
        </div>

        <div style="
            font-size:28px;
            font-weight:800;
            color:#dc2626;
            margin-top:8px">
            {{ $totalBelumBayar }}
        </div>
    </div>

    {{-- Sudah Dibayar --}}
    <div class="inv-card" style="padding:20px;border-left:5px solid #22c55e">
        <div style="font-size:14px;color:#64748b">
            Sudah Dibayar
        </div>

        <div style="
            font-size:28px;
            font-weight:800;
            color:#16a34a;
            margin-top:8px">
            {{ $totalSudahBayar }}
        </div>
    </div>

    {{-- Belum Dibuat --}}
    <div class="inv-card" style="padding:20px;border-left:5px solid #94a3b8">
        <div style="font-size:14px;color:#64748b">
            Belum Dibuat
        </div>

        <div style="
            font-size:28px;
            font-weight:800;
            color:#475569;
            margin-top:8px">
            {{ $totalBelumDibuat }}
        </div>
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

                {{-- PENCARIAN & FILTER SERVER SIDE --}}
        <div style="padding: 16px; border-bottom: 1px solid #e2e8f0; display: flex; gap: 10px; align-items: center;">
            <form action="{{ route('admin.faktur.index') }}" method="GET" style="display:flex; flex:1; gap:10px; flex-wrap:wrap;">
                
                <div style="position:relative;flex:1; min-width: 200px;">
                    <input type="text" name="search" id="invSearch" placeholder="Cari No Invoice, Domain, atau Desa..." value="{{ request('search') }}"
                        style="width:100%;padding:10px 16px;padding-left:40px;border:1px solid #cbd5e1;border-radius:8px;outline:none;font-size:14px;transition:all .2s">
                    <i class="fas fa-search" style="position:absolute;left:14px;top:13px;color:#94a3b8"></i>
                </div>

                {{-- DROPDOWN FILTER DIPINDAHKAN KE DALAM FORM --}}
                <div style="width: 180px;">
                    <select name="status" id="invFilter" style="width:100%;padding:10px;border:1px solid #cbd5e1;border-radius:8px;background:white;cursor:pointer;">
                        <option value="">Semua Status</option>
                        <option value="belum_dibuat" {{ request('status') == 'belum_dibuat' ? 'selected' : '' }}>Belum Dibuat</option>
                        <option value="belum_bayar" {{ request('status') == 'belum_bayar' ? 'selected' : '' }}>Belum Bayar</option>
                        <option value="sudah_bayar" {{ request('status') == 'sudah_bayar' ? 'selected' : '' }}>Sudah Bayar</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-primary" style="padding: 0 20px; border-radius: 8px;">
                    Cari
                </button>
            </form>
        </div>

        <div style="overflow-x:auto">
            <table class="inv-table" id="invTable">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>No Invoice</th>
                        <th>Nama Desa</th>
                        <th>Domain</th>
                        <th style="text-align:center">Tipe</th>
                        <th>Tanggal Konfirmasi</th>
                        <th style="text-align:center">Status</th>
                        <th style="text-align:center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($data as $i => $row)
                        @if($row->faktur->isEmpty() && !in_array($row->id_pengajuan, $perpanjanganBelumBuat))
                            <tr data-status="belum_dibuat" style="animation-delay:{{$i*0.05}}s">
                                <td>{{ $data->firstItem() + $i }}</td>
                                <td><span class="inv-id">-</span></td>
                                <td>{{ $row->nama_desa }}</td>
                                <td><span class="inv-date">{{ $row->nama_domain }}.desa.id</span></td>
                                
                                <td style="text-align:center">
                                    <span class="px-2 py-0.5 rounded text-xs bg-blue-100 text-blue-700 font-medium">Baru</span>
                                </td>

                                <td><span class="inv-date">-</span></td>

                                <td style="text-align:center">
                                    <span class="inv-badge" style="background:#f1f5f9;color:#475569"><span class="d" style="background:#94a3b8"></span>Belum Dibuat</span>
                                </td>

                                <td style="text-align:center">
                                    @php
                                        $isRequested = \App\Models\Pesan::where('id_pengajuan', $row->id_pengajuan)
                                            ->where('judul', 'Konfirmasi Pembayaran Disetujui')
                                            ->where('role_tujuan', 'admin')
                                            ->exists();
                                    @endphp

                                    @if($isRequested)
                                        <form action="{{ route('admin.faktur.store', $row->id_pengajuan) }}" method="POST" style="display:inline">
                                            @csrf
                                            <button type="submit" class="inv-btn-d js-confirm-print"><i class="fas fa-plus"></i> Cetak Faktur</button>
                                        </form>
                                    @else
                                        <span class="text-xs text-gray-400 italic">Menunggu Konfirmasi</span>
                                    @endif
                                </td>
                            </tr>
                        @else
                            @foreach($row->faktur as $indexFaktur => $fakturItem)
                                <tr data-status="{{ $fakturItem->status }}" style="animation-delay:{{$i*0.05}}s">
                                    <td>
                                        {{ $data->firstItem() + $i }}
                                        {{ $row->faktur->count() > 1 ? '.' . ($indexFaktur + 1) : '' }}
                                    </td>
                                    <td><span class="inv-id">{{ $fakturItem->no_invoice }}</span></td>
                                    <td>{{ $row->nama_desa }}</td>
                                    <td><span class="inv-date">{{ $row->nama_domain }}.desa.id</span></td>
                                    
                                    <td style="text-align:center">
                                        @if($fakturItem->tipe == 'perpanjangan')
                                            <span class="px-2 py-0.5 rounded text-xs bg-purple-100 text-purple-700 font-medium">Perpanjangan</span>
                                        @else
                                            <span class="px-2 py-0.5 rounded text-xs bg-blue-100 text-blue-700 font-medium">Baru</span>
                                        @endif
                                    </td>

                                    <td><span class="inv-date">{{ $fakturItem->tanggal_konfirmasi ? $fakturItem->tanggal_konfirmasi->format('d/m/Y') : '-' }}</span></td>

                                    <td style="text-align:center">
                                        @if($fakturItem->status == 'sudah_bayar')
                                            <span class="inv-badge badge-green"><span class="d"></span>Sudah Dibayar</span>
                                        @elseif($fakturItem->status == 'belum_bayar')
                                            <span class="inv-badge badge-red"><span class="d"></span>Belum Dibayar</span>
                                        @elseif($fakturItem->status == 'kedaluarsa')
                                            <span class="inv-badge" style="background:#f1f5f9;color:#475569"><span class="d" style="background:#94a3b8"></span>Kedaluarsa</span>
                                        @endif
                                    </td>

                                    <td style="text-align:center">
                                        <a href="{{ route('admin.faktur.show', $fakturItem->id) }}" class="inv-btn-d"><i class="fas fa-eye"></i> Detail</a>
                                    </td>
                                </tr>
                            @endforeach

                            @if(in_array($row->id_pengajuan, $perpanjanganBelumBuat))
                                <tr data-status="belum_dibuat" style="animation-delay:{{$i*0.05}}s">
                                    <td>
                                        {{ $data->firstItem() + $i }}.{{ $row->faktur->count() + 1 }}
                                    </td>
                                    <td><span class="inv-id">-</span></td>
                                    <td>{{ $row->nama_desa }}</td>
                                    <td><span class="inv-date">{{ $row->nama_domain }}.desa.id</span></td>
                                    
                                    <td style="text-align:center">
                                        <span class="px-2 py-0.5 rounded text-xs bg-purple-100 text-purple-700 font-medium">Perpanjangan</span>
                                    </td>

                                    <td><span class="inv-date">-</span></td>

                                    <td style="text-align:center">
                                        <span class="inv-badge" style="background:#f1f5f9;color:#475569"><span class="d" style="background:#94a3b8"></span>Belum dibuat</span>
                                    </td>

                                    <td style="text-align:center">
                                        <form action="{{ route('admin.faktur.store', $row->id_pengajuan) }}" method="POST" style="display:inline">
                                            @csrf
                                            <button type="submit" class="inv-btn-d js-confirm-print"><i class="fas fa-plus"></i> Buat Faktur</button>
                                        </form>
                                    </td>
                                </tr>
                            @endif
                        @endif
                    @empty
                        <tr class="inv-empty"><td colspan="8"><i class="fas fa-inbox"></i>Belum ada faktur</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @include('components.inv-pagination', ['paginator' => $data])
    </div>
</div>

<!-- MODAL POPUP CONFIRMATION CETAK FAKTUR -->
<div id="printConfirmationModal" class="hidden fixed inset-0 bg-gray-900 bg-opacity-50 overflow-y-auto h-full w-full z-50 flex items-center justify-center">
    <div class="relative mx-auto p-5 border w-96 shadow-lg rounded-xl bg-white">
        <div class="mt-3 text-center">
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-blue-100 mb-4">
                <i class="fas fa-print text-blue-600 text-xl"></i>
            </div>
            <h3 class="text-lg leading-6 font-medium text-gray-900">Konfirmasi</h3>
            <div class="mt-2 px-7 py-3">
                <p class="text-sm text-gray-500">Apakah Anda yakin ingin mencetak faktur?</p>
            </div>
        </div>
        <div class="items-center px-4 py-3 flex justify-center gap-3">
            <button id="printModalNoBtn" class="px-4 py-2 bg-gray-200 text-gray-800 text-base font-medium rounded-md shadow-sm hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-300">Batal</button>
            <button id="printModalYesBtn" class="px-4 py-2 bg-blue-600 text-white text-base font-medium rounded-md shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-300">Ya, Lanjutkan</button>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded',function(){
    // Hapus event listener untuk 'invFilter' (dropdown) karena sekarang pakai Form GET Server-side
    // Kita hanya butuh logika Modal Konfirmasi Cetak Faktur

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

    yesBtn.addEventListener('click', function() {
        if (formToSubmit) formToSubmit.submit();
        closeModal();
    });

    noBtn.addEventListener('click', closeModal);
    modal.addEventListener('click', function(e) {
        if (e.target === modal) closeModal();
    });

    function closeModal() {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        formToSubmit = null;
    }
});
</script>
@endsection