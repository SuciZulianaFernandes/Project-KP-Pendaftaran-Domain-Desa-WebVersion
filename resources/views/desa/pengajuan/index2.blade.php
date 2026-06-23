@extends('layouts.desa')
@section('title', 'Informasi Desa')
@section('content')

@if ($errors->any())
    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
        <p class="font-bold">Perbaiki Kesalahan Berikut:</p>
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="bg-white rounded-3xl shadow-xl border border-slate-100 p-10">

    @include('desa.pengajuan._steps', ['currentStep' => 2])

    <div class="mt-8">

        <h2 class="text-2xl font-bold text-gray-800 mb-6">
            Isi Informasi Desa
        </h2>

        <form action="{{ route('desa.pengajuan.informasi.store') }}"
              method="POST"
              id="formInformasi">

            @csrf

            <!-- Nama Organisasi -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">

                <div>
                    <label for="nama_desa"
                           class="block text-sm font-semibold text-gray-700 mb-2">
                        Nama Organisasi
                        <span class="text-red-600">*</span>
                    </label>

                    <input type="text"
                           id="nama_desa"
                           name="nama_desa"
                           value="{{ old('nama_desa', $data_desa['nama_desa'] ?? '') }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-[#1A85A5] focus:border-[#1A85A5] transition duration-150"
                           placeholder="Contoh: Pemerintah Desa XXX"
                           required>
                </div>

            </div>

            <!-- Alamat -->
            <div class="mb-6">

                <label for="alamat"
                       class="block text-sm font-semibold text-gray-700 mb-2">
                    Alamat Lengkap
                    <span class="text-red-600">*</span>
                </label>

                <textarea id="alamat"
                          name="alamat"
                          rows="3"
                          class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-[#1A85A5] focus:border-[#1A85A5] transition duration-150"
                          placeholder="Jl. Contoh No. 123"
                          required>{{ old('alamat', $data_desa['alamat'] ?? '') }}</textarea>

            </div>

            <!-- Provinsi Kabupaten Kecamatan -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">

                <!-- PROVINSI -->
                <div>

                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Provinsi
                        <span class="text-red-600">*</span>
                    </label>

                    <div class="w-full px-4 py-3 bg-gray-100 border border-gray-300 rounded-lg text-gray-700 flex items-center shadow-sm">
                        Provinsi Riau
                    </div>

                    <input type="hidden"
                           name="provinsi"
                           value="Provinsi Riau">

                </div>

                <!-- KABUPATEN -->
                <div>

                    <label for="kota_kabupaten"
                           class="block text-sm font-semibold text-gray-700 mb-2">
                        Kabupaten
                        <span class="text-red-600">*</span>
                    </label>

                    <select id="kota_kabupaten"
                            name="kota_kabupaten"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-[#1A85A5] focus:border-[#1A85A5] transition duration-150 bg-white"
                            required>

                        <option value="">
                            -- Pilih Kabupaten --
                        </option>

                    </select>

                    @error('kota_kabupaten')
                        <p class="text-red-500 text-xs mt-1">
                            {{ $message }}
                        </p>
                    @enderror

                </div>

                <!-- KECAMATAN -->
                <div>

                    <label for="kecamatan"
                           class="block text-sm font-semibold text-gray-700 mb-2">
                        Kecamatan
                        <span class="text-red-600">*</span>
                    </label>

                    <select id="kecamatan"
                            name="kecamatan"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-[#1A85A5] focus:border-[#1A85A5] transition duration-150 bg-white"
                            required
                            disabled>

                        <option value="">
                            -- Pilih Kecamatan --
                        </option>

                    </select>

                    @error('kecamatan')
                        <p class="text-red-500 text-xs mt-1">
                            {{ $message }}
                        </p>
                    @enderror

                </div>

            </div>

            <!-- Desa & Kode Pos -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">

                <div>

                    <label for="desa_kelurahan"
                           class="block text-sm font-semibold text-gray-700 mb-2">
                        Desa
                        <span class="text-red-600">*</span>
                    </label>

                    <select id="desa_kelurahan"
                            name="desa_kelurahan"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-[#1A85A5] focus:border-[#1A85A5] transition duration-150 bg-white"
                            required
                            disabled>

                        <option value="">
                            -- Pilih Desa --
                        </option>

                    </select>

                    @error('desa_kelurahan')
                        <p class="text-red-500 text-xs mt-1">
                            {{ $message }}
                        </p>
                    @enderror

                </div>

                <div>

                    <label for="kode_pos"
                           class="block text-sm font-semibold text-gray-700 mb-2">
                        Kode Pos
                        <span class="text-red-600">*</span>
                    </label>

                    <input type="text"
                           id="kode_pos"
                           name="kode_pos"
                           value="{{ old('kode_pos', $data_desa['kode_pos'] ?? '') }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-[#1A85A5] focus:border-[#1A85A5] transition duration-150"
                           placeholder="Contoh: 28111"
                           required>

                </div>

            </div>

            <!-- Telepon & Faksimili -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">

                <div>

                    <label for="Telepon"
                           class="block text-sm font-semibold text-gray-700 mb-2">
                        Telepon
                        <span class="text-red-600">*</span>
                    </label>

                    <input type="tel"
                           id="Telepon"
                           name="Telepon"
                           value="{{ old('Telepon', $data_desa['Telepon'] ?? '') }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-[#1A85A5] focus:border-[#1A85A5] transition duration-150"
                           placeholder="Contoh: 0761123456"
                           required>

                </div>

                <div>

                    <label for="Faksimili"
                           class="block text-sm font-semibold text-gray-700 mb-2">
                        Faksimili
                    </label>

                    <input type="tel"
                           id="Faksimili"
                           name="Faksimili"
                           value="{{ old('Faksimili', $data_desa['Faksimili'] ?? '') }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-[#1A85A5] focus:border-[#1A85A5] transition duration-150"
                           placeholder="Contoh: 0761123457">

                </div>

            </div>

            <!-- BUTTON -->
            <div class="flex justify-end mt-8 space-x-4">

                <a href="{{ route('desa.pengajuan.index') }}"
                   class="px-7 py-3 border border-gray-300 rounded-lg text-gray-700 font-semibold hover:bg-gray-50 transition duration-150">
                    Kembali
                </a>

                <button type="submit"
                        class="px-7 py-3 bg-gradient-to-r from-[#109696] via-[#1A85A5] to-[#1760C5] hover:opacity-90 text-white font-semibold rounded-lg transition-all duration-200 shadow-lg">

                    Lanjutkan

                </button>

            </div>

        </form>

    </div>

</div>

@push('scripts')

<script>
document.addEventListener('DOMContentLoaded', function () {

    const PROVINSI_RIAU_ID = '14';
    const API_URL = 'https://www.emsifa.com/api-wilayah-indonesia/api';

    const kotaSelect = document.getElementById('kota_kabupaten');
    const kecamatanSelect = document.getElementById('kecamatan');
    const desaSelect = document.getElementById('desa_kelurahan');

    async function fetchData(endpoint) {

        try {

            const response = await fetch(`${API_URL}${endpoint}`);

            if (!response.ok) {
                throw new Error('Network response was not ok');
            }

            return await response.json();

        } catch (error) {

            console.error("Gagal fetch API:", error);
            return null;

        }

    }

    // LOAD KABUPATEN
    async function loadKabupaten() {

        kotaSelect.innerHTML =
            '<option value="">Memuat data Kabupaten...</option>';

        const allRegencies =
            await fetchData(`/regencies/${PROVINSI_RIAU_ID}.json`);

        if (!allRegencies) {

            kotaSelect.innerHTML =
                '<option value="">Gagal memuat data</option>';

            return;
        }

        const kabupatenOnly =
            allRegencies.filter(item => !item.name.includes('Kota'));

        kotaSelect.innerHTML =
            '<option value="">-- Pilih Kabupaten --</option>';

        kabupatenOnly.forEach(kab => {

            const option = document.createElement('option');

            // DISIMPAN KE DATABASE
            option.value = kab.name;

            // UNTUK API
            option.dataset.id = kab.id;

            // TAMPILAN
            option.textContent = kab.name;

            kotaSelect.appendChild(option);

        });

    }

    // LOAD KECAMATAN
    async function loadKecamatan(kabId) {

        if (!kabId) {

            kecamatanSelect.innerHTML =
                '<option value="">-- Pilih Kecamatan --</option>';

            kecamatanSelect.disabled = true;

            return;
        }

        kecamatanSelect.disabled = false;

        kecamatanSelect.innerHTML =
            '<option value="">Memuat data Kecamatan...</option>';

        const districts =
            await fetchData(`/districts/${kabId}.json`);

        if (!districts) {

            kecamatanSelect.innerHTML =
                '<option value="">Gagal memuat data</option>';

            return;
        }

        kecamatanSelect.innerHTML =
            '<option value="">-- Pilih Kecamatan --</option>';

        districts.forEach(disc => {

            const option = document.createElement('option');

            // DISIMPAN
            option.value = disc.name;

            // UNTUK API
            option.dataset.id = disc.id;

            option.textContent = disc.name;

            kecamatanSelect.appendChild(option);

        });

    }

    // LOAD DESA
    async function loadDesa(kecId) {

        if (!kecId) {

            desaSelect.innerHTML =
                '<option value="">-- Pilih Desa --</option>';

            desaSelect.disabled = true;

            return;
        }

        desaSelect.disabled = false;

        desaSelect.innerHTML =
            '<option value="">Memuat data Desa...</option>';

        const villages =
            await fetchData(`/villages/${kecId}.json`);

        if (!villages) {

            desaSelect.innerHTML =
                '<option value="">Gagal memuat data</option>';

            return;
        }

        desaSelect.innerHTML =
            '<option value="">-- Pilih Desa --</option>';

        villages.forEach(vill => {

            const option = document.createElement('option');

            // DISIMPAN
            option.value = vill.name;

            option.textContent = vill.name;

            desaSelect.appendChild(option);

        });

    }

    // EVENT KABUPATEN
    kotaSelect.addEventListener('change', function() {

        const selectedOption =
            this.options[this.selectedIndex];

        const kabId =
            selectedOption.dataset.id;

        loadKecamatan(kabId);

        desaSelect.innerHTML =
            '<option value="">-- Pilih Desa --</option>';

        desaSelect.disabled = true;

    });

    // EVENT KECAMATAN
    kecamatanSelect.addEventListener('change', function() {

        const selectedOption =
            this.options[this.selectedIndex];

        const kecId =
            selectedOption.dataset.id;

        loadDesa(kecId);

    });

    loadKabupaten();

});
</script>

@endpush
@endsection