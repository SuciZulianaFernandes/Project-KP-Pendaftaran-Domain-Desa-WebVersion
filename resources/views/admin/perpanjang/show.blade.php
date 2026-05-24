@extends('layouts.admin')
@section('title', 'Detail Perpanjangan Domain')

@section('content')

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

                @php
                    // Logika Status
                    $finalStatus = $pengajuan->status_pengajuan;

                    // Jika faktur belum ada, statusnya 'diproses'
                    if (!$faktur) {
                        $finalStatus = 'diproses';
                    } 
                    // Jika faktur ada tapi belum bayar, status 'diproses'
                    elseif ($faktur->status == 'belum_bayar') {
                        $finalStatus = 'diproses';
                    } 
                    // Jika status aktif, cek tabel aktivasi
                    elseif ($finalStatus == 'aktif' && $pengajuan->aktivasi) {
                        $finalStatus = $pengajuan->aktivasi->status_akt;
                    }
                @endphp

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

    <!-- CONTENT -->
    <div class="flex-1">

        <div class="bg-white p-6 rounded-xl shadow">

            <!-- HEADER -->
            <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4 mb-6">

                <div>
                    <h2 class="text-2xl font-bold text-gray-800">
                        Detail Perpanjangan Domain
                    </h2>

                    <p class="text-sm text-gray-500 mt-1">
                        Informasi lengkap perpanjangan domain desa.
                    </p>
                </div>

                <a href="{{ route('admin.perpanjang.list') }}"
                class="bg-gray-500 hover:bg-gray-600 text-white font-semibold py-2 px-4 rounded-lg inline-flex items-center justify-center transition">

                    <i class="fas fa-arrow-left mr-2"></i>
                    Kembali
                </a>

            </div>

            {{-- NOTIFIKASI: FAKTUR BELUM DIBUAT --}}
            @if(!$faktur)
                <div class="mb-6 bg-blue-50 p-5 rounded-xl border border-blue-200">
                    <h3 class="font-bold text-lg mb-2 text-blue-800">
                        Desa Menyetujui Pembayaran
                    </h3>
                    <p class="text-sm text-blue-700 mb-4">
                        Desa telah mengkonfirmasi kesiapan pembayaran. Silakan terbitkan faktur.
                    </p>
                    
                    <form action="{{ route('admin.faktur.store', $pengajuan->id_pengajuan) }}" method="POST" style="display:inline">
                        @csrf
                        <button type="submit" class="inline-flex items-center gap-2 bg-red-600 hover:bg-red-700 text-white text-sm font-semibold px-4 py-2 rounded-lg transition shadow-sm js-confirm-print">
                            <i class="fas fa-print"></i> Cetak Faktur
                        </button>
                    </form>
                </div>
            @endif

            <!-- INFORMASI INSTANSI -->
            <div class="mb-6">

                <div class="border-b pb-3 mb-5">
                    <h3 class="text-lg font-semibold text-gray-800">
                        Informasi Instansi
                    </h3>

                    <p class="text-sm text-gray-500 mt-1">
                        Detail data instansi dan informasi domain.
                    </p>
                </div>

                <div class="grid grid-cols-1 xl:grid-cols-2 gap-x-10 gap-y-4 text-sm">

                    <div class="flex flex-col sm:flex-row">
                        <span class="sm:w-52 text-gray-500 font-medium">Nama Organisasi</span>
                        <div class="flex">
                            <span class="hidden sm:inline w-4 text-center">:</span>
                            <span class="text-gray-800 break-words">{{ $pengajuan->nama_desa }}</span>
                        </div>
                    </div>

                    <div class="flex flex-col sm:flex-row">
                        <span class="sm:w-52 text-gray-500 font-medium">Provinsi</span>
                        <div class="flex">
                            <span class="hidden sm:inline w-4 text-center">:</span>
                            <span class="text-gray-800 break-words">{{ $pengajuan->provinsi }}</span>
                        </div>
                    </div>

                    <div class="flex flex-col sm:flex-row">
                        <span class="sm:w-52 text-gray-500 font-medium">Kabupaten</span>
                        <div class="flex">
                            <span class="hidden sm:inline w-4 text-center">:</span>
                            <span class="text-gray-800 break-words">{{ $pengajuan->kota_kabupaten }}</span>
                        </div>
                    </div>

                    <div class="flex flex-col sm:flex-row">
                        <span class="sm:w-52 text-gray-500 font-medium">Kecamatan</span>
                        <div class="flex">
                            <span class="hidden sm:inline w-4 text-center">:</span>
                            <span class="text-gray-800 break-words">{{ $pengajuan->kecamatan }}</span>
                        </div>
                    </div>

                    <div class="flex flex-col sm:flex-row">
                        <span class="sm:w-52 text-gray-500 font-medium">Desa</span>
                        <div class="flex">
                            <span class="hidden sm:inline w-4 text-center">:</span>
                            <span class="text-gray-800 break-words">{{ $pengajuan->desa_kelurahan }}</span>
                        </div>
                    </div>

                    <div class="flex flex-col sm:flex-row">
                        <span class="sm:w-52 text-gray-500 font-medium">Telepon</span>
                        <div class="flex">
                            <span class="hidden sm:inline w-4 text-center">:</span>
                            <span class="text-gray-800 break-words">{{ $pengajuan->telepon }}</span>
                        </div>
                    </div>

                    <div class="flex flex-col sm:flex-row">
                        <span class="sm:w-52 text-gray-500 font-medium">Faksimili</span>
                        <div class="flex">
                            <span class="hidden sm:inline w-4 text-center">:</span>
                            <span class="text-gray-800 break-words">{{ $pengajuan->faksimili }}</span>
                        </div>
                    </div>

                    <div class="flex flex-col sm:flex-row">
                        <span class="sm:w-52 text-gray-500 font-medium">Kode Pos</span>
                        <div class="flex">
                            <span class="hidden sm:inline w-4 text-center">:</span>
                            <span class="text-gray-800 break-words">{{ $pengajuan->kode_pos }}</span>
                        </div>
                    </div>

                    <div class="flex flex-col sm:flex-row">
                        <span class="sm:w-52 text-gray-500 font-medium">Email Registran</span>
                        <div class="flex">
                            <span class="hidden sm:inline w-4 text-center">:</span>
                            <span class="text-gray-800 break-words">{{ $pengajuan->email }}</span>
                        </div>
                    </div>

                    <div class="flex flex-col sm:flex-row xl:col-span-2">
                        <span class="sm:w-52 text-gray-500 font-medium">Alamat</span>
                        <div class="flex">
                            <span class="hidden sm:inline w-4 text-center">:</span>
                            <span class="text-gray-800 break-words">{{ $pengajuan->alamat }}</span>
                        </div>
                    </div>

                    <div class="flex flex-col sm:flex-row xl:col-span-2">
                        <span class="sm:w-52 text-gray-500 font-medium">Tanggal Pembuatan</span>
                        <div class="flex">
                            <span class="hidden sm:inline w-4 text-center">:</span>
                            <span class="text-gray-800 break-words">{{ $pengajuan->created_at->format('d M Y') }}</span>
                        </div>
                    </div>

                </div>

            </div>

            {{-- DATA PERPANJANGAN (Hanya muncul jika faktur sudah dibuat) --}}
            @if($faktur)
            <div class="mb-6 bg-gray-50 p-5 rounded-xl border">

                <div class="border-b pb-3 mb-5">
                    <h3 class="text-lg font-semibold text-gray-800">
                        Data Perpanjangan
                    </h3>

                    <p class="text-sm text-gray-500 mt-1">
                        Informasi faktur dan status pembayaran perpanjangan domain.
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-10 gap-y-4 text-sm">

                    <div class="flex flex-col sm:flex-row">
                        <span class="sm:w-44 text-gray-500 font-medium">No Invoice</span>
                        <div class="flex">
                            <span class="hidden sm:inline w-4 text-center">:</span>
                            <span class="font-semibold text-gray-800">{{ $faktur->no_invoice }}</span>
                        </div>
                    </div>

                    <div class="flex flex-col sm:flex-row">
                        <span class="sm:w-44 text-gray-500 font-medium">Tanggal Faktur</span>
                        <div class="flex">
                            <span class="hidden sm:inline w-4 text-center">:</span>
                            <span class="font-semibold text-gray-800">{{ $faktur->created_at->format('d M Y H:i') }}</span>
                        </div>
                    </div>

                    <div class="flex flex-col sm:flex-row">
                        <span class="sm:w-44 text-gray-500 font-medium">Status Pembayaran</span>
                        <div class="flex">
                            <span class="hidden sm:inline w-4 text-center">:</span>
                            <span class="font-semibold">
                                @if($faktur->status == 'sudah_bayar')
                                    <span class="text-green-600">Lunas</span>
                                @else
                                    <span class="text-red-600">Belum Lunas</span>
                                @endif
                            </span>
                        </div>
                    </div>

                    <div class="flex flex-col sm:flex-row">
                        <span class="sm:w-44 text-gray-500 font-medium">Total Tagihan</span>
                        <div class="flex">
                            <span class="hidden sm:inline w-4 text-center">:</span>
                            <span class="font-semibold text-gray-800">Rp {{ number_format($faktur->total,0,',','.') }}</span>
                        </div>
                    </div>

                </div>

            </div>
            @endif

            <!-- RIWAYAT DATA FAKTUR -->
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
                                    <p class="text-gray-500 text-xs">Nomor Invoice</p>
                                    <p class="font-semibold text-gray-800">{{ $fakturItem->no_invoice }}</p>
                                </div>

                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-2">

                                    <div>
                                        <p class="text-gray-500 text-xs">Total Tagihan</p>
                                        <p class="font-medium text-gray-800">Rp {{ number_format($fakturItem->total,0,',','.') }}</p>
                                    </div>

                                    <div>
                                        <p class="text-gray-500 text-xs">Status Pembayaran</p>
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

            <!-- RIWAYAT DOMAIN -->
            <div class="mb-6 bg-gray-50 p-5 rounded-xl border">

                <div class="border-b pb-3 mb-5">
                    <h3 class="text-lg font-semibold text-gray-800">
                        Riwayat Domain
                    </h3>

                    <p class="text-sm text-gray-500 mt-1">
                        Informasi masa aktif domain saat ini.
                    </p>
                </div>

                <div class="space-y-4 text-sm">

                    <div class="flex flex-col sm:flex-row">
                        <span class="sm:w-60 text-gray-500 font-medium">Tanggal Aktivasi Terakhir</span>
                        <div class="flex">
                            <span class="hidden sm:inline w-4 text-center">:</span>
                            <span class="font-semibold text-gray-800">
                                {{ $pengajuan->aktivasi ? $pengajuan->aktivasi->tgl_aktivasi->format('d M Y') : '-' }}
                            </span>
                        </div>
                    </div>

                    <div class="flex flex-col sm:flex-row">
                        <span class="sm:w-60 text-gray-500 font-medium">Masa Berlaku Hingga</span>
                        <div class="flex">
                            <span class="hidden sm:inline w-4 text-center">:</span>
                            <span class="font-semibold text-gray-800">
                                {{ $pengajuan->aktivasi ? $pengajuan->aktivasi->masa_berlaku->format('d M Y') : '-' }}
                            </span>
                        </div>
                    </div>

                </div>

            </div>

            <hr class="my-5">

            {{-- BAGIAN AKTIVASI (Hanya muncul jika faktur ada & status sesuai) --}}
            @if($faktur && $finalStatus == 'menunggu_aktivasi' && $faktur->status == 'sudah_bayar')

            <div class="bg-blue-50 p-5 rounded-xl border border-blue-200">

                <h3 class="font-bold text-lg mb-2 text-gray-800">
                    Aktivasi Perpanjangan
                </h3>
                <p class="text-sm text-gray-600 mb-4">
                    Status saat ini:
                    <strong>Menunggu Aktivasi</strong>.
                    Lakukan aktivasi untuk memperbarui masa berlaku domain.
                </p>

                <form action="/admin/aktivasi/proses/{{ $pengajuan->id_pengajuan }}" method="POST" id="formAktivasi">

                    @csrf

                    <div class="flex justify-end">

                        <button
                            type="submit"
                            class="js-confirm-btn bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-6 rounded-lg shadow transition duration-200"
                            data-confirm-message="Apakah Anda yakin ingin mengaktifkan perpanjangan domain ini?"
                        >

                            <i class="fas fa-check-circle mr-2"></i>
                            Aktivasikan Sekarang
                        </button>

                    </div>

                </form>

            </div>

            @elseif($faktur && $finalStatus == 'aktif')

            <div class="bg-green-50 p-4 rounded-xl border border-green-200">

                <p class="text-green-700 font-semibold">
                    Domain sudah aktif dan berhasil diperpanjang.
                </p>

            </div>

            @elseif($faktur && $finalStatus == 'kadaluarsa')

            <div class="bg-gray-100 p-4 rounded-xl border border-gray-300">

                <p class="text-gray-700 font-semibold">
                    Masa berlaku domain ini telah kadaluarsa 
                </p>

            </div>

            @elseif($faktur)

            <div class="bg-blue-50 p-4 rounded-xl border border-blue-200">

                <p class="text-blue-800 font-semibold mb-2">
                    Faktur telah diterbitkan. Menunggu pembayaran selesai sebelum dapat diaktivasi.
                </p>
                <a href="{{ route('admin.faktur.index') }}"
                class="text-sm underline hover:text-blue-700">

                    Lihat di Manajemen Faktur
                </a>

            </div>

            @endif

        </div>

    </div>

</div>

@endsection