@extends('layouts.admin')

@section('title', 'Edit User')

@section('content')

<div x-data="{ role: '{{ old('role', $user->role) }}' }" class="container mx-auto px-4 py-6">

    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-semibold text-gray-800">
            Edit User: {{ $user->username }}
        </h1>

        <a href="{{ route('admin.users.index') }}"
           class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded inline-flex items-center">
            <i class="fas fa-arrow-left mr-2"></i> Kembali
        </a>
    </div>

    <div class="bg-white shadow-md rounded-lg p-6">

        <form action="{{ route('admin.users.update', $user) }}" method="POST">
            @csrf
            @method('PUT')

            {{-- ERROR ALERT --}}
            @if ($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6">
                    <strong class="font-bold">Terjadi Kesalahan!</strong>

                    <ul class="mt-2 list-disc list-inside text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- USERNAME & ROLE --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <div>
                    <label for="username" class="block text-sm font-medium text-gray-700 mb-2">
                        Username
                    </label>

                    <input
                        type="text"
                        id="username"
                        name="username"
                        value="{{ old('username', $user->username) }}"
                        placeholder="Masukkan username unik"
                        minlength="4"
                        maxlength="30"
                        title="Username minimal 4 karakter tanpa spasi"
                        oninvalid="this.setCustomValidity('Username minimal 4 karakter dan wajib diisi')"
                        oninput="this.setCustomValidity('')"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500"
                        required
                    >
                </div>

                <div>
                    <label for="role" class="block text-sm font-medium text-gray-700 mb-2">
                        Role
                    </label>

                    <select
                        id="role"
                        name="role"
                        x-model="role"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500"
                        required
                    >
                        <option value="admin">Admin</option>
                        <option value="desa">Desa</option>
                    </select>
                </div>

            </div>

            {{-- DETAIL ADMIN --}}
            <div x-show="role === 'admin'" x-transition class="mt-6">

                <h3 class="text-lg font-medium text-gray-900 mb-4">
                    Detail Admin
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                            Nama Lengkap
                        </label>

                        <input
                            type="text"
                            id="name"
                            name="name"
                            value="{{ old('name', $user->name) }}"
                            placeholder="Masukkan nama lengkap"
                            minlength="3"
                            maxlength="100"
                            title="Nama minimal 3 karakter"
                            oninvalid="this.setCustomValidity('Nama lengkap wajib diisi minimal 3 karakter')"
                            oninput="this.setCustomValidity('')"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500"
                            :required="role === 'admin'"
                        >
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                            Email
                        </label>

                        <input
                            type="email"
                            id="email"
                            name="email"
                            value="{{ old('email', $user->email) }}"
                            placeholder="contoh@email.com"
                            title="Masukkan email yang valid"
                            oninvalid="this.setCustomValidity('Masukkan alamat email yang valid')"
                            oninput="this.setCustomValidity('')"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500"
                            :required="role === 'admin'"
                        >
                    </div>

                    <div class="md:col-span-2">
                        <label for="no_hp" class="block text-sm font-medium text-gray-700 mb-2">
                            No. HP
                        </label>

                        <input
                            type="text"
                            id="no_hp"
                            name="no_hp"
                            value="{{ old('no_hp', $user->no_hp) }}"
                            placeholder="08xxxxxxxxxx"
                            pattern="[0-9]+"
                            maxlength="15"
                            title="Nomor HP hanya boleh angka"
                            oninvalid="this.setCustomValidity('Nomor HP hanya boleh berisi angka')"
                            oninput="this.setCustomValidity('')"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500"
                        >
                    </div>

                </div>
            </div>

            {{-- DETAIL DESA --}}
            <div x-show="role === 'desa'" x-transition class="mt-6">

                <h3 class="text-lg font-medium text-gray-900 mb-4">
                    Detail Desa
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    <div>
                        <label for="nama_desa" class="block text-sm font-medium text-gray-700 mb-2">
                            Nama Desa
                        </label>

                        <input
                            type="text"
                            id="nama_desa"
                            name="nama_desa"
                            value="{{ old('nama_desa', $user->desa->nama_desa ?? '') }}"
                            placeholder="Masukkan nama desa"
                            minlength="3"
                            maxlength="100"
                            title="Nama desa minimal 3 karakter"
                            oninvalid="this.setCustomValidity('Nama desa wajib diisi')"
                            oninput="this.setCustomValidity('')"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500"
                            :required="role === 'desa'"
                        >
                    </div>

                </div>
            </div>

            {{-- PASSWORD --}}
            <div class="mt-6">

                <div class="bg-yellow-50 border border-yellow-200 text-yellow-700 px-4 py-3 rounded-lg mb-4 text-sm">
                    Kosongkan password jika tidak ingin mengubah password user.
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                            Password Baru
                        </label>

                        <input
                            type="password"
                            id="password"
                            name="password"
                            placeholder="Minimal 8 karakter"
                            minlength="8"
                            title="Password minimal 8 karakter"
                            oninvalid="this.setCustomValidity('Password minimal 8 karakter')"
                            oninput="this.setCustomValidity('')"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500"
                        >
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                            Konfirmasi Password Baru
                        </label>

                        <input
                            type="password"
                            id="password_confirmation"
                            name="password_confirmation"
                            placeholder="Ulangi password baru"
                            minlength="8"
                            oninvalid="this.setCustomValidity('Konfirmasi password wajib diisi jika mengubah password')"
                            oninput="this.setCustomValidity('')"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500"
                        >
                    </div>

                </div>

            </div>

            {{-- BUTTON --}}
            <div class="mt-8 flex justify-end">

                <a href="{{ route('admin.users.index') }}"
                   class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded mr-2">
                    Batal
                </a>

                <button
                    type="submit"
                    class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded"
                >
                    Simpan Perubahan
                </button>

            </div>

        </form>

    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {

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

});
</script>

@endsection