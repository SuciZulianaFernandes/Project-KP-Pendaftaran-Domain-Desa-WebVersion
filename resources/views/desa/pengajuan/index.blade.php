@extends('layouts.desa')

@section('title','Pendaftaran Domain')

@section('content')

<div class="bg-white rounded-xl shadow p-10">
    <div class="flex justify-center mb-12">
    </div>

    <h2 class="text-xl font-semibold mb-10 text-gray-700">
        Pendaftaran Domain
    </h2>

    <!-- STEP -->
    <div class="flex justify-center mb-12">
        <div class="flex items-center w-full max-w-4xl">
            <div class="flex flex-col items-center">
                <div class="w-10 h-10 bg-red-700 text-white rounded-full flex items-center justify-center font-semibold">1</div>
                <span class="text-sm mt-2">Input Nama Domain</span>
            </div>
            <div class="flex-1 h-1 bg-gray-300 mx-4"></div>
            <div class="flex flex-col items-center">
                <div class="w-10 h-10 bg-gray-300 text-white rounded-full flex items-center justify-center">2</div>
                <span class="text-sm mt-2 text-gray-500">Informasi Instansi</span>
            </div>
            <div class="flex-1 h-1 bg-gray-300 mx-4"></div>
            <div class="flex flex-col items-center">
                <div class="w-10 h-10 bg-gray-300 text-white rounded-full flex items-center justify-center">3</div>
                <span class="text-sm mt-2 text-gray-500">Persyaratan Domain</span>
            </div>
            <div class="flex-1 h-1 bg-gray-300 mx-4"></div>
            <div class="flex flex-col items-center">
                <div class="w-10 h-10 bg-gray-300 text-white rounded-full flex items-center justify-center">4</div>
                <span class="text-sm mt-2 text-gray-500">Pratinjau</span>
            </div>
        </div>
    </div>

    <!-- FORM -->
    <div class="flex justify-center">
        <div class="w-full max-w-md">
            <h3 class="font-semibold text-gray-700 mb-5 text-center">
                Inputkan Nama Domain Baru Anda
            </h3>

            <!-- Informasi Cek Domain -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                <p class="text-sm text-blue-800 mb-2">
                    <span class="font-semibold">ℹ️ Informasi:</span> Sebelum mendaftar, pastikan untuk mengecek nama domain yang akan anda daftarkan di:
                </p>
                <div class="flex flex-col gap-1 ml-5">
                    <a href="https://domain.go.id/" target="_blank" class="text-red-700 hover:underline font-medium text-sm inline-flex items-center gap-1">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                        https://domain.go.id/
                    </a>
                    <a href="https://pandi.id/" target="_blank" class="text-red-700 hover:underline font-medium text-sm inline-flex items-center gap-1">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                        https://pandi.id/
                    </a>
                </div>
            </div>

            <p class="text-center text-gray-600 text-sm mb-5">Masukkan nama domain yang ingin didaftarkan (Minimal 3 huruf)</p>

            <form id="form-domain" action="{{ route('desa.pengajuan.informasi') }}" method="GET">
                <div class="space-y-3">
                    <!-- Input dan Suffix -->
                    <div class="flex border rounded-lg overflow-hidden focus-within:ring-2 focus-within:ring-red-700 focus-within:border-transparent">
                        <input
                            type="text"
                            id="domain-input"
                            name="nama_domain"
                            placeholder="Gunakan minimal 3 huruf atau angka"
                            value="{{ old('nama_domain', session('pengajuan.nama_domain')) }}" 
                            class="flex-1 px-4 py-3 focus:outline-none"
                            autocomplete="off"
                            required
                            minlength="3"
                        />
                        <span class="px-4 flex items-center bg-gray-100 text-gray-600 font-medium">
                            .desa.id
                        </span>
                    </div>

                    <!-- Error Message -->
                    <div id="error-message" class="hidden text-red-600 text-sm text-center bg-red-50 p-2 rounded-lg"></div>

                    <!-- Tombol Daftar -->
                    <button type="submit" id="btn-daftar" class="w-full bg-red-800 hover:bg-red-700 text-white font-semibold py-3 rounded-lg transition-colors duration-200 flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Daftar
                    </button>
                </div>
            </form>

            <!-- Preview Domain -->
            <div id="domain-preview" class="mt-6 hidden">
                <div class="bg-gray-100 p-4 rounded-lg border border-gray-200 text-center">
                    <p class="text-sm text-gray-500">Domain yang akan didaftarkan:</p>
                    <p id="preview-text" class="text-lg font-semibold text-red-700 mt-1"></p>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const domainInput = document.getElementById('domain-input');
    const formDomain = document.getElementById('form-domain');
    const errorMessage = document.getElementById('error-message');
    const domainPreview = document.getElementById('domain-preview');
    const previewText = document.getElementById('preview-text');

    // Hanya izinkan huruf dan angka
    domainInput.addEventListener('keydown', function(event) {
        const key = event.key;
        const regex = /^[a-zA-Z0-9]$/;
        const allowedSpecialKeys = ['Backspace', 'Delete', 'Tab', 'ArrowLeft', 'ArrowRight', 'ArrowUp', 'ArrowDown'];

        if (key === 'Enter') {
            return; // Biar form submit
        }

        if (regex.test(key) || allowedSpecialKeys.includes(key)) {
            return;
        }

        event.preventDefault();
    });

    // Real-time preview
    domainInput.addEventListener('input', function() {
        const domain = this.value.toLowerCase().replace(/[^a-zA-Z0-9]/g, '');
        
        if (domain.length >= 3) {
            domainPreview.classList.remove('hidden');
            previewText.textContent = domain + '.desa.id';
            errorMessage.classList.add('hidden');
        } else {
            domainPreview.classList.add('hidden');
        }
    });

    // Validasi sebelum submit
    formDomain.addEventListener('submit', function(event) {
        const domain = domainInput.value.toLowerCase().replace(/[^a-zA-Z0-9]/g, '');
        
        // Update input value dengan cleaned value
        domainInput.value = domain;

        if (domain.length < 3) {
            event.preventDefault();
            errorMessage.textContent = 'Nama domain minimal 3 karakter (huruf/angka)';
            errorMessage.classList.remove('hidden');
            return;
        }

        // Tampilkan konfirmasi
        const konfirmasi = confirm(`Anda akan mendaftarkan domain:\n\n${domain}.desa.id\n\nPastikan domain sudah Anda cek ketersediaannya di domain.go.id atau pandi.id\n\nLanjutkan?`);
        
        if (!konfirmasi) {
            event.preventDefault();
        }
    });

    // Fokus ke input saat halaman load
    domainInput.focus();
});
</script>
@endpush

@endsection