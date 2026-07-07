@extends('layouts.admin')

@section('title', 'Pengajuan Perpanjang Domain')

@section('content')

<div class="space-y-6">
    
    <!-- HEADER -->
    <div>
        <h1 class="text-2xl font-extrabold text-slate-800 tracking-tight">Pengajuan Perpanjang Domain</h1>
        <p class="text-sm text-slate-400 mt-1">Kelola perpanjangan domain dan status pembayaran</p>
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
        <form action="{{ route('admin.perpanjang.list') }}" method="GET" class="p-6 border-b border-slate-100">
            <div class="flex flex-col md:flex-row gap-4">
                
                <div class="relative flex-1">
                    <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                    <input type="text" name="search" placeholder="Cari Nama Domain..." value="{{ request('search') }}"
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
                        <option value="belum_dibuat" {{ request('status') == 'belum_dibuat' ? 'selected' : '' }}>Belum Dibuat</option>
                        <option value="belum_bayar" {{ request('status') == 'belum_bayar' ? 'selected' : '' }}>Belum Bayar</option>
                        <option value="sudah_bayar" {{ request('status') == 'sudah_bayar' ? 'selected' : '' }}>Sudah Bayar</option>
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
                            Domain <i class="sort-icon text-[10px] ml-1 opacity-50"></i>
                        </th>
                        <th data-type="string" class="sortable px-5 py-3.5 font-semibold text-slate-500 uppercase text-xs tracking-wider cursor-pointer hover:text-slate-700 transition select-none text-center">
                            Status Domain <i class="sort-icon text-[10px] ml-1 opacity-50"></i>
                        </th>
                        <th data-type="string" class="sortable px-5 py-3.5 font-semibold text-slate-500 uppercase text-xs tracking-wider cursor-pointer hover:text-slate-700 transition select-none text-center">
                            Tipe <i class="sort-icon text-[10px] ml-1 opacity-50"></i>
                        </th>
                        <th data-type="string" class="sortable px-5 py-3.5 font-semibold text-slate-500 uppercase text-xs tracking-wider cursor-pointer hover:text-slate-700 transition select-none">
                            Tgl Faktur <i class="sort-icon text-[10px] ml-1 opacity-50"></i>
                        </th>
                        <th data-type="string" class="sortable px-5 py-3.5 font-semibold text-slate-500 uppercase text-xs tracking-wider cursor-pointer hover:text-slate-700 transition select-none text-center">
                            Status Faktur <i class="sort-icon text-[10px] ml-1 opacity-50"></i>
                        </th>
                        <th class="px-5 py-3.5 font-semibold text-slate-500 uppercase text-xs tracking-wider text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($data as $indexPerpanjang => $row)
                        @if($row['type'] == 'belum_dibuat')
                            <tr data-status="belum_dibuat" class="hover:bg-slate-50/50 transition-colors">
                                <td class="px-5 py-4 text-slate-400 font-medium">{{ ($data->currentPage() - 1) * $data->perPage() + $loop->iteration }}</td>
                                
                                <td class="px-5 py-4 font-medium text-[#1A85A5]">
                                    {{ $row['pengajuan']->nama_domain }}<span class="text-slate-400">.desa.id</span>
                                </td>
                                
                                <td class="px-5 py-4 text-center">
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-sky-50 text-sky-700 border border-sky-100">
                                        <span class="w-1.5 h-1.5 bg-sky-500 rounded-full"></span>Diproses
                                    </span>
                                </td>

                                <td class="px-5 py-4 text-center">
                                    <span class="px-2.5 py-1 rounded-lg text-xs font-semibold bg-purple-50 text-purple-600 border border-purple-100">
                                        Perpanjangan
                                    </span>
                                </td>

                                <td class="px-5 py-4 text-slate-400">-</td>

                                <td class="px-5 py-4 text-center">
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-slate-100 text-slate-500 border border-slate-200">
                                        <span class="w-1.5 h-1.5 bg-slate-400 rounded-full"></span>Belum Dibuat
                                    </span>
                                </td>

                                <td class="px-5 py-4 text-center">
                                    <a href="{{ route('admin.perpanjang.show', $row['pengajuan']->uuid) }}" 
                                       class="inline-flex items-center gap-1.5 px-4 py-2 bg-[#109696]/10 text-[#109696] rounded-lg text-xs font-semibold hover:bg-[#109696] hover:text-white transition-all duration-200">
                                        <i class="fas fa-eye text-[10px]"></i> Detail
                                    </a>
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

                            <tr data-status="{{ $fakturItem->status }}" class="hover:bg-slate-50/50 transition-colors">
                                <td class="px-5 py-4 text-slate-400 font-medium">{{ ($data->currentPage() - 1) * $data->perPage() + $loop->iteration }}</td>
                                
                                <td class="px-5 py-4 font-medium text-[#1A85A5]">
                                    {{ $pengajuanRow->nama_domain }}<span class="text-slate-400">.desa.id</span>
                                </td>
                                
                                <td class="px-5 py-4 text-center">
                                    @if($statusAkhir == 'menunggu_aktivasi')
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-orange-50 text-orange-700 border border-orange-100">
                                            <span class="w-1.5 h-1.5 bg-orange-500 rounded-full"></span>Menunggu Aktivasi
                                        </span>
                                    @elseif($statusAkhir == 'diproses')
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-sky-50 text-sky-700 border border-sky-100">
                                            <span class="w-1.5 h-1.5 bg-sky-500 rounded-full"></span>Diproses
                                        </span>
                                    @elseif($statusAkhir == 'aktif')
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-100">
                                            <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full"></span>Aktif
                                        </span>
                                    @elseif($statusAkhir == 'kadaluarsa')
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-rose-50 text-rose-700 border border-rose-100">
                                            <span class="w-1.5 h-1.5 bg-rose-500 rounded-full"></span>Kadaluarsa
                                        </span>
                                    @elseif($statusAkhir == 'nonaktif')
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-slate-100 text-slate-500 border border-slate-200">
                                            <span class="w-1.5 h-1.5 bg-slate-400 rounded-full"></span>Nonaktif
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-slate-100 text-slate-500 border border-slate-200">
                                            <span class="w-1.5 h-1.5 bg-slate-400 rounded-full"></span>{{ ucfirst(str_replace('_', ' ', $statusAkhir)) }}
                                        </span>
                                    @endif
                                </td>

                                <td class="px-5 py-4 text-center">
                                    <span class="px-2.5 py-1 rounded-lg text-xs font-semibold bg-purple-50 text-purple-600 border border-purple-100">
                                        Perpanjangan
                                    </span>
                                </td>

                                <td class="px-5 py-4 text-slate-500">
                                    {{ $fakturItem->created_at->format('d/m/Y') }}
                                </td>

                                <td class="px-5 py-4 text-center">
                                    @if($fakturItem->status == 'belum_bayar')
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-rose-50 text-rose-700 border border-rose-100">
                                            <span class="w-1.5 h-1.5 bg-rose-500 rounded-full"></span>Belum Bayar
                                        </span>
                                    @elseif($fakturItem->status == 'sudah_bayar')
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-100">
                                            <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full"></span>Sudah Bayar
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-slate-100 text-slate-500 border border-slate-200">
                                            <span class="w-1.5 h-1.5 bg-slate-400 rounded-full"></span>Kadaluarsa
                                        </span>
                                    @endif
                                </td>

                                <td class="px-5 py-4 text-center">
                                    <a href="{{ route('admin.perpanjang.show', $fakturItem->uuid) }}" 
                                       class="inline-flex items-center gap-1.5 px-4 py-2 bg-[#109696]/10 text-[#109696] rounded-lg text-xs font-semibold hover:bg-[#109696] hover:text-white transition-all duration-200">
                                        <i class="fas fa-eye text-[10px]"></i> Detail
                                    </a>
                                </td>
                            </tr>
                        @endif
                    @empty
                        <tr class="inv-empty">
                            <td colspan="7" class="px-5 py-16 text-center">
                                <div class="flex flex-col items-center gap-3 text-slate-400">
                                    <i class="fas fa-inbox text-4xl text-slate-300"></i>
                                    <p class="font-medium">Belum ada pengajuan perpanjangan</p>
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

