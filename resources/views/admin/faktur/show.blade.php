@extends('layouts.admin')

@section('title', 'Detail Faktur')

@section('content')
<div class="bg-white p-6 rounded-xl shadow">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-xl font-bold">Detail Faktur</h2>
            <p class="text-sm text-gray-600 mt-1">Invoice #{{ $faktur->no_invoice }}</p>
        </div>
        <a href="{{ route('admin.faktur.index') }}" class="text-blue-600 hover:underline flex items-center">
            <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Kembali ke Daftar Faktur
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Informasi Desa -->
        <div>
            <h3 class="font-semibold text-gray-700 mb-3 border-b pb-2">Informasi Desa</h3>
            <div class="space-y-2 text-sm">
                <p><span class="font-medium">Nama Desa:</span> {{ $faktur->nama_desa }}</p>
                <p><span class="font-medium">Domain:</span> {{ $faktur->nama_domain }}.desa.id</p>
            </div>
        </div>

        <!-- Informasi Faktur -->
        <div>
            <h3 class="font-semibold text-gray-700 mb-3 border-b pb-2">Informasi Faktur</h3>
            <div class="space-y-2 text-sm">
                <p><span class="font-medium">No. Invoice:</span> {{ $faktur->no_invoice }}</p>
                <p><span class="font-medium">Total:</span> Rp {{ number_format($faktur->total, 0, ',', '.') }}</p>
                <p>
                    <span class="font-medium">Status:</span>
                    @if($faktur->status == 'belum_bayar')
                        <span class="ml-2 bg-yellow-500 text-white px-2 py-1 rounded-full text-xs">Belum Bayar</span>
                    @elseif($faktur->status == 'sudah_bayar')
                        <span class="ml-2 bg-green-500 text-white px-2 py-1 rounded-full text-xs">Sudah Bayar</span>
                    @elseif($faktur->status == 'kedaluarsa')
                        <span class="ml-2 bg-red-500 text-white px-2 py-1 rounded-full text-xs">Kedaluarsa</span>
                    @endif
                </p>
            </div>
        </div>
    </div>

    <!-- Rincian Pembayaran -->
    <div class="mt-8 pt-6 border-t">
        <h3 class="font-semibold text-gray-700 mb-3">Rincian Pembayaran</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-sm">
            <div>
                <p><span class="font-medium">Tanggal Dibuat:</span> {{ $faktur->created_at->format('d F Y') }}</p>
                <p><span class="font-medium">Batas Pembayaran:</span> {{ $faktur->expired_at->format('d F Y') }}</p>
            </div>
            <div>
                <p><span class="font-medium">Jenis Aplikasi:</span> Informasi Desa</p>
                <p><span class="font-medium">Durasi:</span> 1 Tahun</p>
            </div>
        </div>
    </div>

    <!-- Catatan -->
    @if($faktur->catatan)
    <div class="mt-6 pt-6 border-t">
        <h3 class="font-semibold text-gray-700 mb-3">Catatan</h3>
        <p class="text-sm text-gray-600 bg-gray-50 p-3 rounded">{{ $faktur->catatan }}</p>
    </div>
    @endif

    <!-- Bukti Pembayaran (jika ada) -->
    @if($faktur->status == 'sudah_bayar' && $faktur->bukti_pembayaran_path)
    <div class="mt-6 pt-6 border-t">
        <h3 class="font-semibold text-gray-700 mb-3">Bukti Pembayaran</h3>
        <a href="{{ asset('storage/' . $faktur->bukti_pembayaran_path) }}" target="_blank" class="inline-flex items-center text-blue-600 hover:underline">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
            Lihat Bukti Pembayaran
        </a>
    </div>
    @endif
</div>
@endsection