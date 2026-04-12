@extends('layouts.desa')
@section('title', 'Pesan')

@section('content')
<div class="bg-white p-6 rounded-xl shadow">

    <h2 class="text-xl font-bold mb-4">Pesan Masuk</h2>

    @forelse($data as $row)
    <div class="border-l-4 p-4 mb-4 rounded 
    @if(str_contains($row->judul, 'Konfirmasi Pembayaran')) 
        border-green-500 bg-green-50
    @else 
        border-red-500 bg-red-50
    @endif">

    <h3 class="font-semibold">{{ $row->judul }}</h3>
    <p class="text-sm mt-1">{{ $row->isi }}</p>

    <p class="text-xs text-gray-500 mt-2">
        {{ $row->created_at->format('d M Y H:i') }}
    </p>

    {{-- 🔥 tombol --}}
    @if(str_contains($row->judul, 'Konfirmasi Pembayaran') && $row->is_read == 0)
<form action="{{ route('desa.konfirmasi.pembayaran', $row->id_pengajuan ?? 0) }}" method="POST">        @csrf
        <button class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">
            Ya, Kirimkan Faktur
        </button>
    </form>
    @endif

</div>
    @empty
    <div class="text-center text-gray-500">
        Belum ada pesan
    </div>
    @endforelse

</div>
@endsection