@extends('layouts.admin')
@section('title', 'Daftar Domain Terdaftar')

@section('content')

@include('components.inv-styles')

<!-- WIDGET STATISTIK -->
<div class="container-fluid" style="padding:0 24px;max-width:1400px; margin-bottom: 20px;">
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">

        <!-- Widget 1 -->
        <div class="bg-white p-4 rounded-lg shadow-sm border-l-4 border-blue-500 flex justify-between items-center">
            <div>
                <p class="text-gray-500 text-xs font-bold uppercase">Total Domain</p>
                <h3 class="text-xl font-bold text-gray-800 mt-1">{{ $totalDomain }}</h3>
            </div>
            <div class="text-blue-500">
                <i class="fas fa-globe fa-2x"></i>
            </div>
        </div>

        <!-- Widget 2 -->
        <div class="bg-white p-4 rounded-lg shadow-sm border-l-4 border-green-500 flex justify-between items-center">
            <div>
                <p class="text-gray-500 text-xs font-bold uppercase">Domain Aktif</p>
                <h3 class="text-xl font-bold text-gray-800 mt-1">{{ $totalAktif }}</h3>
            </div>
            <div class="text-green-500">
                <i class="fas fa-check-circle fa-2x"></i>
            </div>
        </div>

        <!-- Widget 3 -->
        <div class="bg-white p-4 rounded-lg shadow-sm border-l-4 border-gray-500 flex justify-between items-center">
            <div>
                <p class="text-gray-500 text-xs font-bold uppercase">Nonaktif</p>
                <h3 class="text-xl font-bold text-gray-800 mt-1">{{ $totalNonaktif }}</h3>
            </div>
            <div class="text-gray-500">
                <i class="fas fa-pause-circle fa-2x"></i>
            </div>
        </div>

        <!-- Widget 4 -->
        <div class="bg-white p-4 rounded-lg shadow-sm border-l-4 border-red-500 flex justify-between items-center">
            <div>
                <p class="text-gray-500 text-xs font-bold uppercase">Kadaluarsa</p>
                <h3 class="text-xl font-bold text-gray-800 mt-1">{{ $totalKadaluarsa }}</h3>
            </div>
            <div class="text-red-500">
                <i class="fas fa-exclamation-circle fa-2x"></i>
            </div>
        </div>

    </div>
</div>

<!-- HEADER & FILTER DI LUAR CARD -->
<div class="container-fluid" style="padding:0 24px;max-width:1400px;margin-bottom:20px">

    <div style="display:flex;align-items:flex-end;justify-content:space-between;flex-wrap:wrap;gap:10px;margin-bottom:18px">

        <div>
            <h1 style="font-size:22px;font-weight:800;margin:0;letter-spacing:-.5px">
                Daftar Domain Terdaftar
            </h1>

            <p style="font-size:14px;color:#64748b;margin:4px 0 0">
                Kelola status aktivasi dan masa berlaku domain desa
            </p>
        </div>

        <!-- FILTER STATUS -->
        <div style="display:flex; background:#f8fafc; padding:4px; border-radius:6px;">
            
            <a href="{{ route('admin.domain_terdaftar', ['status' => 'all', 'kecamatan' => request('kecamatan')]) }}"
                class="px-4 py-1.5 text-xs font-medium rounded transition-all
                {{ $statusFilter == 'all' || empty($statusFilter) ? 'bg-white shadow-sm text-blue-600 font-bold' : 'text-gray-500 hover:text-gray-700' }}">
                Semua
            </a>

            <a href="{{ route('admin.domain_terdaftar', ['status' => 'aktif', 'kecamatan' => request('kecamatan')]) }}"
                class="px-4 py-1.5 text-xs font-medium rounded transition-all
                {{ $statusFilter == 'aktif' ? 'bg-white shadow-sm text-green-600 font-bold' : 'text-gray-500 hover:text-gray-700' }}">
                Aktif
            </a>

            <a href="{{ route('admin.domain_terdaftar', ['status' => 'kadaluarsa', 'kecamatan' => request('kecamatan')]) }}"
                class="px-4 py-1.5 text-xs font-medium rounded transition-all
                {{ $statusFilter == 'kadaluarsa' ? 'bg-white shadow-sm text-red-600 font-bold' : 'text-gray-500 hover:text-gray-700' }}">
                Kadaluarsa
            </a>

        </div>

    </div>

    <!-- FILTER KECAMATAN -->
    <div>
        <form method="GET" action="{{ route('admin.domain_terdaftar') }}">

            <input type="hidden" name="status" value="{{ $statusFilter }}">

            <select name="kecamatan"
                onchange="this.form.submit()"
                style="padding:10px 14px; border:1px solid #e2e8f0; border-radius:6px; font-size:14px; min-width:250px; outline:none; background:white;">

                <option value="">-- Semua Kecamatan --</option>

                @foreach($kecamatanList as $kecamatan)
                    <option value="{{ $kecamatan }}"
                        {{ request('kecamatan') == $kecamatan ? 'selected' : '' }}>
                        {{ $kecamatan }}
                    </option>
                @endforeach

            </select>
        </form>
    </div>

