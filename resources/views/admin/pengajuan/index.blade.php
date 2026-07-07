@extends('layouts.admin')

@section('title', 'Pengajuan Domain')

@section('content')

<div class="space-y-6">
    
    <!-- HEADER -->
    <div>
        <h1 class="text-2xl font-extrabold text-slate-800 tracking-tight">Manajemen Pengajuan Domain</h1>
        <p class="text-sm text-slate-400 mt-1">Kelola dan verifikasi domain baru atau perlu perbaikan</p>
    </div>

    <!-- WIDGET STATISTIK -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        
        <!-- Ditinjau -->
        <div class="bg-white rounded-2xl border border-slate-100 p-5 flex items-center gap-4 shadow-sm hover:shadow-md transition-shadow">
            <div class="w-12 h-12 bg-gradient-to-br from-amber-400 to-amber-500 rounded-xl flex items-center justify-center shadow-sm shadow-amber-500/20 flex-shrink-0">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
            </div>
            <div>
                <p class="text-slate-400 text-xs font-semibold uppercase tracking-wider">Ditinjau</p>
                <h3 class="text-2xl font-extrabold text-amber-600 mt-0.5">{{ $totalDitinjau }}</h3>
            </div>
        </div>

        <!-- Perlu Perbaikan -->
        <div class="bg-white rounded-2xl border border-slate-100 p-5 flex items-center gap-4 shadow-sm hover:shadow-md transition-shadow">
            <div class="w-12 h-12 bg-gradient-to-br from-rose-400 to-rose-500 rounded-xl flex items-center justify-center shadow-sm shadow-rose-500/20 flex-shrink-0">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M11.42 15.17l-5.1-5.1m0 0L11.42 4.97m-5.1 5.1H21M3 21h18"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M11.42 15.17L6.32 10.07m0 0l5.1-5.1m-5.1 5.1H21M3 21h18"/>
                </svg>
            </div>
            <div>
                <p class="text-slate-400 text-xs font-semibold uppercase tracking-wider">Perlu Perbaikan</p>
                <h3 class="text-2xl font-extrabold text-rose-600 mt-0.5">{{ $totalPerbaikan }}</h3>
            </div>
        </div>

        <!-- Diproses -->
        <div class="bg-white rounded-2xl border border-slate-100 p-5 flex items-center gap-4 shadow-sm hover:shadow-md transition-shadow">
            <div class="w-12 h-12 bg-gradient-to-br from-sky-400 to-sky-500 rounded-xl flex items-center justify-center shadow-sm shadow-sky-500/20 flex-shrink-0">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12a7.5 7.5 0 0015 0m-15 0a7.5 7.5 0 1115 0m-15 0H3m16.5 0H21m-1.5 0H12m-8.457 3.077l1.41-.513m14.095-5.13l1.41-.513M5.106 17.785l1.15-.964m11.49-9.642l1.149-.964M7.501 19.795l.75-1.3m7.5-12.99l.75-1.3m-6.063 16.658l.26-1.477m2.605-14.772l.26-1.477m0 17.726l-.26-1.477M10.698 4.614l-.26-1.477M16.5 19.794l-.75-1.299M7.5 4.205L6.75 2.906m9.944 18.366l-.26-1.477M10.698 4.614l-.26-1.477"/>
                </svg>
            </div>
            <div>
                <p class="text-slate-400 text-xs font-semibold uppercase tracking-wider">Diproses</p>
                <h3 class="text-2xl font-extrabold text-sky-600 mt-0.5">{{ $totalDiproses }}</h3>
            </div>
        </div>

        <!-- Menunggu Aktivasi -->
        <div class="bg-white rounded-2xl border border-slate-100 p-5 flex items-center gap-4 shadow-sm hover:shadow-md transition-shadow">
            <div class="w-12 h-12 bg-gradient-to-br from-orange-400 to-orange-500 rounded-xl flex items-center justify-center shadow-sm shadow-orange-500/20 flex-shrink-0">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div>
                <p class="text-slate-400 text-xs font-semibold uppercase tracking-wider">Menunggu Aktivasi</p>
                <h3 class="text-2xl font-extrabold text-orange-600 mt-0.5">{{ $totalAktivasi }}</h3>
            </div>
        </div>

    </div>

    <!-- CARD TABLE UTAMA -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
        
        <!-- ALERTS -->
        @if(session('success'))
            <div class="mx-6 mt-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm font-medium rounded-xl flex items-center justify-between">
                <span>{{ session('success') }}</span>
                <button type="button" class="text-emerald-500 hover:text-emerald-700 transition">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        @endif
        @if(session('error'))
            <div class="mx-6 mt-6 p-4 bg-rose-50 border border-rose-200 text-rose-700 text-sm font-medium rounded-xl flex items-center justify-between">
                <span>{{ session('error') }}</span>
                <button type="button" class="text-rose-500 hover:text-rose-700 transition">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        @endif

        <!-- FORM SEARCH & FILTER -->
        <form action="{{ route('admin.pengajuan.index') }}" method="GET" class="p-6 border-b border-slate-100">
            <div class="flex flex-col md:flex-row gap-4">
                
                <div class="relative flex-1">
                    <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                    <input type="text" name="search" id="invSearch" placeholder="Cari Nama Desa atau Domain..." value="{{ request('search') }}"
                        class="w-full pl-11 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#109696]/20 focus:border-[#109696] transition">
                </div>
                
                <button type="submit"
                    class="bg-[#109696] hover:bg-[#0d7a7a] text-white font-semibold py-2.5 px-6 rounded-xl text-sm transition shadow-sm shadow-[#109696]/20 flex items-center justify-center gap-2">
                    <i class="fas fa-search text-xs"></i> Cari
                </button>

                <div class="relative">
                    <select id="invFilter" name="status" onchange="this.form.submit()" 
                        class="appearance-none pl-4 pr-10 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#109696]/20 focus:border-[#109696] transition w-full md:w-52 cursor-pointer">
                        <option value="">Semua Status</option>
                        <option value="ditinjau" {{ request('status') == 'ditinjau' ? 'selected' : '' }}>Ditinjau</option>
                        <option value="perlu_perbaikan" {{ request('status') == 'perlu_perbaikan' ? 'selected' : '' }}>Perlu Perbaikan</option>
                        <option value="diproses" {{ request('status') == 'diproses' ? 'selected' : '' }}>Diproses</option>
                        <option value="menunggu_aktivasi" {{ request('status') == 'menunggu_aktivasi' ? 'selected' : '' }}>Menunggu Aktivasi</option>
                    </select>
                    <i class="fas fa-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 text-xs pointer-events-none"></i>
                </div>
            </div>
        </form>

        <!-- TABLE -->
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left" id="invTable">
                <thead>
                    <tr class="bg-slate-50/80 border-b border-slate-100">
                        <th class="px-5 py-3.5 font-semibold text-slate-500 uppercase text-xs tracking-wider">No</th>
                        <th data-type="string" class="sortable px-5 py-3.5 font-semibold text-slate-500 uppercase text-xs tracking-wider cursor-pointer hover:text-slate-700 transition select-none">
                            Nama Desa <i class="sort-icon text-[10px] ml-1 opacity-50"></i>
                        </th>
                        <th data-type="string" class="sortable px-5 py-3.5 font-semibold text-slate-500 uppercase text-xs tracking-wider cursor-pointer hover:text-slate-700 transition select-none">
                            Domain <i class="sort-icon text-[10px] ml-1 opacity-50"></i>
                        </th>
                        <th data-type="string" class="sortable px-5 py-3.5 font-semibold text-slate-500 uppercase text-xs tracking-wider cursor-pointer hover:text-slate-700 transition select-none">
                            Tgl Pengajuan <i class="sort-icon text-[10px] ml-1 opacity-50"></i>
                        </th>
                        <th data-type="string" class="sortable px-5 py-3.5 font-semibold text-slate-500 uppercase text-xs tracking-wider cursor-pointer hover:text-slate-700 transition select-none text-center">
                            Status <i class="sort-icon text-[10px] ml-1 opacity-50"></i>
                        </th>
                        <th class="px-5 py-3.5 font-semibold text-slate-500 uppercase text-xs tracking-wider text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($data as $indexPengajuan => $row)
                        <tr data-status="{{ $row->status_pengajuan }}" class="hover:bg-slate-50/50 transition-colors">
                            
                            <td class="px-5 py-4 text-slate-400 font-medium">{{ $data->firstItem() + $indexPengajuan }}</td>
                            
                            <td class="px-5 py-4 font-medium text-slate-700">{{ $row->nama_desa }}</td>
                            
                            <td class="px-5 py-4">
                                <span class="font-semibold text-[#1A85A5]">{{ $row->nama_domain }}<span class="text-slate-400">.desa.id</span></span>
                            </td>
                            
                            <td class="px-5 py-4 text-slate-500">{{ $row->tgl_pengajuan }}</td>
                            
                            <td class="px-5 py-4 text-center">
                                @if($row->status_pengajuan == 'ditinjau')
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-amber-50 text-amber-700 border border-amber-100">
                                        <span class="w-1.5 h-1.5 bg-amber-500 rounded-full"></span>Ditinjau
                                    </span>
                                @elseif($row->status_pengajuan == 'perlu_perbaikan')
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-rose-50 text-rose-700 border border-rose-100">
                                        <span class="w-1.5 h-1.5 bg-rose-500 rounded-full"></span>Perlu Perbaikan
                                    </span>
                                @elseif($row->status_pengajuan == 'diproses')
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-sky-50 text-sky-700 border border-sky-100">
                                        <span class="w-1.5 h-1.5 bg-sky-500 rounded-full"></span>Diproses
                                    </span>
                                @elseif($row->status_pengajuan == 'menunggu_aktivasi')
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-orange-50 text-orange-700 border border-orange-100">
                                        <span class="w-1.5 h-1.5 bg-orange-500 rounded-full"></span>Menunggu Aktivasi
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-slate-100 text-slate-600 border border-slate-200">
                                        <span class="w-1.5 h-1.5 bg-slate-400 rounded-full"></span>Draft
                                    </span>
                                @endif
                            </td>

                            <td class="px-5 py-4 text-center">
                                <a href="{{ route('admin.pengajuan.detail', $row->uuid) }}" 
                                   class="inline-flex items-center gap-1.5 px-4 py-2 bg-[#109696]/10 text-[#109696] rounded-lg text-xs font-semibold hover:bg-[#109696] hover:text-white transition-all duration-200">
                                    <i class="fas fa-eye text-[10px]"></i>
                                    Detail
                                </a>
                            </td>

                        </tr>
                    @empty
                        <tr class="inv-empty">
                            <td colspan="6" class="px-5 py-16 text-center">
                                <div class="flex flex-col items-center gap-3 text-slate-400">
                                    <i class="fas fa-inbox text-4xl text-slate-300"></i>
                                    <p class="font-medium">Tidak ada data pengajuan</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- PAGINATION -->
        <div class="p-6 border-t border-slate-100">
            @include('components.inv-pagination', ['paginator' => $data])
        </div>

    </div>
