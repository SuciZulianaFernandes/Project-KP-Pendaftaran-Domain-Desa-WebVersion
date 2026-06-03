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

    // 1. Jika status pengajuan menunggu aktivasi
    if ($pengajuan->status_pengajuan == 'menunggu_aktivasi') {

        $finalStatus = 'menunggu_aktivasi';

    }
    // 2. Jika status aktif → cek aktivasi terbaru
    elseif ($pengajuan->status_pengajuan == 'aktif') {

        if ($latestAktivasi) {
            $finalStatus = $latestAktivasi->status_akt;
        } else {
            $finalStatus = 'aktif';
        }

    }
    // 3. Jika ada faktur belum bayar
    elseif ($hasUnpaidInvoice) {

        // Tetap diproses
        $finalStatus = 'diproses';

    }
    // 4. Jika ada permintaan perpanjangan & admin belum kirim faktur
    elseif ($perpanjanganMsg) {

        $fakturPerpanjangan = \App\Models\Faktur::where('id_pengajuan', $pengajuan->id_pengajuan)
            ->where('tipe', 'perpanjangan')
            ->where('created_at', '>', $perpanjanganMsg->created_at)
            ->exists();

        if (!$fakturPerpanjangan) {

            // Tetap diproses
            $finalStatus = 'diproses';

        } else {

            // Faktur sudah ada → tetap diproses
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

                    <span class="text-gray-700">Status :</span>

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

                <a href="{{ url()->previous() }}" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded inline-flex items-center justify-center">
                    <i class="fas fa-arrow-left mr-2"></i> Kembali
                </a>

            </div>

            <!-- INFORMASI INSTANSI -->
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

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6 text-sm">
                
                @foreach($pengajuan->dokumenPersyaratan as $dok)
                    
                    <div>
                        <div class="flex justify-between border-b pb-2 gap-3">
                            <span class="text-gray-700">{{ $dok->jenis_dokumen }}</span>

                            <a href="{{ route('dokumen.lihat', $dok->id_dokumen) }}"
                            target="_blank"
                            class="text-red-600 text-xs whitespace-nowrap font-semibold hover:underline">
                                Lihat Dokumen
                            </a>
                        </div>

                        @if($pengajuan->status_pengajuan == 'perlu_perbaikan')

                        <form
    action="{{ route('desa.verifikasi.updateDokumen', $dok->id_dokumen) }}"
    method="POST"
    enctype="multipart/form-data"
    class="mt-2 space-y-2"
>

                            @csrf
                            @method('PUT')

                            <input
                                type="file"
                                name="file"
                                required
                                class="w-full text-xs border rounded p-1 bg-gray-50"
                            >

                            <div class="flex justify-end">

                                <button
                                    type="submit"
                                    class="bg-green-600 hover:bg-green-700 text-white text-xs px-3 py-1 rounded"
                                >
                                    Upload
                                </button>

                            </div>

                        </form>

                        @endif
                    </div>

                @endforeach

            </div>

            {{-- RIWAYAT DATA FAKTUR --}}
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

                            <div class="flex justify-start lg:justify-end">

                                <a href="{{ route('desa.faktur.show', $fakturItem->id) }}"
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

            <!-- STATUS INFO -->

            {{-- DIPROSES --}}
            @if($finalStatus == 'diproses')

                @php
                    // Cek apakah desa sudah pernah mengkonfirmasi pembayaran (mengirim pesan ke admin)
                    $sudahKonfirmasiBayar = \App\Models\Pesan::where('id_pengajuan', $pengajuan->id_pengajuan)
                        ->where('role_tujuan', 'admin')
                        ->where('judul', 'Konfirmasi Pembayaran Disetujui')
                        ->exists();
                    
                    // Ambil faktur belum bayar terbaru (jika ada)
                    $fakturDesa = $pengajuan->faktur
                        ->where('status', 'belum_bayar')
                        ->sortByDesc('created_at')
                        ->first();

                    // Cek apakah ada faktur perpanjangan yang belum bayar
                    $fakturPerpanjanganAda = false;

                    if ($perpanjanganMsg) {
                        $fakturPerpanjanganAda = \App\Models\Faktur::where('id_pengajuan', $pengajuan->id_pengajuan)
                            ->where('tipe', 'perpanjangan')
                            ->where('created_at', '>', $perpanjanganMsg->created_at)
                            ->exists();
                    }
                @endphp

                {{-- SCENARIO 1: BELUM DIKLIK (Belum ada konfirmasi dari desa) --}}
                @if(!$sudahKonfirmasiBayar && !$fakturPerpanjanganAda)

                    <div class="bg-blue-50 p-4 rounded border border-blue-200 mt-4">
                        <p class="text-blue-800 font-semibold mb-3">
                            Pengajuan sedang diproses. Apakah Anda siap melakukan pembayaran?
                        </p>
                        <form action="{{ route('desa.konfirmasi.pembayaran', $pengajuan->id_pengajuan) }}" method="POST">
                            @csrf
                            <button class="js-confirm-btn bg-red-700 hover:bg-red-800 text-white font-bold py-2 px-6 rounded shadow transition duration-200 inline-flex items-center"
                                    data-confirm-message="Apakah Anda yakin ingin mengonfirmasi dan meminta faktur?">
                                <i class="fas fa-paper-plane mr-2"></i> Ya, Kirimkan Faktur
                            </button>
                        </form>
                    </div>

                {{-- SCENARIO 2: SUDAH DIKLIK / ADA FAKTUR --}}
                @else

                    <div class="bg-blue-50 p-4 rounded border border-blue-200 mt-4">
                        <p class="text-blue-800 font-semibold mb-2">
                            @if($hasUnpaidInvoice || $fakturPerpanjanganAda)
                                Faktur telah diterbitkan. Silahkan upload bukti pembayaran.
                            @else
                                Menunggu faktur dari admin kominfo
                            @endif
                        </p>

                        @if($fakturDesa)
                            <a href="{{ route('desa.faktur.show', $fakturDesa->id) }}" class="text-sm underline hover:text-blue-700">
                                Lihat Detail Faktur
                            </a>
                        @endif
                    </div>

                @endif

            {{-- MENUNGGU AKTIVASI --}}
            @elseif($finalStatus == 'menunggu_aktivasi')

                <div class="bg-orange-50 p-4 rounded border border-orange-200 mt-4">
                    <p class="text-orange-800 font-semibold">
                        Menunggu aktivasi dari admin kominfo
                    </p>
                </div>

            {{-- AKTIF --}}
            @elseif($finalStatus == 'aktif')

                <div class="bg-green-50 p-4 rounded border border-green-200 mt-4">
                    <p class="text-green-700 font-semibold">
                        Domain sudah aktif.
                    </p>
                </div>

            {{-- KADALUARSA --}}
            @elseif($finalStatus == 'kadaluarsa')

                <div class="bg-gray-100 p-4 rounded border border-gray-300 mt-4">
                    <p class="text-gray-700 font-semibold">
                        Masa berlaku domain ini telah kadaluarsa pada tanggal {{ \Carbon\Carbon::parse($latestAktivasi->masa_berlaku)->format('d M Y') }}.
                    </p>
                </div>

            {{-- NONAKTIF --}}
            @elseif($finalStatus == 'nonaktif')

                <div class="bg-gray-100 p-4 rounded border border-gray-300 mt-4">
                    <p class="text-gray-700 font-semibold">
                        Domain saat ini dalam status nonaktif.
                    </p>
                </div>

            {{-- DITINJAU --}}
            @elseif($finalStatus == 'ditinjau')

                <div class="bg-orange-50 p-4 rounded border border-orange-200 mt-4">
                    <p class="text-orange-700 font-semibold">
                        Pengajuan sedang ditinjau oleh admin.
                    </p>
                </div>

            {{-- PERLU PERBAIKAN --}}
            @elseif($finalStatus == 'perlu_perbaikan')

                <div class="bg-red-50 p-4 rounded border border-red-200 mt-4">
                    <p class="text-red-700 font-semibold">
                        Dokumen perlu diperbaiki sesuai catatan admin.
                    </p>
                </div>

            @endif

        </div>

    </div>

</div>
@endsection