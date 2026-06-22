@extends('layouts.admin')

@section('title', 'Edit User')

@section('content')

<div x-data="{ role: '{{ old('role', $user->role) }}' }" class="space-y-6">

    <!-- HEADER -->
    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
        <div>
            <h1 class="text-2xl font-extrabold text-slate-800 tracking-tight">Edit User: {{ $user->username }}</h1>
            <p class="text-sm text-slate-400 mt-1">Ubah data akun user yang sudah terdaftar</p>
        </div>
        <a href="{{ route('admin.users.index') }}"
           class="inline-flex items-center gap-2 bg-slate-100 hover:bg-slate-200 text-slate-600 font-semibold py-2.5 px-5 rounded-xl transition text-sm">
            <i class="fas fa-arrow-left text-xs"></i> Kembali
        </a>
    </div>

    <div class="bg-white p-6 md:p-8 rounded-2xl border border-slate-100 shadow-sm">

        <form action="{{ route('admin.users.update', $user) }}" method="POST">
            @csrf
            @method('PUT')

            {{-- ERROR ALERT --}}
            @if ($errors->any())
                <div class="bg-rose-50 border border-rose-200 text-rose-700 px-5 py-4 rounded-xl mb-6">
                    <div class="flex items-center gap-2 mb-2">
                        <i class="fas fa-exclamation-circle text-rose-500"></i>
                        <strong class="font-bold text-sm">Terjadi Kesalahan!</strong>
                    </div>
                    <ul class="list-disc list-inside text-sm space-y-0.5 ml-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- AKUN LOGIN --}}
            <div class="mb-8">
                <h3 class="text-sm font-bold text-slate-800 uppercase tracking-wider mb-4 flex items-center gap-2">
                    <span class="w-1.5 h-5 bg-gradient-to-b from-[#109696] to-[#1760C5] rounded-full"></span>
                    Akun Login
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-5 bg-slate-50/50 p-5 rounded-xl border border-slate-100">
                    <div class="flex flex-col">
                        <label for="username" class="text-slate-400 text-xs font-semibold mb-1.5">Username <span class="text-rose-500">*</span></label>
                        <input
                            type="text"
                            id="username"
                            name="username"
                            value="{{ old('username', $user->username) }}"
                            placeholder="Masukkan username unik"
                            minlength="4"
                            maxlength="30"
                            class="w-full border border-slate-200 rounded-lg text-sm p-2.5 bg-white focus:outline-none focus:ring-2 focus:ring-[#109696]/20 focus:border-[#109696] transition"
                            required
                        >
                    </div>

                    <div class="flex flex-col">
                        <label for="role" class="text-slate-400 text-xs font-semibold mb-1.5">Role <span class="text-rose-500">*</span></label>
                        <select
                            id="role"
                            name="role"
                            x-model="role"
                            class="w-full border border-slate-200 rounded-lg text-sm p-2.5 bg-white focus:outline-none focus:ring-2 focus:ring-[#109696]/20 focus:border-[#109696] transition"
                            required
                        >
                            <option value="admin">Admin</option>
                            <option value="desa">Desa</option>
                        </select>
                    </div>
                </div>
            </div>

            {{-- DETAIL ADMIN --}}
            <div x-show="role === 'admin'" x-transition.duration.200ms class="mb-8">
                <h3 class="text-sm font-bold text-slate-800 uppercase tracking-wider mb-4 flex items-center gap-2">
                    <span class="w-1.5 h-5 bg-gradient-to-b from-[#109696] to-[#1760C5] rounded-full"></span>
                    Informasi Admin
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-5 bg-slate-50/50 p-5 rounded-xl border border-slate-100">
                    <div class="flex flex-col">
                        <label for="name" class="text-slate-400 text-xs font-semibold mb-1.5">Nama Lengkap <span class="text-rose-500">*</span></label>
                        <input
                            type="text"
                            id="name"
                            name="name"
                            value="{{ old('name', $user->name) }}"
                            placeholder="Masukkan nama lengkap"
                            minlength="3"
                            maxlength="100"
                            class="w-full border border-slate-200 rounded-lg text-sm p-2.5 bg-white focus:outline-none focus:ring-2 focus:ring-[#109696]/20 focus:border-[#109696] transition"
                            :required="role === 'admin'"
                        >
                    </div>

                    <div class="flex flex-col">
                        <label for="email" class="text-slate-400 text-xs font-semibold mb-1.5">Email <span class="text-rose-500">*</span></label>
                        <input
                            type="email"
                            id="email"
                            name="email"
                            value="{{ old('email', $user->email) }}"
                            placeholder="contoh@email.com"
                            class="w-full border border-slate-200 rounded-lg text-sm p-2.5 bg-white focus:outline-none focus:ring-2 focus:ring-[#109696]/20 focus:border-[#109696] transition"
                            :required="role === 'admin'"
                        >
                    </div>

                    <div class="flex flex-col md:col-span-2">
                        <label for="no_hp" class="text-slate-400 text-xs font-semibold mb-1.5">No. HP</label>
                        <input
                            type="text"
                            id="no_hp"
                            name="no_hp"
                            value="{{ old('no_hp', $user->no_hp) }}"
                            placeholder="08xxxxxxxxxx"
                            pattern="[0-9]+"
                            maxlength="15"
                            class="w-full border border-slate-200 rounded-lg text-sm p-2.5 bg-white focus:outline-none focus:ring-2 focus:ring-[#109696]/20 focus:border-[#109696] transition"
                        >
                    </div>
                </div>
            </div>

            {{-- DETAIL DESA --}}
            <div x-show="role === 'desa'" x-transition.duration.200ms class="mb-8">
                <h3 class="text-sm font-bold text-slate-800 uppercase tracking-wider mb-4 flex items-center gap-2">
                    <span class="w-1.5 h-5 bg-gradient-to-b from-[#109696] to-[#1760C5] rounded-full"></span>
                    Informasi Desa
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-5 bg-slate-50/50 p-5 rounded-xl border border-slate-100">
                    <div class="flex flex-col">
                        <label for="nama_desa" class="text-slate-400 text-xs font-semibold mb-1.5">Nama Desa <span class="text-rose-500">*</span></label>
                        <input
                            type="text"
                            id="nama_desa"
                            name="nama_desa"
                            value="{{ old('nama_desa', $user->desa->nama_desa ?? '') }}"
                            placeholder="Masukkan nama desa"
                            minlength="3"
                            maxlength="100"
                            class="w-full border border-slate-200 rounded-lg text-sm p-2.5 bg-white focus:outline-none focus:ring-2 focus:ring-[#109696]/20 focus:border-[#109696] transition"
                            :required="role === 'desa'"
                        >
                    </div>
                </div>
            </div>

            {{-- PASSWORD --}}
            <div class="mb-8">
                <h3 class="text-sm font-bold text-slate-800 uppercase tracking-wider mb-4 flex items-center gap-2">
                    <span class="w-1.5 h-5 bg-gradient-to-b from-[#109696] to-[#1760C5] rounded-full"></span>
                    Ubah Password
                </h3>

                <div class="bg-amber-50 border border-amber-200 text-amber-700 px-4 py-3 rounded-xl mb-4 text-sm flex items-center gap-2">
                    <i class="fas fa-info-circle text-amber-500"></i>
                    Kosongkan jika tidak ingin mengubah password.
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-5 bg-slate-50/50 p-5 rounded-xl border border-slate-100">
                    <div class="flex flex-col">
                        <label for="password" class="text-slate-400 text-xs font-semibold mb-1.5">Password Baru</label>
                        <input
                            type="password"
                            id="password"
                            name="password"
                            placeholder="Minimal 8 karakter"
                            minlength="8"
                            class="w-full border border-slate-200 rounded-lg text-sm p-2.5 bg-white focus:outline-none focus:ring-2 focus:ring-[#109696]/20 focus:border-[#109696] transition"
                        >
                    </div>

                    <div class="flex flex-col">
                        <label for="password_confirmation" class="text-slate-400 text-xs font-semibold mb-1.5">Konfirmasi Password Baru</label>
                        <input
                            type="password"
                            id="password_confirmation"
                            name="password_confirmation"
                            placeholder="Ulangi password baru"
                            minlength="8"
                            class="w-full border border-slate-200 rounded-lg text-sm p-2.5 bg-white focus:outline-none focus:ring-2 focus:ring-[#109696]/20 focus:border-[#109696] transition"
                        >
                    </div>
                </div>
            </div>

            <hr class="border-slate-100">

            {{-- BUTTON --}}
            <div class="mt-6 flex flex-col sm:flex-row sm:items-center sm:justify-end gap-3">
                <a href="{{ route('admin.users.index') }}"
                   class="inline-flex items-center justify-center gap-2 bg-slate-100 hover:bg-slate-200 text-slate-600 font-semibold py-2.5 px-6 rounded-xl transition text-sm">
                    Batal
                </a>

                <button
                    type="submit"
                    class="js-confirm-btn inline-flex items-center justify-center gap-2 bg-[#109696] hover:bg-[#0d7a7a] text-white font-bold py-2.5 px-6 rounded-xl shadow-sm transition text-sm"
                    data-confirm-message="Yakin ingin menyimpan perubahan data user {{ $user->username }}?"
                >
                    <i class="fas fa-save text-xs"></i> Simpan Perubahan
                </button>
            </div>

        </form>

    </div>
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

    // Validasi password hanya jika diisi
    const password = document.getElementById('password');
    const confirmPassword = document.getElementById('password_confirmation');

    function validatePassword() {
        if (password.value.length > 0 || confirmPassword.value.length > 0) {
            if (password.value !== confirmPassword.value) {
                confirmPassword.setCustomValidity('Konfirmasi password tidak sama');
            } else {
                confirmPassword.setCustomValidity('');
            }
        } else {
            confirmPassword.setCustomValidity('');
        }
    }

    password.addEventListener('input', validatePassword);
    confirmPassword.addEventListener('input', validatePassword);

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