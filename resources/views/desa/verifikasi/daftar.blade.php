@extends('layouts.desa')

@section('title', 'Daftar Pengajuan Verifikasi Dokumen')

@section('content')

<div class="space-y-6">
    <!-- HEADER -->
    <div>
        <h1 class="text-2xl font-extrabold text-slate-800 tracking-tight">Daftar Pengajuan Domain</h1>
        <p class="text-sm text-slate-400 mt-1">Kelola dan verifikasi status domain desa</p>
    </div>

    <!-- CARD TABLE UTAMA -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
        
        <!-- ALERTS -->
        @if(session('success'))
            <div class="mx-6 mt-6 px-4 py-3 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-xl text-sm flex items-center justify-between">
                <span>{{ session('success') }}</span>
                <button type="button" onclick="this.closest('div').remove()" class="text-emerald-400 hover:text-emerald-600 transition">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        @endif
        @if(session('error'))
            <div class="mx-6 mt-6 px-4 py-3 bg-rose-50 border border-rose-200 text-rose-700 rounded-xl text-sm flex items-center justify-between">
                <span>{{ session('error') }}</span>
                <button type="button" onclick="this.closest('div').remove()" class="text-rose-400 hover:text-rose-600 transition">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        @endif

        <!-- FORM SEARCH & FILTER -->
        <form action="{{ route('desa.verifikasi.daftar') }}" method="GET" class="p-6 border-b border-slate-100">
    <div class="flex flex-col md:flex-row gap-4">
        
        <div class="relative flex-1">
            <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
            <input type="text" name="search" placeholder="Cari berdasarkan nama domain..." value="{{ request('search') }}"
                class="w-full pl-11 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#109696]/20 focus:border-[#109696] transition">
        </div>
        
        <button type="submit"
            class="bg-[#109696] hover:bg-[#0d7a7a] text-white font-semibold py-2.5 px-6 rounded-xl text-sm transition shadow-sm shadow-[#109696]/20 flex items-center justify-center gap-2">
            <i class="fas fa-search text-xs"></i> Cari
        </button>

        <div class="relative">
            <select name="status" onchange="this.form.submit()" 
                class="appearance-none pl-4 pr-10 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#109696]/20 focus:border-[#109696] transition w-full md:w-52 cursor-pointer">
                <option value="">Semua Status</option>
                <option value="ditinjau" {{ request('status') == 'ditinjau' ? 'selected' : '' }}>Ditinjau</option>
                <option value="perlu_perbaikan" {{ request('status') == 'perlu_perbaikan' ? 'selected' : '' }}>Perlu Perbaikan</option>
                <option value="diproses" {{ request('status') == 'diproses' ? 'selected' : '' }}>Diproses</option>
                <option value="menunggu_aktivasi" {{ request('status') == 'menunggu_aktivasi' ? 'selected' : '' }}>Menunggu Aktivasi</option>
                {{-- Opsi 'aktif' dihapus karena di-query controller mengecualikan status aktif --}}
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
                            Nama Domain <i class="sort-icon text-[10px] ml-1 opacity-50"></i>
                        </th>
                        <th data-type="string" class="sortable px-5 py-3.5 font-semibold text-slate-500 uppercase text-xs tracking-wider cursor-pointer hover:text-slate-700 transition select-none">
                            Tanggal Pengajuan <i class="sort-icon text-[10px] ml-1 opacity-50"></i>
                        </th>
                        <th data-type="string" class="sortable px-5 py-3.5 font-semibold text-slate-500 uppercase text-xs tracking-wider cursor-pointer hover:text-slate-700 transition select-none text-center">
                            Status <i class="sort-icon text-[10px] ml-1 opacity-50"></i>
                        </th>
                        <th class="px-5 py-3.5 font-semibold text-slate-500 uppercase text-xs tracking-wider">Catatan</th>
                        <th class="px-5 py-3.5 font-semibold text-slate-500 uppercase text-xs tracking-wider text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($data as $indexVerifikasi => $row)
                        <tr data-status="{{ $row->status_pengajuan }}" class="hover:bg-slate-50/50 transition-colors">
                            
                            <td class="px-5 py-4 text-slate-400 font-medium">{{ method_exists($data, 'firstItem') ? $data->firstItem() + $indexVerifikasi : $indexVerifikasi + 1 }}</td>
                            
                            <td class="px-5 py-4 font-medium text-slate-700">{{ $row->nama_domain }}<span class="text-slate-400">.desa.id</span></td>
                            
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
                                @elseif($row->status_pengajuan == 'aktif')
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-100">
                                        <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full"></span>Aktif
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-slate-100 text-slate-600 border border-slate-200">
                                        <span class="w-1.5 h-1.5 bg-slate-400 rounded-full"></span>Draft
                                    </span>
                                @endif
                            </td>

                            <td class="px-5 py-4 text-slate-500 text-sm">
                                {{ $row->catatan_umum ?? '-' }}
                            </td>

                            <td class="px-5 py-4 text-center">
                                <a href="{{ route('desa.verifikasi.detail', $row->id_pengajuan) }}" 
                                   class="inline-flex items-center gap-1.5 px-4 py-2 bg-[#109696]/10 text-[#109696] rounded-lg text-xs font-semibold hover:bg-[#109696] hover:text-white transition-all duration-200">
                                    <i class="fas fa-eye text-[10px]"></i>
                                    Lihat
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
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

<!-- SORTING SCRIPT -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const sortHeaders = document.querySelectorAll('th.sortable');
    
    sortHeaders.forEach(header => {
        header.addEventListener('click', () => {
            const table = header.closest('table');
            const tbody = table.querySelector('tbody');
            const allRows = Array.from(tbody.querySelectorAll('tr'));
            
            const type = header.dataset.type;
            const icon = header.querySelector('.sort-icon');
            const colIndex = Array.from(header.parentNode.children).indexOf(header);

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