@extends('layouts.desa')
@section('title', 'Pratinjau Pengajuan')
@section('content')
<div class="bg-white rounded-xl shadow p-10">
    @include('desa.pengajuan._steps', ['currentStep' => 4])
    <div class="flex justify-center mt-12">
        <div class="w-full max-w-3xl">
            <h3 class="font-semibold text-gray-700 mb-5 text-center">Pratinjau Pengajuan</h3>
            
            <div class="space-y-6">
                <!-- Bagian Domain (Gaya Disamakan) -->
                <div class="bg-gray-50 p-4 rounded-lg">
                    <h4 class="font-semibold text-gray-800 mb-3">Domain</h4>
                    <p class="text-sm text-gray-700">{{ $data['nama_domain'] }}.desa.id</p>
                </div>

                <!-- Bagian Informasi Instansi (Dirapikan) -->
                <div class="bg-gray-50 p-4 rounded-lg">
                    <h4 class="font-semibold text-gray-800 mb-3">Informasi Instansi</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-4 gap-y-2 text-sm text-gray-700">
                        <p><strong>Nama Lembaga:</strong> {{ $data['data_desa']['nama_desa'] ?? '-' }}</p>
                        <p><strong>Klasifikasi Instansi:</strong> {{ $data['data_desa']['klasifikasi_instansi'] ?? '-' }}</p>
                        <p><strong>Provinsi:</strong> {{ $data['data_desa']['provinsi'] ?? '-' }}</p>
                        <p><strong>Kabupaten/Kota:</strong> {{ $data['data_desa']['kota_kabupaten'] ?? '-' }}</p>
                        <p><strong>Kecamatan:</strong> {{ $data['data_desa']['kecamatan'] ?? '-' }}</p>
                        <p><strong>Kelurahan/Desa:</strong> {{ $data['data_desa']['desa_kelurahan'] ?? '-' }}</p>
                        <p><strong>Kode Pos:</strong> {{ $data['data_desa']['kode_pos'] ?? '-' }}</p>
                        <p><strong>Telepon:</strong> {{ $data['data_desa']['Telepon'] ?? '-' }}</p>
                        <p><strong>Faksimili:</strong> {{ $data['data_desa']['Faksimili'] ?? '-' }}</p>
                        <p><strong>Email:</strong> {{ $data['data_desa']['email'] ?? '-' }}</p>
                        
                        <!-- Alamat memenuhi lebar penuh -->
                        <div class="md:col-span-2">
                            <p class="block"><strong>Alamat Lengkap:</strong></p>
                            <p class="whitespace-pre-wrap">{{ $data['data_desa']['alamat'] ?? '-' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Bagian Dokumen (Gaya Disamakan) -->
                <div class="bg-gray-50 p-4 rounded-lg">
                    <h4 class="font-semibold text-gray-800 mb-3">Dokumen yang Diunggah</h4>
                    <ul class="text-sm text-gray-700 list-disc list-inside space-y-1">
                        @foreach($data['data_dokumen'] as $dok)
                            <li>{{ $dok['nama_file'] }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
            
            <!-- Form Aksi -->
            <form action="{{ route('desa.pengajuan.submit') }}" method="POST" class="mt-8">
                @csrf
                <div class="flex justify-between">
                    <a href="{{ route('desa.pengajuan.dokumen') }}" class="px-6 py-2 border border-gray-300 rounded-md text-gray-700">Kembali</a>
                    <button type="submit" class="px-8 py-2 bg-green-600 text-white font-semibold rounded-md hover:bg-green-700 transition duration-150">Ajukan Domain</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection