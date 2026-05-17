@extends('layouts.desa')
@section('title', 'Pratinjau Pengajuan')

@section('content')

<div class="bg-white rounded-md shadow p-6 md:p-8">

    <!-- JUDUL -->
    <div class="mb-8">
        <h1 class="text-sm font-semibold text-gray-700">
            Pendaftaran Domain
        </h1>
    </div>

    @include('desa.pengajuan._steps', ['currentStep' => 4])

    <div class="mt-10">

        <!-- SUB JUDUL -->
        <div class="text-center mb-8">
            <h2 class="font-semibold text-gray-800 text-sm">
                Pratinjau
            </h2>
        </div>

        <!-- INFORMASI INSTANSI -->
<div class="grid grid-cols-1 gap-5 mb-5">

    <div class="border border-gray-200 rounded-md overflow-hidden bg-white">

        <div class="bg-red-800 text-white px-4 py-3 font-semibold text-sm rounded-t-md">
            Informasi Instansi
        </div>

        <div class="overflow-x-auto">

            <table class="w-full text-sm">

                <tr class="border-b border-gray-200">

                    <td class="px-4 py-4 text-blue-700 font-medium w-1/4 bg-gray-50">
                        Nama Organisasi
                    </td>

                    <td class="px-4 py-4 w-1/4">
                        {{ $data['data_desa']['nama_desa'] ?? '-' }}
                    </td>

                    <td class="px-4 py-4 text-blue-700 font-medium w-1/4 bg-gray-50">
                        Provinsi
                    </td>

                    <td class="px-4 py-4">
                        {{ $data['data_desa']['provinsi'] ?? '-' }}
                    </td>

                </tr>

                <tr class="border-b border-gray-200">

                    <td class="px-4 py-4 text-blue-700 font-medium bg-gray-50">
                        Kabupaten
                    </td>

                    <td class="px-4 py-4">
                        {{ session('nama_kabupaten') ?? ($data['data_desa']['kota_kabupaten'] ?? '-') }}
                    </td>

                    <td class="px-4 py-4 text-blue-700 font-medium bg-gray-50">
                        Kecamatan
                    </td>

                    <td class="px-4 py-4">
                        {{ session('nama_kecamatan') ?? ($data['data_desa']['kecamatan'] ?? '-') }}
                    </td>

                </tr>

                <tr class="border-b border-gray-200">

                    <td class="px-4 py-4 text-blue-700 font-medium bg-gray-50">
                        Desa
                    </td>

                    <td class="px-4 py-4">
                        {{ session('nama_desa') ?? ($data['data_desa']['desa_kelurahan'] ?? '-') }}
                    </td>

                    <td class="px-4 py-4 text-blue-700 font-medium bg-gray-50">
                        Kode Pos
                    </td>

                    <td class="px-4 py-4">
                        {{ $data['data_desa']['kode_pos'] ?? '-' }}
                    </td>

                </tr>

                <tr class="border-b border-gray-200">

                    <td class="px-4 py-4 text-blue-700 font-medium bg-gray-50">
                        Telepon
                    </td>

                    <td class="px-4 py-4">
                        {{ $data['data_desa']['Telepon'] ?? '-' }}
                    </td>

                    <td class="px-4 py-4 text-blue-700 font-medium bg-gray-50">
                        Faksimili
                    </td>

                    <td class="px-4 py-4">
                        {{ $data['data_desa']['Faksimili'] ?? '-' }}
                    </td>

                </tr>

                <tr class="border-b border-gray-200">

                    <td class="px-4 py-4 text-blue-700 font-medium bg-gray-50">
                        Email Registran
                    </td>

                    <td class="px-4 py-4">
                        {{ auth()->user()->email ?? '-' }}
                    </td>

                    <td class="px-4 py-4 text-blue-700 font-medium bg-gray-50">
                        Alamat
                    </td>

                    <td class="px-4 py-4">
                        {{ $data['data_desa']['alamat'] ?? '-' }}
                    </td>

                </tr>

                <tr>

                    <td class="px-4 py-4 text-blue-700 font-medium bg-gray-50">
                        Tanggal Pembuatan
                    </td>

                    <td class="px-4 py-4">
                        {{ date('d-m-Y') }}
                    </td>

                    <td></td>
                    <td></td>

                </tr>

            </table>

        </div>

    </div>

