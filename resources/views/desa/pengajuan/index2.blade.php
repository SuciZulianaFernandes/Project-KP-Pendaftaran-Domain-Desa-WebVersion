@extends('layouts.desa')
@section('title', 'Informasi Desa')
@section('content')

<!-- TOMBOL PERBAIKAN: TAMBAHKAN BLOK INI UNTUK MENAMPILKAN ERROR -->
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
<!-- AKHIR BLOK PERBAIKAN -->

<div class="bg-white rounded-xl shadow p-10">
    @include('desa.pengajuan._steps', ['currentStep' => 2])

    <div class="mt-8">
        <h2 class="text-2xl font-bold text-gray-800 mb-6">Isi Informasi Desa</h2>
        
        <form action="{{ route('pengajuan.informasi.store') }}" method="POST" id="formInformasi">
            @csrf
            
            <!-- Baris 1: Nama Desa & Klasifikasi Instansi -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label for="nama_desa" class="block text-sm font-semibold text-gray-700 mb-2">Nama Desa</label>
                    <input type="text" id="nama_desa" name="nama_desa" value="{{ old('nama_desa', $data_desa['nama_desa'] ?? '') }}" class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-red-500 focus:border-red-500 transition duration-150" placeholder="Contoh: Sukamaju" required>
                </div>
                <div>
                    <label for="klasifikasi_instansi" class="block text-sm font-semibold text-gray-700 mb-2">Klasifikasi Instansi</label>
                    <select id="klasifikasi_instansi" name="klasifikasi_instansi" class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-red-500 focus:border-red-500 transition duration-150" required>
                        <option value="">-- Pilih Klasifikasi --</option>
                        <option value="Desa" {{ old('klasifikasi_instansi', $data_desa['klasifikasi_instansi'] ?? '') === 'Desa' ? 'selected' : '' }}>Desa</option>
                        <option value="Kelurahan" {{ old('klasifikasi_instansi', $data_desa['klasifikasi_instansi'] ?? '') === 'Kelurahan' ? 'selected' : '' }}>Kelurahan</option>
                    </select>
                </div>
            </div>

            <!-- Alamat Lengkap -->
            <div class="mb-6">
                <label for="alamat" class="block text-sm font-semibold text-gray-700 mb-2">Alamat Lengkap</label>
                <textarea id="alamat" name="alamat" rows="3" class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-red-500 focus:border-red-500 transition duration-150" placeholder="Jl. Contoh No. 123" required>{{ old('alamat', $data_desa['alamat'] ?? '') }}</textarea>
            </div>

            <!-- Baris 2: Provinsi, Kota/Kabupaten, Kecamatan -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div>
                    <label for="provinsi" class="block text-sm font-semibold text-gray-700 mb-2">Provinsi</label>
                    <select id="provinsi" name="provinsi" class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-red-500 focus:border-red-500 transition duration-150" required>
                        <option value="">-- Pilih Provinsi --</option>
                        <!-- Data Provinsi akan diisi oleh JavaScript -->
                    </select>
                </div>
                <div>
                    <label for="kota_kabupaten" class="block text-sm font-semibold text-gray-700 mb-2">Kota/Kabupaten</label>
                    <select id="kota_kabupaten" name="kota_kabupaten" class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-red-500 focus:border-red-500 transition duration-150" required disabled>
                        <option value="">-- Pilih Kota --</option>
                    </select>
                </div>
                <div>
                    <label for="kecamatan" class="block text-sm font-semibold text-gray-700 mb-2">Kecamatan</label>
                    <select id="kecamatan" name="kecamatan" class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-red-500 focus:border-red-500 transition duration-150" required disabled>
                        <option value="">-- Pilih Kecamatan --</option>
                    </select>
                </div>
            </div>

            <!-- Baris 3: Desa/Kelurahan & Kode Pos -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label for="desa_kelurahan" class="block text-sm font-semibold text-gray-700 mb-2">Desa/Kelurahan</label>
                    <select id="desa_kelurahan" name="desa_kelurahan" class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-red-500 focus:border-red-500 transition duration-150" required disabled>
                        <option value="">-- Pilih Desa --</option>
                    </select>
                </div>
                <div>
                    <label for="kode_pos" class="block text-sm font-semibold text-gray-700 mb-2">Kode Pos</label>
                    <input type="text" id="kode_pos" name="kode_pos" value="{{ old('kode_pos', $data_desa['kode_pos'] ?? '') }}" class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-red-500 focus:border-red-500 transition duration-150" placeholder="Contoh: 28111" required>
                </div>
            </div>

            <!-- Baris 4: Telepon & Faksimili -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label for="Telepon" class="block text-sm font-semibold text-gray-700 mb-2">Telepon</label>
                    <input type="tel" id="Telepon" name="Telepon" value="{{ old('Telepon', $data_desa['Telepon'] ?? '') }}" class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-red-500 focus:border-red-500 transition duration-150" placeholder="Contoh: 0761123456" required>
                </div>
                <div>
                    <label for="Faksimili" class="block text-sm font-semibold text-gray-700 mb-2">Faksimili</label>
                    <input type="tel" id="Faksimili" name="Faksimili" value="{{ old('Faksimili', $data_desa['Faksimili'] ?? '') }}" class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-red-500 focus:border-red-500 transition duration-150" placeholder="Contoh: 0761123457" required>
                </div>
            </div>

            <!-- Tombol Aksi -->
            <div class="flex justify-end mt-8 space-x-4">
                <a href="{{ route('pengajuan.index') }}" class="px-7 py-3 border border-gray-300 rounded-lg text-gray-700 font-semibold hover:bg-gray-50 transition duration-150">Kembali</a>
                <button type="submit" class="px-7 py-3 bg-red-700 text-white font-semibold rounded-lg hover:bg-red-800 transition duration-150">Lanjutkan</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    // --- DEBUGGING: Periksa apakah script berjalan ---
    // Buka Console Browser (F12) dan lihat apakah muncul pesan ini.
    console.log("Script Form Pengajuan berhasil dimuat.");

    // --- DATA WILAYAH PROVINSI RIAU ---
    const addressData = {
        "Provinsi Riau": {
            "Kota Pekanbaru": {
                "Kecamatan Sukajadi": ["Kelurahan Sukajadi", "Kelurahan Paledang", "Kelurahan Tangkerang Selatan"],
                "Kecamatan Marpoyan Damai": ["Kelurahan Maharatu", "Kelurahan Sidomulyo Timur", "Kelurahan Harjosari"],
                "Kecamatan Rumbai": ["Kelurahan Umban Sari", "Kelurahan Sri Meranti", "Kelurahan Palas"]
            },
            "Kabupaten Kampar": {
                "Kecamatan Bangkinang Kota": ["Desa Bangkinang Kota", "Desa Kuok", "Desa Sungai Pagar"],
                "Kecamatan Kampar": ["Desa Kampar Kiri Hilir", "Desa Kampar Kiri Hulu", "Desa Air Tiris"],
                "Kecamatan Siak Hulu": ["Desa Kubang Jaya", "Desa Kampar Permai", "Desa Koto Masjid"]
            },
            "Kabupaten Siak": {
                "Kecamatan Siak": ["Desa Kampung Rempak", "Desa Mempura", "Desa Kampung Dalam"],
                "Kecamatan Dayun": ["Desa Dayun", "Desa Lubuk Dalam", "Desa Melibur"],
                "Kecamatan Koto Gasib": ["Desa Gasib Kecil", "Desa Suka Mulya", "Desa Pekan Heran"]
            },
            "Kabupaten Bengkalis": {
                "Kecamatan Bengkalis": ["Kelurahan Bengkalis Kota", "Kelurahan Pekanbaru", "Desa Sei Nibung"],
                "Kecamatan Mandau": ["Kelurahan Duri", "Kelurahan Balik Alam", "Desa Pematang Pudu"],
                "Kecamatan Rupat": ["Desa Tanjung Medang", "Desa Batu Panjang", "Desa Teluk Rhu"]
            }
        }
    };

    // --- ELEMEN DOM ---
    const provinsiSelect = document.getElementById('provinsi');
    const kotaSelect = document.getElementById('kota_kabupaten');
    const kecamatanSelect = document.getElementById('kecamatan');
    const desaSelect = document.getElementById('desa_kelurahan');

    // --- PERBAIKAN: Ambil data lama dengan cara yang lebih aman ---
    // Ini mencegah error jika data dari session bermasalah.
    let oldData = {};
    @if(isset($data_desa) && is_array($data_desa))
        oldData = @json($data_desa);
    @endif

    // --- FUNGSI UNTUK MENGISI DROPDOWN ---
    function populateSelect(selectElement, options, placeholder, valueToSelect = '') {
        selectElement.innerHTML = `<option value="">${placeholder}</option>`;
        if (options) {
            if (Array.isArray(options)) {
                options.forEach(item => {
                    const option = document.createElement('option');
                    option.value = item;
                    option.textContent = item;
                    if (item === valueToSelect) {
                        option.selected = true;
                    }
                    selectElement.appendChild(option);
                });
            } else {
                Object.keys(options).forEach(key => {
                    const option = document.createElement('option');
                    option.value = key;
                    option.textContent = key;
                    if (key === valueToSelect) {
                        option.selected = true;
                    }
                    selectElement.appendChild(option);
                });
            }
        }
    }

    // --- EVENT LISTENER & LOGIKA DINAMIS ---
    
    // 1. Isi Provinsi saat halaman dimuat
    populateSelect(provinsiSelect, addressData, '-- Pilih Provinsi --', oldData.provinsi ?? '');
    if (oldData.provinsi) {
        provinsiSelect.dispatchEvent(new Event('change'));
    }

    // 2. Event saat Provinsi berubah
    provinsiSelect.addEventListener('change', function() {
        console.log("Provinsi berubah ke:", this.value); // DEBUGGING
        const selectedProv = this.value;
        kotaSelect.disabled = !selectedProv;
        kecamatanSelect.disabled = true;
        desaSelect.disabled = true;
        kotaSelect.innerHTML = '<option value="">-- Pilih Kota --</option>';
        kecamatanSelect.innerHTML = '<option value="">-- Pilih Kecamatan --</option>';
        desaSelect.innerHTML = '<option value="">-- Pilih Desa --</option>';

        if (selectedProv && addressData[selectedProv]) {
            populateSelect(kotaSelect, addressData[selectedProv], '-- Pilih Kota --', oldData.kota_kabupaten ?? '');
            if (oldData.kota_kabupaten) {
                kotaSelect.dispatchEvent(new Event('change'));
            }
        }
    });

    // 3. Event saat Kota/Kabupaten berubah
    kotaSelect.addEventListener('change', function() {
        console.log("Kota berubah ke:", this.value); // DEBUGGING
        const selectedProv = provinsiSelect.value;
        const selectedKota = this.value;
        kecamatanSelect.disabled = !selectedKota;
        desaSelect.disabled = true;
        kecamatanSelect.innerHTML = '<option value="">-- Pilih Kecamatan --</option>';
        desaSelect.innerHTML = '<option value="">-- Pilih Desa --</option>';

        if (selectedProv && selectedKota && addressData[selectedProv][selectedKota]) {
            populateSelect(kecamatanSelect, addressData[selectedProv][selectedKota], '-- Pilih Kecamatan --', oldData.kecamatan ?? '');
            if (oldData.kecamatan) {
                kecamatanSelect.dispatchEvent(new Event('change'));
            }
        }
    });

    // 4. Event saat Kecamatan berubah
    kecamatanSelect.addEventListener('change', function() {
        console.log("Kecamatan berubah ke:", this.value); // DEBUGGING
        const selectedProv = provinsiSelect.value;
        const selectedKota = kotaSelect.value;
        const selectedKec = this.value;
        desaSelect.disabled = !selectedKec;
        desaSelect.innerHTML = '<option value="">-- Pilih Desa --</option>';

        if (selectedProv && selectedKota && selectedKec && addressData[selectedProv][selectedKota][selectedKec]) {
            populateSelect(desaSelect, addressData[selectedProv][selectedKota][selectedKec], '-- Pilih Desa --', oldData.desa_kelurahan ?? '');
        }
    });
});
</script>

@endpush
@endsection