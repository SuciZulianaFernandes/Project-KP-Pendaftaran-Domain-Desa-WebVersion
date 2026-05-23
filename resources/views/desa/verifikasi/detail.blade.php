@extends('layouts.desa') 
@section('title', 'Detail Pengajuan')

@section('content')

@php
    // --- FIX: Ambil data aktivasi TERBARU berdasarkan masa berlaku ---
    // Ini untuk memastikan kita membaca data perpanjangan terakhir, bukan data lama yang kadaluarsa.
    $latestAktivasi = \App\Models\Aktivasi::where('id_pengajuan', $pengajuan->id_pengajuan)
                            ->orderBy('masa_berlaku', 'desc')
                            ->first();

    // Tentukan status akhir berdasarkan relasi aktivasi TERBARU
    $finalStatus = $pengajuan->status_pengajuan;
    
    // Jika status pengajuan sudah 'aktif', cek tabel aktivasi TERBARU
    if ($pengajuan->status_pengajuan == 'aktif' && $latestAktivasi) {
        $finalStatus = $latestAktivasi->status_akt;
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

                    {{-- LOGIKA WARNA DOT INDICATOR --}}
                    <span class="w-2 h-2 rounded-full
                        @if($finalStatus == 'ditinjau') bg-yellow-500
                        @elseif($finalStatus == 'perlu_perbaikan') bg-red-500
                        @elseif($finalStatus == 'diproses') bg-blue-500
                        @elseif($finalStatus == 'menunggu_aktivasi') bg-orange-500
                        
                        {{-- LOGIKA KHUSUS DARI TABEL AKTIVASI --}}
                        @elseif($finalStatus == 'aktif') bg-green-600
                        @elseif($finalStatus == 'kadaluarsa') bg-gray-500
                        @elseif($finalStatus == 'nonaktif') bg-gray-400
                        
                        @else bg-gray-400
                        @endif">
                    </span>

                    <span class="text-gray-700">
                        Status :
                    </span>

                    {{-- LOGIKA WARNA TEKS STATUS --}}
                    <span class="font-semibold
                        @if($finalStatus == 'ditinjau') text-yellow-600
                        @elseif($finalStatus == 'perlu_perbaikan') text-red-600
                        @elseif($finalStatus == 'diproses') text-blue-600
                        @elseif($finalStatus == 'menunggu_aktivasi') text-orange-600
                        
                        {{-- LOGIKA KHUSUS DARI TABEL AKTIVASI --}}
                        @elseif($finalStatus == 'aktif') text-green-600
                        @elseif($finalStatus == 'kadaluarsa') text-gray-600
                        @elseif($finalStatus == 'nonaktif') text-gray-500
                        
                        @else text-gray-500
                        @endif">

                        {{-- LOGIKA DISPLAY TEKS --}}
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

            <!-- DOKUMEN (GAYA 100% SAMA DENGAN DETAIL PENGAJUAN ADMIN) -->
            <h3 class="font-semibold mb-4">
                Dokumen Persyaratan Domain
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6 text-sm">
                
                @foreach($pengajuan->dokumenPersyaratan as $dok)
                    
                    <div>
                        <!-- Baris List Sama Persis dengan Admin -->
                        <div class="flex justify-between border-b pb-2 gap-3">
                            <span class="text-gray-700">{{ $dok->jenis_dokumen }}</span>

                            <a href="{{ asset('storage/'.$dok->path_file) }}"
                            target="_blank"
                            class="text-red-600 text-xs whitespace-nowrap font-semibold hover:underline">
                                Lihat Dokumen
                            </a>
                        </div>

                        <!-- FORM UPLOAD PERBAIKAN (Hanya Muncul Jika Status Perlu Perbaikan) -->
                        @if($pengajuan->status_pengajuan == 'perlu_perbaikan')

                        <form
                            action="{{ route('verifikasi.updateDokumen', $dok->id) }}"
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

            {{-- RIWAYAT DATA FAKTUR (TAMBAHAN) --}}
            @if($pengajuan->faktur->isNotEmpty())

            <div class="mb-6 bg-gray-50 p-4 rounded border">

                <h3 class="font-bold text-lg mb-4">
                    Riwayat Data Faktur
                </h3>

                <div class="space-y-4">

                    @foreach($pengajuan->faktur as $fakturItem)

                    <div class="bg-white border rounded-xl p-4 shadow-sm hover:shadow-md transition duration-200">

                        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">

                            <!-- INFORMASI FAKTUR -->
                            <div class="space-y-1 text-sm">

                                <p class="font-semibold text-gray-800">
                                    {{ $fakturItem->no_invoice }}
                                </p>

                                <p class="text-gray-600">
                                    Total :
                                    <span class="font-medium text-gray-800">
                                        Rp {{ number_format($fakturItem->total,0,',','.') }}
                                    </span>
                                </p>

                                <p class="text-gray-600">
                                    Status :
                                    <span class="
                                        @if($fakturItem->status == 'belum_bayar') text-yellow-600
                                        @elseif($fakturItem->status == 'sudah_bayar') text-green-600
                                        @elseif($fakturItem->status == 'kedaluarsa') text-red-600
                                        @endif
                                        font-semibold
                                    ">
                                        {{ ucfirst(str_replace('_',' ',$fakturItem->status)) }}
                                    </span>
                                </p>

                            </div>

                            <!-- BUTTON DETAIL (ROUTING DIUBAH KE DESA) -->
                            <div class="flex justify-start md:justify-end">

                                <a href="{{ route('desa.faktur.show', $fakturItem->id) }}"
                                class="inline-flex items-center gap-2 bg-red-700 hover:bg-red-800 text-white text-sm font-semibold px-4 py-2 rounded-lg transition duration-200 shadow-sm">
                                    <i class="fas fa-eye"></i>
                                    Detail
                                </a>

                            </div>

                        </div>

                    </div>

                    @endforeach

                </div>

            </div>

            @endif
            <!-- END RIWAYAT FAKTUR -->

            <!-- STATUS INFO -->
            
            @php
                $fakturCollection = $pengajuan->faktur;
                $notifKonfirmasi = $pengajuan->pesan->where('judul', 'like', '%Konfirmasi Pembayaran%')->first();
                $isRequestSent = $notifKonfirmasi ? ($notifKonfirmasi->is_read == 1) : false;
            @endphp

            @if($fakturCollection && $fakturCollection->isNotEmpty() && $finalStatus == 'diproses')
                @php $latestFaktur = $fakturCollection->last(); @endphp
                
                <div class="bg-green-50 p-4 rounded border border-green-200 mt-4">
                    <p class="text-green-800 font-semibold mb-2">
                        Faktur telah tersedia, silahkan bayar dan upload bukti pembayaran di sini
                    </p>
                    <a href="{{ route('desa.faktur.show', $latestFaktur->id) }}" class="text-green-700 underline hover:text-green-900 text-sm font-medium">
                        (Klik disini untuk melihat detail faktur)
                    </a>
                </div>

            @elseif($pengajuan->status_pengajuan == 'diproses')
                
                @if($isRequestSent)
                    <div class="bg-blue-50 p-4 rounded border border-blue-200 mt-4">
                        <p class="text-blue-700 font-semibold">
                            Pengajuan sedang diproses oleh admin.
                        </p>
                    </div>
                @else
                    <div class="bg-orange-50 p-4 rounded border border-orange-200 mt-4">
                        <p class="text-orange-800 font-semibold mb-3">
                            Konfirmasi Pembayaran
                        </p>
                        <p class="text-sm text-orange-700 mb-3">
                            Pengajuan domain {{ $pengajuan->nama_domain }}.desa.id telah disetujui. Silakan klik tombol untuk mengirimkan faktur.
                        </p>
                        
                        <form action="{{ route('desa.konfirmasi.pembayaran', $pengajuan->id_pengajuan) }}" method="POST">
                            @csrf
                            <button class="bg-red-500 hover:bg-red-600 text-white text-sm px-4 py-2 rounded shadow">
                                Ya, Kirimkan Faktur
                            </button>
                        </form>
                    </div>
                @endif

            @elseif($pengajuan->status_pengajuan == 'ditinjau')
                <div class="bg-orange-50 p-4 rounded border border-orange-200 mt-4">
                    <p class="text-orange-700 font-semibold">
                        Pengajuan sedang ditinjau oleh admin.
                    </p>
                </div>

            @elseif($pengajuan->status_pengajuan == 'menunggu_aktivasi')

                <div class="bg-orange-50 p-4 rounded border border-orange-200 mt-4">
                    <p class="text-orange-700 font-semibold">
                        Pengajuan telah diverifikasi dan sedang menunggu aktivasi domain.
                    </p>
                </div>

            @elseif($pengajuan->status_pengajuan == 'aktif')
                
                @if($latestAktivasi && $latestAktivasi->status_akt == 'kadaluarsa')
                    <div class="bg-gray-100 p-4 rounded border border-gray-300 mt-4">
                        <p class="text-gray-700 font-semibold">
                            Masa berlaku domain ini telah kadaluarsa pada tanggal {{ \Carbon\Carbon::parse($latestAktivasi->masa_berlaku)->format('d M Y') }}. 
                        </p>
                    </div>
                @elseif($latestAktivasi && $latestAktivasi->status_akt == 'nonaktif')
                    <div class="bg-gray-100 p-4 rounded border border-gray-300 mt-4">
                        <p class="text-gray-700 font-semibold">
                            Domain saat ini dalam status nonaktif.
                        </p>
                    </div>
                @else
                    <div class="bg-green-50 p-4 rounded border border-green-200 mt-4">
                        <p class="text-green-700 font-semibold">
                            Domain sudah aktif dan dapat digunakan.
                        </p>
                    </div>
                @endif

            @elseif($pengajuan->status_pengajuan == 'kadaluarsa')

                <div class="bg-gray-100 p-4 rounded border border-gray-300 mt-4">
                    <p class="text-gray-700 font-semibold">
                        Masa berlaku domain ini telah kadaluarsa.
                    </p>
                </div>

            @endif

        </div>

    </div>

</div>
@endsection