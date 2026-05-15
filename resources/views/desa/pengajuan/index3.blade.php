@extends('layouts.desa')
@section('title', 'Persyaratan Domain')
@section('content')
<div class="bg-white rounded-xl shadow p-6 md:p-10">
    @include('desa.pengajuan._steps', ['currentStep' => 3])
    
    <div class="flex justify-center mt-8 md:mt-12">
        <div class="w-full max-w-4xl">
            <h3 class="font-semibold text-xl text-gray-700 mb-6 text-center">Unggah Persyaratan</h3>

            @if ($errors->any())
                <div class="mb-4 p-4 bg-red-100 border border-red-300 text-red-700 rounded">
                    <ul class="list-disc pl-5 text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('desa.pengajuan.dokumen.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-10 gap-y-6">
                    <!-- KOLOM KIRI (3 Item) -->
                    <div class="space-y-5">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Surat Permohonan <span class="text-red-600">*</span></label>
                            <!-- STYLE DIUBAH SESUAI GAMBAR -->
                            <input type="file" name="surat_permohonan" accept="application/pdf" required
                                class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 file:mr-4 file:py-2.5 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-red-700 file:text-white hover:file:bg-red-800">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Surat Kuasa <span class="text-red-600">*</span></label>
                            <!-- STYLE DIUBAH SESUAI GAMBAR -->
                            <input type="file" name="surat_kuasa" accept="application/pdf" required
                                class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 file:mr-4 file:py-2.5 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-red-700 file:text-white hover:file:bg-red-800">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Surat Penunjukan Pejabat <span class="text-red-600">*</span></label>
                            <!-- STYLE DIUBAH SESUAI GAMBAR -->
                            <input type="file" name="surat_penunjukan_pejabat" accept="application/pdf" required
                                class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 file:mr-4 file:py-2.5 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-red-700 file:text-white hover:file:bg-red-800">
                        </div>
                    </div>

                    <!-- KOLOM KANAN (2 Item) -->
                    <div class="space-y-5 pt-1">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Kartu Pegawai <span class="text-red-600">*</span></label>
                            <!-- STYLE DIUBAH SESUAI GAMBAR -->
                            <input type="file" name="ktp_asn_pejabat" accept="application/pdf" required
                                class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 file:mr-4 file:py-2.5 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-red-700 file:text-white hover:file:bg-red-800">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Dasar Hukum Pembentukan Desa <span class="text-red-600">*</span></label>
                            <!-- STYLE DIUBAH SESUAI GAMBAR -->
                            <input type="file" name="perda_pembentukan_desa" accept="application/pdf" required
                                class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 file:mr-4 file:py-2.5 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-red-700 file:text-white hover:file:bg-red-800">
                        </div>
                    </div>
                </div>

                <div class="mt-8 mb-4 text-sm text-gray-500">
                    <p class="font-semibold">• Wajib Diisi.</p>
                    <p>• Semua dokumen yang diunggah harus format pdf. (Max 1024KB setiap dokumen.)</p>
                </div>

                <div class="flex justify-end mt-8 space-x-3 border-t pt-6">
                    <a href="{{ route('desa.pengajuan.informasi') }}" class="px-6 py-2.5 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">Kembali</a>
                    <button type="submit" class="px-6 py-2.5 bg-red-700 text-white rounded-lg hover:bg-red-800 transition shadow-sm">Selanjutnya</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection