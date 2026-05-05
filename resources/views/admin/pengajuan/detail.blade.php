@extends('layouts.admin')
@section('title', 'Detail Pengajuan')

@section('content')
<div class="bg-white p-6 rounded-xl shadow">

    <!-- HEADER -->
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-xl font-bold text-gray-800">Detail Pengajuan</h2>
        <a href="{{ route('admin.pengajuan.index') }}" 
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
            <strong>Status</strong> : 
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

    <!-- INFORMASI INSTANSI -->
    <h3 class="font-semibold mb-4">Informasi Instansi</h3>

    <div class="grid grid-cols-2 gap-x-10 gap-y-3 text-sm mb-6">
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
            <span class="w-48 text-gray-600">Klasifikasi Instansi</span>
            <span class="w-4 text-center">:</span>
            <span>KELURAHAN / DESA</span>
        </div>

        <div class="flex">
            <span class="w-48 text-gray-600">Kota/Kabupaten</span>
            <span class="w-4 text-center">:</span>
            <span>{{ $pengajuan->kota_kabupaten }}</span>
        </div>

        <div class="flex">
            <span class="w-48 text-gray-600">Nama Instansi</span>
            <span class="w-4 text-center">:</span>
            <span>{{ $pengajuan->nama_desa }}</span>
        </div>

        <div class="flex">
            <span class="w-48 text-gray-600">Kecamatan</span>
            <span class="w-4 text-center">:</span>
            <span>{{ $pengajuan->kecamatan }}</span>
        </div>

        <div class="flex">
            <span class="w-48 text-gray-600">Telepon</span>
            <span class="w-4 text-center">:</span>
            <span>{{ $pengajuan->telepon }}</span>
        </div>

        <div class="flex">
            <span class="w-48 text-gray-600">Desa / Kelurahan</span>
            <span class="w-4 text-center">:</span>
            <span>{{ $pengajuan->desa_kelurahan }}</span>
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

        <div class="flex col-span-2">
            <span class="w-48 text-gray-600">Tanggal Pembuatan</span>
            <span class="w-4 text-center">:</span>
            <span>{{ $pengajuan->created_at->format('d M Y') }}</span>
        </div>
    </div>

    <!-- DOKUMEN -->
    <h3 class="font-semibold mb-4">Dokumen Persyaratan Domain</h3>

    <div class="grid grid-cols-2 gap-4 mb-6 text-sm">
        @foreach($pengajuan->dokumenPersyaratan as $dok)
            <div class="flex justify-between border-b pb-2">
                <span>{{ $dok->jenis_dokumen }}</span>
                <a href="{{ asset('storage/'.$dok->path_file) }}" target="_blank" class="text-red-600 text-xs">
                    Lihat Dokumen
                </a>
            </div>
        @endforeach
    </div>

    <!-- FAKTUR -->
    @if($pengajuan->faktur->isNotEmpty())
    <div class="mb-6 bg-gray-50 p-4 rounded border">
        <h3 class="font-bold text-lg mb-3">Riwayat Data Faktur</h3>
        @foreach($pengajuan->faktur as $f)
            <div class="mb-3 p-3 bg-white border rounded">
                <p><strong>{{ $f->no_invoice }}</strong></p>
                <p>Total: Rp {{ number_format($f->total,0,',','.') }}</p>
            </div>
        @endforeach
    </div>
    @endif

    <hr class="my-4">

    <!-- VERIFIKASI -->
    @if(in_array($pengajuan->status_pengajuan, ['ditinjau', 'perlu_perbaikan']))
        <form action="{{ route('admin.verifikasi.proses', $pengajuan->id_pengajuan) }}" method="POST" id="formVerifikasi">
            @csrf
            @method('PUT')

            <h3 class="font-semibold mb-3">Hasil Verifikasi</h3>

            <div class="flex items-center gap-6 mb-4">
                <label>
                    <input type="radio" name="status" value="diproses" id="diproses"> Disetujui
                </label>

                <label>
                    <input type="radio" name="status" value="perlu_perbaikan" id="perbaikan"> Perlu Perbaikan
                </label>

                <label class="flex items-center gap-2">
                    <input type="checkbox" name="kirim_email" id="kirim_email">
                    Kirim konfirmasi pembayaran
                </label>
            </div>

            <textarea name="catatan" placeholder="Catatan..." class="w-full border p-2 rounded mb-4"></textarea>

            <div class="text-right">
                <!-- Tombol Kirim dengan Pesan Spesifik -->
                <button type="submit" class="js-confirm-btn bg-red-700 text-white px-6 py-2 rounded" data-confirm-message="Apakah anda yakin ingin mengirim verifikasi ini?">
                    Kirim
                </button>
            </div>
        </form>
        
        <!-- Script untuk form verifikasi biasa -->
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
        <!-- FORM AKTIVASI KHUSUS -->
        <div class="bg-blue-50 p-4 rounded border border-blue-200">
            <h3 class="font-bold text-lg mb-2">Aktivasi Domain</h3>
            <p class="text-sm text-gray-600 mb-4">
                Status saat ini: <strong>Menunggu Aktivasi</strong>
            </p>
            
            {{-- Menggunakan URL langsung untuk bypass error route --}}
            <form action="/admin/aktivasi/proses/{{ $pengajuan->id_pengajuan }}" method="POST" id="formAktivasi">
                @csrf
                
                <div class="flex justify-end">
                    <!-- Tombol Aktivasi dengan Pesan Spesifik -->
                    <button type="submit" class="js-confirm-btn bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-6 rounded shadow transition duration-200" data-confirm-message="Apakah Anda yakin ingin mengaktifkan domain ini?">
                        <i class="fas fa-check-circle mr-2"></i> Aktivasikan Domain
                    </button>
                </div>
            </form>
        </div>

    @else
        <div class="bg-green-50 p-4 rounded border border-green-200 mt-4">
            <p class="text-green-700 font-semibold">
                Data sudah diverifikasi.
            </p>
        </div>
    @endif

    <!-- MODAL POPUP CONFIRMATION -->
    <!-- Hidden by default (hidden class). Flex when active. -->
    <div id="confirmationModal" class="hidden fixed inset-0 bg-gray-900 bg-opacity-50 overflow-y-auto h-full w-full z-50 flex items-center justify-center">
        <!-- Modal Content -->
        <div class="relative mx-auto p-5 border w-96 shadow-lg rounded-xl bg-white">
            
            <div class="mt-3 text-center">
                <!-- Icon -->
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-yellow-100 mb-4">
                    <svg class="h-6 w-6 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </div>
                
                <!-- Pesan Dinamis -->
                <h3 class="text-lg leading-6 font-medium text-gray-900">Konfirmasi</h3>
                <div class="mt-2 px-7 py-3">
                    <p id="modalConfirmMessage" class="text-sm text-gray-500">
                        Apakah anda yakin?
                    </p>
                </div>
            </div>
            
            <!-- Buttons -->
            <div class="items-center px-4 py-3 flex justify-center gap-3">
                <button id="modalNoBtn" class="px-4 py-2 bg-gray-200 text-gray-800 text-base font-medium rounded-md shadow-sm hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-300">
                    Batal
                </button>
                <button id="modalYesBtn" class="px-4 py-2 bg-green-600 text-white text-base font-medium rounded-md shadow-sm hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-300">
                    Ya, saya yakin
                </button>
            </div>
        </div>
    </div>

    <!-- Script untuk Logika Popup Dinamis -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const modal = document.getElementById('confirmationModal');
            const yesBtn = document.getElementById('modalYesBtn');
            const noBtn = document.getElementById('modalNoBtn');
            const modalMessage = document.getElementById('modalConfirmMessage');
            const confirmBtns = document.querySelectorAll('.js-confirm-btn');
            
            let formToSubmit = null;

            // Tampilkan modal saat tombol diklik
            confirmBtns.forEach(btn => {
                btn.addEventListener('click', function(e) {
                    e.preventDefault(); // Mencegah submit langsung
                    formToSubmit = this.closest('form'); // Simpan form
                    
                    // Ambil pesan spesifik dari data attribute, atau pakai default
                    const message = this.getAttribute('data-confirm-message') || 'Apakah anda yakin?';
                    modalMessage.textContent = message;
                    
                    // Tampilkan modal
                    modal.classList.remove('hidden');
                    modal.classList.add('flex');
                });
            });

            // Logika tombol "Ya, saya yakin"
            yesBtn.addEventListener('click', function() {
                if (formToSubmit) {
                    formToSubmit.submit(); // Submit form
                }
                closeModal();
            });

            // Logika tombol "Batal"
            noBtn.addEventListener('click', function() {
                closeModal();
            });

            // Tutup modal jika klik overlay
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