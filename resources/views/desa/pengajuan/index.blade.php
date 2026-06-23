@extends('layouts.desa')

@section('title','Pendaftaran Domain')

@section('content')

<div class="bg-white rounded-3xl shadow-xl border border-slate-100 p-10">

    @include('desa.pengajuan._steps', ['currentStep' => 1])

    <!-- FORM -->
    <div class="flex justify-center">
        <div class="w-full max-w-md">

            <h3 class="font-semibold text-gray-700 mb-5 text-center">
                Inputkan Nama Domain Baru Anda
            </h3>

            <!-- Informasi Cek Domain -->
            <div class="bg-gradient-to-r from-[#109696]/10 via-[#1A85A5]/10 to-[#1760C5]/10 border border-[#1A85A5]/20 rounded-xl p-4 mb-6">
                <p class="text-sm text-[#1760C5] mb-2">
                    <span class="font-semibold">ℹ️ Informasi:</span>
                    Sebelum mendaftar, pastikan untuk mengecek nama domain yang akan anda daftarkan di:
                </p>

                <div class="flex flex-col gap-1 ml-5">
                    <a href="https://domain.go.id/" target="_blank"
                        class="text-[#1760C5] hover:underline font-medium text-sm inline-flex items-center gap-1">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14">
                            </path>
                        </svg>
                        https://domain.go.id/
                    </a>

                    <a href="https://pandi.id/" target="_blank"
                        class="text-[#1760C5] hover:underline font-medium text-sm inline-flex items-center gap-1">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14">
                            </path>
                        </svg>
                        https://pandi.id/
                    </a>
                </div>
            </div>

            <p class="text-center text-gray-600 text-sm mb-5">
                Masukkan nama domain yang ingin didaftarkan (Minimal 3 huruf)
            </p>

            <form id="form-domain" action="{{ route('desa.pengajuan.informasi') }}" method="GET">
                <div class="space-y-3">

                    <!-- Input dan Suffix -->
                    <div
                        class="flex border rounded-lg overflow-hidden focus-within:ring-2 focus-within:ring-[#1A85A5] focus-within:border-transparent">

                        <input type="text" id="domain-input" name="nama_domain"
                            placeholder="Gunakan minimal 3 huruf atau angka"
                            value="{{ old('nama_domain', session('pengajuan.nama_domain')) }}"
                            class="flex-1 px-4 py-3 focus:outline-none"
                            autocomplete="off"
                            required
                            minlength="3" />

                        <span class="px-4 flex items-center bg-gray-100 text-gray-600 font-medium">
                            .desa.id
                        </span>
                    </div>

                    <!-- Error Message -->
                    <div id="error-message"
                        class="hidden text-red-600 text-sm text-center bg-red-50 p-2 rounded-lg">
                    </div>

                    <!-- Tombol Daftar -->
                    <button type="submit" id="btn-daftar"
                        class="w-full bg-gradient-to-r from-[#109696] via-[#1A85A5] to-[#1760C5] hover:opacity-90 text-white font-semibold py-3 rounded-lg transition-all duration-200 flex items-center justify-center gap-2 shadow-lg">

                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z">
                            </path>
                        </svg>

                        Daftar
                    </button>

                </div>
            </form>

            <!-- Preview Domain -->
            <div id="domain-preview" class="mt-6 hidden">
                <div class="bg-gray-100 p-4 rounded-lg border border-gray-200 text-center">
                    <p class="text-sm text-gray-500">
                        Domain yang akan didaftarkan:
                    </p>
                    <p id="preview-text" class="text-lg font-semibold text-[#1760C5] mt-1"></p>
                </div>
            </div>

        </div>
    </div>
</div>

{{-- MODAL KONFIRMASI --}}
<div id="confirmModal" class="hidden fixed inset-0 bg-slate-900/60 backdrop-blur-sm h-full w-full z-50 flex items-center justify-center p-4">
    <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-sm transform transition-all">
        <div class="p-6 text-center">
            <div class="mx-auto flex items-center justify-center h-14 w-14 rounded-full bg-gradient-to-r from-[#109696]/10 via-[#1A85A5]/10 to-[#1760C5]/10 mb-4">
                <i class="fas fa-globe text-[#1760C5] text-xl"></i>
            </div>
            <h3 class="text-lg font-bold text-slate-800 mb-2">Konfirmasi Pendaftaran</h3>
            <p id="confirmMessage" class="text-sm text-slate-500 leading-relaxed"></p>
        </div>
        <div class="px-6 pb-6 flex items-center justify-center gap-3">
            <button id="confirmNoBtn" class="flex-1 py-2.5 bg-slate-100 text-slate-600 text-sm font-semibold rounded-xl hover:bg-slate-200 transition">Batal</button>
            <button id="confirmYesBtn" class="flex-1 py-2.5 bg-gradient-to-r from-[#109696] via-[#1A85A5] to-[#1760C5] hover:opacity-90 text-white text-sm font-semibold rounded-xl transition shadow-sm">Ya, Lanjutkan</button>
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
    const confirmModal = document.getElementById('confirmModal');
    const confirmMessage = document.getElementById('confirmMessage');
    const confirmYesBtn = document.getElementById('confirmYesBtn');
    const confirmNoBtn = document.getElementById('confirmNoBtn');

    // Hanya izinkan huruf dan angka
    domainInput.addEventListener('keydown', function(event) {
        const key = event.key;
        const regex = /^[a-zA-Z0-9]$/;
        const allowedSpecialKeys = [
            'Backspace',
            'Delete',
            'Tab',
            'ArrowLeft',
            'ArrowRight',
            'ArrowUp',
            'ArrowDown'
        ];

        if (key === 'Enter') {
            return;
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

        domainInput.value = domain;

        if (domain.length < 3) {
            event.preventDefault();
            errorMessage.textContent = 'Nama domain minimal 3 karakter (huruf/angka)';
            errorMessage.classList.remove('hidden');
            return;
        }

        event.preventDefault();
        confirmMessage.innerHTML = 'Anda akan mendaftarkan domain:<br><span class="font-bold text-[#1760C5] text-base">' + domain + '.desa.id</span><br><br><span class="text-xs">Pastikan domain sudah Anda cek ketersediaannya di domain.go.id atau pandi.id</span>';
        confirmModal.classList.remove('hidden');
        confirmModal.classList.add('flex');
    });

    confirmYesBtn.addEventListener('click', function() {
        closeModal();
        formDomain.submit();
    });

    confirmNoBtn.addEventListener('click', closeModal);
    confirmModal.addEventListener('click', function(e) { if (e.target === confirmModal) closeModal(); });

    function closeModal() {
        confirmModal.classList.add('hidden');
        confirmModal.classList.remove('flex');
    }

    domainInput.focus();
});
</script>
@endpush

@endsection