@extends('layouts.admin')

@section('title', 'Profil')

@section('content')
<div class="space-y-6">

    <!-- HEADER -->
    <div>
        <h1 class="text-2xl font-extrabold text-slate-800 tracking-tight">Profil Saya</h1>
        <p class="text-sm text-slate-400 mt-1">Kelola informasi akun dan keamanan</p>
    </div>

    {{-- FLASH MESSAGE --}}
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

    <form action="/admin/profile" method="POST" id="profileForm">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

            <!-- DATA DIRI -->
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50 flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-[#109696]/10 flex items-center justify-center">
                        <i class="fas fa-user text-[#109696] text-sm"></i>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-slate-800">Data Diri</h3>
                        <p class="text-xs text-slate-400">Informasi pribadi akun admin</p>
                    </div>
                </div>

                <div class="p-6">
                    <div class="bg-slate-50/50 p-5 rounded-xl border border-slate-100 space-y-5">
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

                        <div class="flex flex-col">
                            <label class="text-slate-400 text-xs font-semibold mb-1.5">No. HP</label>
                            <input
                                type="text"
                                name="no_hp"
                                value="{{ auth()->user()->no_hp }}"
                                pattern="[0-9]+"
                                maxlength="15"
                                class="w-full border border-slate-200 rounded-lg text-sm p-2.5 bg-white focus:outline-none focus:ring-2 focus:ring-[#109696]/20 focus:border-[#109696] transition"
                            >
                        </div>
                    </div>
                </div>
            </div>

            <!-- KEAMANAN -->
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50 flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-[#1760C5]/10 flex items-center justify-center">
                        <i class="fas fa-shield-alt text-[#1760C5] text-sm"></i>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-slate-800">Keamanan</h3>
                        <p class="text-xs text-slate-400">Ubah password akun</p>
                    </div>
                </div>

                <div class="p-6">
                    <div class="bg-amber-50 border border-amber-200 text-amber-700 px-4 py-3 rounded-xl mb-5 text-sm flex items-center gap-2">
                        <i class="fas fa-info-circle text-amber-500"></i>
                        Kosongkan jika tidak ingin mengubah password.
                    </div>

                    <div class="bg-slate-50/50 p-5 rounded-xl border border-slate-100 space-y-5">
                        <div class="flex flex-col">
                            <label class="text-slate-400 text-xs font-semibold mb-1.5">Password Lama</label>
                            <input
                                type="password"
                                name="password_lama"
                                placeholder="Masukkan password lama"
                                class="w-full border border-slate-200 rounded-lg text-sm p-2.5 bg-white focus:outline-none focus:ring-2 focus:ring-[#109696]/20 focus:border-[#109696] transition"
                            >
                        </div>

                        <div class="flex flex-col">
                            <label class="text-slate-400 text-xs font-semibold mb-1.5">Password Baru</label>
                            <input
                                type="password"
                                name="password_baru"
                                id="password_baru"
                                placeholder="Minimal 8 karakter"
                                minlength="8"
                                class="w-full border border-slate-200 rounded-lg text-sm p-2.5 bg-white focus:outline-none focus:ring-2 focus:ring-[#109696]/20 focus:border-[#109696] transition"
                            >
                        </div>

                        <div class="flex flex-col">
                            <label class="text-slate-400 text-xs font-semibold mb-1.5">Konfirmasi Password Baru</label>
                            <input
                                type="password"
                                name="password_baru_confirmation"
                                id="password_baru_confirmation"
                                placeholder="Ulangi password baru"
                                minlength="8"
                                class="w-full border border-slate-200 rounded-lg text-sm p-2.5 bg-white focus:outline-none focus:ring-2 focus:ring-[#109696]/20 focus:border-[#109696] transition"
                            >
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-end gap-3 mt-6">
            <a href="{{ url()->previous() }}"
               class="inline-flex items-center justify-center gap-2 bg-slate-100 hover:bg-slate-200 text-slate-600 font-semibold py-2.5 px-6 rounded-xl transition text-sm">
                Batal
            </a>
            <button
                type="submit"
                class="js-confirm-btn inline-flex items-center justify-center gap-2 bg-[#109696] hover:bg-[#0d7a7a] text-white font-bold py-2.5 px-6 rounded-xl shadow-sm transition text-sm"
                data-confirm-message="Yakin ingin menyimpan perubahan profil?"
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
document.addEventListener('DOMContentLoaded', function () {

    // Validasi konfirmasi password
    const passwordBaru = document.getElementById('password_baru');
    const passwordConfirm = document.getElementById('password_baru_confirmation');

    function validatePassword() {
        if (passwordBaru.value.length > 0 || passwordConfirm.value.length > 0) {
            if (passwordBaru.value !== passwordConfirm.value) {
                passwordConfirm.setCustomValidity('Konfirmasi password tidak sama');
            } else {
                passwordConfirm.setCustomValidity('');
            }
        } else {
            passwordConfirm.setCustomValidity('');
        }
    }

    if (passwordBaru && passwordConfirm) {
        passwordBaru.addEventListener('input', validatePassword);
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