</div>

<!-- CARD HANYA SEARCH + TABLE -->
<div class="container-fluid" style="padding:0 24px;max-width:1400px">

    <div class="inv-card">

        <!-- SEARCH -->
        <div style="margin-bottom:20px; position:relative;">
            <input type="text"
                id="invSearch"
                placeholder="Cari Nama Desa atau Domain..."
                style="width:100%; padding:10px 15px; border:1px solid #e2e8f0; border-radius:6px; outline:none; font-size:14px;">

            <i class="fas fa-search"
                style="position:absolute; right:15px; top:12px; color:#94a3b8;"></i>
        </div>

        <!-- TABLE -->
        <div style="overflow-x:auto">
            <table class="inv-table" id="invTable">

                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Desa</th>
                        <th>Kecamatan</th>
                        <th>Domain</th>
                        <th style="text-align:center">Tipe</th>
                        <th>Tgl Aktivasi</th>
                        <th>Masa Berlaku Hingga</th>
                        <th style="text-align:center">Status</th>
                        <th style="text-align:center">Aksi</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($data as $i => $row)

                    <tr data-status="{{ $row->aktivasi->status_akt ?? '' }}"
                        style="animation-delay:{{$i*0.05}}s">

                        <td>{{ $data->firstItem() + $i }}</td>

                        <td>
                            <span class="inv-date">
                                {{-- PERUBAHAN: Mengambil dari kolom 'desa_kelurahan' bukan 'nama_desa' --}}
                                {{ $row->desa_kelurahan }}
                            </span>
                        </td>

                        <td>
                            <span class="inv-date">
                                {{ $row->kecamatan }}
                            </span>
                        </td>

                        <td>
                            <span class="inv-id">
                                {{ $row->nama_domain }}.desa.id
                            </span>
                        </td>

                        <!-- TIPE -->
                        <td style="text-align:center">

                            @php
                                $fakturTerakhir = $row->faktur->last();
                            @endphp

                            @if($fakturTerakhir && $fakturTerakhir->tipe == 'perpanjangan')

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
                                {{ $row->aktivasi ? $row->aktivasi->tgl_aktivasi->format('d/m/Y') : '-' }}
                            </span>
                        </td>

                        <td>
                            @if($row->aktivasi)

                                <span class="inv-date {{ $row->aktivasi->status_akt == 'kadaluarsa' ? 'text-red-600 font-bold' : '' }}">

                                    {{ $row->aktivasi->masa_berlaku->format('d/m/Y') }}

                                </span>

                            @else

                                <span class="inv-date">-</span>

                            @endif
                        </td>

                        <!-- STATUS -->
                        <td style="text-align:center">

                            @if($row->aktivasi->status_akt == 'aktif')

                                <span class="inv-badge badge-green">
                                    <span class="d"></span>Aktif
                                </span>

                            @elseif($row->aktivasi->status_akt == 'kadaluarsa')

                                <span class="inv-badge badge-red">
                                    <span class="d"></span>Kadaluarsa
                                </span>

                            @else

                                <span class="inv-badge"
                                    style="background:#f1f5f9;color:#475569">

                                    <span class="d"
                                        style="background:#94a3b8"></span>

                                    {{ ucfirst($row->aktivasi->status_akt) }}

                                </span>

                            @endif

                        </td>

                        <!-- AKSI -->
                        <td style="text-align:center">
                            <a href="{{ route('admin.pengajuan.detail', $row->id_pengajuan) }}"
                                class="inv-btn-d">

                                <i class="fas fa-eye"></i>Detail
                            </a>
                        </td>

                    </tr>

                    @empty

                    <tr class="inv-empty">
                        <td colspan="9">
                            <i class="fas fa-inbox"></i>
                            Tidak ada data domain
                        </td>
                    </tr>

                    @endforelse

                </tbody>

            </table>
        </div>

        <!-- PAGINATION -->
        @include('components.inv-pagination', ['paginator' => $data])

    </div>
</div>

<!-- SEARCH SCRIPT -->
<script>
document.addEventListener('DOMContentLoaded', function () {

    var s = document.getElementById('invSearch'),
        rows = Array.from(document.querySelectorAll('#invTable tbody tr[data-status]')),
        empty = document.querySelector('.inv-empty');

    function filterSearch() {

        var q = s.value.trim().toLowerCase(),
            n = 0;

        rows.forEach(function (r) {

            var show = (!q || r.textContent.toLowerCase().includes(q));

            r.style.display = show ? '' : 'none';

            if (show) n++;
        });

        if (empty) {
            empty.style.display = n ? 'none' : '';
        }
    }

    s.addEventListener('input', filterSearch);
});
</script>

@endsection