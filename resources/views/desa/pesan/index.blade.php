@extends('layouts.desa')
@section('title', 'Pesan')

@section('content')
<div class="bg-white p-6 rounded-xl shadow">

    <h2 class="text-xl font-bold mb-4">Pesan Masuk</h2>

    @forelse($data as $row)
    <div class="border-l-4 p-4 mb-4 rounded 
        
        {{-- LOGIKA WARNA BERDASARKAN JUDUL PESAN --}}
        @if(str_contains($row->judul, 'Perlu Perbaikan') || str_contains($row->judul, 'Ditolak')) 
            border-red-500 bg-red-50
        @elseif(str_contains($row->judul, 'Konfirmasi Pembayaran') || str_contains($row->judul, 'Domain Aktif')) 
            border-green-500 bg-green-50
        @else 
            border-gray-400 bg-gray-50
        @endif
    ">
        <h3 class="font-semibold">{{ $row->judul }}</h3>
        <p class="text-sm mt-1">{{ $row->isi }}</p>

        <p class="text-xs text-gray-500 mt-2">
            {{ $row->created_at->format('d M Y H:i') }}
        </p>

        {{-- 
            LOGIKA TOMBOL: 
            TOMBOL INI HANYA AKAN MUNCUL JIKA JUDUL PESAN PASTI "Konfirmasi Pembayaran" DAN STATUS BELUM DIBACA 
        --}}
          @if(str_contains($row->judul, 'Konfirmasi Pembayaran') && $row->is_read == 0)
            <form action="{{ route('desa.konfirmasi.pembayaran', $row->id_pengajuan ?? 0) }}" method="POST" class="mt-3">        
                @csrf
                <button class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600 text-sm">
                    Ya, Kirimkan Faktur
                </button>
            </form>
        @endif

    </div>
    @empty
    <div class="text-center text-gray-500 py-8">
        Belum ada pesan
    </div>
    @endforelse

</div>
@endsection