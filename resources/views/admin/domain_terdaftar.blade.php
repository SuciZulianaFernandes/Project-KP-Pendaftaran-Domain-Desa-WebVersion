@extends('layouts.admin')

@section('title', 'Daftar Domain Terdaftar')

@section('content')

<div class="space-y-6">
    
    <!-- WIDGET STATISTIK -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        
        <!-- Total Domain -->
        <div class="bg-white rounded-2xl border border-slate-100 p-5 flex items-center gap-4 shadow-sm hover:shadow-md transition-shadow">
            <div class="w-12 h-12 bg-gradient-to-br from-[#109696] to-[#1A85A5] rounded-xl flex items-center justify-center shadow-sm shadow-[#109696]/20 flex-shrink-0">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9.004 9.004 0 008.716-6.747M12 21a9.004 9.004 0 01-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3m0 0a8.997 8.997 0 017.843 4.582M12 3a8.997 8.997 0 00-7.843 4.582m15.686 0A11.953 11.953 0 0112 10.5c-2.998 0-5.74-1.1-7.843-2.918m15.686 0A8.959 8.959 0 0121 12c0 .778-.099 1.533-.284 2.253m0 0A17.919 17.919 0 0112 16.5c-3.162 0-6.133-.815-8.716-2.247m0 0A9.015 9.015 0 013 12c0-1.605.42-3.113 1.157-4.418"/></svg>
            </div>
            <div>
                <p class="text-slate-400 text-xs font-semibold uppercase tracking-wider">Total Domain</p>
                <h3 class="text-2xl font-extrabold text-slate-800 mt-0.5">{{ $totalDomain }}</h3>
            </div>
        </div>

        <!-- Domain Aktif -->
        <div class="bg-white rounded-2xl border border-slate-100 p-5 flex items-center gap-4 shadow-sm hover:shadow-md transition-shadow">
            <div class="w-12 h-12 bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-xl flex items-center justify-center shadow-sm shadow-emerald-500/20 flex-shrink-0">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div>
                <p class="text-slate-400 text-xs font-semibold uppercase tracking-wider">Domain Aktif</p>
                <h3 class="text-2xl font-extrabold text-slate-800 mt-0.5">{{ $totalAktif }}</h3>
            </div>
        </div>

        <!-- Nonaktif -->
        <div class="bg-white rounded-2xl border border-slate-100 p-5 flex items-center gap-4 shadow-sm hover:shadow-md transition-shadow">
            <div class="w-12 h-12 bg-gradient-to-br from-slate-400 to-slate-500 rounded-xl flex items-center justify-center shadow-sm shadow-slate-400/20 flex-shrink-0">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 5.25a3 3 0 013 3m3 0a6 6 0 01-7.029 5.912c-.563-.097-1.159.026-1.563.43L10.5 17.25H8.25v2.25H6v2.25H2.25v-2.818c0-.597.237-1.17.659-1.591l6.499-6.499c.404-.404.527-1 .43-1.563A6 6 0 1121.75 8.25z"/></svg>
            </div>
            <div>
                <p class="text-slate-400 text-xs font-semibold uppercase tracking-wider">Nonaktif</p>
                <h3 class="text-2xl font-extrabold text-slate-800 mt-0.5">{{ $totalNonaktif }}</h3>
            </div>
        </div>

        <!-- Kadaluarsa -->
        <div class="bg-white rounded-2xl border border-slate-100 p-5 flex items-center gap-4 shadow-sm hover:shadow-md transition-shadow">
            <div class="w-12 h-12 bg-gradient-to-br from-rose-400 to-rose-500 rounded-xl flex items-center justify-center shadow-sm shadow-rose-500/20 flex-shrink-0">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"/></svg>
            </div>
            <div>
                <p class="text-slate-400 text-xs font-semibold uppercase tracking-wider">Kadaluarsa</p>
                <h3 class="text-2xl font-extrabold text-slate-800 mt-0.5">{{ $totalKadaluarsa }}</h3>
            </div>
        </div>

    </div>

    <!-- HEADER & FILTER -->
    <div class="bg-white rounded-2xl border border-slate-100 p-6 shadow-sm">
        
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 mb-6">
            
            <div>
                <h1 class="text-xl font-extrabold text-slate-800 tracking-tight">Daftar Domain Terdaftar</h1>
                <p class="text-sm text-slate-400 mt-1">Kelola status aktivasi dan masa berlaku domain desa</p>
            </div>

            <!-- FILTER STATUS (Pill/Toggle) - DITAMBAHKAN PARAMETER SEARCH -->
            <div class="flex bg-slate-100 p-1 rounded-xl border border-slate-200/60 w-fit">
                
                <a href="{{ route('admin.domain_terdaftar', ['status' => 'all', 'kecamatan' => request('kecamatan'), 'search' => request('search')]) }}"
                   class="px-5 py-2 text-xs font-semibold rounded-lg transition-all duration-200
                   {{ $statusFilter == 'all' || empty($statusFilter) ? 'bg-white text-[#109696] shadow-sm' : 'text-slate-500 hover:text-slate-700' }}">
                    Semua
                </a>

                <a href="{{ route('admin.domain_terdaftar', ['status' => 'aktif', 'kecamatan' => request('kecamatan'), 'search' => request('search')]) }}"
                   class="px-5 py-2 text-xs font-semibold rounded-lg transition-all duration-200
                   {{ $statusFilter == 'aktif' ? 'bg-white text-emerald-600 shadow-sm' : 'text-slate-500 hover:text-slate-700' }}">
                    Aktif
                </a>

                <a href="{{ route('admin.domain_terdaftar', ['status' => 'kadaluarsa', 'kecamatan' => request('kecamatan'), 'search' => request('search')]) }}"
                   class="px-5 py-2 text-xs font-semibold rounded-lg transition-all duration-200
                   {{ $statusFilter == 'kadaluarsa' ? 'bg-white text-rose-600 shadow-sm' : 'text-slate-500 hover:text-slate-700' }}">
                    Kadaluarsa
                </a>

                <a href="{{ route('admin.domain_terdaftar', ['status' => 'nonaktif', 'kecamatan' => request('kecamatan'), 'search' => request('search')]) }}"
                   class="px-5 py-2 text-xs font-semibold rounded-lg transition-all duration-200
                   {{ $statusFilter == 'nonaktif' ? 'bg-white text-slate-600 shadow-sm' : 'text-slate-500 hover:text-slate-700' }}">
                    Nonaktif
                </a>

            </div>
        </div>

        <!-- FILTER KECAMATAN & SEARCH (DIGABUNG JADI 1 FORM) -->
        <form method="GET" action="{{ route('admin.domain_terdaftar') }}" class="flex flex-col md:flex-row gap-4 mb-6">
            
            <input type="hidden" name="status" value="{{ $statusFilter }}">

            <div class="relative flex-1">
                <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                <input type="text"
                    name="search"
                    value="{{ $search ?? '' }}"
                    placeholder="Cari Nama Desa atau Domain..."
                    class="w-full pl-11 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#109696]/20 focus:border-[#109696] transition">
            </div>

            <div class="relative flex-shrink-0">
                <select name="kecamatan"
                    class="appearance-none pl-4 pr-10 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#109696]/20 focus:border-[#109696] transition w-full md:w-64 cursor-pointer">
                    <option value="">-- Semua Kecamatan --</option>
                    @foreach($kecamatanList as $kecamatan)
                        <option value="{{ $kecamatan }}" {{ request('kecamatan') == $kecamatan ? 'selected' : '' }}>
                            {{ $kecamatan }}
                        </option>
                    @endforeach
                </select>
                <i class="fas fa-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 text-xs pointer-events-none"></i>
            </div>

            <button type="submit" class="bg-[#109696] hover:bg-[#0d7a7a] text-white font-semibold py-2.5 px-5 rounded-xl text-sm transition shadow-sm flex-shrink-0">
                Cari
            </button>
        </form>

        <!-- TABLE -->
        <div class="overflow-x-auto border border-slate-100 rounded-xl">
            <table class="w-full text-sm text-left">
                <thead>
                    <tr class="bg-slate-50/80 border-b border-slate-100">
                        <th class="px-5 py-3.5 font-semibold text-slate-500 uppercase text-xs tracking-wider">No</th>
                        <th class="px-5 py-3.5 font-semibold text-slate-500 uppercase text-xs tracking-wider">Nama Desa</th>
                        <th class="px-5 py-3.5 font-semibold text-slate-500 uppercase text-xs tracking-wider">Kecamatan</th>
                        <th class="px-5 py-3.5 font-semibold text-slate-500 uppercase text-xs tracking-wider">Domain</th>
                        <th class="px-5 py-3.5 font-semibold text-slate-500 uppercase text-xs tracking-wider text-center">Tipe</th>
                        <th class="px-5 py-3.5 font-semibold text-slate-500 uppercase text-xs tracking-wider">Tgl Aktivasi</th>
                        <th class="px-5 py-3.5 font-semibold text-slate-500 uppercase text-xs tracking-wider">Masa Berlaku</th>
                        <th class="px-5 py-3.5 font-semibold text-slate-500 uppercase text-xs tracking-wider text-center">Status</th>
                        <th class="px-5 py-3.5 font-semibold text-slate-500 uppercase text-xs tracking-wider text-center">Aksi</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-50">
                    @forelse($data as $i => $row)
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        
                        <td class="px-5 py-4 text-slate-400 font-medium">{{ $data->firstItem() + $i }}</td>
                        <td class="px-5 py-4 font-medium text-slate-700">{{ $row->desa_kelurahan }}</td>
                        <td class="px-5 py-4 text-slate-500">{{ $row->kecamatan }}</td>
                        
                        <td class="px-5 py-4">
                            <span class="font-semibold text-[#1A85A5]">{{ $row->nama_domain }}<span class="text-slate-400">.desa.id</span></span>
                        </td>

                        <td class="px-5 py-4 text-center">
                            @php $fakturTerakhir = $row->faktur->last(); @endphp
                            @if($fakturTerakhir && $fakturTerakhir->tipe == 'perpanjangan')
                                <span class="px-2.5 py-1 rounded-lg text-xs font-semibold bg-purple-50 text-purple-600 border border-purple-100">Perpanjangan</span>
                            @else
                                <span class="px-2.5 py-1 rounded-lg text-xs font-semibold bg-sky-50 text-sky-600 border border-sky-100">Baru</span>
                            @endif
                        </td>

                        <td class="px-5 py-4 text-slate-500">
                            {{ $row->aktivasi ? $row->aktivasi->tgl_aktivasi->format('d/m/Y') : '-' }}
                        </td>

                        <td class="px-5 py-4">
                            @if($row->aktivasi)
                                <span class="{{ $row->aktivasi->status_akt == 'kadaluarsa' || $row->aktivasi->status_akt == 'nonaktif' ? 'text-rose-500 font-bold' : 'text-slate-500' }}">
                                    {{ $row->aktivasi->masa_berlaku->format('d/m/Y') }}
                                </span>
                            @else
                                <span class="text-slate-400">-</span>
                            @endif
                        </td>

                        <td class="px-5 py-4 text-center">
                            @if($row->aktivasi->status_akt == 'aktif')
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-600 border border-emerald-100">
                                    <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full"></span>Aktif
                                </span>
                            @elseif($row->aktivasi->status_akt == 'kadaluarsa')
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-rose-50 text-rose-600 border border-rose-100">
                                    <span class="w-1.5 h-1.5 bg-rose-500 rounded-full"></span>Kadaluarsa
                                </span>
                            @elseif($row->aktivasi->status_akt == 'nonaktif')
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-slate-100 text-slate-500 border border-slate-200">
                                    <span class="w-1.5 h-1.5 bg-slate-400 rounded-full"></span>Nonaktif
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-slate-50 text-slate-500 border border-slate-100">
                                    <span class="w-1.5 h-1.5 bg-slate-300 rounded-full"></span>{{ ucfirst($row->aktivasi->status_akt) }}
                                </span>
                            @endif
                        </td>

                        <td class="px-5 py-4 text-center">
                            <a href="{{ route('admin.pengajuan.detail', $row->uuid) }}" 
                               class="inline-flex items-center gap-1.5 px-4 py-2 bg-[#109696]/10 text-[#109696] rounded-lg text-xs font-semibold hover:bg-[#109696] hover:text-white transition-all duration-200">
                                <i class="fas fa-eye text-[10px]"></i> Detail
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="px-5 py-16 text-center">
                            <div class="flex flex-col items-center gap-3 text-slate-400">
                                <i class="fas fa-inbox text-4xl text-slate-300"></i>
                                <p class="font-medium">Tidak ada data domain</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            @include('components.inv-pagination', ['paginator' => $data])
        </div>

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