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
@endphp

<div class="flex flex-col lg:flex-row gap-6">

    <!-- SIDEBAR KIRI -->
    <div class="w-full lg:w-64 flex-shrink-0 space-y-4">

        <!-- STATUS DOMAIN -->
        <div class="bg-white rounded-xl shadow border overflow-hidden">

            <div class="px-5 py-4 border-b bg-gray-50">
                <h3 class="font-semibold text-gray-800">
                    Status Domain
                </h3>
            </div>

            <div class="p-5 text-sm">

                <p class="mb-3">
                    <strong>Domain</strong><br>
                    {{ $pengajuan->nama_domain }}.desa.id
                </p>

                <p class="flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full
                        @if($finalStatus == 'ditinjau') bg-yellow-500
                        @elseif($finalStatus == 'perlu_perbaikan') bg-red-500
                        @elseif($finalStatus == 'diproses') bg-blue-500
                        @elseif($finalStatus == 'menunggu_aktivasi') bg-orange-500
                        @elseif($finalStatus == 'aktif') bg-green-600
                        @elseif($finalStatus == 'kadaluarsa') bg-gray-500
                        @elseif($finalStatus == 'nonaktif') bg-gray-400
                        @else bg-gray-400
                        @endif">
                    </span>

                    <span class="text-gray-700">
                        Status :
                    </span>

                    <span class="font-semibold
                        @if($finalStatus == 'ditinjau') text-yellow-600
                        @elseif($finalStatus == 'perlu_perbaikan') text-red-600
                        @elseif($finalStatus == 'diproses') text-blue-600
                        @elseif($finalStatus == 'menunggu_aktivasi') text-orange-600
                        @elseif($finalStatus == 'aktif') text-green-600
                        @elseif($finalStatus == 'kadaluarsa') text-gray-600
                        @elseif($finalStatus == 'nonaktif') text-gray-500
                        @else text-gray-500
                        @endif">
                        {{ ucfirst(str_replace('_', ' ', $finalStatus)) }}
                    </span>
                </p>

            </div>
        </div>

    </div>

    <!-- CONTENT KANAN -->
    <div class="flex-1">

        <div class="bg-white p-6 rounded-xl shadow">

            <!-- HEADER -->
            <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4 mb-6">

                <h2 class="text-xl font-bold text-gray-800">
                    Detail Pengajuan
                </h2>

                <a href="{{ url()->previous() }}" 
                   class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded inline-flex items-center justify-center">
                    <i class="fas fa-arrow-left mr-2"></i> Kembali
                </a>

            </div>

            <!-- INFORMASI INSTANSI (TETAP SEMULA) -->
            <h3 class="font-semibold mb-4">
                Informasi Instansi
            </h3>

            <div class="grid grid-cols-1 xl:grid-cols-2 gap-x-10 gap-y-3 text-sm mb-6">

                <div class="flex">
                    <span class="w-48 text-gray-600">Nama Organisasi</span>
                    <span class="w-4 text-center">:</span>
                    <span>{{ $pengajuan->nama_desa }}</span>
                </div>

                <div class="flex">
                    <span class="w-48 text-gray-600">Provinsi</span>
                    <span class="w-4 text-center">:</span>
                    <span>{{ $pengajuan->provinsi }}</span>
                </div>

                <div class="flex">
                    <span class="w-48 text-gray-600">Kabupaten</span>
                    <span class="w-4 text-center">:</span>
                    <span>{{ $pengajuan->kota_kabupaten }}</span>
                </div>

                <div class="flex">
                    <span class="w-48 text-gray-600">Kecamatan</span>
                    <span class="w-4 text-center">:</span>
                    <span>{{ $pengajuan->kecamatan }}</span>
                </div>

                <div class="flex">
                    <span class="w-48 text-gray-600">Desa</span>
                    <span class="w-4 text-center">:</span>
                    <span>{{ $pengajuan->desa_kelurahan }}</span>
                </div>

                <div class="flex">
                    <span class="w-48 text-gray-600">Telepon</span>
                    <span class="w-4 text-center">:</span>
                    <span>{{ $pengajuan->telepon }}</span>
                </div>

                <div class="flex">
                    <span class="w-48 text-gray-600">Faksimili</span>
                    <span class="w-4 text-center">:</span>
                    <span>{{ $pengajuan->faksimili }}</span>
                </div>

                <div class="flex">
                    <span class="w-48 text-gray-600">Kode Pos</span>
                    <span class="w-4 text-center">:</span>
                    <span>{{ $pengajuan->kode_pos }}</span>
                </div>

                <div class="flex">
                    <span class="w-48 text-gray-600">Email Registran</span>
                    <span class="w-4 text-center">:</span>
                    <span>{{ $pengajuan->email }}</span>
                </div>

                <div class="flex">
                    <span class="w-48 text-gray-600">Alamat</span>
                    <span class="w-4 text-center">:</span>
                    <span>{{ $pengajuan->alamat }}</span>
                </div>

                <div class="flex xl:col-span-2">
                    <span class="w-48 text-gray-600">Tanggal Pembuatan</span>
                    <span class="w-4 text-center">:</span>
                    <span>{{ $pengajuan->created_at->format('d M Y') }}</span>
                </div>

            </div>

                                    <!-- DOKUMEN -->
            <h3 class="font-semibold mb-4">
                Dokumen Persyaratan Domain
            </h3>

            @php
                // MAPPING LABEL: Mengubah key database menjadi teks yang rapi untuk dibaca admin
                $labelDokumen = [
                    'surat_permohonan' => 'Surat Permohonan Domain Desa',
                    'surat_kuasa' => 'Surat Kuasa dari Desa',
                    'perda_pembentukan_desa' => 'Dasar Hukum Pembentukan Desa / Surat Pelantikan Kepala Desa',
                    // Legacy (jika ada data lama)
                    'surat_penunjukan_pejabat' => 'Surat Penunjukan Pejabat',
                    'ktp_asn_pejabat' => 'Kartu Pegawai / KTP ASN'
                ];
            @endphp

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6 text-sm">

                @foreach($pengajuan->dokumenPersyaratan as $dok)

                <div class="flex flex-col border-b pb-2 gap-2">
                    
                    <div class="flex justify-between items-start gap-3">
                        
                        <div class="flex flex-col">
                            {{-- GANTI $dok->jenis_dokumen MENJADI $labelDokumen[...] --}}
                            <span class="text-gray-700 font-medium">{{ $labelDokumen[$dok->jenis_dokumen] ?? $dok->jenis_dokumen }}</span>
                            
                            {{-- LOGIKA NOTIFIKASI DOKUMEN DIPERBARUI --}}
                            @if($pengajuan->status_pengajuan == 'perlu_perbaikan' && $dok->updated_at > $pengajuan->updated_at)
                                <span class="mt-1 inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800">
                                    <svg class="mr-1.5 h-2 w-2 text-green-500" fill="currentColor" viewBox="0 0 8 8">
                                        <circle cx="4" cy="4" r="3" />
                                    </svg>
                                    Sudah Diperbarui
                                </span>
                            @endif
                        </div>

                        <a href="{{ route('dokumen.lihat', $dok->id_dokumen) }}"
                        target="_blank"
                        class="text-red-600 text-xs whitespace-nowrap font-semibold hover:underline flex items-center gap-1">
                            <i class="fas fa-eye"></i> Lihat
                        </a>
                    </div>

                    @if($pengajuan->status_pengajuan == 'perlu_perbaikan')
                        <p class="text-xs text-gray-400">
                            Diupdate: {{ $dok->updated_at->format('d M Y, H:i') }}
                        </p>
                    @endif

                </div>

                @endforeach

            </div>
            
           <!-- RIWAYAT DATA FAKTUR (GAYA DIUBAH MENJADI SAMA DENGAN SHOW PERPANJANG) -->
