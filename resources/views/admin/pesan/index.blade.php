@extends('layouts.admin')
@section('title', 'Pesan Admin')

@section('content')

<div class="bg-white p-6 rounded-xl shadow">

    <!-- HEADER -->
    <div class="flex justify-between items-center mb-6">

        <h2 class="text-2xl font-bold">
            Pesan Admin
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
    <form action="{{ route('admin.pesan.hapus.selected') }}"
        method="POST"
        id="deleteForm">

        @csrf
        @method('DELETE')

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            <!-- ========================================== -->
            <!-- KOLOM 1 : KONFIRMASI DARI DESA -->
            <!-- ========================================== -->
            <div class="bg-gray-50 p-5 rounded-xl border">

                <h2 class="text-xl font-bold mb-4 text-blue-600">
                    Konfirmasi Desa
                </h2>

                @forelse($data->where('judul', 'Konfirmasi Pembayaran Disetujui') as $row)

                    <div class="border-l-4 border-blue-500 bg-blue-50 p-4 mb-4 rounded">

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

                        <div class="mt-3">

                            <a href="{{ route('admin.faktur.index') }}"
                                class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded text-sm">

                                Manajemen Faktur

                            </a>

                        </div>

                    </div>

                @empty

                    <div class="text-center text-gray-400 py-8">

                        Tidak ada konfirmasi dari desa

                    </div>

                @endforelse

            </div>


            <!-- ========================================== -->
            <!-- KOLOM 2 : BUKTI PEMBAYARAN -->
            <!-- ========================================== -->
            <div class="bg-gray-50 p-5 rounded-xl border">

                <h2 class="text-xl font-bold mb-4 text-yellow-600">
                    Bukti Pembayaran Masuk
                </h2>

                @forelse($data->where('judul', 'Bukti Pembayaran') as $row)

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

                        <p class="text-sm mt-1">
                            {{ $row->isi }}
                        </p>

                        <p class="text-xs text-gray-500 mt-2">
                            {{ $row->created_at->format('d M Y H:i') }}
                        </p>

                    </div>

                @empty

                    <div class="text-center text-gray-400 py-8">

                        Tidak ada bukti pembayaran masuk

                    </div>

                @endforelse

            </div>


            <!-- ========================================== -->
            <!-- KOLOM 3 : PERPANJANGAN -->
            <!-- ========================================== -->
            <div class="bg-gray-50 p-5 rounded-xl border">

                <h2 class="text-xl font-bold mb-4 text-purple-600">
                    Permintaan Perpanjangan
                </h2>

                @forelse($data->where('judul', 'Permintaan Perpanjangan Domain') as $row)

                    <div class="border-l-4 border-purple-500 bg-purple-50 p-4 mb-4 rounded">

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

                        <div class="mt-3">

                            <a href="{{ route('admin.faktur.index') }}"
                                class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded text-sm">

                                Manajemen Faktur

                            </a>

                        </div>

                    </div>

                @empty

                    <div class="text-center text-gray-400 py-8">

                        Tidak ada permintaan perpanjangan

                    </div>

                @endforelse

            </div>

        </div>

    </form>

</div>


<script>

let deleteMode = false;

function handleDeleteButton()
{
    // Klik pertama -> tampilkan checkbox
    if(!deleteMode){

        deleteMode = true;

        document.querySelectorAll('.delete-checkbox')
            .forEach(item => {
                item.classList.remove('hidden');
            });

        return;
    }

    // Cek apakah ada yang dipilih
    const checked =
        document.querySelectorAll('input[name="pesan_ids[]"]:checked');

    if(checked.length === 0){

        alert('Pilih pesan terlebih dahulu');
        return;
    }

    // POPUP KONFIRMASI
    if(confirm('Apakah Anda yakin ingin menghapus pesan yang dipilih?')){

        document.getElementById('deleteForm').submit();

    }
}

</script>

@endsection