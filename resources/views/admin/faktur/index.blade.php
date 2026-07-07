@extends('layouts.admin')

@section('title', 'Manajemen Faktur')

@section('content')
<div class="space-y-6">

    <!-- HEADER -->
    <div>
        <h1 class="text-2xl font-extrabold text-slate-800 tracking-tight">Manajemen Faktur</h1>
        <p class="text-sm text-slate-400 mt-1">Kelola semua faktur domain desa</p>
    </div>

    <!-- WIDGET FAKTUR -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">

        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Total Faktur</p>
                    <p class="text-2xl font-extrabold text-slate-800 mt-2">{{ $totalFaktur }}</p>
                </div>
                <div class="w-10 h-10 rounded-xl bg-slate-100 flex items-center justify-center">
                    <i class="fas fa-file-invoice text-slate-500"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Belum Dibayar</p>
                    <p class="text-2xl font-extrabold text-rose-600 mt-2">{{ $totalBelumBayar }}</p>
                </div>
                <div class="w-10 h-10 rounded-xl bg-rose-50 flex items-center justify-center">
                    <i class="fas fa-clock text-rose-500"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Sudah Dibayar</p>
                    <p class="text-2xl font-extrabold text-emerald-600 mt-2">{{ $totalSudahBayar }}</p>
                </div>
                <div class="w-10 h-10 rounded-xl bg-emerald-50 flex items-center justify-center">
                    <i class="fas fa-check-circle text-emerald-500"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Belum Dibuat</p>
                    <p class="text-2xl font-extrabold text-slate-500 mt-2">{{ $totalBelumDibuat }}</p>
                </div>
                <div class="w-10 h-10 rounded-xl bg-slate-50 flex items-center justify-center">
                    <i class="fas fa-minus-circle text-slate-400"></i>
                </div>
            </div>
        </div>

    </div>

    <!-- TABLE CARD -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">

        {{-- FLASH MESSAGE --}}
        @if(session('success'))
            <div class="mx-6 mt-6 px-4 py-3 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-xl text-sm flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <i class="fas fa-check-circle text-emerald-500"></i>
                    {{ session('success') }}
                </div>
                <button type="button" onclick="this.closest('div').remove()" class="text-emerald-400 hover:text-emerald-600 transition">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        @endif
        @if(session('error'))
            <div class="mx-6 mt-6 px-4 py-3 bg-rose-50 border border-rose-200 text-rose-700 rounded-xl text-sm flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <i class="fas fa-exclamation-circle text-rose-500"></i>
                    {{ session('error') }}
                </div>
                <button type="button" onclick="this.closest('div').remove()" class="text-rose-400 hover:text-rose-600 transition">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        @endif

        {{-- PENCARIAN & FILTER --}}
        <div class="px-6 py-4 border-b border-slate-100 flex gap-3 items-center flex-wrap">
            <form action="{{ route('admin.faktur.index') }}" method="GET" class="flex flex-1 gap-3 items-center flex-wrap">
                <div class="relative flex-1 min-w-[200px]">
                    <i class="fas fa-search absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                    <input type="text" name="search" placeholder="Cari No Invoice, Domain, atau Desa..." value="{{ request('search') }}"
                        class="w-full pl-10 pr-4 py-2.5 border border-slate-200 rounded-lg text-sm bg-white focus:outline-none focus:ring-2 focus:ring-[#109696]/20 focus:border-[#109696] transition">
                </div>
                <button type="submit" class="bg-[#109696] hover:bg-[#0d7a7a] text-white font-semibold py-2.5 px-5 rounded-lg text-sm transition shadow-sm">
                    Cari
                </button>
                <select name="status" class="py-2.5 px-3 border border-slate-200 rounded-lg text-sm bg-white focus:outline-none focus:ring-2 focus:ring-[#109696]/20 focus:border-[#109696] transition cursor-pointer">
                    <option value="">Semua Status</option>
                    <option value="belum_dibuat" {{ request('status') == 'belum_dibuat' ? 'selected' : '' }}>Belum Dibuat</option>
                    <option value="belum_bayar" {{ request('status') == 'belum_bayar' ? 'selected' : '' }}>Belum Bayar</option>
                    <option value="sudah_bayar" {{ request('status') == 'sudah_bayar' ? 'selected' : '' }}>Sudah Bayar</option>
                </select>
            </form>
        </div>

        {{-- TABLE --}}
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-slate-50/80">
                        <th class="text-left px-6 py-3.5 text-xs font-bold text-slate-400 uppercase tracking-wider">No</th>
                        <th class="text-left px-6 py-3.5 text-xs font-bold text-slate-400 uppercase tracking-wider">No Invoice</th>
                        <th class="text-left px-6 py-3.5 text-xs font-bold text-slate-400 uppercase tracking-wider">Nama Desa</th>
                        <th class="text-left px-6 py-3.5 text-xs font-bold text-slate-400 uppercase tracking-wider">Domain</th>
                        <th class="text-center px-6 py-3.5 text-xs font-bold text-slate-400 uppercase tracking-wider">Tipe</th>
                        <th class="text-left px-6 py-3.5 text-xs font-bold text-slate-400 uppercase tracking-wider">Tgl Konfirmasi</th>
                        <th class="text-center px-6 py-3.5 text-xs font-bold text-slate-400 uppercase tracking-wider">Status</th>
                        <th class="text-center px-6 py-3.5 text-xs font-bold text-slate-400 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @php
                        $nomor = request()->filled('search')
                            ? 1
                            : $data->firstItem();
                    @endphp

                    @forelse($data as $i => $row)
                        @if($row->faktur->isEmpty() && !in_array($row->id_pengajuan, $perpanjanganBelumBuat))
                            <tr class="hover:bg-slate-50/50 transition" style="animation-delay:{{$i*0.05}}s">
                                <td class="px-6 py-4 text-slate-500 font-medium">{{ $nomor++ }}</td>
                                <td class="px-6 py-4"><span class="font-mono text-xs text-slate-400 bg-slate-50 px-2 py-1 rounded">-</span></td>
                                <td class="px-6 py-4 text-slate-700 font-medium">{{ $row->nama_desa }}</td>
                                <td class="px-6 py-4 text-[#1A85A5] font-semibold">{{ $row->nama_domain }}<span class="text-slate-300 font-medium">.desa.id</span></td>
                                <td class="px-6 py-4 text-center">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-sky-50 text-sky-700">Baru</span>
                                </td>
                                <td class="px-6 py-4 text-slate-400">-</td>
                                <td class="px-6 py-4 text-center">
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-slate-100 text-slate-500">
                                        <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span>Belum Dibuat
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @php
                                        $isRequested = \App\Models\Pesan::where('id_pengajuan', $row->id_pengajuan)
                                            ->where('judul', 'Konfirmasi Pembayaran Disetujui')
                                            ->where('role_tujuan', 'admin')
                                            ->exists();
                                    @endphp
                                    @if($isRequested)
                                        <form action="{{ route('admin.faktur.store', $row->uuid) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="js-confirm-print inline-flex items-center gap-1.5 bg-[#109696]/10 hover:bg-[#109696] text-[#109696] hover:text-white text-xs font-bold px-3 py-2 rounded-lg transition-all">
                                                <i class="fas fa-plus text-[10px]"></i> Cetak Faktur
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-xs text-slate-400 italic">Menunggu Konfirmasi</span>
                                    @endif
                                </td>
                            </tr>
                        @else
                            @foreach($row->faktur as $indexFaktur => $fakturItem)
                                <tr class="hover:bg-slate-50/50 transition" style="animation-delay:{{$i*0.05}}s">
                                    <td class="px-6 py-4 text-slate-500 font-medium">{{ $nomor++ }}</td>
                                    <td class="px-6 py-4"><span class="font-mono text-xs font-semibold text-slate-700 bg-slate-50 px-2 py-1 rounded">{{ $fakturItem->no_invoice }}</span></td>
                                    <td class="px-6 py-4 text-slate-700 font-medium">{{ $row->nama_desa }}</td>
                                    <td class="px-6 py-4 text-[#1A85A5] font-semibold">{{ $row->nama_domain }}<span class="text-slate-300 font-medium">.desa.id</span></td>
                                    <td class="px-6 py-4 text-center">
                                        @if($fakturItem->tipe == 'perpanjangan')
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-purple-50 text-purple-700">Perpanjangan</span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-sky-50 text-sky-700">Baru</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-slate-500">{{ $fakturItem->tanggal_konfirmasi ? $fakturItem->tanggal_konfirmasi->format('d/m/Y') : '-' }}</td>
                                    <td class="px-6 py-4 text-center">
                                        @if($fakturItem->status == 'sudah_bayar')
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700">
                                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>Sudah Dibayar
                                            </span>
                                        @elseif($fakturItem->status == 'belum_bayar')
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-rose-50 text-rose-700">
                                                <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>Belum Dibayar
                                            </span>
                                        @elseif($fakturItem->status == 'kedaluarsa')
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-slate-100 text-slate-500">
                                                <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span>Kedaluarsa
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <a href="{{ route('admin.faktur.show', $fakturItem->uuid) }}" class="inline-flex items-center gap-1.5 bg-[#1760C5]/10 hover:bg-[#1760C5] text-[#1760C5] hover:text-white text-xs font-bold px-3 py-2 rounded-lg transition-all">
                                            <i class="fas fa-eye text-[10px]"></i> Detail
                                        </a>
                                    </td>
                                </tr>
                            @endforeach

                            @if(in_array($row->id_pengajuan, $perpanjanganBelumBuat))
                                <tr class="hover:bg-slate-50/50 transition" style="animation-delay:{{$i*0.05}}s">
                                    <td class="px-6 py-4 text-slate-500 font-medium">{{ $nomor++ }}</td>
                                    <td class="px-6 py-4"><span class="font-mono text-xs text-slate-400 bg-slate-50 px-2 py-1 rounded">-</span></td>
                                    <td class="px-6 py-4 text-slate-700 font-medium">{{ $row->nama_desa }}</td>
                                    <td class="px-6 py-4 text-[#1A85A5] font-semibold">{{ $row->nama_domain }}<span class="text-slate-300 font-medium">.desa.id</span></td>
                                    <td class="px-6 py-4 text-center">
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-purple-50 text-purple-700">Perpanjangan</span>
                                    </td>
                                    <td class="px-6 py-4 text-slate-400">-</td>
                                    <td class="px-6 py-4 text-center">
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-slate-100 text-slate-500">
                                            <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span>Belum Dibuat
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <form action="{{ route('admin.faktur.store', $row->uuid) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="js-confirm-print inline-flex items-center gap-1.5 bg-[#109696]/10 hover:bg-[#109696] text-[#109696] hover:text-white text-xs font-bold px-3 py-2 rounded-lg transition-all">
                                                <i class="fas fa-plus text-[10px]"></i> Buat Faktur
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endif
                        @endif
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-16 text-center">
                                <div class="flex flex-col items-center gap-3">
                                    <div class="w-14 h-14 rounded-full bg-slate-100 flex items-center justify-center">
                                        <i class="fas fa-inbox text-slate-300 text-xl"></i>
                                    </div>
                                    <p class="text-slate-400 text-sm font-medium">Belum ada faktur</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- PAGINATION --}}
        @include('components.inv-pagination', ['paginator' => $data])

    </div>
