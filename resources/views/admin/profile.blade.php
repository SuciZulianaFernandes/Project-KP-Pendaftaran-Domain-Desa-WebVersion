@extends('layouts.admin')

@section('title','Profil')

@section('content')

<div class="max-w-5xl mx-auto py-8">

    @if(session('success'))
        <div class="bg-green-100 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <form action="/admin/profile" method="POST">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

            <!-- DATA DIRI -->
            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-lg font-bold mb-5">Data Diri</h3>

                <div class="mb-4">
                    <label class="block text-sm font-medium mb-1">Nama Admin</label>
                    <input 
                        type="text"
                        name="name"
                        value="{{ auth()->user()->name }}"
                        class="w-full border rounded px-4 py-2"
                    >
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium mb-1">Username</label>
                    <input 
                        type="text"
                        name="username"
                        value="{{ auth()->user()->username }}"
                        class="w-full border rounded px-4 py-2"
                    >
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium mb-1">Email</label>
                    <input 
                        type="email"
                        name="email"
                        value="{{ auth()->user()->email }}"
                        class="w-full border rounded px-4 py-2"
                    >
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium mb-1">No. Hp</label>
                    <input 
                        type="text"
                        name="no_hp"
                        value="{{ auth()->user()->no_hp }}"
                        class="w-full border rounded px-4 py-2"
                    >
                </div>
            </div>

            <!-- KEAMANAN -->
            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-lg font-bold mb-5">Keamanan</h3>

                <div class="mb-4">
                    <label class="block text-sm font-medium mb-1">Password Lama</label>
                    <input 
                        type="password" 
                        name="password_lama"
                        placeholder="Masukkan password lama"
                        class="w-full border rounded px-4 py-2"
                    >
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium mb-1">Password Baru</label>
                    <input 
                        type="password" 
                        name="password_baru"
                        placeholder="Masukkan password baru"
                        class="w-full border rounded px-4 py-2"
                    >
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Konfirmasi Password</label>
                    <input 
                        type="password" 
                        name="password_baru_confirmation"
                        placeholder="Ulangi password baru"
                        class="w-full border rounded px-4 py-2"
                    >
                </div>
            </div>

        </div>

        <div class="mt-6 flex justify-end">
            <button 
                type="submit"
                class="bg-red-600 hover:bg-red-700 text-white px-6 py-2 rounded"
            >
                Simpan Perubahan
            </button>
        </div>

    </form>

</div>

@endsection