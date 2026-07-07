@extends('layouts.admin')

@section('title', 'Detail Faktur')

@section('content')

@php
    // ✅ PERHITUNGAN RINCIAN HARGA
    $durasiTahun = $faktur->durasi_tahun ?? 1;
    $hargaPerTahun = 50000;
    $ppnPersen = 11;
    
    // Jika ada field subtotal & ppn di database, gunakan itu
    if (isset($faktur->subtotal) && isset($faktur->ppn)) {
        $subtotal = $faktur->subtotal; // Sudah berupa harga dasar (sebelum PPN)
        $ppn = $faktur->ppn;
        $totalHarga = $faktur->total;
    } else {
        // Fallback kalkulasi manual (untuk data lama)
        $subtotal = $durasiTahun * $hargaPerTahun;
        $ppn = $subtotal * ($ppnPersen / 100);
        $totalHarga = $subtotal + $ppn;
    }
@endphp

<div class="flex flex-col lg:flex-row gap-6">

    <!-- SIDEBAR KIRI -->
    <div class="w-full lg:w-80 flex-shrink-0 space-y-4">
        <x-status-domain :status="$faktur->status" />

        <!-- RINGKASAN BAYAR -->
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
            <div class="px-5 py-4 border-b border-slate-100 bg-slate-50/50">
                <h3 class="font-bold text-slate-800 text-xs uppercase tracking-widest">Ringkasan</h3>
            </div>
            <div class="p-5 space-y-4">
                <div>
                    <p class="text-slate-400 text-[10px] font-bold uppercase tracking-widest mb-1">Total Tagihan</p>
                    <p class="text-xl font-extrabold text-[#109696] tracking-tight">Rp {{ number_format($totalHarga, 0, ',', '.') }}</p>
                </div>
                <div class="border-t border-slate-100 pt-4 space-y-3">
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-slate-400">Masa Aktif</span>
                        <span class="font-semibold text-slate-700">{{ $durasiTahun }} Tahun</span>
                    </div>
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-slate-400">Tipe</span>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold
                            {{ $faktur->tipe == 'perpanjangan' ? 'bg-purple-50 text-purple-700' : 'bg-sky-50 text-sky-700' }}">
                            {{ $faktur->tipe == 'perpanjangan' ? 'Perpanjangan' : 'Baru' }}
                        </span>
                    </div>
                </div>
                <div class="border-t border-slate-100 pt-4">
                    <p class="text-slate-400 text-[10px] font-bold uppercase tracking-widest mb-1">Batas Pembayaran</p>
                    <p class="text-sm font-semibold text-slate-700">{{ $faktur->expired_at->format('d M Y') }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- CONTENT KANAN -->
    <div class="flex-1 min-w-0">

        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">

            <!-- INVOICE HEADER -->
            <div class="px-6 md:px-8 py-6 bg-gradient-to-br from-slate-800 to-slate-900 relative">
                <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4">
                    <div>
                        <p class="text-slate-400 text-[10px] font-bold uppercase tracking-widest">Invoice</p>
                        <h2 class="text-2xl font-extrabold text-white tracking-tight mt-1">INV-#{{ $faktur->no_invoice }}</h2>
                        <p class="text-slate-300 text-sm font-medium mt-1">{{ $faktur->nama_desa }}</p>
                    </div>
                    <a href="{{ url()->previous() }}"
                       class="inline-flex items-center gap-2 bg-white/10 hover:bg-white/20 text-white font-semibold py-2.5 px-5 rounded-xl transition text-sm backdrop-blur-sm">
                        <i class="fas fa-arrow-left text-xs"></i> Kembali
                    </a>
                </div>
            </div>

            <div class="p-6 md:p-8 space-y-8">

                <!-- DETAIL FAKTUR -->
                <div>
                    <h3 class="text-sm font-bold text-slate-800 uppercase tracking-wider mb-4 flex items-center gap-2">
                        <span class="w-1.5 h-5 bg-gradient-to-b from-[#109696] to-[#1760C5] rounded-full"></span>
                        Detail Faktur
                    </h3>

                    <div class="bg-slate-50/50 p-5 rounded-xl border border-slate-100">
                        <div class="space-y-0 divide-y divide-slate-100">
                            <div class="flex justify-between items-center py-3.5 first:pt-0">
                                <span class="text-sm text-slate-400">No. Invoice</span>
                                <span class="text-sm font-semibold text-slate-700 font-mono">{{ $faktur->no_invoice }}</span>
                            </div>
                            <div class="flex justify-between items-center py-3.5">
                                <span class="text-sm text-slate-400">Nama Desa</span>
                                <span class="text-sm font-semibold text-slate-700">{{ $faktur->nama_desa }}</span>
                            </div>
                            <div class="flex justify-between items-center py-3.5">
                                <span class="text-sm text-slate-400">Domain</span>
                                <span class="text-sm font-semibold text-[#1A85A5]">{{ $faktur->nama_domain }}<span class="text-slate-300 font-medium">.desa.id</span></span>
                            </div>
                            <div class="flex justify-between items-center py-3.5">
                                <span class="text-sm text-slate-400">Tipe Pembayaran</span>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold
                                    {{ $faktur->tipe == 'perpanjangan' ? 'bg-purple-50 text-purple-700' : 'bg-sky-50 text-sky-700' }}">
                                    {{ $faktur->tipe == 'perpanjangan' ? 'Perpanjangan' : 'Baru (Registrasi)' }}
                                </span>
                            </div>
                            <div class="flex justify-between items-center py-3.5">
                                <span class="text-sm text-slate-400">Masa Aktif</span>
                                <span class="text-sm font-semibold text-slate-700">{{ $durasiTahun }} Tahun</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ✅ RINCIAN HARGA DENGAN PPN --}}
                <div>
                    <h3 class="text-sm font-bold text-slate-800 uppercase tracking-wider mb-4 flex items-center gap-2">
                        <span class="w-1.5 h-5 bg-gradient-to-b from-[#109696] to-[#1760C5] rounded-full"></span>
                        Rincian Harga
                    </h3>

                    <div class="bg-gradient-to-br from-slate-50 to-slate-100/80 p-5 rounded-xl border border-slate-200">
                        <div class="space-y-0 divide-y divide-slate-200">
                            <div class="flex justify-between items-center py-3.5 first:pt-0">
                                <span class="text-sm text-slate-500">Biaya Domain ({{ $durasiTahun }} tahun × Rp {{ number_format($hargaPerTahun, 0, ',', '.') }})</span>
                                <span class="text-sm font-semibold text-slate-700">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between items-center py-3.5">
                                <span class="text-sm text-emerald-600 flex items-center gap-1.5">
                                    <i class="fas fa-percent text-[10px]"></i>
                                    PPN {{ $ppnPersen }}%
                                </span>
                                <span class="text-sm font-semibold text-emerald-600">Rp {{ number_format($ppn, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between items-center py-4 last:pb-0">
                                <span class="text-base font-bold text-slate-800">Total Pembayaran</span>
                                <span class="text-xl font-extrabold text-[#109696]">Rp {{ number_format($totalHarga, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- INFORMASI PEMBAYARAN -->
                <div>
                    <h3 class="text-sm font-bold text-slate-800 uppercase tracking-wider mb-4 flex items-center gap-2">
                        <span class="w-1.5 h-5 bg-gradient-to-b from-[#109696] to-[#1760C5] rounded-full"></span>
                        Informasi Pembayaran
                    </h3>

                    <div class="bg-slate-50/50 p-5 rounded-xl border border-slate-100">
                        <div class="space-y-0 divide-y divide-slate-100">
                            <div class="flex justify-between items-center py-3.5 first:pt-0">
                                <span class="text-sm text-slate-400">Tanggal Terbit</span>
                                <span class="text-sm font-semibold text-slate-700">{{ $faktur->created_at->format('d M Y') }}</span>
                            </div>

                            @if($faktur->status == 'sudah_bayar')
                            <div class="flex justify-between items-center py-3.5">
                                <span class="text-sm text-slate-400">Tanggal Pembayaran</span>
                                <span class="text-sm font-semibold text-emerald-600">{{ $faktur->tanggal_konfirmasi ? $faktur->tanggal_konfirmasi->format('d M Y') : $faktur->updated_at->format('d M Y') }}</span>
                            </div>
                            @endif

                            <div class="flex justify-between items-center py-3.5 last:pb-0">
                                <span class="text-sm text-slate-400">Batas Pembayaran</span>
                                <span class="text-sm font-semibold text-slate-700">{{ $faktur->expired_at->format('d M Y') }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- CATATAN -->
                @if($faktur->catatan)
                <div>
                    <h3 class="text-sm font-bold text-slate-800 uppercase tracking-wider mb-4 flex items-center gap-2">
                        <span class="w-1.5 h-5 bg-gradient-to-b from-[#109696] to-[#1760C5] rounded-full"></span>
                        Catatan
                    </h3>

                    <div class="bg-slate-50/50 p-5 rounded-xl border border-slate-100">
                        <p class="text-sm text-slate-600 leading-relaxed">{{ $faktur->catatan }}</p>
                    </div>
                </div>
                @endif

                <!-- BUKTI PEMBAYARAN -->
                @if($faktur->status == 'sudah_bayar' && $faktur->bukti_pembayaran_path)
                <div>
                    <h3 class="text-sm font-bold text-slate-800 uppercase tracking-wider mb-4 flex items-center gap-2">
                        <span class="w-1.5 h-5 bg-gradient-to-b from-[#109696] to-[#1760C5] rounded-full"></span>
                        Bukti Pembayaran
                    </h3>

                    <div class="bg-slate-50/50 p-5 rounded-xl border border-slate-100">
                        <img src="{{ asset('storage/' . $faktur->bukti_pembayaran_path) }}"
                             alt="Bukti Pembayaran"
                             class="max-w-full max-h-96 rounded-xl border border-slate-200 object-contain mb-4">

                        <a href="{{ asset('storage/' . $faktur->bukti_pembayaran_path) }}"
                           download
                           class="inline-flex items-center gap-2 bg-[#1760C5] hover:bg-[#1250a5] text-white text-sm font-bold px-5 py-2.5 rounded-xl transition shadow-sm">
                            <i class="fas fa-download text-xs"></i> Download Bukti Pembayaran
                        </a>
                    </div>
                </div>
                @endif

            </div>
        </div>
    </div>
</div>
@endsection