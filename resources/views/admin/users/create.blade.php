@extends('layouts.admin')

@section('title', 'Tambah User')

@section('content')
<!-- Inisialisasi Alpine.js dengan data awal -->
<div x-data="{ role: '@if(old('role')) {{ old('role') }} @else admin @endif' }" class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-semibold text-gray-800">Tambah User Baru</h1>
        <a href="{{ route('admin.users.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded inline-flex items-center">
            <i class="fas fa-arrow-left mr-2"></i> Kembali
        </a>
    </div>

    <div class="bg-white shadow-md rounded-lg p-6">
        <form action="{{ route('admin.users.store') }}" method="POST">
            @csrf

            @if ($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <strong class="font-bold">Error!</strong>
                    <ul class="mt-2 list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Kolom yang selalu muncul -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="username" class="block text-sm font-medium text-gray-700 mb-2">Username</label>
                    <input type="text" id="username" name="username" value="{{ old('username') }}" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500" required>
                </div>

                <div>
                    <label for="role" class="block text-sm font-medium text-gray-700 mb-2">Role</label>
                    <select id="role" name="role" x-model="role"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500" required>
                        <option value="">Pilih Role</option>
                        <option value="admin">Admin</option>
                        <option value="desa">Desa</option>
                    </select>
                </div>
            </div>

            <!-- Kolom khusus untuk ADMIN -->
            <div x-show="role === 'admin'" x-transition class="mt-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Detail Admin</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap</label>
                        <!-- PERUBAHAN: required menjadi :required="role === 'admin'" -->
                        <input type="text" id="name" name="name" value="{{ old('name') }}" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500" 
                               :required="role === 'admin'">
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                        <!-- PERUBAHAN: required menjadi :required="role === 'admin'" -->
                        <input type="email" id="email" name="email" value="{{ old('email') }}" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500" 
                               :required="role === 'admin'">
                    </div>

                    <div class="md:col-span-2">
                        <label for="no_hp" class="block text-sm font-medium text-gray-700 mb-2">No. HP</label>
                        <input type="text" id="no_hp" name="no_hp" value="{{ old('no_hp') }}" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500">
                    </div>
                </div>
            </div>

            <!-- Kolom khusus untuk DESA -->
            <div x-show="role === 'desa'" x-transition class="mt-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Detail Desa</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="nama_desa" class="block text-sm font-medium text-gray-700 mb-2">Nama Desa</label>
                        <!-- PERUBAHAN: required menjadi :required="role === 'desa'" -->
                        <input type="text" id="nama_desa" name="nama_desa" value="{{ old('nama_desa') }}" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500" 
                               :required="role === 'desa'">
                    </div>
                </div>
            </div>

            <!-- Kolom Password (selalu muncul) -->
            <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                    <input type="password" id="password" name="password" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500" required>
                </div>

                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">Konfirmasi Password</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500" required>
                </div>
            </div>

            <div class="mt-8 flex justify-end">
                <a href="{{ route('admin.users.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded mr-2">
                    Batal
                </a>
                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection