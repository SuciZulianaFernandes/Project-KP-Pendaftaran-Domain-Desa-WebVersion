@extends('layouts.admin')
@section('title', 'Detail Pengajuan')

@section('content')

@php
    // LOGIKA BARU: Cek status aktual yang sebenarnya (terutama untuk kasus Kadaluarsa)
    $finalStatus = $pengajuan->status_pengajuan;

    // Jika status pengajuan 'aktif', cek tabel aktivasi terakhir
    if ($finalStatus == 'aktif' && $pengajuan->aktivasi) {
        // Ambil aktivasi terakhir berdasarkan masa berlaku (DESC)
        $latestAktivasi = \App\Models\Aktivasi::where('id_pengajuan', $pengajuan->id_pengajuan)
                            ->orderBy('masa_berlaku', 'desc')
                            ->first();
        
        // Jika ada data aktivasi dan statusnya bukan 'aktif', override status tampilan
        if ($latestAktivasi && $latestAktivasi->status_akt != 'aktif') {
            $finalStatus = $latestAktivasi->status_akt; // nilain 'kadaluarsa' atau 'nonaktif'
        }
    }
@endphp

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

                <p class="flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full
                        @if($finalStatus == 'ditinjau') bg-yellow-500
                        @elseif($finalStatus == 'perlu_perbaikan') bg-red-500
                        @elseif($finalStatus == 'diproses') bg-blue-500
                        @elseif($finalStatus == 'menunggu_aktivasi') bg-orange-500
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
                    Detail Pengajuan
                </h2>

                <a href="{{ url()->previous() }}" 
                   class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded inline-flex items-center justify-center">
                    <i class="fas fa-arrow-left mr-2"></i> Kembali
                </a>

            </div>

            <!-- INFORMASI INSTANSI (TETAP SEMULA) -->
            <h3 class="font-semibold mb-4">
                Informasi Instansi
            </h3>

            <div class="grid grid-cols-1 xl:grid-cols-2 gap-x-10 gap-y-3 text-sm mb-6">

                <div class="flex">
                    <span class="w-48 text-gray-600">Nama Organisasi</span>
                    <span class="w-4 text-center">:</span>
                    <span>{{ $pengajuan->nama_desa }}</span>
                </div>

                <div class="flex">
                    <span class="w-48 text-gray-600">Provinsi</span>
                    <span class="w-4 text-center">:</span>
                    <span>{{ $pengajuan->provinsi }}</span>
                </div>

                <div class="flex">
                    <span class="w-48 text-gray-600">Kabupaten</span>
                    <span class="w-4 text-center">:</span>
                    <span>{{ $pengajuan->kota_kabupaten }}</span>
                </div>

                <div class="flex">
                    <span class="w-48 text-gray-600">Kecamatan</span>
                    <span class="w-4 text-center">:</span>
                    <span>{{ $pengajuan->kecamatan }}</span>
                </div>

                <div class="flex">
                    <span class="w-48 text-gray-600">Desa</span>
                    <span class="w-4 text-center">:</span>
                    <span>{{ $pengajuan->desa_kelurahan }}</span>
                </div>

                <div class="flex">
                    <span class="w-48 text-gray-600">Telepon</span>
                    <span class="w-4 text-center">:</span>
                    <span>{{ $pengajuan->telepon }}</span>
                </div>

                <div class="flex">
                    <span class="w-48 text-gray-600">Faksimili</span>
                    <span class="w-4 text-center">:</span>
                    <span>{{ $pengajuan->faksimili }}</span>
                </div>

                <div class="flex">
                    <span class="w-48 text-gray-600">Kode Pos</span>
                    <span class="w-4 text-center">:</span>
                    <span>{{ $pengajuan->kode_pos }}</span>
                </div>

                <div class="flex">
                    <span class="w-48 text-gray-600">Email Registran</span>
                    <span class="w-4 text-center">:</span>
                    <span>{{ $pengajuan->email }}</span>
                </div>

                <div class="flex">
                    <span class="w-48 text-gray-600">Alamat</span>
                    <span class="w-4 text-center">:</span>
                    <span>{{ $pengajuan->alamat }}</span>
                </div>

                <div class="flex xl:col-span-2">
                    <span class="w-48 text-gray-600">Tanggal Pembuatan</span>
                    <span class="w-4 text-center">:</span>
                    <span>{{ $pengajuan->created_at->format('d M Y') }}</span>
                </div>

            </div>

            <!-- DOKUMEN -->
            <h3 class="font-semibold mb-4">
                Dokumen Persyaratan Domain
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6 text-sm">

                @foreach($pengajuan->dokumenPersyaratan as $dok)

                <div class="flex justify-between border-b pb-2 gap-3">
                    <span>{{ $dok->jenis_dokumen }}</span>

                    <a href="{{ asset('storage/'.$dok->path_file) }}"
                    target="_blank"
                    class="text-red-600 text-xs whitespace-nowrap">
                        Lihat Dokumen
                    </a>
                </div>

                @endforeach

            </div>

           <!-- RIWAYAT DATA FAKTUR (GAYA DIUBAH MENJADI SAMA DENGAN SHOW PERPANJANG) -->
