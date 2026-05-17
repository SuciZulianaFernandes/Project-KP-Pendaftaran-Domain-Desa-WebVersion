@extends('layouts.desa') 
@section('title', 'Detail Pengajuan')

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

                <p class="flex items-center gap-2">

                    <span class="w-2 h-2 rounded-full
                        @if($pengajuan->status_pengajuan == 'ditinjau') bg-yellow-500
                        @elseif($pengajuan->status_pengajuan == 'perlu_perbaikan') bg-red-500
                        @elseif($pengajuan->status_pengajuan == 'diproses') bg-blue-500
                        @elseif($pengajuan->status_pengajuan == 'menunggu_aktivasi') bg-orange-500
                        @elseif($pengajuan->status_pengajuan == 'aktif') bg-green-600
                        @endif">
                    </span>

                    <span class="text-gray-700">
                        Status :
                    </span>

                    <span class="font-semibold
                        @if($pengajuan->status_pengajuan == 'ditinjau') text-yellow-600
                        @elseif($pengajuan->status_pengajuan == 'perlu_perbaikan') text-red-600
                        @elseif($pengajuan->status_pengajuan == 'diproses') text-blue-600
                        @elseif($pengajuan->status_pengajuan == 'menunggu_aktivasi') text-orange-600
                        @elseif($pengajuan->status_pengajuan == 'aktif') text-green-600
                        @endif">

                        {{ ucfirst(str_replace('_', ' ', $pengajuan->status_pengajuan)) }}

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

                <a href="{{ url('/desa/verifikasi') }}"
                class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded inline-flex items-center justify-center">
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

    <div class="bg-white border rounded-lg p-4">

        <div class="flex justify-between items-center gap-3 border-b pb-2">

            <span class="text-gray-700">
                {{ $dok->jenis_dokumen }}
            </span>

            <a
                href="{{ asset('storage/'.$dok->path_file) }}"
                target="_blank"
                class="text-red-600 text-xs whitespace-nowrap hover:underline"
            >
                Lihat Dokumen
            </a>

        </div>

        <!-- FORM UPLOAD PERBAIKAN -->
        @if($pengajuan->status_pengajuan == 'perlu_perbaikan')

        <form
            action="{{ route('verifikasi.updateDokumen', $dok->id) }}"
            method="POST"
            enctype="multipart/form-data"
            class="mt-4 space-y-3"
        >

            @csrf
            @method('PUT')

            <input
                type="file"
                name="file"
                required
                class="w-full text-sm border rounded-lg p-2 bg-gray-50"
            >

            <div class="flex justify-end">

                <button
                    type="submit"
                    class="bg-green-600 hover:bg-green-700 text-white text-sm px-4 py-2 rounded-lg"
                >
                    Upload
                </button>

            </div>

        </form>

        @endif

    </div>

    @endforeach

</div>

            <!-- STATUS INFO -->
            @if($pengajuan->status_pengajuan == 'diproses')

            <div class="bg-blue-50 p-4 rounded border border-blue-200 mt-4">

                <p class="text-blue-700 font-semibold">
                    Pengajuan sedang diproses oleh admin.
                </p>

            </div>

            @elseif($pengajuan->status_pengajuan == 'menunggu_aktivasi')

            <div class="bg-orange-50 p-4 rounded border border-orange-200 mt-4">

                <p class="text-orange-700 font-semibold">
                    Pengajuan telah diverifikasi dan sedang menunggu aktivasi domain.
                </p>

            </div>

            @elseif($pengajuan->status_pengajuan == 'aktif')

            <div class="bg-green-50 p-4 rounded border border-green-200 mt-4">

                <p class="text-green-700 font-semibold">
                    Domain sudah aktif dan dapat digunakan.
                </p>

            </div>

            @endif

        </div>

    </div>

</div>

@endsection