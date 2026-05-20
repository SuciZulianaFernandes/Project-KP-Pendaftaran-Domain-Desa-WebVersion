@extends('layouts.admin')
@section('title', 'Detail Perpanjangan Domain')

@section('content')

<div class="flex flex-col lg:flex-row gap-6">

    <!-- SIDEBAR KIRI -->
    <div class="w-full lg:w-64 flex-shrink-0 space-y-4">

        <!-- STATUS DOMAIN -->
        <div class="bg-white rounded-xl shadow border overflow-hidden">

            <div class="px-5 py-4 border-b bg-gray-50">
                <h3 class="font-semibold text-gray-800">
                    Status Domain
                </h3>
            </div>

            <div class="p-5 text-sm">

                <p class="mb-3">
                    <strong>Domain</strong><br>
                    {{ $pengajuan->nama_domain }}.desa.id
                </p>

                @php
                    // --- LOGIKA BARU: Tentukan status akhir berdasarkan relasi aktivasi ---
                    $finalStatus = $pengajuan->status_pengajuan;

                    // 1. Jika faktur belum bayar, tampilkan status 'diproses'
                    if ($faktur->status == 'belum_bayar') {
                        $finalStatus = 'diproses';
                    }
                    // 2. Jika status pengajuan aktif, cek tabel aktivasi
                    elseif ($finalStatus == 'aktif' && $pengajuan->aktivasi) {
                        $finalStatus = $pengajuan->aktivasi->status_akt;
                    }
                @endphp

                <p class="flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full
                        @if($finalStatus == 'ditinjau') bg-yellow-500
                        @elseif($finalStatus == 'perlu_perbaikan') bg-red-500
                        @elseif($finalStatus == 'diproses') bg-blue-500
                        @elseif($finalStatus == 'menunggu_aktivasi') bg-orange-500
                        
                        {{-- LOGIKA KHUSUS DARI TABEL AKTIVASI --}}
                        @elseif($finalStatus == 'aktif') bg-green-600
                        @elseif($finalStatus == 'kadaluarsa') bg-gray-500
                        @elseif($finalStatus == 'nonaktif') bg-gray-400
                        
                        @else bg-gray-400
                        @endif">
                    </span>

                    <span class="text-gray-700">
                        Status :
                    </span>

                    <span class="font-semibold
                        @if($finalStatus == 'ditinjau') text-yellow-600
                        @elseif($finalStatus == 'perlu_perbaikan') text-red-600
                        @elseif($finalStatus == 'diproses') text-blue-600
                        @elseif($finalStatus == 'menunggu_aktivasi') text-orange-600
                        
                        {{-- LOGIKA KHUSUS DARI TABEL AKTIVASI --}}
                        @elseif($finalStatus == 'aktif') text-green-600
                        @elseif($finalStatus == 'kadaluarsa') text-gray-600
                        @elseif($finalStatus == 'nonaktif') text-gray-500
                        
                        @else text-gray-500
                        @endif">
                        {{ ucfirst(str_replace('_', ' ', $finalStatus)) }}
                    </span>
                </p>

            </div>

        </div>

    </div>

    <!-- CONTENT KANAN -->
    <div class="flex-1">

        <div class="bg-white p-6 rounded-xl shadow">

            <!-- HEADER -->
            <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4 mb-6">

                <h2 class="text-xl font-bold text-gray-800">
                    Detail Perpanjangan Domain
                </h2>

                <a href="{{ route('admin.perpanjang.list') }}"
                class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded inline-flex items-center justify-center">
                    <i class="fas fa-arrow-left mr-2"></i> Kembali
                </a>

            </div>

            <!-- INFORMASI INSTANSI -->
            <h3 class="font-semibold mb-4">
                Informasi Instansi
            </h3>

            <div class="grid grid-cols-1 xl:grid-cols-2 gap-x-10 gap-y-3 text-sm mb-6">

                <div class="flex">
                    <span class="w-48 text-gray-600">Nama Desa</span>
                    <span class="w-4 text-center">:</span>
                    <span>{{ $pengajuan->nama_desa }}</span>
                </div>

                <div class="flex">
                    <span class="w-48 text-gray-600">Provinsi</span>
                    <span class="w-4 text-center">:</span>
                    <span>{{ $pengajuan->provinsi }}</span>
                </div>

                <div class="flex">
                    <span class="w-48 text-gray-600">Kab/Kota</span>
                    <span class="w-4 text-center">:</span>
                    <span>{{ $pengajuan->kota_kabupaten }}</span>
                </div>

                <div class="flex">
                    <span class="w-48 text-gray-600">Kecamatan</span>
                    <span class="w-4 text-center">:</span>
                    <span>{{ $pengajuan->kecamatan }}</span>
                </div>

                <div class="flex xl:col-span-2">
                    <span class="w-48 text-gray-600">Alamat</span>
                    <span class="w-4 text-center">:</span>
                    <span>{{ $pengajuan->alamat }}</span>
                </div>

            </div>

            <!-- DATA PERPANJANGAN / FAKTUR -->
            <div class="mb-6 bg-gray-50 p-4 rounded border">

                <h3 class="font-bold text-lg mb-3 text-blue-800">
                    Data Perpanjangan
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                    
                    <div class="flex">
                        <span class="w-40 text-gray-600">No Invoice</span>
                        <span class="w-4 text-center">:</span>
                        <span class="font-semibold">{{ $faktur->no_invoice }}</span>
                    </div>

                    <div class="flex">
                        <span class="w-40 text-gray-600">Tgl Faktur</span>
                        <span class="w-4 text-center">:</span>
                        <span class="font-semibold">{{ $faktur->created_at->format('d M Y H:i') }}</span>
                    </div>

                    <div class="flex">
                        <span class="w-40 text-gray-600">Status Pembayaran</span>
                        <span class="w-4 text-center">:</span>
                        <span class="font-semibold">
                            @if($faktur->status == 'sudah_bayar') 
                                <span class="text-green-600">Lunas</span>
                            @else 
                                <span class="text-red-600">Belum Lunas</span>
                            @endif
                        </span>
                    </div>

                    <div class="flex">
                        <span class="w-40 text-gray-600">Total Tagihan</span>
                        <span class="w-4 text-center">:</span>
                        <span class="font-semibold">Rp {{ number_format($faktur->total,0,',','.') }}</span>
                    </div>

                </div>
            </div>

            <!-- RIWAYAT DOMAIN -->
            <div class="mb-6 bg-gray-50 p-4 rounded border">

                <h3 class="font-bold text-lg mb-3">
                    Riwayat Domain
                </h3>

                <div class="text-sm space-y-1">
                    <div class="flex">
                        <span class="w-64 text-gray-600">Tgl Aktivasi Terakhir</span>
                        <span class="w-4 text-center">:</span>
                        <span class="font-semibold">
                            {{ $pengajuan->aktivasi ? $pengajuan->aktivasi->tgl_aktivasi->format('d M Y') : '-' }}
                        </span>
                    </div>
                    <div class="flex">
                        <span class="w-64 text-gray-600">Masa Berlaku Hingga</span>
                        <span class="w-4 text-center">:</span>
                        <span class="font-semibold">
                            {{ $pengajuan->aktivasi ? $pengajuan->aktivasi->masa_berlaku->format('d M Y') : '-' }}
                        </span>
                    </div>
                </div>
            </div>

            <hr class="my-4">

            <!-- AKTIVASI PERPANJANGAN -->
            @if($finalStatus == 'menunggu_aktivasi' && $faktur->status == 'sudah_bayar')
                
                <!-- FORM AKTIVASI KHUSUS -->
                <div class="bg-blue-50 p-4 rounded border border-blue-200">

                    <h3 class="font-bold text-lg mb-2">
                        Aktivasi Perpanjangan
                    </h3>
                    
                    <p class="text-sm text-gray-600 mb-4">
                        Status saat ini:
                        <strong>Menunggu Aktivasi</strong>. 
                        Lakukan aktivasi untuk memperbarui masa berlaku domain.
                    </p>

                    <form action="/admin/aktivasi/proses/{{ $pengajuan->id_pengajuan }}" method="POST" id="formAktivasi">

                        @csrf

                        <div class="flex justify-end">

                            <button
                                type="submit"
                                class="js-confirm-btn bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-6 rounded shadow transition duration-200"
                                data-confirm-message="Apakah Anda yakin ingin mengaktifkan perpanjangan domain ini?"
                            >
                                <i class="fas fa-check-circle mr-2"></i>
                                Aktivasikan Sekarang
                            </button>

                        </div>

                    </form>

                </div>

            @elseif($finalStatus == 'aktif')

                <div class="bg-green-50 p-4 rounded border border-green-200 mt-4">

                    <p class="text-green-700 font-semibold">
                        Domain sudah aktif dan diperpanjang.
                    </p>

                </div>

            @elseif($finalStatus == 'kadaluarsa')
            
                <div class="bg-gray-100 p-4 rounded border border-gray-300 mt-4">
                    <p class="text-gray-700 font-semibold">
                        Masa berlaku domain ini telah kadaluarsa.
                    </p>
                </div>

            @else

                <div class="bg-yellow-50 p-4 rounded border border-yellow-200 mt-4">

                    <p class="text-yellow-800 font-semibold">
                        Faktur telah diterbitkan. Menunggu pembayaran selesai sebelum dapat diaktivasi.
                    </p>
                    <a href="{{ route('admin.faktur.index') }}" class="ml-2 text-sm underline hover:text-red-700 font-normal">
                            Lihat di Manajemen Faktur
                        </a>

                </div>

            @endif

        </div>

    </div>