</div>
        <!-- INFORMASI DOMAIN -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-5 mb-5">

            <!-- DOMAIN -->
            <div class="border border-gray-200 rounded-md overflow-hidden bg-white">

                <div class="bg-red-800 text-white px-4 py-3 font-semibold text-sm rounded-t-md">
                    Informasi Domain
                </div>

                <table class="w-full text-sm">

                    <tr>

                        <td class="px-4 py-4 text-blue-700 w-1/2">
                            Nama Domain
                        </td>

                        <td class="px-4 py-4 text-right">
                            {{ $data['nama_domain'] }}.desa.id
                        </td>

                    </tr>

                </table>

            </div>

            <!-- MASA AKTIF -->
            <div class="border border-gray-200 rounded-md overflow-hidden bg-white">

                <div class="bg-red-800 text-white px-4 py-3 font-semibold text-sm rounded-t-md">
                    Masa Aktif
                </div>

                <table class="w-full text-sm">

                    <tr><td class="px-4 py-4 text-blue-700 w-1/2">
                            Masa Aktif
                        </td>

                        <td class="px-4 py-4 text-right">
                            1 Tahun Rp 50,000
                        </td>

                    </tr>

                </table>

            </div>

        </div>

        <!-- DOKUMEN -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-5 mb-8">

            <!-- KOLOM KIRI -->
            <div class="border border-gray-200 rounded-md overflow-hidden bg-white">

                <div class="bg-red-800 text-white px-4 py-3 font-semibold text-sm rounded-t-md">
                    Dokumen Persyaratan
                </div>

                <table class="w-full text-sm">

                    <tr class="border-b">

                        <td class="px-4 py-4 text-blue-700 w-1/2">
                            Surat Permohonan
                        </td>

                        <td class="px-4 py-4">
                            {{ $data['data_dokumen']['surat_permohonan']['nama_file'] ?? '-' }}
                        </td>

                    </tr>

                    <tr class="border-b">

                        <td class="px-4 py-4 text-blue-700">
                            Surat Kuasa
                        </td>

                        <td class="px-4 py-4">
                            {{ $data['data_dokumen']['surat_kuasa']['nama_file'] ?? '-' }}
                        </td>

                    </tr>

                    <tr>

                        <td class="px-4 py-4 text-blue-700">
                            Surat Penunjukan Pejabat
                        </td>

                        <td class="px-4 py-4">
                            {{ $data['data_dokumen']['surat_penunjukan_pejabat']['nama_file'] ?? '-' }}
                        </td>

                    </tr>

                </table>

            </div>

            <!-- KOLOM KANAN -->
            <div class="border border-gray-200 rounded-md overflow-hidden bg-white">

                <div class="bg-red-800 text-white px-4 py-3 font-semibold text-sm rounded-t-md">
                    &nbsp;
                </div>

                <table class="w-full text-sm">

                    <tr class="border-b">

                        <td class="px-4 py-4 text-blue-700 w-1/2">
                            Kartu Pegawai
                        </td>

                        <td class="px-4 py-4">
                            {{ $data['data_dokumen']['ktp_asn_pejabat']['nama_file'] ?? '-' }}
                        </td>

                    </tr>

                    <tr>

                        <td class="px-4 py-4 text-blue-700">
                            Dasar Hukum Pembentukan Desa
                        </td>

                        <td class="px-4 py-4">
                            {{ $data['data_dokumen']['perda_pembentukan_desa']['nama_file'] ?? '-' }}
                        </td>

                    </tr>

                </table>

            </div>

        </div>

        <!-- FORM -->
        <form id="formPengajuan"
            action="{{ route('desa.pengajuan.submit') }}"
            method="POST">

            @csrf

            <div class="flex justify-end gap-3">

                <a href="{{ route('desa.pengajuan.dokumen') }}"
                class="px-6 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded text-sm">
                    &lt; Kembali
                </a>

                <button
                    type="submit"
                    id="btnAjukan"
                    class="px-8 py-2 bg-red-800 hover:bg-red-900 text-white rounded text-sm flex items-center gap-2"
                >

                    <span id="btnText">
                        Submit
                    </span>

                    <svg id="btnSpinner"
                    class="animate-spin h-4 w-4 text-white hidden"
                    xmlns="http://www.w3.org/2000/svg"
                    fill="none"
                    viewBox="0 0 24 24">

                        <circle class="opacity-25"
                        cx="12"
                        cy="12"
                        r="10"
                        stroke="currentColor"
                        stroke-width="4"></circle>

                        <path class="opacity-75"
                        fill="currentColor"
                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>

                    </svg>

                </button>

            </div>

        </form>

    </div>

