@extends('layouts.desa')

@section('title', 'Profile')

@section('content')
<div class="space-y-6">

    <!-- HEADER -->
    <div>
        <h1 class="text-2xl font-extrabold text-slate-800 tracking-tight">Profil Desa</h1>
        <p class="text-sm text-slate-400 mt-1">Kelola informasi akun admin dan detail data desa</p>
    </div>

    {{-- FLASH MESSAGE SUCCESS --}}
    @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-5 py-4 rounded-xl text-sm flex items-center justify-between">
            <div class="flex items-center gap-2">
                <i class="fas fa-check-circle text-emerald-500"></i>
                {{ session('success') }}
            </div>
            <button type="button" onclick="this.closest('div').remove()" class="text-emerald-400 hover:text-emerald-600 transition">
                <i class="fas fa-times"></i>
            </button>
        </div>
    @endif

    {{-- FLASH MESSAGE ERROR SESSION --}}
    @if(session('error'))
        <div class="bg-rose-50 border border-rose-200 text-rose-700 px-5 py-4 rounded-xl text-sm flex items-center justify-between mt-4">
            <div class="flex items-center gap-2">
                <i class="fas fa-exclamation-circle text-rose-500"></i>
                {{ session('error') }}
            </div>
            <button type="button" onclick="this.closest('div').remove()" class="text-rose-400 hover:text-rose-600 transition">
                <i class="fas fa-times"></i>
            </button>
        </div>
    @endif

    {{-- FLASH MESSAGE VALIDATION ERRORS --}}
    @if($errors->any())
        <div class="bg-rose-50 border border-rose-200 text-rose-700 px-5 py-4 rounded-xl text-sm mt-4">
            <div class="flex items-center gap-2 font-bold mb-2">
                <i class="fas fa-exclamation-triangle text-rose-500"></i>
                Ada kesalahan dalam pengisian form:
            </div>
            <ul class="list-disc list-inside ml-5">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('desa.profile.update') }}" method="POST" id="profileForm">
    @csrf
    @method('PUT')

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden flex flex-col">
            <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50 flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-[#109696]/10 flex items-center justify-center">
                    <i class="fas fa-user-shield text-[#109696] text-sm"></i>
                </div>
                <div>
                    <h3 class="text-sm font-bold text-slate-800">Informasi Admin</h3>
                    <p class="text-xs text-slate-400">Data diri dan keamanan akun desa</p>
                </div>
            </div>

            <div class="p-6 flex-1">
                <div class="bg-slate-50/50 p-5 rounded-xl border border-slate-100 space-y-5 mb-5">
                    
                    <div class="flex flex-col">
                        <label class="text-slate-400 text-xs font-semibold mb-1.5">Nama Admin <span class="text-rose-500">*</span></label>
                        <input
                            type="text"
                            name="name"
                            value="{{ auth()->user()->name }}"
                            class="w-full border border-slate-200 rounded-lg text-sm p-2.5 bg-white focus:outline-none focus:ring-2 focus:ring-[#109696]/20 focus:border-[#109696] transition"
                            required
                        >
                    </div>

                    <div class="flex flex-col">
                        <label class="text-slate-400 text-xs font-semibold mb-1.5">Username <span class="text-rose-500">*</span></label>
                        <input
                            type="text"
                            name="username"
                            value="{{ auth()->user()->username }}"
                            class="w-full border border-slate-200 rounded-lg text-sm p-2.5 bg-white focus:outline-none focus:ring-2 focus:ring-[#109696]/20 focus:border-[#109696] transition"
                            required
                        >
                    </div>

                    <div class="flex flex-col">
                        <label class="text-slate-400 text-xs font-semibold mb-1.5">Email <span class="text-rose-500">*</span></label>
                        <input
                            type="email"
                            name="email"
                            value="{{ auth()->user()->email }}"
                            class="w-full border border-slate-200 rounded-lg text-sm p-2.5 bg-white focus:outline-none focus:ring-2 focus:ring-[#109696]/20 focus:border-[#109696] transition"
                            required
                        >
                    </div>
                </div>

                    <div class="bg-amber-50 border border-amber-200 text-amber-700 px-4 py-3 rounded-xl mb-5 text-sm flex items-center gap-2">
    <i class="fas fa-info-circle text-amber-500"></i>
    Kosongkan jika tidak ingin mengubah password.
</div>

<div class="bg-slate-50/50 p-5 rounded-xl border border-slate-100 space-y-5">
    <div class="flex flex-col">
        <label class="text-slate-400 text-xs font-semibold mb-1.5">Password Baru</label>
        <div class="relative">
            <input
                type="password"
                name="password"
                id="password"
                placeholder="Minimal 8 karakter"
                class="w-full border border-slate-200 rounded-lg text-sm p-2.5 pr-10 bg-white focus:outline-none focus:ring-2 focus:ring-[#109696]/20 focus:border-[#109696] transition"
            >
            <button type="button" onclick="togglePassword('password', 'eye_desa_baru')" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 focus:outline-none">
                <i id="eye_desa_baru" class="fas fa-eye"></i>
            </button>
        </div>
    </div>

    <div class="flex flex-col">
        <label class="text-slate-400 text-xs font-semibold mb-1.5">Konfirmasi Password</label>
        <div class="relative">
            <input
                type="password"
                name="password_confirmation"
                id="password_confirmation"
                placeholder="Ulangi password baru"
                class="w-full border border-slate-200 rounded-lg text-sm p-2.5 pr-10 bg-white focus:outline-none focus:ring-2 focus:ring-[#109696]/20 focus:border-[#109696] transition"
            >
            <button type="button" onclick="togglePassword('password_confirmation', 'eye_desa_confirm')" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 focus:outline-none">
                <i id="eye_desa_confirm" class="fas fa-eye"></i>
            </button>
        </div>
    </div>
</div>
                </div>
            </div>

            <!-- INFORMASI DESA -->
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden flex flex-col">
                <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50 flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-[#1760C5]/10 flex items-center justify-center">
                        <i class="fas fa-building text-[#1760C5] text-sm"></i>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-slate-800">Informasi Desa</h3>
                        <p class="text-xs text-slate-400">Detail data wilayah dan aparat desa</p>
                    </div>
                </div>

                <div class="p-6 flex-1">
                    <div class="bg-slate-50/50 p-5 rounded-xl border border-slate-100 space-y-5 h-full">
                        <div class="flex flex-col">
                            <label class="text-slate-400 text-xs font-semibold mb-1.5">Nama Desa</label>
                            <input
                                type="text"
                                name="nama_desa"
                                value="{{ old('nama_desa', $desa->nama_desa ?? '') }}"
                                class="w-full border border-slate-200 rounded-lg text-sm p-2.5 bg-white focus:outline-none focus:ring-2 focus:ring-[#109696]/20 focus:border-[#109696] transition"
                            >
                        </div>

                        <div class="flex flex-col">
                            <label class="text-slate-400 text-xs font-semibold mb-1.5">Nama Kepala Desa</label>
                            <input
                                type="text"
                                name="nama_kepala_desa"
                                value="{{ old('nama_kepala_desa', $desa->nama_kepala_desa ?? '') }}"
                                class="w-full border border-slate-200 rounded-lg text-sm p-2.5 bg-white focus:outline-none focus:ring-2 focus:ring-[#109696]/20 focus:border-[#109696] transition"
                            >
                        </div>

                        <div class="flex flex-col">
                            <label class="text-slate-400 text-xs font-semibold mb-1.5">NIP Kepala Desa</label>
                            <input
                                type="text"
                                name="nip_kepala_desa"
                                value="{{ old('nip_kepala_desa', $desa->nip_kepala_desa ?? '') }}"
                                class="w-full border border-slate-200 rounded-lg text-sm p-2.5 bg-white focus:outline-none focus:ring-2 focus:ring-[#109696]/20 focus:border-[#109696] transition"
                            >
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div class="flex flex-col">
                                <label class="text-slate-400 text-xs font-semibold mb-1.5">Provinsi</label>
                                <input
                                    type="text"
                                    name="id_prov"
                                    value="{{ old('id_prov', $desa->id_prov ?? '') }}"
                                    class="w-full border border-slate-200 rounded-lg text-sm p-2.5 bg-white focus:outline-none focus:ring-2 focus:ring-[#109696]/20 focus:border-[#109696] transition"
                                >
                            </div>

                            <div class="flex flex-col">
                                <label class="text-slate-400 text-xs font-semibold mb-1.5">Kabupaten</label>
                                <input
                                    type="text"
                                    name="id_kab"
                                    value="{{ old('id_kab', $desa->id_kab ?? '') }}"
                                    class="w-full border border-slate-200 rounded-lg text-sm p-2.5 bg-white focus:outline-none focus:ring-2 focus:ring-[#109696]/20 focus:border-[#109696] transition"
                                >
                            </div>
                        </div>

                        <div class="flex flex-col">
                            <label class="text-slate-400 text-xs font-semibold mb-1.5">Alamat Lengkap</label>
                            <textarea
                                name="alamat"
                                rows="3"
                                class="w-full border border-slate-200 rounded-lg text-sm p-2.5 bg-white focus:outline-none focus:ring-2 focus:ring-[#109696]/20 focus:border-[#109696] transition resize-none"
                            >{{ old('alamat', $desa->alamat ?? '') }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <!-- TOMBOL AKSI -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-end gap-3 mt-6">
            <a href="{{ url()->previous() }}"
               class="inline-flex items-center justify-center gap-2 bg-slate-100 hover:bg-slate-200 text-slate-600 font-semibold py-2.5 px-6 rounded-xl transition text-sm">
                Batal
            </a>
            <button
                type="submit"
                class="js-confirm-btn inline-flex items-center justify-center gap-2 bg-[#109696] hover:bg-[#0d7a7a] text-white font-bold py-2.5 px-6 rounded-xl shadow-sm transition text-sm"
                data-confirm-message="Yakin ingin menyimpan perubahan profil desa?"
            >
                <i class="fas fa-save text-xs"></i> Simpan Perubahan
            </button>
        </div>

    </form>
</div>

<!-- MODAL KONFIRMASI -->
<div id="confirmationModal" class="hidden fixed inset-0 bg-slate-900/60 backdrop-blur-sm h-full w-full z-50 flex items-center justify-center p-4">
    <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-sm transform transition-all">
        <div class="p-6 text-center">
            <div class="mx-auto flex items-center justify-center h-14 w-14 rounded-full bg-[#109696]/10 mb-4">
                <i class="fas fa-question text-[#109696] text-xl"></i>
            </div>
            <h3 class="text-lg font-bold text-slate-800 mb-2">Konfirmasi Aksi</h3>
            <p id="modalConfirmMessage" class="text-sm text-slate-500 leading-relaxed">Apakah anda yakin?</p>
        </div>
        <div class="px-6 pb-6 flex items-center justify-center gap-3">
            <button id="modalNoBtn" class="flex-1 py-2.5 bg-slate-100 text-slate-600 text-sm font-semibold rounded-xl hover:bg-slate-200 transition">Batal</button>
            <button id="modalYesBtn" class="flex-1 py-2.5 bg-[#109696] text-white text-sm font-semibold rounded-xl hover:bg-[#0d7a7a] transition shadow-sm">Ya, Lanjutkan</button>
        </div>
    </div>
</div>

<script>
function togglePassword(inputId, iconId) {
    const passwordInput = document.getElementById(inputId);
    const eyeIcon = document.getElementById(iconId);
    
    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        eyeIcon.classList.remove('fa-eye');
        eyeIcon.classList.add('fa-eye-slash');
    } else {
        passwordInput.type = 'password';
        eyeIcon.classList.remove('fa-eye-slash');
        eyeIcon.classList.add('fa-eye');
    }
}

document.addEventListener('DOMContentLoaded', function () {

    // Validasi konfirmasi password dinamis
    const password = document.getElementById('password');
    const passwordConfirm = document.getElementById('password_confirmation');

    function validatePassword() {
        if (password.value.length > 0 || passwordConfirm.value.length > 0) {
            if (password.value !== passwordConfirm.value) {
                passwordConfirm.setCustomValidity('Konfirmasi password tidak sama');
            } else {
                passwordConfirm.setCustomValidity('');
            }
        } else {
            passwordConfirm.setCustomValidity('');
        }
    }

    if (password && passwordConfirm) {
        password.addEventListener('input', validatePassword);
        passwordConfirm.addEventListener('input', validatePassword);
    }

    // Modal konfirmasi
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
            modalMessage.textContent = this.getAttribute('data-confirm-message') || 'Apakah anda yakin?';
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        });
    });

    yesBtn.addEventListener('click', function() {
        if (formToSubmit) formToSubmit.submit();
        closeModal();
    });

    noBtn.addEventListener('click', closeModal);
    modal.addEventListener('click', function(e) { if (e.target === modal) closeModal(); });

    function closeModal() {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        formToSubmit = null;
    }
});
</script>
@endsection