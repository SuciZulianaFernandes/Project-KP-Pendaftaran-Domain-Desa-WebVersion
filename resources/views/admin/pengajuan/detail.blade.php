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
                @elseif($pengajuan->status_pengajuan == 'diproses') bg-blue-500
                @elseif($pengajuan->status_pengajuan == 'menunggu_aktivasi') bg-orange-500
                @elseif($pengajuan->status_pengajuan == 'aktif') bg-green-600
                @endif">
                {{ ucfirst(str_replace('_', ' ', $pengajuan->status_pengajuan)) }}
            </span>
        </p>
    </div>

    <!-- INFORMASI DESA -->
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
                <a href="{{ asset('storage/'.$dok->path_file) }}" target="_blank" class="text-blue-600 underline">Lihat</a>
            </div>
        @endforeach
    </div>

    <!-- INFO FAKTUR (Opsional: Untuk memudahkan admin cek) -->
        <!-- INFO FAKTUR (Menampilkan semua faktur: Baru & Perpanjangan) -->
    @if($pengajuan->faktur->isNotEmpty())
    <div class="mb-6 bg-gray-50 p-4 rounded border">
        <h3 class="font-bold text-lg mb-3">Riwayat Data Faktur</h3>
        
        <div class="space-y-4">
            @foreach($pengajuan->faktur as $fakturItem)
                <div class="p-3 bg-white rounded border">
                    <div class="flex justify-between items-start mb-2">
                        <div>
                            <p class="font-semibold text-sm text-gray-700">
                                @if($fakturItem->tipe == 'perpanjangan')
                                    <span class="px-2 py-0.5 rounded text-xs bg-purple-100 text-purple-700 mr-1">Perpanjangan</span>
                                @else
                                    <span class="px-2 py-0.5 rounded text-xs bg-blue-100 text-blue-700 mr-1">Baru</span>
                                @endif
                                {{ $fakturItem->no_invoice }}
                            </p>
                        </div>
                        <span class="text-xs font-medium px-2 py-1 rounded 
                            @if($fakturItem->status == 'sudah_bayar') bg-green-100 text-green-700
                            @elseif($fakturItem->status == 'belum_bayar') bg-red-100 text-red-700
                            @else bg-gray-100 text-gray-700 @endif">
                            {{ ucfirst($fakturItem->status) }}
                        </span>
                    </div>
                    
                    <p class="text-sm text-gray-600 mb-1"><strong>Total:</strong> Rp {{ number_format($fakturItem->total, 0, ',', '.') }}</p>
                    
                    @if($fakturItem->bukti_pembayaran_path)
                        <a href="{{ asset('storage/'.$fakturItem->bukti_pembayaran_path) }}" target="_blank" class="text-blue-600 underline text-sm">
                            <i class="fas fa-file-image mr-1"></i>Lihat Bukti Bayar
                        </a>
                    @endif
                </div>
            @endforeach
        </div>
    </div>
    @endif

    <hr class="my-4">

    <!-- LOGIKA TAMPILAN BERDASARKAN STATUS -->
    
    <!-- 1. FORM VERIFIKASI (Muncul jika: ditinjau / perlu_perbaikan) -->
    @if(in_array($pengajuan->status_pengajuan, ['ditinjau', 'perlu_perbaikan']))
        <form action="{{ route('admin.verifikasi.proses', $pengajuan->id_pengajuan) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-4">
                <label class="font-semibold">Pilih Status:</label><br>
                <label><input type="radio" name="status" value="diproses" required> Diproses</label>
                <label class="ml-4"><input type="radio" name="status" value="perlu_perbaikan"> Perlu Perbaikan</label>
            </div>
            <div class="mb-4">
                <label class="font-semibold">Catatan</label>
                <textarea name="catatan" class="w-full border p-2 rounded">{{ $pengajuan->catatan_umum }}</textarea>
            </div>
            <button class="bg-green-600 text-white px-4 py-2 rounded">Simpan Status</button>
        </form>

    <!-- 2. TOMBOL AKTIVASI (Muncul jika: menunggu_aktivasi) -->
    @elseif($pengajuan->status_pengajuan == 'menunggu_aktivasi')
        <div class="bg-green-50 p-4 rounded border border-green-200">
            <p class="mb-3 font-semibold text-green-800">Bukti pembayaran sudah dikirim. Silakan aktivasi domain.</p>
            
            <form action="{{ route('admin.aktivasi.proses', $pengajuan->id_pengajuan) }}" method="POST">
                @csrf
                <button class="bg-green-700 text-white px-6 py-2 rounded shadow hover:bg-green-800 font-bold">
                    Aktivasi Domain
                </button>
            </form>
        </div>

    <!-- 3. SUDAH AKTIF -->
    @elseif($pengajuan->status_pengajuan == 'aktif')
        <div class="text-green-700 font-bold bg-green-100 p-3 rounded inline-block">
            Domain Sudah Aktif
        </div>
    @endif

</div>
@endsection