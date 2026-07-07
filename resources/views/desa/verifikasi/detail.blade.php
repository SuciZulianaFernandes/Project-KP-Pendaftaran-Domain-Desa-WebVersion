@extends('layouts.desa')  
@section('title', 'Detail Pengajuan')

@section('content')

@php
    // =========================
    // LOGIKA STATUS FINAL
    // =========================

    $finalStatus = $pengajuan->status_pengajuan;

    // Ambil aktivasi terbaru
    $latestAktivasi = \App\Models\Aktivasi::where('id_pengajuan', $pengajuan->id_pengajuan)
                            ->orderBy('masa_berlaku', 'desc')
                            ->first();

    // Cek faktur belum bayar
    $hasUnpaidInvoice = $pengajuan->faktur
        ->where('status', 'belum_bayar')
        ->count() > 0;

    // Cek pesan perpanjangan terbaru
    $perpanjanganMsg = \App\Models\Pesan::where('id_pengajuan', $pengajuan->id_pengajuan)
        ->where('judul', 'Permintaan Perpanjangan Domain')
        ->latest()
        ->first();

    // =========================
    // PRIORITAS STATUS
    // =========================

    if ($pengajuan->status_pengajuan == 'menunggu_aktivasi') {
        $finalStatus = 'menunggu_aktivasi';
    }
    elseif ($pengajuan->status_pengajuan == 'aktif') {
        if ($latestAktivasi) {
            $finalStatus = $latestAktivasi->status_akt;
        } else {
            $finalStatus = 'aktif';
        }
    }
    elseif ($hasUnpaidInvoice) {
        $finalStatus = 'diproses';
    }
    elseif ($perpanjanganMsg) {
        $fakturPerpanjangan = \App\Models\Faktur::where('id_pengajuan', $pengajuan->id_pengajuan)
            ->where('tipe', 'perpanjangan')
            ->where('created_at', '>', $perpanjanganMsg->created_at)
            ->exists();

        if (!$fakturPerpanjangan) {
            $finalStatus = 'diproses';
        } else {
            $finalStatus = 'diproses';
        }
    }

    // Konfirmasi pembayaran
    $konfirmasiMsg = $pengajuan->pesan()
        ->where('role_tujuan', 'desa') 
        ->where('judul', 'Konfirmasi Pembayaran')
        ->latest()
        ->first();

    $hasConfirmedPayment = $konfirmasiMsg && $konfirmasiMsg->is_read == 1;

    // Mapping Label Dokumen
    $labelDokumen = [
        'surat_permohonan' => 'Surat Permohonan Domain Desa',
        'surat_kuasa' => 'Surat Kuasa dari Desa',
        'perda_pembentukan_desa' => 'Dasar Hukum Pembentukan Desa / Surat Pelantikan Kepala Desa',
        // Legacy (jika ada data lama)
        'surat_penunjukan_pejabat' => 'Surat Penunjukan Pejabat',
        'ktp_asn_pejabat' => 'Kartu Pegawai / KTP ASN'
    ];

@endphp

@if(session('success'))
<div class="bg-emerald-50 border-l-4 border-emerald-500 text-emerald-700 p-4 mb-6 rounded-lg" role="alert">
    {{ session('success') }}
</div>
@endif

@if(session('error'))
<div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-lg" role="alert">
    {{ session('error') }}
</div>
@endif