</div>

<!-- MODAL SUKSES -->
<div id="modalSukses"
class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm hidden">

    <div
    id="modalContent"
    class="bg-white rounded-2xl shadow-2xl p-8 mx-4 w-full max-w-sm text-center transform transition-all duration-300 scale-95 opacity-0">

        <div class="mx-auto mb-5 w-20 h-20 bg-green-100 rounded-full flex items-center justify-center">

            <svg class="w-12 h-12 text-green-500"
            fill="none"
            stroke="currentColor"
            viewBox="0 0 24 24">

                <path stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="3"
                d="M5 13l4 4L19 7"></path>

            </svg>

        </div>

        <h2 class="text-2xl font-bold text-gray-800 mb-3">
            Sukses
        </h2>

        <p class="text-gray-600 text-sm leading-relaxed mb-8">
            Pengajuan pendaftaran nama domain berhasil dikirim dan akan ditinjau oleh admin Kominfo. 
        </p>

        <button
        onclick="redirectToVerifikasi()"
        class="w-full py-3 bg-orange-500 hover:bg-orange-600 text-white font-semibold rounded-lg transition duration-200 text-base">
            OK
        </button>

    </div>

</div>

@push('scripts')

<script>
document.getElementById('formPengajuan').addEventListener('submit', function(e) {

    e.preventDefault();

    const btnAjukan = document.getElementById('btnAjukan');
    const btnText = document.getElementById('btnText');
    const btnSpinner = document.getElementById('btnSpinner');

    btnAjukan.disabled = true;
    btnAjukan.classList.add('opacity-75', 'cursor-not-allowed');

    btnText.textContent = 'Mengirim...';
    btnSpinner.classList.remove('hidden');

    const formData = new FormData(this);

    fetch(this.action, {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => {

        if (!response.ok) {
            throw new Error('Terjadi kesalahan');
        }

        return response.json();

    })
    .then(data => {

        showModalSukses();

    })
    .catch(error => {

        btnAjukan.disabled = false;
        btnAjukan.classList.remove('opacity-75', 'cursor-not-allowed');

        btnText.textContent = 'Submit';
        btnSpinner.classList.add('hidden');

        alert('Terjadi kesalahan saat mengirim pengajuan.');

    });

});

function showModalSukses() {

    const modal = document.getElementById('modalSukses');
    const content = document.getElementById('modalContent');

    modal.classList.remove('hidden');

    setTimeout(() => {

        content.classList.remove('scale-95', 'opacity-0');
        content.classList.add('scale-100', 'opacity-100');

    }, 10);

}

function redirectToVerifikasi() {

    window.location.href = '{{ url('/desa/verifikasi') }}';

}

document.getElementById('modalSukses').addEventListener('click', function(e) {

    if (e.target === this) {
        redirectToVerifikasi();
    }

});
</script>

@endpush

@endsection