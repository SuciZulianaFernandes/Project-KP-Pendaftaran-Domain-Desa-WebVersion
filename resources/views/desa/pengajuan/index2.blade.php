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
        
        <form action="{{ route('desa.pengajuan.informasi.store') }}" method="POST" id="formInformasi">
            @csrf
            
            <!-- Baris 1: Nama Desa & Klasifikasi Instansi -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label for="nama_desa" class="block text-sm font-semibold text-gray-700 mb-2">Nama Desa</label>
                    <input type="text" id="nama_desa" name="nama_desa" value="{{ old('nama_desa', $data_desa['nama_desa'] ?? '') }}" class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-red-500 focus:border-red-500 transition duration-150" placeholder="Contoh: Sukamaju" required>
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
                    <label for="desa_kelurahan" class="block text-sm font-semibold text-gray-700 mb-2">Desa</label>
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
<a href="{{ route('desa.pengajuan.index') }}" class="px-7 py-3 border border-gray-300 rounded-lg text-gray-700 font-semibold hover:bg-gray-50 transition duration-150">Kembali</a>                <button type="submit" class="px-7 py-3 bg-red-700 text-white font-semibold rounded-lg hover:bg-red-800 transition duration-150">Lanjutkan</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    console.log("Script Form Pengajuan berhasil dimuat.");

    // --- DATA WILAYAH PROVINSI RIAU ---
    const addressData = {
        "Provinsi Riau": {
            "Kota Pekanbaru": {
                "Kecamatan Sukajadi": ["Kelurahan Sukajadi", "Kelurahan Paledang", "Kelurahan Tangkerang Selatan"],
                "Kecamatan Marpoyan Damai": ["Kelurahan Maharatu", "Kelurahan Sidomulyo Timur", "Kelurahan Harjosari"],
                "Kecamatan Rumbai": ["Kelurahan Umban Sari", "Kelurahan Sri Meranti", "Kelurahan Palas"]
            },
            "Kabupaten Bengkalis": {
                "Kecamatan Bengkalis": ["Desa Air Putih", "Desa Damai", "Desa Kelapapati", "Desa Kelebuk", "Desa Kelemantan", "Desa Kelemantan Barat", "Desa Ketam Putih", "Desa Kuala Alam", "Desa Meskom", "Desa Pematang Duku", "Desa Pematang Duku Timur", "Desa Penebal", "Desa Penampi", "Desa Prapat Tunggal", "Desa Sebauk", "Desa Sungai Alam", "Desa Sekodi", "Desa Senderak", "Desa Teluk Latak", "Desa Temeran", "Desa Wonosari", "Desa Pedekik", "Desa Pangkalan Batang", "Desa Pangkalan Batang Barat", "Desa Sungai Batang", "Desa Simpang Ayam"],
                "Kecamatan Mandau": ["Kelurahan Duri", "Kelurahan Balik Alam", "Desa Pematang Pudu", "Desa Guntung"],
                "Kecamatan Rupat": ["Desa Tanjung Medang", "Desa Batu Panjang", "Desa Teluk Rhu", "Desa Darul Aman"],
                "Kecamatan Siak Kecil": ["Desa Tengganau", "Desa Sungai Selari", "Desa Palkun", "Desa Jangkang"]
            },
            "Kabupaten Kampar": {
                "Kecamatan Bangkinang Kota": ["Desa Bangkinang Kota", "Desa Kuok", "Desa Sungai Pagar"],
                "Kecamatan Kampar": ["Desa Kampar Kiri Hilir", "Desa Kampar Kiri Hulu", "Desa Air Tiris"]
            }
        }
    };

    // --- ELEMEN DOM ---
    const provinsiSelect = document.getElementById('provinsi');
    const kotaSelect = document.getElementById('kota_kabupaten');
    const kecamatanSelect = document.getElementById('kecamatan');
    const desaSelect = document.getElementById('desa_kelurahan');

    // --- FUNGSI UNTUK MENGISI DROPDOWN ---
    function populateSelect(selectElement, options, placeholder, valueToSelect = '') {
        selectElement.innerHTML = `<option value="">${placeholder}</option>`;
        if (options) {
            const items = Array.isArray(options) ? options : Object.keys(options);
            items.forEach(item => {
                const option = document.createElement('option');
                option.value = item;
                option.textContent = item;
                if (item === valueToSelect) {
                    option.selected = true;
                }
                selectElement.appendChild(option);
            });
        }
    }
    
    // --- FUNGSI UNTUK MENGISI ULANG FORM SAAT HALAMAN DIMUAT ---
    function repopulateForm(data) {
        // Reset state anak-anak dropdown terlebih dahulu
        kotaSelect.disabled = true;
        kecamatanSelect.disabled = true;
        desaSelect.disabled = true;
        kotaSelect.innerHTML = '<option value="">-- Pilih Kota --</option>';
        kecamatanSelect.innerHTML = '<option value="">-- Pilih Kecamatan --</option>';
        desaSelect.innerHTML = '<option value="">-- Pilih Desa --</option>';

        if (!data.provinsi || !addressData[data.provinsi]) return;

        // 1. Isi dan Aktifkan Kota/Kabupaten
        const kotaOptions = addressData[data.provinsi];
        populateSelect(kotaSelect, kotaOptions, '-- Pilih Kota --', data.kota_kabupaten ?? '');
        kotaSelect.disabled = false;

        if (!data.kota_kabupaten || !kotaOptions[data.kota_kabupaten]) return;

        // 2. Isi dan Aktifkan Kecamatan
        const kecOptions = kotaOptions[data.kota_kabupaten];
        populateSelect(kecamatanSelect, kecOptions, '-- Pilih Kecamatan --', data.kecamatan ?? '');
        kecamatanSelect.disabled = false;

        if (!data.kecamatan || !kecOptions[data.kecamatan]) return;
        
        // 3. Isi dan Aktifkan Desa/Kelurahan
        const desaOptions = kecOptions[data.kecamatan];
        populateSelect(desaSelect, desaOptions, '-- Pilih Desa --', data.desa_kelurahan ?? '');
        desaSelect.disabled = false;
    }

    // --- INISIALISASI SAAT HALAMAN PERTAMA KALI DIMUAT ---
    // Isi dropdown Provinsi
    populateSelect(provinsiSelect, Object.keys(addressData), '-- Pilih Provinsi --');

    // Ambil data lama dari session
    let oldData = {};
    @if(isset($data_desa) && is_array($data_desa))
        oldData = @json($data_desa);
    @endif

    // Jika ada data lama, panggil fungsi untuk mengisi ulang form
    if (Object.keys(oldData).length > 0) {
        console.log("Mengisi ulang form dengan data:", oldData);
        // Pilih provinsi di dropdown
        provinsiSelect.value = oldData.provinsi;
        // Jalankan fungsi untuk mengisi anak-anaknya
        repopulateForm(oldData);
    }

    // --- EVENT LISTENER UNTUK PERUBAHAN MANUAL OLEH USER ---
    
    // Event saat Provinsi berubah
    provinsiSelect.addEventListener('change', function() {
        const selectedProv = this.value;
        kotaSelect.disabled = !selectedProv;
        kecamatanSelect.disabled = true;
        desaSelect.disabled = true;
        kecamatanSelect.innerHTML = '<option value="">-- Pilih Kecamatan --</option>';
        desaSelect.innerHTML = '<option value="">-- Pilih Desa --</option>';

        if (selectedProv && addressData[selectedProv]) {
            populateSelect(kotaSelect, addressData[selectedProv], '-- Pilih Kota --');
        }
    });

    // Event saat Kota/Kabupaten berubah
    kotaSelect.addEventListener('change', function() {
        const selectedProv = provinsiSelect.value;
        const selectedKota = this.value;
        kecamatanSelect.disabled = !selectedKota;
        desaSelect.disabled = true;
        desaSelect.innerHTML = '<option value="">-- Pilih Desa --</option>';

        if (selectedProv && selectedKota && addressData[selectedProv][selectedKota]) {
            populateSelect(kecamatanSelect, addressData[selectedProv][selectedKota], '-- Pilih Kecamatan --');
        }
    });

    // Event saat Kecamatan berubah
    kecamatanSelect.addEventListener('change', function() {
        const selectedProv = provinsiSelect.value;
        const selectedKota = kotaSelect.value;
        const selectedKec = this.value;
        desaSelect.disabled = !selectedKec;
        desaSelect.innerHTML = '<option value="">-- Pilih Desa --</option>';

        if (selectedProv && selectedKota && selectedKec && addressData[selectedProv][selectedKota][selectedKec]) {
            populateSelect(desaSelect, addressData[selectedProv][selectedKota][selectedKec], '-- Pilih Desa --');
        }
    });
});
</script>

@endpush
@endsection