@if($pengajuan->faktur->isNotEmpty())

<div class="mb-6 bg-gray-50 p-4 rounded-xl border">

    <div class="flex items-center justify-between mb-4">

        <div>
            <h3 class="text-lg font-semibold text-gray-800">
                Riwayat Data Faktur
            </h3>

            <p class="text-sm text-gray-500">
                Daftar faktur yang berkaitan dengan domain ini.
            </p>
        </div>

    </div>

    <div class="space-y-4">

        @foreach($pengajuan->faktur as $fakturItem)

        <div class="bg-white border rounded-xl p-4 shadow-sm hover:shadow-md transition duration-200">

            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">

                <!-- INFORMASI -->
                <div class="space-y-2 text-sm">

                    <div>
                        <p class="text-gray-500 text-xs">
                            Nomor Invoice
                        </p>

                        <p class="font-semibold text-gray-800">
                            {{ $fakturItem->no_invoice }}
                        </p>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-2">

                        <div>
                            <p class="text-gray-500 text-xs">
                                Total Tagihan
                            </p>

                            <p class="font-medium text-gray-800">
                                Rp {{ number_format($fakturItem->total,0,',','.') }}
                            </p>
                        </div>

                        <div>
                            <p class="text-gray-500 text-xs">
                                Status Pembayaran
                            </p>

                            <p class="font-semibold
                                @if($fakturItem->status == 'belum_bayar') text-yellow-600
                                @elseif($fakturItem->status == 'sudah_bayar') text-green-600
                                @elseif($fakturItem->status == 'kedaluarsa') text-red-600
                                @endif">

                                {{ ucfirst(str_replace('_',' ',$fakturItem->status)) }}
                            </p>
                        </div>

                    </div>

                </div>

                <!-- BUTTON -->
                <div class="flex justify-start lg:justify-end">

                    <a href="{{ route('admin.faktur.show', $fakturItem->id) }}"
                    class="inline-flex items-center gap-2 bg-red-700 hover:bg-red-800 text-white text-sm font-semibold px-4 py-2 rounded-lg transition duration-200 shadow-sm">

                        <i class="fas fa-eye"></i>

                        Detail Faktur
                    </a>

                </div>

            </div>

        </div>

        @endforeach

    </div>

