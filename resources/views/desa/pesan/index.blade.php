@extends('layouts.desa')
@section('title', 'Pesan')

@section('content')

<div class="bg-white p-6 rounded-xl shadow">

    <!-- HEADER -->
    <div class="flex justify-between items-center mb-6">

        <h2 class="text-2xl font-bold">
            Pesan Masuk
        </h2>

        <!-- BUTTON HAPUS -->
        <button type="button"
            id="deleteButton"
            onclick="handleDeleteButton()"
            class="bg-red-500 hover:bg-red-600 text-white w-10 h-10 rounded-full flex items-center justify-center transition">

            <i class="fas fa-trash text-sm"></i>

        </button>

    </div>

    <!-- FORM HAPUS -->
    <form action="{{ route('desa.pesan.hapus.selected') }}"
        method="POST"
        id="deleteForm">

        @csrf
        @method('DELETE')

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            <!-- ========================================== -->
            <!-- KOLOM 1 : DOMAIN & FAKTUR -->
            <!-- ========================================== -->
            <div class="bg-gray-50 p-5 rounded-xl border">

                <h2 class="text-xl font-bold mb-4 text-green-600">
                    Domain & Faktur
                </h2>

                @forelse(
                    $data->filter(function($item){

                        return
                            str_contains($item->judul, 'Domain Aktif') ||
                            str_contains($item->judul, 'Faktur Telah Dibuat') ||
                            str_contains($item->judul, 'Faktur Perpanjangan Dibuat');

                    }) as $row
                )

                    <div class="border-l-4 border-green-500 bg-green-50 p-4 mb-4 rounded">

                        <!-- CHECKBOX -->
                        <div class="hidden delete-checkbox mb-3">

                            <input type="checkbox"
                                name="pesan_ids[]"
                                value="{{ $row->id }}"
                                class="w-4 h-4">

                        </div>

                        <h3 class="font-semibold text-base">
                            {{ $row->judul }}
                        </h3>

                        <p class="text-sm mt-1">
                            {{ $row->isi }}
                        </p>

                        <p class="text-xs text-gray-500 mt-2">
                            {{ $row->created_at->format('d M Y H:i') }}
                        </p>

                        {{-- BUTTON LIHAT FAKTUR --}}
                        @if(
                            str_contains($row->judul, 'Faktur Telah Dibuat') ||
                            str_contains($row->judul, 'Faktur Perpanjangan Dibuat')
                        )

                            <div class="mt-3">

                                <a href="{{ route('desa.faktur.index') }}"
                                    class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded text-sm">

                                    Lihat Faktur

                                </a>

                            </div>

                        @endif

                    </div>

                @empty

                    <div class="text-center text-gray-400 py-8">

                        Tidak ada pesan domain

                    </div>

                @endforelse

            </div>


            <!-- ========================================== -->
            <!-- KOLOM 2 : KONFIRMASI PEMBAYARAN (DIMODIFIKASI) -->
            <!-- ========================================== -->
            <div class="bg-gray-50 p-5 rounded-xl border">

                <h2 class="text-xl font-bold mb-4 text-yellow-600">
                    Konfirmasi Pembayaran
                </h2>

                @forelse(
                    $data->filter(function($item){

                        return str_contains($item->judul, 'Konfirmasi Pembayaran');

                    }) as $row
                )

                    <div class="border-l-4 border-yellow-500 bg-yellow-50 p-4 mb-4 rounded">

                        <!-- CHECKBOX -->
                        <div class="hidden delete-checkbox mb-3">

                            <input type="checkbox"
                                name="pesan_ids[]"
                                value="{{ $row->id }}"
                                class="w-4 h-4">

                        </div>

                        <h3 class="font-semibold text-base">
                            {{ $row->judul }}
                        </h3>

                        {{-- LOGIKA PERUBAHAN TEKS --}}
                        <p class="text-sm mt-1">
                            @if($row->is_read == 1)
                                Silahkan tunggu faktur dari admin kominfo
                            @else
                                {{ $row->isi }}
                            @endif
                        </p>

                        <p class="text-xs text-gray-500 mt-2">
                            {{ $row->created_at->format('d M Y H:i') }}
                        </p>

                        {{-- BUTTON KONFIRMASI (Hanya muncul jika belum dibaca/diklik) --}}
                        @if($row->is_read == 0)

                            <form action="{{ route('desa.konfirmasi.pembayaran', $row->id_pengajuan ?? 0) }}"
                                method="POST"
                                class="mt-3">

                                @csrf

                                <button class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded text-sm">

                                    Ya, Kirimkan Faktur

                                </button>

                            </form>

                        @endif

                    </div>

                @empty

                    <div class="text-center text-gray-400 py-8">

                        Tidak ada konfirmasi pembayaran

                    </div>

                @endforelse

            </div>


            <!-- ========================================== -->
            <!-- KOLOM 3 : PERLU PERBAIKAN -->
            <!-- ========================================== -->
            <div class="bg-gray-50 p-5 rounded-xl border">

                <h2 class="text-xl font-bold mb-4 text-red-600">
                    Perlu Perbaikan
                </h2>

                @forelse(
                    $data->filter(function($item){

                        return
                            str_contains($item->judul, 'Perlu Perbaikan') ||
                            str_contains($item->judul, 'Ditolak');

                    }) as $row
                )

                    <div class="border-l-4 border-red-500 bg-red-50 p-4 mb-4 rounded">

                        <!-- CHECKBOX -->
                        <div class="hidden delete-checkbox mb-3">

                            <input type="checkbox"
                                name="pesan_ids[]"
                                value="{{ $row->id }}"
                                class="w-4 h-4">

                        </div>

                        <h3 class="font-semibold text-base">
                            {{ $row->judul }}
                        </h3>

                        <p class="text-sm mt-1">
                            {{ $row->isi }}
                        </p>

                        <p class="text-xs text-gray-500 mt-2">
                            {{ $row->created_at->format('d M Y H:i') }}
                        </p>

                    </div>

                @empty

                    <div class="text-center text-gray-400 py-8">

                        Tidak ada pesan perbaikan

                    </div>

                @endforelse

            </div>

        </div>

    </form>

</div>


<!-- SCRIPT -->
<script>

let deleteMode = false;

function handleDeleteButton()
{
    // klik pertama -> tampilkan checkbox
    if(!deleteMode){

        deleteMode = true;

        document.querySelectorAll('.delete-checkbox')
            .forEach(item => {

                item.classList.remove('hidden');

            });

        return;
    }

    // cek apakah ada yang dipilih
    const checked =
        document.querySelectorAll('input[name="pesan_ids[]"]:checked');

    if(checked.length === 0){

        alert('Pilih pesan terlebih dahulu');
        return;
    }

    // popup konfirmasi
    if(confirm('Apakah Anda yakin ingin menghapus pesan yang dipilih?')){

        document.getElementById('deleteForm').submit();

    }
}

</script>

@endsection