<div class="space-y-6">
    <div class="flex flex-col lg:flex-row gap-6">

        <!-- SIDEBAR KIRI -->
        <div class="w-full lg:w-80 flex-shrink-0 space-y-6">

            <!-- STATUS DOMAIN -->
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
                <div class="px-5 py-4 border-b border-slate-100 bg-slate-50/50">
                    <h3 class="font-bold text-slate-800 text-xs uppercase tracking-widest">Status Domain</h3>
                </div>

                <div class="p-5 space-y-5">
                    <div>
                        <p class="text-slate-400 text-[10px] font-bold uppercase tracking-widest mb-1">Nama Domain</p>
                        <p class="text-[#1A85A5] font-extrabold text-xl tracking-tight">
                            {{ $pengajuan->nama_domain }}<span class="text-slate-300 font-bold">.desa.id</span>
                        </p>
                    </div>

                    <div class="flex items-center gap-3 p-3.5 rounded-xl border
                        @if($finalStatus == 'ditinjau') bg-amber-50 border-amber-100
                        @elseif($finalStatus == 'perlu_perbaikan') bg-rose-50 border-rose-100
                        @elseif($finalStatus == 'diproses') bg-sky-50 border-sky-100
                        @elseif($finalStatus == 'menunggu_aktivasi') bg-orange-50 border-orange-100
                        @elseif($finalStatus == 'aktif') bg-emerald-50 border-emerald-100
                        @elseif($finalStatus == 'kadaluarsa') bg-slate-100 border-slate-200
                        @else bg-slate-50 border-slate-100 @endif">
                        
                        <span class="w-3 h-3 rounded-full flex-shrink-0
                            @if($finalStatus == 'ditinjau') bg-amber-500
                            @elseif($finalStatus == 'perlu_perbaikan') bg-rose-500
                            @elseif($finalStatus == 'diproses') bg-sky-500
                            @elseif($finalStatus == 'menunggu_aktivasi') bg-orange-500
                            @elseif($finalStatus == 'aktif') bg-emerald-500
                            @elseif($finalStatus == 'kadaluarsa') bg-slate-400
                            @else bg-slate-300 @endif">
                        </span>

                        <span class="text-sm font-bold capitalize
                            @if($finalStatus == 'ditinjau') text-amber-700
                            @elseif($finalStatus == 'perlu_perbaikan') text-rose-700
                            @elseif($finalStatus == 'diproses') text-sky-700
                            @elseif($finalStatus == 'menunggu_aktivasi') text-orange-700
                            @elseif($finalStatus == 'aktif') text-emerald-700
                            @elseif($finalStatus == 'kadaluarsa') text-slate-600
                            @else text-slate-500 @endif">
                            {{ str_replace('_', ' ', $finalStatus) }}
                        </span>
                    </div>
                </div>
            </div>

        </div>

        <!-- CONTENT KANAN -->
        <div class="flex-1 min-w-0">

            <div class="bg-white p-6 md:p-8 rounded-2xl border border-slate-100 shadow-sm">

                <!-- HEADER -->
                <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4 mb-8">
                    <div>
                        <h2 class="text-2xl font-extrabold text-slate-800 tracking-tight">Detail Pengajuan</h2>
                        <p class="text-sm text-slate-400 mt-1">Informasi lengkap pengajuan domain desa</p>
                    </div>

                    <a href="{{ url()->previous() }}" 
                       class="inline-flex items-center gap-2 bg-slate-100 hover:bg-slate-200 text-slate-600 font-semibold py-2.5 px-5 rounded-xl transition text-sm">
                        <i class="fas fa-arrow-left text-xs"></i> Kembali
                    </a>
                </div>

                <!-- INFORMASI INSTANSI -->
                <div class="mb-8">
                    <h3 class="text-sm font-bold text-slate-800 uppercase tracking-wider mb-4 flex items-center gap-2">
                        <span class="w-1.5 h-5 bg-gradient-to-b from-[#109696] to-[#1760C5] rounded-full"></span>
                        Informasi Instansi
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-4 text-sm bg-slate-50/50 p-5 rounded-xl border border-slate-100">
                        
                        <div class="flex flex-col">
                            <span class="text-slate-400 text-xs font-semibold mb-0.5">Nama Organisasi</span>
                            <span class="text-slate-700 font-medium">{{ $pengajuan->nama_desa }}</span>
                        </div>

                        <div class="flex flex-col">
                            <span class="text-slate-400 text-xs font-semibold mb-0.5">Provinsi</span>
                            <span class="text-slate-700 font-medium">{{ $pengajuan->provinsi }}</span>
                        </div>

                        <div class="flex flex-col">
                            <span class="text-slate-400 text-xs font-semibold mb-0.5">Kabupaten</span>
                            <span class="text-slate-700 font-medium">{{ $pengajuan->kota_kabupaten }}</span>
                        </div>

                        <div class="flex flex-col">
                            <span class="text-slate-400 text-xs font-semibold mb-0.5">Kecamatan</span>
                            <span class="text-slate-700 font-medium">{{ $pengajuan->kecamatan }}</span>
                        </div>

                        <div class="flex flex-col">
                            <span class="text-slate-400 text-xs font-semibold mb-0.5">Desa</span>
                            <span class="text-slate-700 font-medium">{{ $pengajuan->desa_kelurahan }}</span>
                        </div>

                        <div class="flex flex-col">
                            <span class="text-slate-400 text-xs font-semibold mb-0.5">Telepon</span>
                            <span class="text-slate-700 font-medium">{{ $pengajuan->telepon }}</span>
                        </div>

                        <div class="flex flex-col">
                            <span class="text-slate-400 text-xs font-semibold mb-0.5">Faksimili</span>
                            <span class="text-slate-700 font-medium">{{ $pengajuan->faksimili }}</span>
                        </div>

                        <div class="flex flex-col">
                            <span class="text-slate-400 text-xs font-semibold mb-0.5">Kode Pos</span>
                            <span class="text-slate-700 font-medium">{{ $pengajuan->kode_pos }}</span>
                        </div>

                        <div class="flex flex-col">
                            <span class="text-slate-400 text-xs font-semibold mb-0.5">Email Registran</span>
                            <span class="text-slate-700 font-medium">{{ $pengajuan->email }}</span>
                        </div>

                        <div class="flex flex-col">
                            <span class="text-slate-400 text-xs font-semibold mb-0.5">Tanggal Pembuatan</span>
                            <span class="text-slate-700 font-medium">{{ $pengajuan->created_at->format('d M Y') }}</span>
                        </div>

                        <div class="flex flex-col md:col-span-2">
                            <span class="text-slate-400 text-xs font-semibold mb-0.5">Alamat</span>
                            <span class="text-slate-700 font-medium">{{ $pengajuan->alamat }}</span>
                        </div>

                    </div>
                </div>

                {{-- ========================================== --}}
                {{-- CATATAN ADMIN (MUNCUL JIKA PERLU PERBAIKAN) --}}
                {{-- ========================================== --}}
                @if($pengajuan->status_pengajuan == 'perlu_perbaikan' && !empty($pengajuan->catatan_umum))
                <div class="mb-8 p-4 bg-rose-50 border border-rose-200 rounded-xl shadow-sm">
                    <div class="flex items-start gap-3">
                        <div class="w-9 h-9 bg-rose-100 rounded-lg flex items-center justify-center text-rose-600 flex-shrink-0 mt-0.5">
                            <i class="fas fa-exclamation-triangle text-sm"></i>
                        </div>
                        <div>
                            <p class="font-bold text-rose-800 text-sm mb-1">Catatan dari Admin:</p>
                            <p class="text-rose-700 text-sm whitespace-pre-line leading-relaxed">{{ $pengajuan->catatan_umum }}</p>
                        </div>
                    </div>
                </div>
                @endif

                <!-- DOKUMEN PERSYARATAN -->
                <div class="mb-8">
                    <h3 class="text-sm font-bold text-slate-800 uppercase tracking-wider mb-4 flex items-center gap-2">
                        <span class="w-1.5 h-5 bg-gradient-to-b from-[#109696] to-[#1760C5] rounded-full"></span>
                        Dokumen Persyaratan Domain
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        
                        @foreach($pengajuan->dokumenPersyaratan as $dok)
                        <div class="border border-slate-100 rounded-xl p-4 bg-slate-50/50 hover:border-[#109696]/30 hover:shadow-sm transition-all group flex flex-col gap-3">
                            
                            <div class="flex justify-between items-start gap-3">
                                <div class="flex items-center gap-3 min-w-0">
                                    <div class="w-10 h-10 bg-[#109696]/10 text-[#109696] rounded-lg flex items-center justify-center flex-shrink-0 group-hover:bg-[#109696] group-hover:text-white transition">
                                        <i class="fas fa-file-alt text-sm"></i>
                                    </div>
                                    <div class="min-w-0">
                                        <p class="text-slate-700 text-sm font-medium truncate">{{ $labelDokumen[$dok->jenis_dokumen] ?? $dok->jenis_dokumen }}</p>
                                        
                                        @if($pengajuan->status_pengajuan == 'perlu_perbaikan' && $dok->updated_at > $pengajuan->updated_at)
                                            <span class="inline-flex items-center gap-1 mt-1 text-[10px] font-bold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-full w-fit">
                                                <i class="fas fa-check-circle"></i> Diperbarui
                                            </span>
                                        @elseif($pengajuan->status_pengajuan == 'perlu_perbaikan')
                                            <p class="text-[10px] text-slate-400 mt-0.5">Update: {{ $dok->updated_at->format('d M Y, H:i') }}</p>
                                        @endif
                                    </div>
                                </div>

                                <a href="{{ route('dokumen.lihat', [$dok->uuid, $dok->nama_file]) }}"
                                target="_blank"
                                class="text-[#109696] hover:text-white bg-[#109696]/10 hover:bg-[#109696] text-xs font-bold px-3 py-2 rounded-lg transition-all flex-shrink-0">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </div>

                            {{-- FORM UPLOAD ULANG --}}
                            @if($pengajuan->status_pengajuan == 'perlu_perbaikan')
                            <form
                                action="{{ route('desa.verifikasi.updateDokumen', $dok->uuid) }}"
                                method="POST"
                                enctype="multipart/form-data"
                                class="mt-auto pt-3 border-t border-slate-100 space-y-2"
                                onsubmit="return confirm('Yakin ingin mengganti dokumen ini dengan file baru?')"
                            >
                                @csrf
                                @method('PUT')

                                <input
                                    type="file"
                                    name="file"
                                    required
                                    accept="application/pdf"
                                    class="w-full text-xs border border-dashed border-slate-300 rounded-lg p-2 bg-slate-50 file:mr-2 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-[#109696] file:text-white hover:file:bg-[#0d7a7a] cursor-pointer"
                                >

                                <div class="flex justify-end">
                                    <button
                                        type="submit"
                                        class="inline-flex items-center gap-1.5 px-4 py-2 bg-[#109696] hover:bg-[#0d7a7a] text-white rounded-lg text-xs font-bold transition shadow-sm"
                                    >
                                        <i class="fas fa-upload text-[10px]"></i> Upload Ulang
                                    </button>
                                </div>

                            </form>
                            @endif
                        </div>
                        @endforeach

                    </div>
                </div>

                {{-- RIWAYAT DATA FAKTUR --}}
                @if($pengajuan->faktur->isNotEmpty())
                <div class="mb-8">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-sm font-bold text-slate-800 uppercase tracking-wider flex items-center gap-2">
                            <span class="w-1.5 h-5 bg-gradient-to-b from-[#109696] to-[#1760C5] rounded-full"></span>
                            Riwayat Data Faktur
                        </h3>
                    </div>

                    <div class="space-y-3">
                        @foreach($pengajuan->faktur as $fakturItem)
                        <div class="bg-slate-50/50 border border-slate-100 rounded-xl p-5 hover:shadow-sm transition">
                            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                                <div class="flex items-start gap-4">
                                    <div class="w-10 h-10 bg-[#1760C5]/10 text-[#1760C5] rounded-lg flex items-center justify-center flex-shrink-0 mt-0.5">
                                        <i class="fas fa-file-invoice-dollar"></i>
                                    </div>
                                    <div>
                                        <p class="font-bold text-slate-800">{{ $fakturItem->no_invoice }}</p>
                                        <div class="flex flex-wrap gap-x-6 gap-y-1 mt-2 text-sm">
                                            <div>
                                                <span class="text-slate-400 text-xs">Total Tagihan</span>
                                                <p class="font-semibold text-slate-700">Rp {{ number_format($fakturItem->total, 0, ',', '.') }}</p>
                                            </div>
                                            <div>
                                                <span class="text-slate-400 text-xs">Status</span>
                                                <p class="font-bold text-xs mt-0.5
                                                    @if($fakturItem->status == 'belum_bayar') text-amber-600
                                                    @elseif($fakturItem->status == 'sudah_bayar') text-emerald-600
                                                    @elseif($fakturItem->status == 'kedaluarsa') text-rose-600
                                                    @endif">
                                                    {{ ucfirst(str_replace('_',' ',$fakturItem->status)) }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <a href="{{ route('desa.faktur.show', $fakturItem->uuid) }}"
                                class="inline-flex items-center gap-2 bg-[#109696]/10 hover:bg-[#109696] text-[#109696] hover:text-white text-xs font-bold px-4 py-2.5 rounded-lg transition-all flex-shrink-0">
                                    <i class="fas fa-eye text-[10px]"></i> Detail Faktur
                                </a>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                <hr class="my-8 border-slate-100">

                {{-- DIPROSES --}}
                @if($finalStatus == 'diproses')

                    @php
                        $sudahKonfirmasiBayar = \App\Models\Pesan::where('id_pengajuan', $pengajuan->id_pengajuan)
                            ->where('role_tujuan', 'admin')
                            ->where('judul', 'Konfirmasi Pembayaran Disetujui')
                            ->exists();
                        
                        $fakturDesa = $pengajuan->faktur
                            ->where('status', 'belum_bayar')
                            ->sortByDesc('created_at')
                            ->first();

                        $fakturPerpanjanganAda = false;

                        if ($perpanjanganMsg) {
                            $fakturPerpanjanganAda = \App\Models\Faktur::where('id_pengajuan', $pengajuan->id_pengajuan)
                                ->where('tipe', 'perpanjangan')
                                ->where('created_at', '>', $perpanjanganMsg->created_at)
                                ->exists();
                        }
                        
                        // Konstanta Harga
                        $hargaDasarPerTahun = 50000;
                        $ppnPersen = 11;
                    @endphp

                    @if(!$sudahKonfirmasiBayar && !$fakturPerpanjanganAda)

                    <div class="bg-sky-50 p-6 rounded-xl border border-sky-100">
                        <div class="flex items-start gap-4 mb-5">
                            <div class="w-10 h-10 bg-sky-100 rounded-lg flex items-center justify-center text-sky-600 flex-shrink-0 mt-0.5">
                                <i class="fas fa-info-circle"></i>
                            </div>
                            <div>
                                <h4 class="font-bold text-slate-800">Pengajuan Sedang Diproses</h4>
                                <p class="text-sm text-slate-500 mt-1">Pilih masa berlaku perpanjangan untuk meminta faktur.</p>
                            </div>
                        </div>
                        
                        <form action="{{ route('desa.ajukan.faktur', $pengajuan->uuid) }}" method="POST" id="formKonfirmasi">
                            @csrf
                            
                            <select name="durasi_tahun" id="durasi_tahun" required class="w-full px-4 py-2.5 border border-slate-200 rounded-lg text-sm bg-white focus:outline-none focus:ring-2 focus:ring-[#109696]/20 focus:border-[#109696] transition">
                                <option value="" disabled selected>-- Pilih Tahun --</option>
                                <option value="1">1 Tahun (Rp 55.500)</option>
                                <option value="2">2 Tahun (Rp 111.000)</option>
                                <option value="3">3 Tahun (Rp 166.500)</option>
                                <option value="4">4 Tahun (Rp 222.000)</option>
                                <option value="5">5 Tahun (Rp 277.500)</option>
                            </select>

                            <div class="mt-3 p-3 bg-amber-50 border border-amber-200 rounded-lg text-xs text-amber-800">
                                <strong><i class="fas fa-info-circle mr-1"></i>Keterangan Harga:</strong><br>
                                • Biaya per tahun: Rp 50.000<br>
                                • PPN 11%: Rp 5.500 per tahun<br>
                                • <strong>Total per tahun: Rp 55.500 (sudah termasuk PPN)</strong><br><br>
                                <strong><i class="fas fa-exclamation-triangle mr-1"></i>Disclaimer:</strong> 
                                Masa berlaku akan dihitung oleh admin mulai dari tanggal domain diaktifkan. Pastikan pilihan tahun sudah sesuai dengan kebutuhan desa.
                            </div>

                            <div class="flex justify-end mt-4">
                                <button type="submit" 
                                        class="js-confirm-btn bg-[#109696] hover:bg-[#0d7a7a] text-white font-bold py-2.5 px-6 rounded-xl text-sm shadow-sm transition inline-flex items-center gap-2"
                                        data-confirm-message="Yakin ingin mengajukan permintaan faktur perpanjangan?">
                                    <i class="fas fa-paper-plane text-xs"></i> Ya, Kirimkan Faktur
                                </button>
                            </div>
                        </form>
                    </div>

                    @else

                    <div class="bg-sky-50 p-6 rounded-xl border border-sky-100">
                        <div class="flex items-start gap-4">
                            <div class="w-10 h-10 bg-sky-100 rounded-lg flex items-center justify-center text-sky-600 flex-shrink-0 mt-0.5">
                                <i class="fas fa-file-invoice"></i>
                            </div>
                            <div>
                                <p class="text-sky-800 font-semibold text-sm">
                                    @if($hasUnpaidInvoice || $fakturPerpanjanganAda)
                                        Faktur telah diterbitkan. Silahkan upload bukti pembayaran.
                                    @else
                                        Menunggu faktur dari admin kominfo
                                    @endif
                                </p>

                                @if($fakturDesa)
                                    <a href="{{ route('desa.faktur.show', $fakturDesa->uuid) }}" class="text-sm text-[#109696] font-semibold hover:underline mt-1 inline-block">
                                        Lihat Detail Faktur →
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>

                    @endif

                {{-- MENUNGGU AKTIVASI --}}
                @elseif($finalStatus == 'menunggu_aktivasi')

                    <div class="bg-orange-50 p-4 rounded-xl border border-orange-200 flex items-center gap-3 mt-2">
                        <div class="w-10 h-10 bg-orange-100 rounded-lg flex items-center justify-center text-orange-600 flex-shrink-0">
                            <i class="fas fa-hourglass-half"></i>
                        </div>
                        <p class="text-orange-700 font-semibold text-sm">
                            Menunggu aktivasi dari admin kominfo
                        </p>
                    </div>

                {{-- AKTIF --}}
                @elseif($finalStatus == 'aktif')

                    <div class="bg-emerald-50 p-4 rounded-xl border border-emerald-100 flex items-center gap-3 mt-2">
                        <i class="fas fa-check-circle text-emerald-500 text-lg"></i>
                        <p class="text-emerald-700 font-semibold text-sm">Domain Sudah Aktif</p>
                    </div>

                {{-- KADALUARSA --}}
                @elseif($finalStatus == 'kadaluarsa')

                    <div class="bg-slate-100 p-4 rounded-xl border border-slate-200 flex items-center gap-3 mt-2">
                        <i class="fas fa-exclamation-triangle text-slate-400 text-lg"></i>
                        <p class="text-slate-600 font-semibold text-sm">
                            Masa berlaku domain ini telah kadaluarsa pada tanggal {{ \Carbon\Carbon::parse($latestAktivasi->masa_berlaku)->format('d M Y') }}.
                        </p>
                    </div>

                {{-- NONAKTIF --}}
                @elseif($finalStatus == 'nonaktif')

                    <div class="bg-slate-100 p-4 rounded-xl border border-slate-200 flex items-center gap-3 mt-2">
                        <i class="fas fa-exclamation-triangle text-slate-400 text-lg"></i>
                        <p class="text-slate-600 font-semibold text-sm">
                            Domain saat ini dalam status nonaktif.
                        </p>
                    </div>

                {{-- DITINJAU --}}
                @elseif($finalStatus == 'ditinjau')

                    <div class="bg-amber-50 p-4 rounded-xl border border-amber-200 flex items-center gap-3 mt-2">
                        <div class="w-10 h-10 bg-amber-100 rounded-lg flex items-center justify-center text-amber-600 flex-shrink-0">
                            <i class="fas fa-hourglass-half"></i>
                        </div>
                        <p class="text-amber-700 font-semibold text-sm">
                            Pengajuan sedang ditinjau oleh admin.
                        </p>
                    </div>

                {{-- PERLU PERBAIKAN --}}
                @elseif($finalStatus == 'perlu_perbaikan')

                    <div class="bg-rose-50 p-4 rounded-xl border border-rose-200 flex items-center gap-3 mt-2 mb-4">
                        <div class="w-9 h-9 bg-rose-100 rounded-lg flex items-center justify-center text-rose-600 flex-shrink-0 mt-0.5">
                            <i class="fas fa-exclamation-circle text-sm"></i>
                        </div>
                        <p class="text-rose-700 font-semibold text-sm">
                            Dokumen perlu diperbaiki sesuai catatan admin di atas.
                        </p>
                    </div>

                    <form action="{{ route('desa.verifikasi.kirimUlang', $pengajuan->uuid) }}" method="POST"
                        onsubmit="return confirm('Pastikan semua dokumen yang perlu diperbaiki sudah diupload ulang. Kirim untuk ditinjau ulang oleh admin?')">
                        @csrf
                        @method('PUT')
                        <div class="flex justify-end">
                            <button type="submit"
                                class="inline-flex items-center gap-2 bg-[#109696] hover:bg-[#0d7a7a] text-white font-bold py-2.5 px-6 rounded-xl text-sm shadow-sm transition">
                                <i class="fas fa-paper-plane text-xs"></i> Simpan &amp; Kirim Ulang
                            </button>
                        </div>
                    </form>

                @endif

            </div>
        </div>
    </div>
</div>

<!-- MODAL KONFIRMASI MODERN -->
<div id="confirmationModal" class="hidden fixed inset-0 bg-slate-900/60 backdrop-blur-sm h-full w-full z-50 flex items-center justify-center p-4">
    <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-sm transform transition-all">
        
        <div class="p-6 text-center">
            <div class="mx-auto flex items-center justify-center h-14 w-14 rounded-full bg-[#109696]/10 mb-4">
                <i class="fas fa-question text-[#109696] text-xl"></i>
            </div>
            <h3 class="text-lg font-bold text-slate-800 mb-2">
                Konfirmasi Aksi
            </h3>
            <p id="modalConfirmMessage" class="text-sm text-slate-500 leading-relaxed">
                Apakah anda yakin?
            </p>
        </div>
        
        <div class="px-6 pb-6 flex items-center justify-center gap-3">
            <button id="modalNoBtn"
            class="flex-1 py-2.5 bg-slate-100 text-slate-600 text-sm font-semibold rounded-xl hover:bg-slate-200 transition">
                Batal
            </button>
            <button id="modalYesBtn"
            class="flex-1 py-2.5 bg-[#109696] text-white text-sm font-semibold rounded-xl hover:bg-[#0d7a7a] transition shadow-sm">
                Ya, Lanjutkan
            </button>
        </div>

    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('confirmationModal');
    const yesBtn = document.getElementById('modalYesBtn');
    const noBtn = document.getElementById('modalNoBtn');
    const modalMessage = document.getElementById('modalConfirmMessage');
    const confirmBtns = document.querySelectorAll('.js-confirm-btn');

    let formToSubmit = null;

    confirmBtns.forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();

            const formId = this.getAttribute('form');
            formToSubmit = formId ? document.getElementById(formId) : this.closest('form');

            const message = this.getAttribute('data-confirm-message') || 'Apakah anda yakin?';
            modalMessage.textContent = message;

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