</div>

{{-- MODAL KONFIRMASI CETAK FAKTUR --}}
<div id="printConfirmationModal" class="hidden fixed inset-0 bg-slate-900/60 backdrop-blur-sm h-full w-full z-50 flex items-center justify-center p-4">
    <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-sm transform transition-all">
        <div class="p-6 text-center">
            <div class="mx-auto flex items-center justify-center h-14 w-14 rounded-full bg-[#109696]/10 mb-4">
                <i class="fas fa-print text-[#109696] text-xl"></i>
            </div>
            <h3 class="text-lg font-bold text-slate-800 mb-2">Konfirmasi</h3>
            <p class="text-sm text-slate-500 leading-relaxed">Apakah Anda yakin ingin mencetak faktur?</p>
        </div>
        <div class="px-6 pb-6 flex items-center justify-center gap-3">
            <button id="printModalNoBtn" class="flex-1 py-2.5 bg-slate-100 text-slate-600 text-sm font-semibold rounded-xl hover:bg-slate-200 transition">Batal</button>
            <button id="printModalYesBtn" class="flex-1 py-2.5 bg-[#109696] text-white text-sm font-semibold rounded-xl hover:bg-[#0d7a7a] transition shadow-sm">Ya, Lanjutkan</button>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
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
    modal.addEventListener('click', function(e) { if (e.target === modal) closeModal(); });

    function closeModal() {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        formToSubmit = null;
    }
});
</script>
@endsection