@extends('layouts.desa')

@section('content')
<div class="space-y-6" style="padding:0 24px;max-width:1400px;margin:0 auto">

    <!-- HEADER -->
    <div>
        <h1 class="text-2xl font-extrabold text-slate-800 tracking-tight">Daftar Faktur</h1>
        <p class="text-sm text-slate-400 mt-1">Kelola semua faktur domain Anda</p>
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
            <form action="{{ route('desa.faktur.index') }}" method="GET" class="flex flex-1 gap-3 items-center flex-wrap">
                <div class="relative flex-1 min-w-[200px]">
                    <i class="fas fa-search absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                    <input type="text" name="search" placeholder="Cari No Invoice atau Domain..." value="{{ request('search') }}"
                        class="w-full pl-10 pr-4 py-2.5 border border-slate-200 rounded-lg text-sm bg-white focus:outline-none focus:ring-2 focus:ring-[#109696]/20 focus:border-[#109696] transition">
                </div>
                <button type="submit" class="bg-[#109696] hover:bg-[#0d7a7a] text-white font-semibold py-2.5 px-5 rounded-lg text-sm transition shadow-sm">
                    Cari
                </button>
                <select name="status" class="py-2.5 px-3 border border-slate-200 rounded-lg text-sm bg-white focus:outline-none focus:ring-2 focus:ring-[#109696]/20 focus:border-[#109696] transition cursor-pointer">
                    <option value="">Semua Status</option>
                    <option value="belum_bayar" {{ request('status') == 'belum_bayar' ? 'selected' : '' }}>Belum Dibayar</option>
                    <option value="sudah_bayar" {{ request('status') == 'sudah_bayar' ? 'selected' : '' }}>Sudah Dibayar</option>
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
                        <th class="text-left px-6 py-3.5 text-xs font-bold text-slate-400 uppercase tracking-wider">Tgl Invoice</th>
                        <th class="text-left px-6 py-3.5 text-xs font-bold text-slate-400 uppercase tracking-wider">Jatuh Tempo</th>
                        <th class="text-center px-6 py-3.5 text-xs font-bold text-slate-400 uppercase tracking-wider">Status</th>
                        <th class="text-center px-6 py-3.5 text-xs font-bold text-slate-400 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($fakturs as $i => $fakturDesa)
                        <tr class="hover:bg-slate-50/50 transition" style="animation-delay:{{$i*0.05}}s">
                            <td class="px-6 py-4 text-slate-500 font-medium">{{ $fakturs->firstItem() + $i }}</td>
                            <td class="px-6 py-4"><span class="font-mono text-xs font-semibold text-slate-700 bg-slate-50 px-2 py-1 rounded">{{ $fakturDesa->no_invoice }}</span></td>
                            <td class="px-6 py-4 text-slate-700 font-medium">{{ $fakturDesa->pengajuan->nama_desa ?? '-' }}</td>
                            <td class="px-6 py-4 text-[#1A85A5] font-semibold">{{ $fakturDesa->pengajuan->nama_domain ?? '-' }}<span class="text-slate-300 font-medium">.desa.id</span></td>
                            <td class="px-6 py-4 text-center">
                                @if($fakturDesa->tipe == 'perpanjangan')
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-purple-50 text-purple-700">Perpanjangan</span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-sky-50 text-sky-700">Baru</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-slate-500">{{ $fakturDesa->created_at?->format('d/m/Y') ?? '-' }}</td>
                            <td class="px-6 py-4 text-slate-500">{{ $fakturDesa->expired_at?->format('d/m/Y') ?? '-' }}</td>
                            <td class="px-6 py-4 text-center">
                                @if($fakturDesa->status == 'sudah_bayar')
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>Sudah Dibayar
                                    </span>
                                @elseif($fakturDesa->status == 'belum_bayar')
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-rose-50 text-rose-700">
                                        <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>Belum Dibayar
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-slate-100 text-slate-500">
                                        <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span>{{ ucfirst($fakturDesa->status) }}
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center">
                                <a href="{{ route('desa.faktur.show', $fakturDesa->uuid) }}" class="inline-flex items-center gap-1.5 bg-[#1760C5]/10 hover:bg-[#1760C5] text-[#1760C5] hover:text-white text-xs font-bold px-3 py-2 rounded-lg transition-all">
                                    <i class="fas fa-eye text-[10px]"></i> Detail
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-6 py-16 text-center">
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
        @include('components.inv-pagination', ['paginator' => $fakturs])

    </div>
</div>
@endsection