@extends('layouts.desa')
@section('title', 'Status Domain')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
    <!-- Kolom Kiri: Komponen Status Box -->
    <div class="lg:col-span-1">
        <x-status-box :status="$faktur->status" />
    </div>

    <!-- Kolom Kanan: Konten Utama Faktur -->
    <div class="lg:col-span-3">
        <div class="bg-white p-6 rounded-xl shadow">
            <h2 class="text-2xl font-bold mb-4">Status Domain</h2>
            
            <!-- Info Faktur -->
            <div class="mb-6">
                <p class="text-gray-500 text-sm">Invoice</p>
                <p class="font-bold text-lg">#{{ $faktur->no_invoice }}</p>
                <p class="font-semibold">{{ $faktur->nama_desa }}</p>
            </div>

            <!-- Detail Domain -->
            <div class="space-y-2 mb-6">
                <div class="flex justify-between py-2 border-b">
                    <span class="text-gray-600">Domain</span>
                    <span class="font-medium">{{ $faktur->nama_domain }}.desa.id</span>
                </div>
                <div class="flex justify-between py-2 border-b">
                    <span class="text-gray-600">Aplikasi</span>
                    <span class="font-medium">Registrasi</span>
                </div>
                <div class="flex justify-between py-2 border-b">
                    <span class="text-gray-600">Masa Aktif</span>
                    <span class="font-medium">1 Tahun</span>
                </div>
                <div class="flex justify-between py-2">
                    <span class="text-gray-600">Harga</span>
                    <span class="font-bold text-lg">Rp {{ number_format($faktur->total, 0, ',', '.') }}</span>
                </div>
            </div>

            <!-- Petunjuk Pembayaran -->
            <div class="bg-gray-50 p-4 rounded-lg mb-6">
                <h3 class="font-semibold mb-2">Petunjuk Pembayaran</h3>
                <p class="text-sm text-gray-700 mb-2">Silakan lakukan pembayaran ke rekening berikut:</p>
                <div class="text-sm space-y-1">
                    <p><strong>Penerima:</strong> PANDI (Pengelola Nama Domain Internet Indonesia)</p>
                    <p><strong>Bank:</strong> Bank BCA KCU Sudirman</p>
                    <p><strong>No. Rekening:</strong> 888-88-8888</p>
                </div>
            </div>

            <!-- Form Upload Bukti Pembayaran -->
            <form action="{{ route('desa.faktur.konfirmasi', $faktur->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-4">
                    <label for="bukti_pembayaran" class="block text-sm font-medium text-gray-700 mb-2">Bukti Pembayaran *</label>
                    <input type="file" name="bukti_pembayaran" id="bukti_pembayaran" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100" required>
                    @error('bukti_pembayaran')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white font-bold py-3 px-4 rounded-lg transition duration-300">
                    Kirim
                </button>
            </form>
        </div>
    </div>
</div>
@endsection