@extends('layouts.desa')

@section('title', 'Perpanjang Domain')

@section('content')

<div class="space-y-6">
    <!-- HEADER -->
    <div>
        <h1 class="text-2xl font-extrabold text-slate-800 tracking-tight">Domain Aktif</h1>
        <p class="text-sm text-slate-400 mt-1">Pantau masa berlaku dan ajukan perpanjangan domain</p>
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

        <!-- SEARCH & FILTER -->
        <div class="p-6 border-b border-slate-100 flex flex-col md:flex-row gap-4">
            <div class="relative flex-1">
                <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                <input type="text" id="invSearch" placeholder="Cari Nama Domain..." 
                    class="w-full pl-11 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#109696]/20 focus:border-[#109696] transition">
            </div>
            <div class="relative w-full md:w-52">
                <select id="invFilter" class="appearance-none pl-4 pr-10 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#109696]/20 focus:border-[#109696] transition w-full cursor-pointer">
                    <option value="">Semua Status</option>
                    <option value="kadaluarsa">Kadaluarsa</option>
                    <option value="nonaktif">Nonaktif</option>
                    <option value="aktif">Aktif</option>
                </select>
                <i class="fas fa-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 text-xs pointer-events-none"></i>
            </div>
        </div>

        <!-- TABLE -->
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left" id="invTable">
                <thead>
                    <tr class="bg-slate-50/80 border-b border-slate-100">
                        <th class="px-5 py-3.5 font-semibold text-slate-500 uppercase text-xs tracking-wider">No</th>
                        <th data-type="string" class="sortable px-5 py-3.5 font-semibold text-slate-500 uppercase text-xs tracking-wider cursor-pointer hover:text-slate-700 transition select-none">
                            Domain <i class="sort-icon text-[10px] ml-1 opacity-50"></i>
                        </th>
                        <th data-type="string" class="sortable px-5 py-3.5 font-semibold text-slate-500 uppercase text-xs tracking-wider cursor-pointer hover:text-slate-700 transition select-none">
                            Tgl Aktivasi <i class="sort-icon text-[10px] ml-1 opacity-50"></i>
                        </th>
                        <th data-type="string" class="sortable px-5 py-3.5 font-semibold text-slate-500 uppercase text-xs tracking-wider cursor-pointer hover:text-slate-700 transition select-none">
                            Masa Berlaku <i class="sort-icon text-[10px] ml-1 opacity-50"></i>
                        </th>
                        <th data-type="string" class="sortable px-5 py-3.5 font-semibold text-slate-500 uppercase text-xs tracking-wider cursor-pointer hover:text-slate-700 transition select-none text-center">
                            Status <i class="sort-icon text-[10px] ml-1 opacity-50"></i>
                        </th>
                        <th class="px-5 py-3.5 font-semibold text-slate-500 uppercase text-xs tracking-wider text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @php
                        // Inisialisasi variabel counter di luar loop
                        $numDaftarDomain = 0;
                    @endphp

                    @forelse($data as $row)
                        @php
                            // --- FILTER STATUS: HANYA TAMPILKAN AKTIF, KADALUARSA, NONAKTIF ---
                            $allowedStatuses = ['aktif', 'kadaluarsa', 'nonaktif']; 
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
                            
                            // Cek status nonaktif
                            $isNonaktif = ($aktivasi && $aktivasi->status_akt === 'nonaktif') ? true : false;
                            
                            $bisaPerpanjang = false;

                            // --- LOGIKA PERPANJANGAN ---
                            if ($isNonaktif || $kadaluarsa) {
                                $bisaPerpanjang = false;
                            } 
                            elseif ($aktivasi && $aktivasi->tgl_aktivasi) {
                                $batasAwal = $aktivasi->tgl_aktivasi->copy()->addMinutes(3);
                                $bisaPerpanjang = \Carbon\Carbon::now() >= $batasAwal;
                            }
                            // -----------------------------------

                            // Ambil ID Faktur Belum Bayar
                            $fakturBelumBayar = $row->faktur->where('status', 'belum_bayar')->first();
                            $idFakturBelumBayar = $fakturBelumBayar ? $fakturBelumBayar->id : null;

                            // Tentukan Data Status untuk Filter JS
                            $dataStatus = 'aktif';
                            if($isNonaktif) $dataStatus = 'nonaktif';
                            elseif($kadaluarsa) $dataStatus = 'kadaluarsa';
                            elseif($row->ada_faktur_belum_bayar) $dataStatus = 'faktur_tersedia';
                            elseif($row->menunggu_faktur) $dataStatus = 'menunggu_faktur';
                            elseif($bisaPerpanjang) $dataStatus = 'siap_diperpanjang';
                            else $dataStatus = 'aktif';
                        @endphp

                        <tr data-status="{{ $dataStatus }}" class="hover:bg-slate-50/50 transition-colors">
                            
                            <!-- Nomor Urut -->
                            <td class="px-5 py-4 text-slate-400 font-medium">{{ $nomorTampil }}</td>
                            
                            <td class="px-5 py-4 font-medium text-slate-700">{{ $row->nama_domain }}<span class="text-slate-400">.desa.id</span></td>
                            
                            <td class="px-5 py-4 text-slate-500">
                                @if($aktivasi && $aktivasi->tgl_aktivasi)
                                    {{ $aktivasi->tgl_aktivasi->format('d/m/Y') }}
                                @else
                                    -
                                @endif
                            </td>
                            
                            <td class="px-5 py-4">
                                @if($aktivasi && $aktivasi->masa_berlaku)
                                    <span class="{{ $kadaluarsa || $isNonaktif ? 'text-rose-600 font-bold' : 'text-slate-700' }}">
                                        {{ $aktivasi->masa_berlaku->format('d/m/Y') }}
                                    </span>
                                @else
                                    <span class="text-slate-400">-</span>
                                @endif
                            </td>

                            {{-- KOLOM STATUS --}}
                            <td class="px-5 py-4 text-center">
                                @if($isNonaktif)
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-slate-100 text-slate-600 border border-slate-200">
                                        <span class="w-1.5 h-1.5 bg-slate-400 rounded-full"></span>Nonaktif
                                    </span>
                                @elseif($kadaluarsa)
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-rose-50 text-rose-700 border border-rose-100">
                                        <span class="w-1.5 h-1.5 bg-rose-500 rounded-full"></span>Kadaluarsa
                                    </span>
                                @elseif($row->ada_faktur_belum_bayar)
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-sky-50 text-sky-700 border border-sky-100">
                                        <span class="w-1.5 h-1.5 bg-sky-500 rounded-full"></span>Faktur Tersedia
                                    </span>
                                @elseif($row->menunggu_faktur)
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-orange-50 text-orange-700 border border-orange-100">
                                        <span class="w-1.5 h-1.5 bg-orange-500 rounded-full"></span>Menunggu Faktur
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-100">
                                        <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full"></span>Aktif
                                    </span>
                                @endif
                            </td>

                            {{-- KOLOM AKSI --}}
                            <td class="px-5 py-4 text-center">
                                <div class="flex items-center justify-center gap-2">

                                    <!-- DETAIL -->
                                    <a href="{{ route('desa.verifikasi.detail', $row->id_pengajuan) }}" 
                                       class="inline-flex items-center gap-1.5 px-4 py-2 bg-[#109696]/10 text-[#109696] rounded-lg text-xs font-semibold hover:bg-[#109696] hover:text-white transition-all duration-200">
                                        <i class="fas fa-eye text-[10px]"></i> Lihat
                                    </a>

                                    @if($isNonaktif)
                                        <span class="text-slate-400 text-xs italic">Tidak dapat diperpanjang</span>

                                    @elseif($kadaluarsa)
                                        {{-- TIDAK ADA TOMBOL LAIN --}}

                                    @elseif($row->ada_faktur_belum_bayar)
                                        @if($idFakturBelumBayar)
                                            <a href="{{ route('desa.faktur.show', $idFakturBelumBayar) }}" 
                                               class="inline-flex items-center gap-1.5 px-4 py-2 bg-[#1760C5]/10 text-[#1760C5] rounded-lg text-xs font-semibold hover:bg-[#1760C5] hover:text-white transition-all duration-200">
                                               <i class="fas fa-file-invoice-dollar text-[10px]"></i> Faktur
                                            </a>
                                        @else
                                            <span class="text-xs text-slate-400">Data Faktur Error</span>
                                        @endif

                                    @elseif($row->menunggu_faktur)
                                        <span class="text-slate-400 text-xs flex items-center gap-1.5">
                                            <i class="fas fa-hourglass-half"></i> Menunggu Faktur
                                        </span>

                                    @elseif($bisaPerpanjang)
                                        <a href="#" 
                                           class="inline-flex items-center gap-1.5 px-4 py-2 bg-[#109696]/10 text-[#109696] rounded-lg text-xs font-semibold hover:bg-[#109696] hover:text-white transition-all duration-200"
                                           onclick="event.preventDefault(); openPerpanjangModal('{{ $row->id_pengajuan }}', '{{ $aktivasi ? $aktivasi->tgl_aktivasi->format('d M Y') : '-' }}', '{{ $aktivasi ? $aktivasi->masa_berlaku->format('d M Y') : '-' }}')">
                                           <i class="fas fa-redo text-[10px]"></i> Ajukan Perpanjang
                                        </a>

                                    @else
                                        <button disabled class="inline-flex items-center gap-1.5 px-4 py-2 bg-slate-100 text-slate-400 rounded-lg text-xs font-semibold cursor-not-allowed">
                                            <i class="fas fa-redo text-[10px]"></i> Ajukan Perpanjang
                                        </button>
                                    @endif

                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-16 text-center">
                                <div class="flex flex-col items-center gap-3 text-slate-400">
                                    <i class="fas fa-inbox text-4xl text-slate-300"></i>
                                    <p class="font-medium">Tidak ada domain aktif.</p>
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