@if($pengajuan->faktur->isNotEmpty())

<div class="mb-6 bg-gray-50 p-4 rounded-xl border">

    <div class="flex items-center justify-between mb-4">

        <div>
            <h3 class="text-lg font-semibold text-gray-800">
                Riwayat Data Faktur
            </h3>

            <p class="text-sm text-gray-500">
                Daftar faktur yang berkaitan dengan domain ini.
            </p>
        </div>

    </div>

    <div class="space-y-4">

        @foreach($pengajuan->faktur as $fakturItem)

        <div class="bg-white border rounded-xl p-4 shadow-sm hover:shadow-md transition duration-200">

            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">

                <!-- INFORMASI -->
                <div class="space-y-2 text-sm">

                    <div>
                        <p class="text-gray-500 text-xs">
                            Nomor Invoice
                        </p>

                        <p class="font-semibold text-gray-800">
                            {{ $fakturItem->no_invoice }}
                        </p>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-2">

                        <div>
                            <p class="text-gray-500 text-xs">
                                Total Tagihan
                            </p>

                            <p class="font-medium text-gray-800">
                                Rp {{ number_format($fakturItem->total,0,',','.') }}
                            </p>
                        </div>

                        <div>
                            <p class="text-gray-500 text-xs">
                                Status Pembayaran
                            </p>

                            <p class="font-semibold
                                @if($fakturItem->status == 'belum_bayar') text-yellow-600
                                @elseif($fakturItem->status == 'sudah_bayar') text-green-600
                                @elseif($fakturItem->status == 'kedaluarsa') text-red-600
                                @endif">

                                {{ ucfirst(str_replace('_',' ',$fakturItem->status)) }}
                            </p>
                        </div>

                    </div>

                </div>

                <!-- BUTTON -->
                <div class="flex justify-start lg:justify-end">

                    <a href="{{ route('admin.faktur.show', $fakturItem->id) }}"
                    class="inline-flex items-center gap-2 bg-red-700 hover:bg-red-800 text-white text-sm font-semibold px-4 py-2 rounded-lg transition duration-200 shadow-sm">

                        <i class="fas fa-eye"></i>

                        Detail Faktur
                    </a>

                </div>

            </div>

        </div>

        @endforeach

    </div>

</div>

