@extends('layouts.desa')
@section('title', 'Persyaratan Domain')
@section('content')
<div class="bg-white rounded-xl shadow p-10">
    @include('desa.pengajuan._steps', ['currentStep' => 3])
    <div class="flex justify-center mt-12">
        <div class="w-full max-w-2xl">
            <h3 class="font-semibold text-gray-700 mb-5 text-center">Unggah Persyaratan</h3>
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
                <div class="space-y-4">
                    <div><label class="block text-sm font-medium text-gray-700">Surat Permohonan</label><input type="file" name="surat_permohonan" accept="application/pdf" class="mt-1 block w-full" required></div>
                    <div><label class="block text-sm font-medium text-gray-700">Perda Pembentukan Desa</label><input type="file" name="perda_pembentukan_desa" accept="application/pdf" class="mt-1 block w-full" required></div>
                    <div><label class="block text-sm font-medium text-gray-700">Surat Kuasa</label><input type="file" name="surat_kuasa" accept="application/pdf" class="mt-1 block w-full" required></div>
                </div>
                <div class="flex justify-end mt-6 space-x-3">
<!-- Di index3.blade.php, ubah baris ini -->
<a href="{{ route('desa.pengajuan.informasi') }}" class="px-6 py-2 border border-gray-300 rounded-md text-gray-700">Kembali</a>                    <button type="submit" class="px-6 py-2 bg-red-700 text-white rounded-md">Selanjutnya</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection