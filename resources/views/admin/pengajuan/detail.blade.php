@extends('layouts.admin')
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
                {{ $pengajuan->status_pengajuan }}
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

        @foreach($pengajuan->dokumenPersyaratan as $dok)
            <div class="flex justify-between items-center bg-gray-50 p-3 rounded mb-2">
                <span>{{ $dok->jenis_dokumen }}</span>

                <a href="{{ asset('storage/'.$dok->path_file) }}" 
                   target="_blank"
                   class="text-blue-600 underline">
                    Lihat
                </a>
            </div>
        @endforeach
    </div>

    <!-- 🔥 FORM VERIFIKASI ADMIN -->
    <hr class="my-4">

    <form action="{{ route('admin.verifikasi.proses', $pengajuan->id_pengajuan) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-4">
            <label class="font-semibold">Pilih Status:</label><br>

            <label>
                <input type="radio" name="status" value="diproses" required>
                Diproses (kirim konfirmasi pembayaran)
            </label>

            <br>

            <label>
                <input type="radio" name="status" value="perlu_perbaikan">
                Perlu Perbaikan
            </label>
        </div>

        <div class="mb-4">
            <label class="font-semibold">Catatan (opsional)</label>
            <textarea name="catatan" class="w-full border p-2 rounded"></textarea>
        </div>

        <button class="bg-green-600 text-white px-4 py-2 rounded">
            Simpan
        </button>
    </form>

</div>
@endsection