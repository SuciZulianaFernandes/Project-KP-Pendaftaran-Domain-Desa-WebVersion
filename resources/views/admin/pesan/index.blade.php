@extends('layouts.admin')
@section('title', 'Pesan Admin')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

    <!-- KOLOM KIRI: BUKTI PEMBAYARAN MASUK -->
    <div class="bg-white p-6 rounded-xl shadow">
        <h2 class="text-xl font-bold mb-4 text-yellow-600">📦 Bukti Pembayaran Masuk</h2>

        @foreach($data->where('judul', 'Bukti Pembayaran') as $row)
            <div class="border-l-4 border-yellow-500 bg-yellow-50 p-4 mb-4 rounded">
                <h3 class="font-semibold text-base">{{ $row->judul }}</h3>
                <p class="text-sm mt-1">{{ $row->isi }}</p>
                <p class="text-xs text-gray-500 mt-2">{{ $row->created_at }}</p>
            </div>
        @endforeach
        
        @if($data->where('judul', 'Bukti Pembayaran')->isEmpty())
            <div class="text-center text-gray-400 py-8">
                Tidak ada bukti pembayaran masuk
            </div>
        @endif
    </div>

    <!-- KOLOM KANAN: KONFIRMASI DARI DESA -->
    <div class="bg-white p-6 rounded-xl shadow">
        <h2 class="text-xl font-bold mb-4 text-blue-600">✅ Konfirmasi Desa</h2>

        @foreach($data->where('judul', 'Konfirmasi Pembayaran Disetujui') as $row)
            <div class="border-l-4 border-blue-500 bg-blue-50 p-4 mb-4 rounded">
                <h3 class="font-semibold text-base">{{ $row->judul }}</h3>
                <p class="text-sm mt-1">{{ $row->isi }}</p>
                <p class="text-xs text-gray-500 mt-2">{{ $row->created_at }}</p>
                
                <div class="mt-3">
                    <a href="{{ route('admin.faktur.index') }}"
                       class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded text-sm">
                        Manajemen Faktur
                    </a>
                </div>
            </div>
        @endforeach

        @if($data->where('judul', 'Konfirmasi Pembayaran Disetujui')->isEmpty())
            <div class="text-center text-gray-400 py-8">
                Tidak ada konfirmasi dari desa
            </div>
        @endif
    </div>

</div>
@endsection