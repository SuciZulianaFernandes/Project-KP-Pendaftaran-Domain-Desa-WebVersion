@extends('layouts.desa')
@section('title', 'Detail Pengajuan')

@section('content')
<div class="bg-white p-6 rounded-xl shadow">

    <h2 class="text-xl font-bold mb-6">Detail Pengajuan</h2>

    <!-- INFO DOMAIN -->
    <div class="mb-6">
        <p><strong>Domain:</strong> {{ $pengajuan->nama_domain }}.desa.id</p>
        <p>
            <strong>Status:</strong> 
            <span class="px-2 py-1 rounded text-white 
    @if($pengajuan->status_pengajuan == 'ditinjau') bg-yellow-500
    @elseif($pengajuan->status_pengajuan == 'perlu_perbaikan') bg-red-500
    @elseif($pengajuan->status_pengajuan == 'diproses') bg-green-500
    @endif">

    @if($pengajuan->status_pengajuan == 'diproses')
        diproses
    @else
        {{ $pengajuan->status_pengajuan }}
    @endif

</span>
        </p>
    </div>

    <!-- INFORMASI DESA -->
    <div class="mb-6">
        <h3 class="font-semibold mb-3">Informasi Desa</h3>

        <div class="grid grid-cols-2 gap-4 text-sm">
            <p><strong>Nama Desa:</strong> {{ $pengajuan->nama_desa }}</p>
            <p><strong>Telepon:</strong> {{ $pengajuan->telepon }}</p>
            <p><strong>Faksimili:</strong> {{ $pengajuan->faksimili }}</p>
            <p><strong>Kode Pos:</strong> {{ $pengajuan->kode_pos }}</p>
            <p><strong>Provinsi:</strong> {{ $pengajuan->provinsi }}</p>
            <p><strong>Kota/Kabupaten:</strong> {{ $pengajuan->kota_kabupaten }}</p>
            <p><strong>Kecamatan:</strong> {{ $pengajuan->kecamatan }}</p>
            <p><strong>Desa/Kelurahan:</strong> {{ $pengajuan->desa_kelurahan }}</p>
        </div>

        <p class="mt-2"><strong>Alamat:</strong> {{ $pengajuan->alamat }}</p>
    </div>

    <!-- DOKUMEN -->
    <div class="mb-6">
        <h3 class="font-semibold mb-3">Dokumen</h3>

        <ul class="space-y-2">
            @foreach($pengajuan->dokumenPersyaratan as $dok)
                <li class="flex justify-between items-center bg-gray-50 p-3 rounded">
                    <span>{{ $dok->jenis_dokumen }}</span>

                    <div class="flex gap-3">

                        <a href="{{ asset('storage/'.$dok->path_file) }}" 
                           target="_blank"
                           class="text-blue-600 hover:underline">
                            Lihat
                        </a>

                        <!-- 🔥 MUNCUL HANYA JIKA STATUS PERBAIKAN -->
                        @if($pengajuan->status_pengajuan == 'perlu_perbaikan')
                        <form action="{{ route('verifikasi.updateDokumen', $dok->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <input type="file" name="file" required class="text-sm">
                            <button class="text-green-600 text-sm">Upload</button>
                        </form>
                        @endif

                    </div>
                </li>
            @endforeach
        </ul>
    </div>

</div>
@endsection