@endif

            <hr class="my-4">

            <!-- VERIFIKASI -->
            @if(in_array($pengajuan->status_pengajuan, ['ditinjau', 'perlu_perbaikan']))

            <form action="{{ route('admin.verifikasi.proses', $pengajuan->id_pengajuan) }}" method="POST" id="formVerifikasi">

                @csrf
                @method('PUT')

                <h3 class="font-semibold mb-3">
                    Hasil Verifikasi
                </h3>
                <div class="flex flex-col md:flex-row md:items-center gap-4 md:gap-6 mb-4">

                    <label>
                        <input type="radio" name="status" value="diproses" id="diproses">
                        Diproses
                    </label>

                    <label>
                        <input type="radio" name="status" value="perlu_perbaikan" id="perbaikan">
                        Perlu Perbaikan
                    </label>

                    <label class="flex items-center gap-2">
                        <input type="checkbox" name="kirim_email" id="kirim_email">
                        Kirim konfirmasi pembayaran
                    </label>

                </div>

                <textarea
                    name="catatan"
                    placeholder="Catatan..."
                    class="w-full border p-2 rounded mb-4"
                ></textarea>

                <div class="text-right">

                    <button
                        type="submit"
                        class="js-confirm-btn bg-red-700 text-white px-6 py-2 rounded"
                        data-confirm-message="Apakah anda yakin ingin mengirim verifikasi ini?"
                    >
                        Kirim
                    </button>

                </div>
            </form>

            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const diproses = document.getElementById('diproses');
                    const perbaikan = document.getElementById('perbaikan');
                    const kirim_email = document.getElementById('kirim_email');

                    if(diproses && perbaikan && kirim_email) {
                        diproses.addEventListener('change', function() {
                            kirim_email.checked = true;
                        });

                        perbaikan.addEventListener('change', function() {
                            kirim_email.checked = false;
                        });
                    }
                });
            </script>

            @elseif($pengajuan->status_pengajuan == 'menunggu_aktivasi')

            <!-- FORM AKTIVASI -->
            <div class="bg-blue-50 p-4 rounded border border-blue-200">

                <h3 class="font-bold text-lg mb-2">
                    Aktivasi Domain
                </h3>
                <p class="text-sm text-gray-600 mb-4">
                    Status saat ini:
                    <strong>Menunggu Aktivasi</strong>
                </p>

                <form action="/admin/aktivasi/proses/{{ $pengajuan->id_pengajuan }}" method="POST" id="formAktivasi">

                    @csrf

                    <div class="flex justify-end">

                        <button
                            type="submit"
                            class="js-confirm-btn bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-6 rounded shadow transition duration-200"
                            data-confirm-message="Apakah Anda yakin ingin mengaktifkan domain ini?"
                        >
                            <i class="fas fa-check-circle mr-2"></i>
                            Aktivasikan Domain
                        </button>

                    </div>

                </form>

            </div>

            @elseif($pengajuan->status_pengajuan == 'diproses')
            
            @php
                // Logika Cek Respon Desa
                $adaKonfirmasiDesa = $pengajuan->pesan->where('judul', 'Konfirmasi Pembayaran Disetujui')->isNotEmpty();
                $fakturSudahAda = $pengajuan->faktur->where('tipe', '!=', 'perpanjangan')->isNotEmpty();
            @endphp

            <div class="bg-blue-50 p-4 rounded border border-blue-200">

                @if($adaKonfirmasiDesa && !$fakturSudahAda)
                    
                    <!-- Desa Sudah Setuju, Faktur Belum Ada -->
                    <div class="flex items-center gap-3 mb-3">
                        <div class="bg-green-100 p-2 rounded-full text-green-600">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <div>
                            <h4 class="font-bold text-gray-800">Desa Menyetujui Pembayaran</h4>
                            <p class="text-sm text-gray-600">Desa telah mengkonfirmasi kesiapan pembayaran. Silakan terbitkan faktur.</p>
                        </div>
                    </div>

                    <form action="{{ route('admin.faktur.store', $pengajuan->id_pengajuan) }}" method="POST" class="mt-2">
                        @csrf
                        <button type="submit" 
                                class="js-confirm-btn bg-red-700 hover:bg-red-800 text-white font-bold py-2 px-6 rounded shadow transition duration-200 inline-flex items-center"
                                data-confirm-message="Apakah Anda yakin ingin menerbitkan dan mencetak faktur untuk domain ini?">
                            <i class="fas fa-print mr-2"></i> Cetak Faktur
                        </button>
                    </form>

                @elseif($fakturSudahAda)

                    <!-- Faktur Sudah Ada -->
                <p class="text-blue-800 font-semibold mb-2">
                    Faktur telah diterbitkan. Menunggu pembayaran selesai sebelum dapat diaktivasi.
                </p>

                <a href="{{ route('admin.faktur.index') }}"
                class="text-sm underline hover:text-blue-700">

                    Lihat di Manajemen Faktur
                </a>

                @else

                    <!-- Belum Ada Respon -->
                    <p class="text-blue-700 font-semibold flex items-center gap-2">
                        <i class="fas fa-hourglass-half"></i>
                        Menunggu persetujuan desa untuk penerbitan faktur.
                    </p>

                @endif

            </div>

            @elseif($finalStatus == 'aktif')

            <div class="bg-green-50 p-4 rounded border border-green-200 mt-4">

                <p class="text-green-700 font-semibold">
                    Domain Sudah Aktif
                </p>

            </div>
            @elseif($finalStatus == 'kadaluarsa')

            <div class="bg-gray-100 p-4 rounded border border-gray-300 mt-4">

                <p class="text-gray-700 font-semibold">
                    Masa berlaku domain ini telah kadaluarsa pada tanggal {{ \Carbon\Carbon::parse($latestAktivasi->masa_berlaku)->format('d M Y') }}.
                </p>

            </div>
            @endif
        </div>

    </div>

</div>

<!-- MODAL -->
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
            class="px-4 py-2 bg-gray-200 text-gray-800 text-base font-medium rounded-md shadow-sm hover:bg-gray-300">
                Batal
            </button>

            <button id="modalYesBtn"
            class="px-4 py-2 bg-green-600 text-white text-base font-medium rounded-md shadow-sm hover:bg-green-700">
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