<!-- MODAL KONFIRMASI MODERN -->
<div id="printConfirmationModal" class="hidden fixed inset-0 bg-slate-900/60 backdrop-blur-sm h-full w-full z-50 flex items-center justify-center p-4">
    <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-sm transform transition-all">
        
        <div class="p-6 text-center">
            <div class="mx-auto flex items-center justify-center h-14 w-14 rounded-full bg-[#1760C5]/10 mb-4">
                <i class="fas fa-print text-[#1760C5] text-xl"></i>
            </div>
            <h3 class="text-lg font-bold text-slate-800 mb-2">Konfirmasi Aksi</h3>
            <p class="text-sm text-slate-500 leading-relaxed">Apakah Anda yakin ingin membuat faktur?</p>
        </div>
        
        <div class="px-6 pb-6 flex items-center justify-center gap-3">
            <button id="printModalNoBtn"
            class="flex-1 py-2.5 bg-slate-100 text-slate-600 text-sm font-semibold rounded-xl hover:bg-slate-200 transition">
                Batal
            </button>
            <button id="printModalYesBtn"
            class="flex-1 py-2.5 bg-[#1760C5] text-white text-sm font-semibold rounded-xl hover:bg-[#1250a5] transition shadow-sm">
                Ya, Lanjutkan
            </button>
        </div>

    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // --- LOGIC SORTING ---
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

    // --- MODAL LOGIC ---
    const modal = document.getElementById('printConfirmationModal');
    const yesBtn = document.getElementById('printModalYesBtn');
    const noBtn = document.getElementById('printModalNoBtn');
    const confirmBtns = document.querySelectorAll('.js-confirm-print');
    let formToSubmit = null;

    if(confirmBtns.length > 0) {
        confirmBtns.forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                formToSubmit = this.closest('form');
                modal.classList.remove('hidden');
                modal.classList.add('flex');
            });
        });
    }

    if(yesBtn) yesBtn.addEventListener('click', function() { 
        if (formToSubmit) formToSubmit.submit(); 
        closeModal(); 
    });
    if(noBtn) noBtn.addEventListener('click', closeModal);
    if(modal) modal.addEventListener('click', function(e) { 
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