</div>

<!-- MODAL POPUP CONFIRMATION -->
<div id="confirmationModal" class="hidden fixed inset-0 bg-gray-900 bg-opacity-50 overflow-y-auto h-full w-full z-50 flex items-center justify-center">

    <div class="relative mx-auto p-5 border w-96 shadow-lg rounded-xl bg-white">

        <div class="mt-3 text-center">

            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-yellow-100 mb-4">

                <svg class="h-6 w-6 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">

                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>

                </svg>

            </div>

            <h3 class="text-lg leading-6 font-medium text-gray-900">
                Konfirmasi
            </h3>

            <div class="mt-2 px-7 py-3">

                <p id="modalConfirmMessage" class="text-sm text-gray-500">
                    Apakah anda yakin?
                </p>

            </div>

        </div>
        <div class="items-center px-4 py-3 flex justify-center gap-3">

            <button id="modalNoBtn"
            class="px-4 py-2 bg-gray-200 text-gray-800 text-base font-medium rounded-md shadow-sm hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-300">
                Batal
            </button>

            <button id="modalYesBtn"
            class="px-4 py-2 bg-green-600 text-white text-base font-medium rounded-md shadow-sm hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-300">
                Ya, saya yakin
            </button>

        </div>

    </div>

</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('confirmationModal');
    const yesBtn = document.getElementById('modalYesBtn');
    const noBtn = document.getElementById('modalNoBtn');
    const modalMessage = document.getElementById('modalConfirmMessage');
    const confirmBtns = document.querySelectorAll('.js-confirm-btn');

    let formToSubmit = null;

    confirmBtns.forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();

            formToSubmit = this.closest('form');

            const message = this.getAttribute('data-confirm-message') || 'Apakah anda yakin?';

            modalMessage.textContent = message;

            modal.classList.remove('hidden');
            modal.classList.add('flex');
        });
    });

    yesBtn.addEventListener('click', function() {
        if (formToSubmit) {
            formToSubmit.submit();
        }

        closeModal();
    });

    noBtn.addEventListener('click', function() {
        closeModal();
    });

    modal.addEventListener('click', function(e) {
        if (e.target === modal) {
            closeModal();
        }
    });

    function closeModal() {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        formToSubmit = null;
    }
});
</script>

@endsection