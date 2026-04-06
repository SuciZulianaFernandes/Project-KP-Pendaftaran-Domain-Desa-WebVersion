@extends('layouts.admin')
@section('title', 'Faktur')

@section('content')
<div class="bg-white p-6 rounded-xl shadow"> 
    <h2 class="text-xl font-bold mb-2">Manajemen Faktur / Faktur</h2>
    
    <!-- Judul Invoice > #INV -->
    <div class="mb-6 text-sm text-gray-600 border-b pb-3">
        <span>Invoice</span>
        <span class="mx-2">></span>
        <span class="font-semibold text-gray-800">#{{ $faktur->no_invoice }}</span>
    </div>
    
    <form action="{{ route('admin.faktur.kirim', $faktur->id) }}" method="POST">
        @csrf
        
        <!-- Grid untuk 2 kolom utama -->
        <div class="grid grid-cols-2 gap-x-8">
            <!-- KOLOM KIRI -->
            <div class="space-y-4">
                <div>
                    <label for="no_faktur" class="block text-sm font-medium text-gray-700">Nomor Faktur</label>
                    <input type="text" id="no_faktur" name="no_faktur" value="{{ $faktur->no_invoice }}" readonly class="mt-1 block w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-md shadow-sm">
                </div>

                <div>
                    <label for="nama_desa" class="block text-sm font-medium text-gray-700">Nama Desa</label>
                    <input type="text" id="nama_desa" name="nama_desa" value="{{ $faktur->nama_desa }}" readonly class="mt-1 block w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-md shadow-sm">
                </div>

                <div>
                    <label for="domain" class="block text-sm font-medium text-gray-700">Domain</label>
                    <input type="text" id="domain" name="domain" value="{{ $faktur->nama_domain }}.desa.id" readonly class="mt-1 block w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-md shadow-sm">
                </div>
                
                <div>
                    <label for="jenis_aplikasi" class="block text-sm font-medium text-gray-700">Jenis Aplikasi</label>
                    <input type="text" id="jenis_aplikasi" name="jenis_aplikasi" value="Informasi Desa" readonly class="mt-1 block w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-md shadow-sm">
                </div>
            </div>

            <!-- KOLOM KANAN -->
            <div class="space-y-4">
                <div>
                    <label for="biaya_domain" class="block text-sm font-medium text-gray-700">Biaya Domain</label>
                    <input type="text" id="biaya_domain" name="biaya_domain" value="50.000" readonly class="mt-1 block w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-md shadow-sm">
                </div>

                <div>
                    <label for="durasi" class="block text-sm font-medium text-gray-700">Durasi</label>
                    <input type="text" id="durasi" name="durasi" value="1 Tahun" readonly class="mt-1 block w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-md shadow-sm">
                </div>

                <div>
                    <label for="tanggal_faktur" class="block text-sm font-medium text-gray-700">Tanggal Faktur</label>
                    <input type="text" id="tanggal_faktur" name="tanggal_faktur" value="{{ $faktur->created_at->format('d/m/Y') }}" readonly class="mt-1 block w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-md shadow-sm">
                </div>
                
                <div>
                    <label for="batas_pembayaran" class="block text-sm font-medium text-gray-700">Batas Pembayaran</label>
                    <input type="text" id="batas_pembayaran" name="batas_pembayaran" value="{{ $faktur->expired_at->format('d/m/Y') }}" readonly class="mt-1 block w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-md shadow-sm">
                </div>
            </div>
        </div>

        <!-- Kolom Catatan di bawah, memenuhi lebar penuh -->
        <div class="mt-6">
            <label for="catatan" class="block text-sm font-medium text-gray-700">Catatan</label>
            <textarea id="catatan" name="catatan" rows="3" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" placeholder="Tambahkan catatan untuk faktur ini...">{{ $faktur->catatan ?? '' }}</textarea>
        </div>

        <!-- Tombol Aksi -->
        <div class="mt-6 flex justify-end">
            <a href="{{ route('admin.faktur.index') }}" class="bg-gray-400 text-white px-4 py-2 rounded mr-2">Batal</a>
            @if($faktur->status == 'belum_bayar')
                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold px-6 py-2 rounded">
                    Kirim Faktur
                </button>
            @else
                <span class="bg-green-500 text-white px-4 py-2 rounded">Faktur Telah Dikirim</span>
            @endif
        </div>
    </form>
</div>
@endsection