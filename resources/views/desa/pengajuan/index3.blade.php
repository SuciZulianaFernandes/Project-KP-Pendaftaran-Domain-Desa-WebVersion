@extends('layouts.desa')
@section('title', 'Persyaratan Domain')
@section('content')
<div class="bg-white rounded-xl shadow p-10">
    @include('desa.pengajuan._steps', ['currentStep' => 3])
    <div class="flex justify-center mt-12">
        <div class="w-full max-w-2xl">
            <h3 class="font-semibold text-gray-700 mb-5 text-center">Unggah Persyaratan</h3>
            <form action="{{ route('pengajuan.dokumen.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="space-y-4">
                    <div><label class="block text-sm font-medium text-gray-700">Surat Permohonan</label><input type="file" name="surat_permohonan" class="mt-1 block w-full" required></div>
                    <div><label class="block text-sm font-medium text-gray-700">Perda Pembentukan Desa</label><input type="file" name="perda_pembentukan_desa" class="mt-1 block w-full" required></div>
                    <div><label class="block text-sm font-medium text-gray-700">Surat Kuasa</label><input type="file" name="surat_kuasa" class="mt-1 block w-full" required></div>
                </div>
                <div class="flex justify-end mt-6 space-x-3">
<!-- Di index3.blade.php, ubah baris ini -->
<a href="{{ route('pengajuan.informasi') }}" class="px-6 py-2 border border-gray-300 rounded-md text-gray-700">Kembali</a>                    <button type="submit" class="px-6 py-2 bg-red-700 text-white rounded-md">Selanjutnya</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection