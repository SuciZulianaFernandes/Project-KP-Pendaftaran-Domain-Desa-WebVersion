@extends('layouts.admin')
@section('title', 'Detail Perpanjangan Domain')

@section('content')
<div class="bg-white p-6 rounded-xl shadow">

    <!-- HEADER -->
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-xl font-bold text-gray-800">Detail Perpanjangan Domain</h2>
        <a href="{{ route('admin.perpanjang.list') }}" 
           class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded inline-flex items-center">
            <i class="fas fa-arrow-left mr-2"></i> Kembali
        </a>
    </div>

    <!-- STATUS -->
    <div class="mb-6">
        <p class="mb-2">
            <strong>Domain</strong> : {{ $pengajuan->nama_domain }}.desa.id
        </p>
        <p>
            <strong>Status Domain</strong> : 
            <span class="px-2 py-1 rounded text-white 
                @if($pengajuan->status_pengajuan == 'ditinjau') bg-yellow-500
                @elseif($pengajuan->status_pengajuan == 'perlu_perbaikan') bg-red-500
                @elseif($pengajuan->status_pengajuan == 'diproses') bg-blue-500
                @elseif($pengajuan->status_pengajuan == 'menunggu_aktivasi') bg-orange-500
                @elseif($pengajuan->status_pengajuan == 'aktif') bg-green-600
                @endif">
                {{ ucfirst(str_replace('_', ' ', $pengajuan->status_pengajuan)) }}
            </span>
        </p>
    </div>

    <!-- INFORMASI FAKTUR (KHUSUS PERPANJANGAN) -->
    <div class="mb-6 bg-gray-50 p-4 rounded border border-blue-100">
        <h3 class="font-bold text-lg mb-3 text-blue-800">Data Perpanjangan</h3>
        <div class="grid grid-cols-2 gap-4 text-sm">
            <div>
                <span class="text-gray-600">No Invoice</span>
                <p class="font-semibold">{{ $faktur->no_invoice }}</p>
            </div>
            <div>
                <span class="text-gray-600">Tgl Faktur</span>
                <p class="font-semibold">{{ $faktur->created_at->format('d M Y H:i') }}</p>
            </div>
            <div>
                <span class="text-gray-600">Status Pembayaran</span>
                <p class="font-semibold">
                    @if($faktur->status == 'sudah_bayar') 
                        <span class="text-green-600">Lunas</span>
                    @else 
                        <span class="text-red-600">Belum Lunas</span>
                    @endif
                </p>
            </div>
            <div>
                <span class="text-gray-600">Total Tagihan</span>
                <p class="font-semibold">Rp {{ number_format($faktur->total,0,',','.') }}</p>
            </div>
        </div>
    </div>

    <!-- INFORMASI INSTANSI (Sama dengan detail biasa) -->
    <h3 class="font-semibold mb-4">Informasi Instansi</h3>
    <div class="grid grid-cols-2 gap-x-10 gap-y-3 text-sm mb-6">
        <div class="flex"><span class="w-48 text-gray-600">Nama Desa</span><span class="w-4 text-center">:</span><span>{{ $pengajuan->nama_desa }}</span></div>
        <div class="flex"><span class="w-48 text-gray-600">Provinsi</span><span class="w-4 text-center">:</span><span>{{ $pengajuan->provinsi }}</span></div>
        <div class="flex"><span class="w-48 text-gray-600">Kab/Kota</span><span class="w-4 text-center">:</span><span>{{ $pengajuan->kota_kabupaten }}</span></div>
        <div class="flex"><span class="w-48 text-gray-600">Kecamatan</span><span class="w-4 text-center">:</span><span>{{ $pengajuan->kecamatan }}</span></div>
        <div class="flex col-span-2"><span class="w-48 text-gray-600">Alamat</span><span class="w-4 text-center">:</span><span>{{ $pengajuan->alamat }}</span></div>
    </div>

    <!-- RIWAYAT FAKTUR -->
    <div class="mb-6 bg-gray-50 p-4 rounded border">
        <h3 class="font-bold text-lg mb-3">Riwayat Domain</h3>
        <div class="mb-2 text-sm">
            <p>Tgl Aktivasi Terakhir: <strong>{{ $pengajuan->aktivasi ? $pengajuan->aktivasi->tgl_aktivasi->format('d M Y') : '-' }}</strong></p>
            <p>Masa Berlaku Hingga: <strong>{{ $pengajuan->aktivasi ? $pengajuan->aktivasi->masa_berlaku->format('d M Y') : '-' }}</strong></p>
        </div>
    </div>

    <hr class="my-4">

    <!-- AKTIVASI PERPANJANGAN -->
    @if($pengajuan->status_pengajuan == 'menunggu_aktivasi' && $faktur->status == 'sudah_bayar')
        <!-- FORM AKTIVASI KHUSUS -->
        <div class="bg-blue-50 p-4 rounded border border-blue-200">
            <h3 class="font-bold text-lg mb-2">Aktivasi Perpanjangan</h3>
            <p class="text-sm text-gray-600 mb-4">
                Status saat ini: <strong>Menunggu Aktivasi</strong>. Lakukan aktivasi untuk memperbarui masa berlaku domain.
            </p>
            
            <form action="/admin/aktivasi/proses/{{ $pengajuan->id_pengajuan }}" method="POST" id="formAktivasi">
                @csrf
                
                <div class="flex justify-end">
                    <button type="submit" class="js-confirm-btn bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-6 rounded shadow transition duration-200" data-confirm-message="Apakah Anda yakin ingin mengaktifkan perpanjangan domain ini?">
                        <i class="fas fa-check-circle mr-2"></i> Aktivasikan Sekarang
                    </button>
                </div>
            </form>
        </div>
    @elseif($pengajuan->status_pengajuan == 'aktif')
        <div class="bg-green-50 p-4 rounded border border-green-200 mt-4">
            <p class="text-green-700 font-semibold">
                Domain sudah aktif dan diperpanjang.
            </p>
        </div>
    @else
        <div class="bg-yellow-50 p-4 rounded border border-yellow-200 mt-4">
            <p class="text-yellow-800 font-semibold">
                Menunggu pembayaran selesai sebelum dapat diaktivasi.
            </p>
        </div>
    @endif

    <!-- MODAL POPUP CONFIRMATION (SAMA DENGAN SEBELUMNYA) -->
    <div id="confirmationModal" class="hidden fixed inset-0 bg-gray-900 bg-opacity-50 overflow-y-auto h-full w-full z-50 flex items-center justify-center">
        <div class="relative mx-auto p-5 border w-96 shadow-lg rounded-xl bg-white">
            <div class="mt-3 text-center">
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-yellow-100 mb-4">
                    <svg class="h-6 w-6 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </div>
                <h3 class="text-lg leading-6 font-medium text-gray-900">Konfirmasi</h3>
                <div class="mt-2 px-7 py-3">
                    <p id="modalConfirmMessage" class="text-sm text-gray-500">Apakah anda yakin?</p>
                </div>
            </div>
            <div class="items-center px-4 py-3 flex justify-center gap-3">
                <button id="modalNoBtn" class="px-4 py-2 bg-gray-200 text-gray-800 text-base font-medium rounded-md shadow-sm hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-300">Batal</button>
                <button id="modalYesBtn" class="px-4 py-2 bg-green-600 text-white text-base font-medium rounded-md shadow-sm hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-300">Ya, saya yakin</button>
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

</div>
@endsection