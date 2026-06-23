@extends('layouts.admin')

@section('title', 'Detail Pengajuan')

@section('content')

@php
    // LOGIKA BARU: Cek status aktual yang sebenarnya (terutama untuk kasus Kadaluarsa)
    $finalStatus = $pengajuan->status_pengajuan;

    // Jika status pengajuan 'aktif', cek tabel aktivasi terakhir
    if ($finalStatus == 'aktif' && $pengajuan->aktivasi) {
        // Ambil aktivasi terakhir berdasarkan masa berlaku (DESC)
        $latestAktivasi = \App\Models\Aktivasi::where('id_pengajuan', $pengajuan->id_pengajuan)
                            ->orderBy('masa_berlaku', 'desc')
                            ->first();
        
        // Jika ada data aktivasi dan statusnya bukan 'aktif', override status tampilan
        if ($latestAktivasi && $latestAktivasi->status_akt != 'aktif') {
            $finalStatus = $latestAktivasi->status_akt; // nilain 'kadaluarsa' atau 'nonaktif'
        }
    }

    // AMBIL DURASI TAHUN YANG DIPILIH DESA
    $pesanDurasi = \App\Models\Pesan::where('id_pengajuan', $pengajuan->id_pengajuan)
        ->where('judul', 'Konfirmasi Pembayaran Disetujui')
        ->whereNotNull('durasi_tahun')
        ->latest()
        ->first();
    $durasiDipilihSidebar = $pesanDurasi ? $pesanDurasi->durasi_tahun : null;

    // ✅ KONSTANTA HARGA DENGAN PPN
    $hargaDasarPerTahun = 50000;
    $ppnPersen = 11;
@endphp