</div>

@endif

            <hr class="my-4">

                        <!-- VERIFIKASI -->
            @if(in_array($pengajuan->status_pengajuan, ['ditinjau', 'perlu_perbaikan']))

            @php
                // LOGIKA BARU: Cek apakah desa ini PERNAH punya pengajuan lain yang pernah diproses/aktif
                $pernahDaftarSebelumnya = \App\Models\Pengajuan::where('id_user', $pengajuan->id_user)
                    ->where('id_pengajuan', '!=', $pengajuan->id_pengajuan) // Kecuali pengajuan ini sendiri
                    ->whereIn('status_pengajuan', ['aktif', 'diproses', 'menunggu_aktivasi', 'kadaluarsa'])
                    ->exists();

                // Jika belum pernah sama sekali, maka dia boleh dapat gratis
                $adalahPendaftaranPertama = !$pernahDaftarSebelumnya;
            @endphp

            <h3 class="font-semibold mb-3">Hasil Verifikasi</h3>

            {{-- TOMBOL AKTIVASI LANGSUNG (HANYA UNTUK PENDAFTARAN PERTAMA KALI SEUMUR HIDUP DESA) --}}
            @if($adalahPendaftaranPertama)
                <div class="mb-4 flex flex-col sm:flex-row sm:items-center gap-3 bg-green-50 text-green-700 text-sm px-4 py-3 rounded-lg border border-green-200">
                    <i class="fas fa-gift text-lg"></i>
                    <span class="flex-1">Ini adalah <strong>pendaftaran pertama kali</strong> oleh desa ini. Langsung aktifkan atau kembalikan jika perlu perbaikan.</span>
                    <form action="/admin/aktivasi/proses/{{ $pengajuan->id_pengajuan }}" method="POST" class="inline-block flex-shrink-0">
                        @csrf
                        <button type="submit" 
                                class="js-confirm-btn bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-5 rounded shadow transition duration-200 inline-flex items-center text-sm"
                                data-confirm-message="Pendaftaran pertama kali (GRATIS). Yakin ingin langsung mengaktifkan domain ini?">
                            <i class="fas fa-check-circle mr-2"></i> Aktivasikan Domain
                        </button>
                    </form>
                </div>
            @endif

            {{-- FORM VERIFIKASI (WAJIB DIPILIH JIKA BUKAN PENDAFTARAN PERTAMA) --}}
            <form action="{{ route('admin.verifikasi.proses', $pengajuan->id_pengajuan) }}" method="POST" id="formVerifikasi">

                @csrf
                @method('PUT')

                <div class="flex flex-col md:flex-row md:items-center gap-4 md:gap-6 mb-4">

                    @if(!$adalahPendaftaranPertama)
                        <!-- HANYA MUNCUL JIKA BUKAN PENDAFTARAN PERTAMA (WAJIB DIPROSES UNTUK FAKTUR) -->
                        <label>
                            <input type="radio" name="status" value="diproses" id="diproses" required>
                            Diproses
                        </label>

                        <label class="flex items-center gap-2">
                            <input type="checkbox" name="kirim_email" id="kirim_email">
                            Kirim konfirmasi pembayaran
                        </label>
                    @endif

                    <label>
                        <input type="radio" name="status" value="perlu_perbaikan" id="perbaikan" required>
                        Perlu Perbaikan
                    </label>

                </div>

                <textarea
                    name="catatan"
                    placeholder="Catatan (diisi jika memilih perlu perbaikan)..."
                    class="w-full border p-2 rounded mb-4"
                ></textarea>

                <div class="flex justify-end">
                    <button
                        type="submit"
                        class="js-confirm-btn bg-red-700 text-white px-6 py-2 rounded text-sm"
                        data-confirm-message="Apakah anda yakin ingin mengirim verifikasi ini?"
                    >
                        Kirim
                    </button>
                </div>
            </form>

            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const diproses = document.getElementById('diproses');
                    const perbaikan = document.getElementById('perbaikan');
                    const kirim_email = document.getElementById('kirim_email');

                    if(diproses && perbaikan && kirim_email) {
                        diproses.addEventListener('change', function() {
                            kirim_email.checked = true;
                        });

                        perbaikan.addEventListener('change', function() {
                            kirim_email.checked = false;
                        });
                    }
                });
            </script>

            @elseif($pengajuan->status_pengajuan == 'diproses')
            
            @php
                $adaKonfirmasiDesa = $pengajuan->pesan->where('judul', 'Konfirmasi Pembayaran Disetujui')->isNotEmpty();
                $fakturSudahAda = $pengajuan->faktur->where('tipe', 'perpanjangan')->isNotEmpty();
            @endphp

            <div class="bg-blue-50 p-4 rounded border border-blue-200">

                                    @if($adaKonfirmasiDesa && !$fakturSudahAda)
                    
                    @php
                        // TAMBAHKAN whereNotNull AGAR TIDAK KETEMU PESAN LAMA YANG NULL
                        $pesanDurasi = \App\Models\Pesan::where('id_pengajuan', $pengajuan->id_pengajuan)
                            ->where('judul', 'Konfirmasi Pembayaran Disetujui')
                            ->whereNotNull('durasi_tahun') // Hanya ambil yang benar-benar baru memilih tahun
                            ->latest()
                            ->first();
                            
                        $durasiDipilih = $pesanDurasi ? $pesanDurasi->durasi_tahun : 1;
                        $hargaPerTahun = 50000;
                        $totalHarga = $durasiDipilih * $hargaPerTahun;
                        
                        // HITUNG ESTIMASI TANGGAL BERLAKU UNTUK PREVIEW ADMIN
                        $estimasiBerlaku = \Carbon\Carbon::now()->addYears($durasiDipilih)->format('d M Y');
                    @endphp

                    <div class="flex items-center gap-3 mb-3">
                        <div class="bg-green-100 p-2 rounded-full text-green-600">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <div>
                            <h4 class="font-bold text-gray-800">Desa Menyetujui Pembayaran</h4>
                            <p class="text-sm text-gray-600">
                                Desa memilih masa berlaku <strong class="text-red-700">{{ $durasiDipilih }} Tahun</strong> 
                                <span class="text-gray-500">(Estimasi aktif hingga <strong>{{ $estimasiBerlaku }}</strong>)</span> 
                                dengan total tagihan <strong class="text-red-700">Rp {{ number_format($totalHarga, 0, ',', '.') }}</strong>. 
                                Silakan terbitkan faktur.
                            </p>
                        </div>
                    </div>

                    <form action="{{ route('admin.faktur.store', $pengajuan->id_pengajuan) }}" method="POST" class="mt-2">
                        @csrf
                        <!-- KIRIM DURASI TAHUN KE CONTROLLER FAKTUR -->
                        <input type="hidden" name="durasi_tahun" value="{{ $durasiDipilih }}">
                        <input type="hidden" name="total_bayar" value="{{ $totalHarga }}">
                        
                        <button type="submit" 
                                class="js-confirm-btn bg-red-700 hover:bg-red-800 text-white font-bold py-2 px-6 rounded shadow transition duration-200 inline-flex items-center"
                                data-confirm-message="Terbitkan faktur {{ $durasiDipilih }} tahun senilai Rp {{ number_format($totalHarga, 0, ',', '.') }}?">
                            <i class="fas fa-print mr-2"></i> Terbitkan Faktur
                        </button>
                    </form>

                @elseif($fakturSudahAda)

                    <p class="text-blue-800 font-semibold mb-2">
                        Faktur perpanjangan telah diterbitkan. Menunggu pembayaran selesai sebelum dapat diaktivasi.
                    </p>

                    <a href="{{ route('admin.faktur.index') }}" class="text-sm underline hover:text-blue-700">
                        Lihat di Manajemen Faktur
                    </a>

                @else

                    <p class="text-blue-700 font-semibold flex items-center gap-2">
                        <i class="fas fa-hourglass-half"></i>
                        Menunggu persetujuan desa untuk penerbitan faktur perpanjangan.
                    </p>

                @endif

            </div>

                        @elseif($pengajuan->status_pengajuan == 'menunggu_aktivasi')

            <div class="bg-gradient-to-br from-emerald-50 to-white p-6 rounded-2xl border-2 border-green-200 shadow-sm mt-2">
                <div class="flex flex-col lg:flex-row lg:items-end lg:justify-between gap-6">
                    
                    <!-- Kiri: Info & Input Tanggal -->
                    <div class="flex-1">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="bg-green-100 p-2 rounded-lg text-green-600 flex-shrink-0">
                                <i class="fas fa-shield-alt text-lg"></i> <!-- Ikon di judul bukan di tombol -->
                            </div>
                            <div>
                                <h3 class="font-bold text-gray-800">Domain Siap Diaktifkan</h3>
                                <p class="text-sm text-gray-500">Tentukan masa berlaku domain pada inputan di bawah.</p>
                            </div>
                        </div>
                        
                        <form action="/admin/aktivasi/proses/{{ $pengajuan->id_pengajuan }}" method="POST" id="formAktivasi" class="bg-white rounded-xl p-4 border border-green-100 shadow-sm">
                            @csrf
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Mulai Aktif <span class="text-red-500">*</span></label>
                                    <input type="date" name="tgl_mulai" value="{{ date('Y-m-d') }}" required class="w-full border border-gray-300 rounded-lg shadow-sm focus:ring-green-500 focus:border-green-500 text-sm p-2.5">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Berlaku Sampai <span class="text-red-500">*</span></label>
                                    <input type="date" name="tgl_selesai" required class="w-full border border-gray-300 rounded-lg shadow-sm focus:ring-green-500 focus:border-green-500 text-sm p-2.5">
                                </div>
                            </div>
                        </form>
                    </div>

                    <button type="submit" 
                            form="formAktivasi"
                            class="js-confirm-btn bg-green-600 hover:bg-green-700 text-white font-semibold py-3 px-8 rounded-xl shadow-sm hover:shadow-md transition duration-200 text-sm flex-shrink-0 h-fit"
                            data-confirm-message="Apakah Anda yakin ingin mengaktifkan domain ini sesuai tanggal yang diinput?">
                        Aktivasi Domain
                    </button>

                </div>
            </div>

            @elseif($finalStatus == 'aktif')
            <div class="bg-green-50 p-4 rounded border border-green-200 mt-4">
                <p class="text-green-700 font-semibold">Domain Sudah Aktif</p>
            </div>
            
            @elseif($finalStatus == 'kadaluarsa')
            <div class="bg-gray-100 p-4 rounded border border-gray-300 mt-4">
                <p class="text-gray-700 font-semibold">
                    Masa berlaku domain ini telah kadaluarsa pada tanggal {{ \Carbon\Carbon::parse($latestAktivasi->masa_berlaku)->format('d M Y') }}.
                </p>
            </div>
            @endif

        </div>
    </div>
