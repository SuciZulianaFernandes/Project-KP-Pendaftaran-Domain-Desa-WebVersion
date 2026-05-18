@extends('layouts.desa')

@section('title', 'Profile')

@section('content')

<div class="max-w-5xl mx-auto py-8">

    @if(session('success'))
        <div class="bg-green-100 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('desa.profile.update') }}" method="POST">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

            <!-- ADMIN -->
            <div class="bg-white shadow rounded-lg p-6">

                <h3 class="text-lg font-bold mb-5">
                    Informasi Admin
                </h3>

                <div class="mb-4">
                    <label class="block text-sm font-medium mb-1">
                        Nama Admin
                    </label>

                    <input type="text"
                        value="{{ auth()->user()->name }}"
                        class="w-full border rounded px-4 py-2 bg-gray-100"
                        readonly>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium mb-1">
                        Email
                    </label>

                    <input type="email"
                        value="{{ auth()->user()->email }}"
                        class="w-full border rounded px-4 py-2 bg-gray-100"
                        readonly>
                </div>

                <hr class="my-5">

                <div class="mb-4">
                    <label class="block text-sm font-medium mb-1">
                        Password Baru
                    </label>

                    <input type="password"
                        name="password"
                        class="w-full border rounded px-4 py-2">

                    @error('password')
                        <small class="text-red-500">
                            {{ $message }}
                        </small>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">
                        Konfirmasi Password
                    </label>

                    <input type="password"
                        name="password_confirmation"
                        class="w-full border rounded px-4 py-2">
                </div>

            </div>

            <!-- DESA -->
            <div class="bg-white shadow rounded-lg p-6">

                <h3 class="text-lg font-bold mb-5">
                    Informasi Desa
                </h3>

                <div class="mb-4">
                    <label class="block text-sm font-medium mb-1">
                        Nama Desa
                    </label>

                    <input type="text"
                        name="nama_desa"
                        value="{{ old('nama_desa', $desa->nama_desa ?? '') }}"
                        class="w-full border rounded px-4 py-2">
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium mb-1">
                        Nama Kepala Desa
                    </label>

                    <input type="text"
                        name="nama_kepala_desa"
                        value="{{ old('nama_kepala_desa', $desa->nama_kepala_desa ?? '') }}"
                        class="w-full border rounded px-4 py-2">
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium mb-1">
                        NIP Kepala Desa
                    </label>

                    <input type="text"
                        name="nip_kepala_desa"
                        value="{{ old('nip_kepala_desa', $desa->nip_kepala_desa ?? '') }}"
                        class="w-full border rounded px-4 py-2">
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium mb-1">
                        Alamat
                    </label>

                    <textarea name="alamat"
                        rows="4"
                        class="w-full border rounded px-4 py-2">{{ old('alamat', $desa->alamat ?? '') }}</textarea>
                </div>

                <div class="grid grid-cols-2 gap-4">

                    <div>
                        <label class="block text-sm font-medium mb-1">
                            ID Provinsi
                        </label>

                        <input type="text"
                            name="id_prov"
                            value="{{ old('id_prov', $desa->id_prov ?? '') }}"
                            class="w-full border rounded px-4 py-2">
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-1">
                            ID Kabupaten
                        </label>

                        <input type="text"
                            name="id_kab"
                            value="{{ old('id_kab', $desa->id_kab ?? '') }}"
                            class="w-full border rounded px-4 py-2">
                    </div>

                </div>

            </div>

        </div>

        <div class="mt-6 flex justify-end">
            <button type="submit"
                class="bg-red-600 hover:bg-red-700 text-white px-6 py-2 rounded">
                Simpan Perubahan
            </button>
        </div>

    </form>

</div>

@endsection