<!-- MODAL PERPANJANG -->
<div id="modalPerpanjang" class="hidden fixed inset-0 bg-slate-900/60 backdrop-blur-sm h-full w-full z-50 flex items-center justify-center p-4">
    <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-md transform transition-all" id="modalPerpanjangContent">
        
        <div class="p-6 text-center border-b border-slate-100">
            <div class="mx-auto flex items-center justify-center h-14 w-14 rounded-full bg-[#109696]/10 mb-4">
                <i class="fas fa-sync-alt text-[#109696] text-xl"></i>
            </div>
            <h3 class="text-lg font-bold text-slate-800 mb-2">Ajukan Perpanjangan</h3>
            <p class="text-sm text-slate-500">Isi detail perpanjangan domain Anda</p>
        </div>

        <div class="p-6">
            <!-- Info Masa Aktif Saat Ini -->
            <div class="mb-5 p-4 bg-slate-50 rounded-xl border border-slate-200">
                <p class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Masa Aktif Saat Ini</p>
                <div class="flex items-center justify-between text-sm">
                    <span class="text-slate-600">Mulai Aktif:</span>
                    <span class="font-semibold text-slate-800" id="infoMulai">-</span>
                </div>
                <div class="flex items-center justify-between text-sm mt-1">
                    <span class="text-slate-600">Berlaku Sampai:</span>
                    <span class="font-semibold text-slate-800" id="infoSelesai">-</span>
                </div>
            </div>

            <!-- Form Pilih Tahun -->
            <form id="formPerpanjang" action="{{ route('desa.perpanjang.ajukan') }}" method="POST">
                @csrf
                <input type="hidden" name="id_pengajuan" id="inputIdPengajuan" value="">
                
                <div class="mb-5">
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Pilih Durasi Perpanjangan <span class="text-rose-500">*</span></label>
                    <select name="durasi_tahun" id="selectDurasi" required class="w-full px-4 py-2.5 border border-slate-200 rounded-lg text-sm bg-white focus:outline-none focus:ring-2 focus:ring-[#109696]/20 focus:border-[#109696] transition">
                        <option value="" disabled selected>-- Pilih Tahun --</option>
                        <option value="1">1 Tahun</option>
                        <option value="2">2 Tahun</option>
                        <option value="3">3 Tahun</option>
                        <option value="4">4 Tahun</option>
                        <option value="5">5 Tahun</option>
                    </select>
                </div>

                <div class="flex items-center justify-center gap-3">
                    <button type="button" onclick="closePerpanjangModal()" class="flex-1 py-2.5 bg-slate-100 text-slate-600 text-sm font-semibold rounded-xl hover:bg-slate-200 transition">Batal</button>
                    <button type="submit" class="flex-1 py-2.5 bg-[#109696] hover:bg-[#0d7a7a] text-white text-sm font-semibold rounded-xl transition shadow-sm">Ya, Ajukan</button>
                </div>
            </form>
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
                return isAsc ? aVal.localeCompare(bVal) : bVal.localeCompare(aVal);
            });
            allRows.forEach(row => tbody.appendChild(row));
        });
    });
});

// FUNGSI MODAL BARU
function openPerpanjangModal(id, tglMulai, tglSelesai) {
    const modal = document.getElementById('modalPerpanjang');
    
    document.getElementById('infoMulai').textContent = tglMulai;
    document.getElementById('infoSelesai').textContent = tglSelesai;
    document.getElementById('inputIdPengajuan').value = id;
    document.getElementById('selectDurasi').value = '';
    
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function closePerpanjangModal() {
    const modal = document.getElementById('modalPerpanjang');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}

document.getElementById('modalPerpanjang').addEventListener('click', function(e) {
    if (e.target === this) closePerpanjangModal();
});
</script>
@endsection