</div>

<!-- MODAL -->
<div id="confirmationModal" class="hidden fixed inset-0 bg-gray-900 bg-opacity-50 overflow-y-auto h-full w-full z-50 flex items-center justify-center">

    <div class="relative mx-auto p-5 border w-96 shadow-lg rounded-xl bg-white">

        <div class="mt-3 text-center">

            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-yellow-100 mb-4">

                <svg class="h-6 w-6 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">

                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>

                </svg>

            </div>

            <h3 class="text-lg leading-6 font-medium text-gray-900">
                Konfirmasi
            </h3>
            <div class="mt-2 px-7 py-3">

                <p id="modalConfirmMessage" class="text-sm text-gray-500">
                    Apakah anda yakin?
                </p>

            </div>

        </div>
        <div class="items-center px-4 py-3 flex justify-center gap-3">

            <button id="modalNoBtn"
            class="px-4 py-2 bg-gray-200 text-gray-800 text-base font-medium rounded-md shadow-sm hover:bg-gray-300">
                Batal
            </button>

            <button id="modalYesBtn"
            class="px-4 py-2 bg-green-600 text-white text-base font-medium rounded-md shadow-sm hover:bg-green-700">
                Ya, saya yakin
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
        if (formToSubmit) {
            formToSubmit.submit();
        }

        closeModal();
    });

    noBtn.addEventListener('click', function() {
        closeModal();
    });

    modal.addEventListener('click', function(e) {
        if (e.target === modal) {
            closeModal();
        }
    });

    function closeModal() {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        formToSubmit = null;
    }
});
</script>

@endsection