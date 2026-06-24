@extends('layouts.desa')

@section('title', 'Invoice')

@section('content')

@php
    $durasiTahun = $faktur->durasi_tahun ?? 1;
    $hargaPerTahun = 50000;
    $ppnPersen = 11;
    
    if (isset($faktur->subtotal) && isset($faktur->ppn)) {
        $subtotal = $faktur->subtotal / 1.11;
        $ppn = $faktur->ppn;
        $totalHarga = $faktur->total;
    } else {
        $subtotal = $durasiTahun * $hargaPerTahun;
        $ppn = $subtotal * ($ppnPersen / 100);
        $totalHarga = $subtotal + $ppn;
    }
@endphp

<div class="flex flex-col lg:flex-row gap-6">

    <!-- SIDEBAR KIRI -->
    <div class="w-full lg:w-80 flex-shrink-0 space-y-4">
        <x-status-domain :status="$faktur->status" />

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

    <!-- KONTEN UTAMA -->
    <div class="flex-1 min-w-0">
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">

            <!-- HEADER INVOICE -->
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
                            <div class="flex justify-between items-center py-3.5 last:pb-0">
                                <span class="text-sm text-slate-400">Masa Aktif</span>
                                <span class="text-sm font-semibold text-slate-700">{{ $durasiTahun }} Tahun</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- RINCIAN HARGA -->
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

                <!-- PETUNJUK PEMBAYARAN -->
                <div>
                    <h3 class="text-sm font-bold text-slate-800 uppercase tracking-wider mb-4 flex items-center gap-2">
                        <span class="w-1.5 h-5 bg-gradient-to-b from-[#109696] to-[#1760C5] rounded-full"></span>
                        Petunjuk Pembayaran
                    </h3>

                    <div class="bg-slate-50/50 p-5 rounded-xl border border-slate-100">
                        <p class="text-sm text-slate-500 mb-4">Silakan lakukan pembayaran ke rekening berikut:</p>
                        <div class="space-y-0 divide-y divide-slate-100">
                            <div class="flex justify-between items-center py-3.5 first:pt-0">
                                <span class="text-sm text-slate-400">Penerima</span>
                                <span class="text-sm font-semibold text-slate-700 text-right max-w-[60%]">PANDI (Pengelola Nama Domain Internet Indonesia)</span>
                            </div>
                            <div class="flex justify-between items-center py-3.5">
                                <span class="text-sm text-slate-400">Bank</span>
                                <span class="text-sm font-semibold text-slate-700">Bank BCA KCU Sudirman</span>
                            </div>
                            <div class="flex justify-between items-center py-3.5">
                                <span class="text-sm text-slate-400">No. Rekening</span>
                                <span class="text-sm font-semibold text-slate-700 font-mono">888-88-8888</span>
                            </div>
                            <div class="flex justify-between items-center py-3.5 last:pb-0">
                                <span class="text-sm text-slate-400">Jumlah Transfer</span>
                                <span class="text-sm font-extrabold text-[#109696]">Rp {{ number_format($totalHarga, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- BUKTI PEMBAYARAN (JIKA SUDAH ADA) -->
@if($faktur->bukti_pembayaran_path)
<div>
    <h3 class="text-sm font-bold text-slate-800 uppercase tracking-wider mb-4 flex items-center gap-2">
        <span class="w-1.5 h-5 bg-gradient-to-b from-[#109696] to-[#1760C5] rounded-full"></span>
        Bukti Pembayaran
    </h3>

    <div class="bg-slate-50/50 p-5 rounded-xl border border-slate-100">
        <img src="{{ Storage::url($faktur->bukti_pembayaran_path) }}"
             alt="Bukti Pembayaran"
             class="max-w-full max-h-96 rounded-xl border border-slate-200 object-contain mb-4">

        <a href="{{ Storage::url($faktur->bukti_pembayaran_path) }}"
           download
           class="inline-flex items-center gap-2 bg-[#1760C5] hover:bg-[#1250a5] text-white text-sm font-bold px-5 py-2.5 rounded-xl transition shadow-sm shadow-[#1760C5]/20 hover:shadow-md hover:-translate-y-0.5">
            <i class="fas fa-download text-xs"></i> Download Bukti Pembayaran
        </a>
    </div>
</div>
@endif

                <!-- FORM UPLOAD (HANYA BELUM BAYAR) -->
                @if($faktur->status == 'belum_bayar')
                <div>
                    <h3 class="text-sm font-bold text-slate-800 uppercase tracking-wider mb-4 flex items-center gap-2">
                        <span class="w-1.5 h-5 bg-gradient-to-b from-[#109696] to-[#1760C5] rounded-full"></span>
                        Upload Bukti Pembayaran
                    </h3>

                    <form action="{{ route('desa.faktur.konfirmasi', $faktur->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="border-2 border-dashed border-slate-200 rounded-xl p-8 text-center cursor-pointer transition-all duration-200 hover:border-[#109696] hover:bg-[#109696]/5"
                             id="dropZone" onclick="document.getElementById('bukti').click()">
                            <div class="w-12 h-12 bg-slate-100 rounded-xl flex items-center justify-center mx-auto mb-3 transition-colors duration-200" id="dropIcon">
                                <i class="fas fa-cloud-upload-alt text-xl text-slate-400"></i>
                            </div>
                            <p class="text-sm font-medium text-slate-600" id="dropText">Klik atau seret file ke sini</p>
                            <p class="text-xs text-slate-400 mt-1">JPG, PNG maks. 2MB</p>
                            <input type="file" name="bukti_pembayaran" id="bukti" required accept="image/*" class="hidden">
                        </div>
                        <p class="text-sm font-semibold text-[#109696] mt-3 hidden" id="fname"></p>
                        @error('bukti_pembayaran')
                            <p class="text-xs text-rose-500 mt-2 flex items-center gap-1">
                                <i class="fas fa-exclamation-circle text-[10px]"></i>
                                {{ $message }}
                            </p>
                        @enderror

                        <button type="submit"
                                class="mt-5 w-full flex items-center justify-center gap-2 bg-gradient-to-r from-[#109696] to-[#1A85A5] hover:from-[#0e7e7e] hover:to-[#157090] text-white font-bold py-3.5 rounded-xl transition-all duration-200 shadow-md shadow-[#109696]/20 hover:shadow-lg hover:shadow-[#109696]/30 hover:-translate-y-0.5">
                            <i class="fas fa-paper-plane text-sm"></i>
                            Kirim Bukti Pembayaran
                        </button>
                    </form>
                </div>
                @endif

            </div>
        </div>
    </div>
</div>

{{-- MODAL SUKSES --}}
@if(session('success'))
<div class="fixed inset-0 bg-black/45 z-[9999] flex items-center justify-center" id="successModal">
    <div class="bg-white rounded-2xl p-10 text-center max-w-sm w-[90%] shadow-2xl" style="animation: invPopIn .3s ease">
        <div class="w-16 h-16 bg-emerald-50 rounded-full flex items-center justify-center mx-auto mb-4">
            <i class="fas fa-check text-2xl text-emerald-500"></i>
        </div>
        <h3 class="text-xl font-extrabold text-slate-800 mb-2">Sukses</h3>
        <p class="text-sm text-slate-500 leading-relaxed mb-6">{{ session('success') }}</p>
        <button onclick="document.getElementById('successModal').remove()"
                class="w-full py-3 bg-gradient-to-r from-[#109696] to-[#1A85A5] hover:from-[#0e7e7e] hover:to-[#157090] text-white font-bold rounded-xl transition-all duration-200 shadow-md shadow-[#109696]/20">
            OK
        </button>
    </div>
</div>
<style>
@keyframes invPopIn{from{opacity:0;transform:scale(.9) translateY(10px)}to{opacity:1;transform:scale(1) translateY(0)}}
</style>
@endif

@if($faktur->status == 'belum_bayar')
<script>
document.addEventListener('DOMContentLoaded', function() {
    var fi = document.getElementById('bukti'),
        dz = document.getElementById('dropZone'),
        fn = document.getElementById('fname'),
        di = document.getElementById('dropIcon'),
        dt = document.getElementById('dropText');

    fi.addEventListener('change', function() {
        if (this.files.length) {
            fn.textContent = this.files[0].name;
            fn.classList.remove('hidden');
            dz.classList.remove('border-slate-200');
            dz.classList.add('border-emerald-300', 'bg-emerald-50/50');
            di.classList.remove('bg-slate-100');
            di.classList.add('bg-emerald-100');
            di.querySelector('i').classList.remove('text-slate-400');
            di.querySelector('i').classList.add('text-emerald-500');
            dt.textContent = 'File siap diunggah';
            dt.classList.remove('text-slate-600');
            dt.classList.add('text-emerald-700');
        } else {
            resetDrop();
        }
    });

    dz.addEventListener('dragover', function(e) {
        e.preventDefault();
        this.classList.remove('border-slate-200');
        this.classList.add('border-[#109696]', 'bg-[#109696]/5');
    });
    dz.addEventListener('dragleave', function() {
        if (!fi.files.length) resetDrop();
    });
    dz.addEventListener('drop', function(e) {
        e.preventDefault();
        fi.files = e.dataTransfer.files;
        fi.dispatchEvent(new Event('change'));
    });

    function resetDrop() {
        dz.classList.add('border-slate-200');
        dz.classList.remove('border-emerald-300', 'bg-emerald-50/50', 'border-[#109696]', 'bg-[#109696]/5');
        di.classList.add('bg-slate-100');
        di.classList.remove('bg-emerald-100');
        di.querySelector('i').classList.add('text-slate-400');
        di.querySelector('i').classList.remove('text-emerald-500');
        dt.textContent = 'Klik atau seret file ke sini';
        dt.classList.add('text-slate-600');
        dt.classList.remove('text-emerald-700');
    }
});
</script>
@endif
@endsection