</div>

<!-- SORTING SCRIPT (Simplified & Cleaner) -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const sortHeaders = document.querySelectorAll('th.sortable');
    
    sortHeaders.forEach(header => {
        header.addEventListener('click', () => {
            const table = header.closest('table');
            const tbody = table.querySelector('tbody');
            const allRows = Array.from(tbody.querySelectorAll('tr:not(.inv-empty)'));
            
            const type = header.dataset.type;
            const icon = header.querySelector('.sort-icon');
            const colIndex = Array.from(header.parentNode.children).indexOf(header);

            // Reset icons & classes
            document.querySelectorAll('th.sortable .sort-icon').forEach(i => { i.textContent = ''; i.classList.remove('opacity-100'); i.classList.add('opacity-50'); });
            document.querySelectorAll('th.sortable').forEach(h => h.classList.remove('text-[#109696]'));
            
            let isAsc = !header.classList.contains('asc');
            
            sortHeaders.forEach(h => h.classList.remove('asc', 'desc'));
            header.classList.add(isAsc ? 'asc' : 'desc');
            header.classList.add('text-[#109696]');
            icon.textContent = isAsc ? ' ▲' : ' ▼';
            icon.classList.remove('opacity-50');
            icon.classList.add('opacity-100');

            allRows.sort((a, b) => {
                let aVal = a.cells[colIndex].textContent.trim();
                let bVal = b.cells[colIndex].textContent.trim();

                if (type === 'number') {
                     aVal = parseInt(aVal.replace(/\D/g, ''), 10);
                     bVal = parseInt(bVal.replace(/\D/g, ''), 10);
                     return isAsc ? aVal - bVal : bVal - aVal;
                }

                return isAsc ? aVal.localeCompare(bVal) : bVal.localeCompare(aVal);
            });

            allRows.forEach(row => tbody.appendChild(row));
        });
    });
});
</script>

@endsection