<div class="flex flex-col lg:flex-row gap-6">

    <!-- SIDEBAR KIRI -->
    <div class="w-full lg:w-80 flex-shrink-0 space-y-4">

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

                {{-- ✅ DURASI TAHUN YANG DIPILIH DESA (SUDAH PPN) --}}
                @if($durasiDipilihSidebar)
                @php
                    $subtotalSidebar = $durasiDipilihSidebar * $hargaDasarPerTahun;
                    $ppnSidebar = $subtotalSidebar * ($ppnPersen / 100);
                    $totalSidebar = $subtotalSidebar + $ppnSidebar;
                @endphp
                <div class="border-t border-slate-100 pt-5">
                    <p class="text-slate-400 text-[10px] font-bold uppercase tracking-widest mb-1">Durasi Dipilih Desa</p>
                    <div class="flex items-center gap-3 mt-2">
                        <div class="w-10 h-10 rounded-xl bg-[#1760C5]/10 flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-calendar-alt text-[#1760C5] text-sm"></i>
                        </div>
                        <div>
                            <p class="text-xl font-extrabold text-[#1760C5] leading-none">{{ $durasiDipilihSidebar }} <span class="text-sm font-semibold text-slate-400">Tahun</span></p>
                            <p class="text-[10px] text-slate-400 mt-0.5">Rp {{ number_format($totalSidebar, 0, ',', '.') }} <span class="text-emerald-500">(inc. PPN)</span></p>
                        </div>
                    </div>
                </div>
                @endif

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

            <!-- DOKUMEN -->
            <div class="mb-8">
                <h3 class="text-sm font-bold text-slate-800 uppercase tracking-wider mb-4 flex items-center gap-2">
                    <span class="w-1.5 h-5 bg-gradient-to-b from-[#109696] to-[#1760C5] rounded-full"></span>
                    Dokumen Persyaratan Domain
                </h3>

                @php
                    $labelDokumen = [
                        'surat_permohonan' => 'Surat Permohonan Domain Desa',
                        'surat_kuasa' => 'Surat Kuasa dari Desa',
                        'perda_pembentukan_desa' => 'Dasar Hukum Pembentukan Desa / Surat Pelantikan Kepala Desa',
                        'surat_penunjukan_pejabat' => 'Surat Penunjukan Pejabat',
                        'ktp_asn_pejabat' => 'Kartu Pegawai / KTP ASN'
                    ];
                @endphp

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach($pengajuan->dokumenPersyaratan as $dok)
                    <div class="flex items-center justify-between p-4 bg-slate-50/50 rounded-xl border border-slate-100 hover:border-[#109696]/30 hover:shadow-sm transition-all group">
                        
                        <div class="flex items-center gap-3 min-w-0">
                            <div class="w-10 h-10 bg-[#109696]/10 text-[#109696] rounded-lg flex items-center justify-center flex-shrink-0 group-hover:bg-[#109696] group-hover:text-white transition">
                                <i class="fas fa-file-alt text-sm"></i>
                            </div>
                            <div class="min-w-0">
                                <p class="text-slate-700 text-sm font-medium truncate">{{ $labelDokumen[$dok->jenis_dokumen] ?? $dok->jenis_dokumen }}</p>
                                
                                @if($pengajuan->status_pengajuan == 'perlu_perbaikan' && $dok->updated_at > $pengajuan->updated_at)
                                    <span class="inline-flex items-center gap-1 mt-1 text-[10px] font-bold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-full">
                                        <i class="fas fa-check-circle"></i> Diperbarui
                                    </span>
                                @elseif($pengajuan->status_pengajuan == 'perlu_perbaikan')
                                    <p class="text-[10px] text-slate-400 mt-0.5">Update: {{ $dok->updated_at->format('d M Y, H:i') }}</p>
                                @endif
                            </div>
                        </div>

                        <a href="{{ route('dokumen.lihat', $dok->id_dokumen) }}"
                        target="_blank"
                        class="ml-4 text-[#109696] hover:text-white bg-[#109696]/10 hover:bg-[#109696] text-xs font-bold px-3 py-2 rounded-lg transition-all flex-shrink-0">
                            <i class="fas fa-eye"></i>
                        </a>
                    </div>
                    @endforeach
                </div>
            </div>
            
            <!-- RIWAYAT DATA FAKTUR -->
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

                            <a href="{{ route('admin.faktur.show', $fakturItem->id) }}"
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

            <!-- VERIFIKASI & AKSI -->
            @if(in_array($pengajuan->status_pengajuan, ['ditinjau', 'perlu_perbaikan']))

            @php
                $pernahDaftarSebelumnya = \App\Models\Pengajuan::where('id_user', $pengajuan->id_user)
                    ->where('id_pengajuan', '!=', $pengajuan->id_pengajuan)
                    ->whereIn('status_pengajuan', ['aktif', 'diproses', 'menunggu_aktivasi', 'kadaluarsa'])
                    ->exists();

                $adalahPendaftaranPertama = !$pernahDaftarSebelumnya;
            @endphp

            <h3 class="text-sm font-bold text-slate-800 uppercase tracking-wider mb-4 flex items-center gap-2">
                <span class="w-1.5 h-5 bg-gradient-to-b from-[#109696] to-[#1760C5] rounded-full"></span>
                Hasil Verifikasi
            </h3>

            {{-- TOMBOL AKTIVASI LANGSUNG --}}
            @if($adalahPendaftaranPertama)
                <div class="mb-6 flex flex-col sm:flex-row sm:items-center gap-4 bg-emerald-50 text-emerald-800 text-sm p-5 rounded-xl border border-emerald-200">
                    <div class="flex items-center gap-3 flex-1">
                        <div class="w-10 h-10 bg-emerald-100 rounded-lg flex items-center justify-center text-emerald-600 flex-shrink-0">
                            <i class="fas fa-gift"></i>
                        </div>
                        <span class="font-medium">Ini adalah <strong>pendaftaran pertama kali</strong> oleh desa ini. Langsung aktifkan atau kembalikan jika perlu perbaikan.</span>
                    </div>
                    <form action="/admin/aktivasi/proses/{{ $pengajuan->id_pengajuan }}" method="POST" class="flex-shrink-0">
                        @csrf
                        <button type="submit" 
                                class="js-confirm-btn bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2.5 px-6 rounded-xl shadow-sm transition inline-flex items-center text-sm"
                                data-confirm-message="Pendaftaran pertama kali (GRATIS). Yakin ingin langsung mengaktifkan domain ini?">
                            <i class="fas fa-check-circle mr-2"></i> Aktivasikan Domain
                        </button>
                    </form>
                </div>
            @endif

            {{-- FORM VERIFIKASI --}}
            <form action="{{ route('admin.verifikasi.proses', $pengajuan->id_pengajuan) }}" method="POST" id="formVerifikasi" class="bg-slate-50 p-5 rounded-xl border border-slate-100">
                @csrf
                @method('PUT')

                <div class="flex flex-col md:flex-row md:items-center gap-4 mb-5">
                    @if(!$adalahPendaftaranPertama)
                        <label class="flex items-center gap-2 cursor-pointer bg-white px-4 py-2.5 rounded-lg border border-slate-200 has-[:checked]:border-[#109696] has-[:checked]:bg-[#109696]/5 transition">
                            <input type="radio" name="status" value="diproses" id="diproses" required class="accent-[#109696]">
                            <span class="text-sm font-medium text-slate-700">Diproses</span>
                        </label>

                        <label class="flex items-center gap-2 cursor-pointer bg-white px-4 py-2.5 rounded-lg border border-slate-200 has-[:checked]:border-[#109696] has-[:checked]:bg-[#109696]/5 transition">
                            <input type="checkbox" name="kirim_email" id="kirim_email" class="accent-[#109696] rounded">
                            <span class="text-sm font-medium text-slate-700">Kirim konfirmasi pembayaran</span>
                        </label>
                    @endif

                    <label class="flex items-center gap-2 cursor-pointer bg-white px-4 py-2.5 rounded-lg border border-slate-200 has-[:checked]:border-rose-400 has-[:checked]:bg-rose-50 transition">
                        <input type="radio" name="status" value="perlu_perbaikan" id="perbaikan" required class="accent-rose-500">
                        <span class="text-sm font-medium text-slate-700">Perlu Perbaikan</span>
                    </label>
                </div>

                <textarea
                    name="catatan"
                    rows="3"
                    placeholder="Catatan (diisi jika memilih perlu perbaikan)..."
                    class="w-full border border-slate-200 bg-white p-3 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#109696]/20 focus:border-[#109696] transition mb-4"
                ></textarea>

                <div class="flex justify-end">
                    <button
                        type="submit"
                        class="js-confirm-btn bg-[#109696] hover:bg-[#0d7a7a] text-white px-6 py-2.5 rounded-xl text-sm font-bold shadow-sm transition"
                        data-confirm-message="Apakah anda yakin ingin mengirim verifikasi ini?"
                    >
                        Kirim Verifikasi
                    </button>
                </div>
            </form>

            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const diproses = document.getElementById('diproses');
                    const perbaikan = document.getElementById('perbaikan');
                    const kirim_email = document.getElementById('kirim_email');

                    if(diproses && perbaikan && kirim_email) {
                        diproses.addEventListener('change', function() { kirim_email.checked = true; });
                        perbaikan.addEventListener('change', function() { kirim_email.checked = false; });
                    }
                });
            </script>

            @elseif($pengajuan->status_pengajuan == 'diproses')
            
            @php
                $adaKonfirmasiDesa = $pengajuan->pesan->where('judul', 'Konfirmasi Pembayaran Disetujui')->isNotEmpty();
                $fakturSudahAda = $pengajuan->faktur->where('tipe', 'perpanjangan')->isNotEmpty();
            @endphp

            <div class="bg-sky-50 p-6 rounded-xl border border-sky-100">

                @if($adaKonfirmasiDesa && !$fakturSudahAda)
                    
                    @php
                        $pesanDurasi = \App\Models\Pesan::where('id_pengajuan', $pengajuan->id_pengajuan)
                            ->where('judul', 'Konfirmasi Pembayaran Disetujui')
                            ->whereNotNull('durasi_tahun')
                            ->latest()
                            ->first();
                            
                        $durasiDipilih = $pesanDurasi ? $pesanDurasi->durasi_tahun : 1;
                        
                        // ✅ PERHITUNGAN HARGA DENGAN PPN 11%
                        $subtotalHarga = $durasiDipilih * $hargaDasarPerTahun;
                        $ppnHarga = $subtotalHarga * ($ppnPersen / 100);
                        $totalHarga = $subtotalHarga + $ppnHarga;
                        
                        $estimasiBerlaku = \Carbon\Carbon::now()->addYears($durasiDipilih)->format('d M Y');
                    @endphp

                    <div class="flex items-start gap-4 mb-5">
                        <div class="w-10 h-10 bg-emerald-100 rounded-lg flex items-center justify-center text-emerald-600 flex-shrink-0 mt-0.5">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <div>
                            <h4 class="font-bold text-slate-800">Desa Menyetujui Pembayaran</h4>
                            <p class="text-sm text-slate-500 mt-1">
                                Desa memilih masa berlaku <strong class="text-[#1760C5]">{{ $durasiDipilih }} Tahun</strong> 
                                <span class="text-slate-400">(Estimasi aktif hingga <strong>{{ $estimasiBerlaku }}</strong>)</span>
                            </p>
                            
                            {{-- ✅ RINCIAN HARGA DENGAN PPN --}}
                            <div class="mt-3 p-3 bg-white rounded-lg border border-slate-200 inline-block">
                                <div class="flex items-center gap-6 text-sm">
                                    <div>
                                        <span class="text-slate-400 text-xs">Biaya ({{ $durasiDipilih }} thn × Rp {{ number_format($hargaDasarPerTahun, 0, ',', '.') }})</span>
                                        <p class="font-semibold text-slate-700">Rp {{ number_format($subtotalHarga, 0, ',', '.') }}</p>
                                    </div>
                                    <div>
                                        <span class="text-emerald-500 text-xs">PPN {{ $ppnPersen }}%</span>
                                        <p class="font-semibold text-emerald-600">Rp {{ number_format($ppnHarga, 0, ',', '.') }}</p>
                                    </div>
                                    <div class="pl-4 border-l border-slate-200">
                                        <span class="text-[#1760C5] text-xs font-bold">TOTAL</span>
                                        <p class="font-extrabold text-[#1760C5] text-lg">Rp {{ number_format($totalHarga, 0, ',', '.') }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <form action="{{ route('admin.faktur.store', $pengajuan->id_pengajuan) }}" method="POST" class="flex justify-end">
                        @csrf
                        <input type="hidden" name="durasi_tahun" value="{{ $durasiDipilih }}">
                        <input type="hidden" name="total_bayar" value="{{ $totalHarga }}">
                        
                        <button type="submit" 
                                class="js-confirm-btn bg-[#1760C5] hover:bg-[#1250a5] text-white font-bold py-2.5 px-6 rounded-xl shadow-sm transition inline-flex items-center text-sm"
                                data-confirm-message="Terbitkan faktur {{ $durasiDipilih }} tahun senilai Rp {{ number_format($totalHarga, 0, ',', '.') }} (sudah termasuk PPN 11%)?">
                            <i class="fas fa-print mr-2"></i> Terbitkan Faktur
                        </button>
                    </form>

                @elseif($fakturSudahAda)

                    <div class="flex items-center gap-3">
                        <i class="fas fa-file-invoice text-sky-500"></i>
                        <div>
                            <p class="text-sky-800 font-semibold text-sm">Faktur perpanjangan telah diterbitkan.</p>
                            <a href="{{ route('admin.faktur.index') }}" class="text-xs text-sky-600 hover:underline mt-1 inline-block">Lihat di Manajemen Faktur →</a>
                        </div>
                    </div>

                @else

                    <div class="flex items-center gap-3 text-sky-700">
                        <i class="fas fa-hourglass-half animate-pulse"></i>
                        <p class="font-semibold text-sm">Menunggu persetujuan desa untuk penerbitan faktur perpanjangan.</p>
                    </div>

                @endif

            </div>

            @elseif($pengajuan->status_pengajuan == 'menunggu_aktivasi')

            <div class="bg-gradient-to-br from-[#109696]/5 to-[#1760C5]/5 p-6 rounded-2xl border-2 border-[#109696]/20 mt-2">
                <div class="flex flex-col lg:flex-row lg:items-end lg:justify-between gap-6">
                    
                    <div class="flex-1">
                        <div class="flex items-center gap-3 mb-5">
                            <div class="w-10 h-10 bg-[#109696]/10 rounded-lg flex items-center justify-center text-[#109696] flex-shrink-0">
                                <i class="fas fa-shield-alt"></i>
                            </div>
                            <div>
                                <h3 class="font-bold text-slate-800">Domain Siap Diaktifkan</h3>
                                <p class="text-sm text-slate-500">Tentukan masa berlaku domain pada inputan di bawah.</p>
                            </div>
                        </div>
                        
                        <form action="/admin/aktivasi/proses/{{ $pengajuan->id_pengajuan }}" method="POST" id="formAktivasi" class="bg-white rounded-xl p-5 border border-slate-200 shadow-sm" novalidate>
                            @csrf
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-semibold text-slate-500 mb-1.5">Tanggal Mulai Aktif <span class="text-rose-500">*</span></label>
                                    <input type="date" name="tgl_mulai" id="inputTglMulai" value="{{ date('Y-m-d') }}" required class="w-full border border-slate-200 rounded-lg text-sm p-2.5 focus:outline-none focus:ring-2 focus:ring-[#109696]/20 focus:border-[#109696] transition">
                                    <p id="errTglMulai" class="text-rose-500 text-xs mt-1 hidden"><i class="fas fa-exclamation-circle mr-1"></i>Tanggal mulai wajib diisi.</p>
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-slate-500 mb-1.5">Tanggal Berlaku Sampai <span class="text-rose-500">*</span></label>
                                    <input type="date" name="tgl_selesai" id="inputTglSelesai" required class="w-full border border-slate-200 rounded-lg text-sm p-2.5 focus:outline-none focus:ring-2 focus:ring-[#109696]/20 focus:border-[#109696] transition">
                                    <p id="errTglSelesai" class="text-rose-500 text-xs mt-1 hidden"><i class="fas fa-exclamation-circle mr-1"></i>Tanggal berlaku sampai wajib diisi.</p>
                                    <p id="errTglSelesaiInvalid" class="text-rose-500 text-xs mt-1 hidden"><i class="fas fa-exclamation-circle mr-1"></i>Tanggal selesai harus lebih dari tanggal mulai.</p>
                                </div>
                            </div>
                            {{-- Info durasi yang dipilih desa --}}
                            @if($durasiDipilihSidebar)
                            <div class="mt-4 flex items-center gap-2 p-3 rounded-lg bg-[#1760C5]/5 border border-[#1760C5]/10">
                                <i class="fas fa-info-circle text-[#1760C5] text-xs"></i>
                                <p class="text-xs text-[#1760C5] font-medium">Desa memilih durasi <strong>{{ $durasiDipilihSidebar }} tahun</strong>. Sesuaikan tanggal berlaku dengan durasi tersebut.</p>
                            </div>
                            @endif
                        </form>
                    </div>

                    <button type="submit" 
                            form="formAktivasi"
                            id="btnAktivasiDomain"
                            class="js-confirm-btn js-aktivasi-btn bg-[#109696] hover:bg-[#0d7a7a] text-white font-bold py-3 px-8 rounded-xl shadow-sm hover:shadow-md transition text-sm flex-shrink-0 h-fit"
                            data-confirm-message="Apakah Anda yakin ingin mengaktifkan domain ini sesuai tanggal yang diinput?">
                        <i class="fas fa-power-off mr-2"></i> Aktivasi Domain
                    </button>

                </div>
            </div>

            @elseif($finalStatus == 'aktif')
            <div class="bg-emerald-50 p-4 rounded-xl border border-emerald-100 flex items-center gap-3 mt-2">
                <i class="fas fa-check-circle text-emerald-500 text-lg"></i>
                <p class="text-emerald-700 font-semibold text-sm">Domain Sudah Aktif</p>
            </div>
            
            @elseif($finalStatus == 'kadaluarsa')
            <div class="bg-slate-100 p-4 rounded-xl border border-slate-200 flex items-center gap-3 mt-2">
                <i class="fas fa-exclamation-triangle text-slate-400 text-lg"></i>
                <p class="text-slate-600 font-semibold text-sm">
                    Masa berlaku domain ini telah kadaluarsa pada tanggal {{ \Carbon\Carbon::parse($latestAktivasi->masa_berlaku)->format('d M Y') }}.
                </p>
            </div>
            @endif

        </div>
    </div>
</div>

<!-- TOAST ALERT UNTUK VALIDASI -->
<div id="toastAlert" class="hidden fixed top-6 right-6 z-[60] animate-slide-down">
    <div class="flex items-start gap-3 bg-white border border-rose-200 shadow-xl rounded-xl p-4 min-w-[340px] max-w-[420px]">
        <div class="w-9 h-9 bg-rose-100 rounded-lg flex items-center justify-center flex-shrink-0 mt-0.5">
            <i class="fas fa-exclamation-triangle text-rose-500 text-sm"></i>
        </div>
        <div class="flex-1 min-w-0">
            <p class="font-bold text-slate-800 text-sm">Validasi Gagal</p>
            <p id="toastMessage" class="text-slate-500 text-xs mt-1 leading-relaxed"></p>
        </div>
        <button onclick="document.getElementById('toastAlert').classList.add('hidden')" class="text-slate-300 hover:text-slate-500 transition flex-shrink-0 mt-0.5">
            <i class="fas fa-times text-xs"></i>
        </button>
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

<style>
    @keyframes slide-down {
        from { opacity: 0; transform: translateY(-16px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-slide-down {
        animation: slide-down 0.3s ease-out;
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('confirmationModal');
    const yesBtn = document.getElementById('modalYesBtn');
    const noBtn = document.getElementById('modalNoBtn');
    const modalMessage = document.getElementById('modalConfirmMessage');
    const confirmBtns = document.querySelectorAll('.js-confirm-btn');

    // Elemen validasi form aktivasi
    const inputTglMulai = document.getElementById('inputTglMulai');
    const inputTglSelesai = document.getElementById('inputTglSelesai');
    const errTglMulai = document.getElementById('errTglMulai');
    const errTglSelesai = document.getElementById('errTglSelesai');
    const errTglSelesaiInvalid = document.getElementById('errTglSelesaiInvalid');
    const toastAlert = document.getElementById('toastAlert');
    const toastMessage = document.getElementById('toastMessage');

    let formToSubmit = null;

    // Fungsi tampilkan toast alert
    function showToast(message) {
        toastMessage.textContent = message;
        toastAlert.classList.remove('hidden');
        // Auto-hide setelah 5 detik
        clearTimeout(showToast._timer);
        showToast._timer = setTimeout(function() {
            toastAlert.classList.add('hidden');
        }, 5000);
    }

    // Fungsi validasi form aktivasi
    function validateAktivasiForm() {
        if (!inputTglMulai || !inputTglSelesai) return true; // bukan halaman aktivasi

        let isValid = true;

        // Reset semua error
        errTglMulai.classList.add('hidden');
        errTglSelesai.classList.add('hidden');
        errTglSelesaiInvalid.classList.add('hidden');
        inputTglMulai.classList.remove('border-rose-400', 'bg-rose-50/50');
        inputTglSelesai.classList.remove('border-rose-400', 'bg-rose-50/50');

        // Cek tgl_mulai kosong
        if (!inputTglMulai.value) {
            errTglMulai.classList.remove('hidden');
            inputTglMulai.classList.add('border-rose-400', 'bg-rose-50/50');
            isValid = false;
        }

        // Cek tgl_selesai kosong
        if (!inputTglSelesai.value) {
            errTglSelesai.classList.remove('hidden');
            inputTglSelesai.classList.add('border-rose-400', 'bg-rose-50/50');
            isValid = false;
        }

        // Jika keduanya terisi, cek logika tanggal
        if (inputTglMulai.value && inputTglSelesai.value) {
            const tglMulai = new Date(inputTglMulai.value);
            const tglSelesai = new Date(inputTglSelesai.value);

            if (tglSelesai <= tglMulai) {
                errTglSelesaiInvalid.classList.remove('hidden');
                inputTglSelesai.classList.add('border-rose-400', 'bg-rose-50/50');
                isValid = false;
            }
        }

        // Tampilkan toast jika tidak valid
        if (!isValid) {
            if (!inputTglSelesai.value) {
                showToast('Tanggal berlaku sampai wajib diisi. Domain tidak dapat diaktifkan tanpa masa berlaku.');
            } else if (!inputTglMulai.value) {
                showToast('Tanggal mulai aktif wajib diisi.');
            } else {
                showToast('Tanggal selesai harus lebih dari tanggal mulai.');
            }
        }

        return isValid;
    }

    // Hapus error state saat user mulai mengetik
    if (inputTglMulai) {
        inputTglMulai.addEventListener('input', function() {
            errTglMulai.classList.add('hidden');
            this.classList.remove('border-rose-400', 'bg-rose-50/50');
        });
    }
    if (inputTglSelesai) {
        inputTglSelesai.addEventListener('input', function() {
            errTglSelesai.classList.add('hidden');
            errTglSelesaiInvalid.classList.add('hidden');
            this.classList.remove('border-rose-400', 'bg-rose-50/50');
        });
    }

    confirmBtns.forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();

            // Cek apakah ini tombol aktivasi domain (ada form="formAktivasi")
            const isAktivasiBtn = this.classList.contains('js-aktivasi-btn');

            // Jika tombol aktivasi, jalankan validasi dulu
            if (isAktivasiBtn && !validateAktivasiForm()) {
                return; // Stop, jangan tampilkan modal konfirmasi
            }

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