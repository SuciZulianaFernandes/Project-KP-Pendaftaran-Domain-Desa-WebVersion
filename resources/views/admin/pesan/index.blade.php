@extends('layouts.admin')
@section('title', 'Pesan Admin')

@section('content')
<div class="bg-white p-6 rounded-xl shadow">

    <h2 class="text-xl font-bold mb-6">Pesan Masuk</h2>

    @if(session('success'))
        <div class="bg-green-100 text-green-700 p-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    @forelse($data as $row)
        <div class="border-l-4 border-blue-500 bg-blue-50 p-4 mb-4 rounded">

            <!-- JUDUL -->
            <h3 class="font-semibold text-lg">{{ $row->judul }}</h3>

            <!-- ISI -->
            <p class="text-sm mt-1">{{ $row->isi }}</p>

            <!-- TANGGAL -->
            <p class="text-xs text-gray-500 mt-2">
                {{ $row->created_at }}
            </p>

            <!-- 🔥 ACTION KHUSUS -->
            @if($row->judul == 'Konfirmasi Pembayaran Disetujui')
                <div class="mt-3 flex gap-2">

                    <!-- KE HALAMAN FAKTUR -->
                    <a href="{{ route('admin.faktur.index') }}"
                       class="bg-green-600 text-white px-4 py-2 rounded">
                        Manajemen Faktur
                    </a>

                    <!-- 🔥 BUAT FAKTUR LANGSUNG -->
                    <form action="{{ route('admin.faktur.store', $row->id_pengajuan) }}" method="POST">
                        @csrf
                        <button class="bg-blue-500 text-white px-4 py-2 rounded">
                            Buat Faktur
                        </button>
                    </form>

                </div>
            @endif

        </div>
    @empty
        <div class="text-center text-gray-500">
            Belum ada pesan
        </div>
    @endforelse

</div>
@endsection