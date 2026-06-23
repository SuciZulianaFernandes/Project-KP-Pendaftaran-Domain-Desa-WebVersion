@extends('layouts.desa')

@section('title', 'Persyaratan Domain')

@section('content')
<div class="bg-white rounded-3xl shadow-xl border border-slate-100 p-10">
    @include('desa.pengajuan._steps', ['currentStep' => 3])
    
    <div class="flex justify-center mt-8 md:mt-12">
        <div class="w-full max-w-4xl">
            <h3 class="font-semibold text-xl text-gray-700 mb-6 text-center">Unggah Persyaratan</h3>

            @if ($errors->any())
                <div class="mb-4 p-4 bg-red-100 border border-red-300 text-red-700 rounded-lg">
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
                    <!-- KOLOM KIRI (2 Item) -->
                    <div class="space-y-5">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Surat Permohonan Domain Desa <span class="text-red-600">*</span></label>
                            <input type="file" name="surat_permohonan" accept="application/pdf" required
                                class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 file:mr-4 file:py-2.5 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-gradient-to-r file:from-[#109696] file:via-[#1A85A5] file:to-[#1760C5] file:text-white hover:file:opacity-90">
                            <p class="text-xs text-gray-500 mt-1">Surat resmi permohonan domain desa</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Surat Kuasa dari Desa <span class="text-red-600">*</span></label>
                            <input type="file" name="surat_kuasa" accept="application/pdf" required
                                class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 file:mr-4 file:py-2.5 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-gradient-to-r file:from-[#109696] file:via-[#1A85A5] file:to-[#1760C5] file:text-white hover:file:opacity-90">
                            <p class="text-xs text-gray-500 mt-1">Surat kuasa yang dikeluarkan oleh desa</p>
                        </div>
                    </div>

                    <!-- KOLOM KANAN (1 Item) -->
                    <div class="space-y-5 pt-1">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Dasar Hukum Pembentukan Desa / Surat Pelantikan Kepala Desa <span class="text-red-600">*</span></label>
                            <input type="file" name="perda_pembentukan_desa" accept="application/pdf" required
                                class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 file:mr-4 file:py-2.5 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-gradient-to-r file:from-[#109696] file:via-[#1A85A5] file:to-[#1760C5] file:text-white hover:file:opacity-90">
                            <p class="text-xs text-gray-500 mt-1">Perda pembentukan desa atau surat pelantikan kepala desa</p>
                        </div>
                    </div>
                </div>

                <div class="mt-8 mb-4 text-sm text-gray-500">
                    <p class="font-semibold">• Wajib Diisi.</p>
                    <p>• Semua dokumen yang diunggah harus format pdf. (Max 2 MB setiap dokumen.)</p>
                </div>

                <div class="flex justify-end mt-8 space-x-3 border-t pt-6">
                    <a href="{{ route('desa.pengajuan.informasi') }}" class="px-7 py-3 border border-gray-300 rounded-lg text-gray-700 font-semibold hover:bg-gray-50 transition duration-150">Kembali</a>
                    <button type="submit" class="px-7 py-3 bg-gradient-to-r from-[#109696] via-[#1A85A5] to-[#1760C5] hover:opacity-90 text-white font-semibold rounded-lg transition-all duration-200 shadow-lg">Selanjutnya</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection