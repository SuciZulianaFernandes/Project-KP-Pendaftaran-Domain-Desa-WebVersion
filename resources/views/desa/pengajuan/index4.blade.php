@extends('layouts.desa')

@section('title', 'Pratinjau Pengajuan')

@section('content')

<div class="bg-white rounded-3xl shadow-xl border border-slate-100 p-10">

    @include('desa.pengajuan._steps', ['currentStep' => 4])

    <div class="mt-10">

        <div class="text-center mb-8">
            <h3 class="font-semibold text-xl text-gray-700">
                Pratinjau Pengajuan
            </h3>
        </div>

        <div class="grid grid-cols-1 gap-5 mb-5">
            <div class="border border-gray-200 rounded-lg overflow-hidden bg-white">
                <div class="bg-gradient-to-r from-[#109696] via-[#1A85A5] to-[#1760C5] text-white px-4 py-3 font-semibold text-sm">
                    Informasi Instansi
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <tr class="border-b border-gray-200">
                            <td class="px-4 py-4 text-[#1760C5] font-medium w-1/4 bg-gray-50">Nama Organisasi</td>
                            <td class="px-4 py-4 w-1/4 text-gray-700">{{ $data['data_desa']['nama_desa'] ?? '-' }}</td>
                            <td class="px-4 py-4 text-[#1760C5] font-medium w-1/4 bg-gray-50">Provinsi</td>
                            <td class="px-4 py-4 w-1/4 text-gray-700">{{ $data['data_desa']['provinsi'] ?? '-' }}</td>
                        </tr>
                        <tr class="border-b border-gray-200">
                            <td class="px-4 py-4 text-[#1760C5] font-medium bg-gray-50">Kabupaten</td>
                            <td class="px-4 py-4 text-gray-700">{{ session('nama_kabupaten') ?? ($data['data_desa']['kota_kabupaten'] ?? '-') }}</td>
                            <td class="px-4 py-4 text-[#1760C5] font-medium bg-gray-50">Kecamatan</td>
                            <td class="px-4 py-4 text-gray-700">{{ session('nama_kecamatan') ?? ($data['data_desa']['kecamatan'] ?? '-') }}</td>
                        </tr>
                        <tr class="border-b border-gray-200">
                            <td class="px-4 py-4 text-[#1760C5] font-medium bg-gray-50">Desa</td>
                            <td class="px-4 py-4 text-gray-700">{{ session('nama_desa') ?? ($data['data_desa']['desa_kelurahan'] ?? '-') }}</td>
                            <td class="px-4 py-4 text-[#1760C5] font-medium bg-gray-50">Kode Pos</td>
                            <td class="px-4 py-4 text-gray-700">{{ $data['data_desa']['kode_pos'] ?? '-' }}</td>
                        </tr>
                        <tr class="border-b border-gray-200">
                            <td class="px-4 py-4 text-[#1760C5] font-medium bg-gray-50">Telepon</td>
                            <td class="px-4 py-4 text-gray-700">{{ $data['data_desa']['Telepon'] ?? '-' }}</td>
                            <td class="px-4 py-4 text-[#1760C5] font-medium bg-gray-50">Faksimili</td>
                            <td class="px-4 py-4 text-gray-700">{{ $data['data_desa']['Faksimili'] ?? '-' }}</td>
                        </tr>
                        <tr class="border-b border-gray-200">
                            <td class="px-4 py-4 text-[#1760C5] font-medium bg-gray-50">Email Registran</td>
                            <td class="px-4 py-4 text-gray-700">{{ auth()->user()->email ?? '-' }}</td>
                            <td class="px-4 py-4 text-[#1760C5] font-medium bg-gray-50">Alamat</td>
                            <td class="px-4 py-4 text-gray-700">{{ $data['data_desa']['alamat'] ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="px-4 py-4 text-[#1760C5] font-medium bg-gray-50">Tanggal Pembuatan</td>
                            <td class="px-4 py-4 text-gray-700" colspan="3">{{ date('d-m-Y') }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-5 mb-8">
            
            <div class="border border-gray-200 rounded-lg overflow-hidden bg-white">
                <div class="bg-gradient-to-r from-[#109696] via-[#1A85A5] to-[#1760C5] text-white px-4 py-3 font-semibold text-sm">
                    Informasi Domain
                </div>
                <table class="w-full text-sm">
                    <tr>
                        <td class="px-4 py-4 text-[#1760C5] font-medium bg-gray-50 w-2/5">Nama Domain</td>
                        <td class="px-4 py-4 text-gray-700 font-semibold text-lg text-emerald-600">
                            {{ $data['nama_domain'] }}.desa.id
                        </td>
                    </tr>
                </table>
            </div>

            <div class="border border-gray-200 rounded-lg overflow-hidden bg-white">
                <div class="bg-gradient-to-r from-[#109696] via-[#1A85A5] to-[#1760C5] text-white px-4 py-3 font-semibold text-sm">
                    Dokumen Persyaratan
                </div>
                <table class="w-full text-sm">
                    <tr class="border-b border-gray-200">
                        <td class="px-4 py-4 text-[#1760C5] font-medium w-2/5 bg-gray-50">Surat Permohonan Domain Desa</td>
                        <td class="px-4 py-4">
                            <div class="flex items-center gap-2 text-gray-700">
                                <svg class="w-5 h-5 text-[#1760C5] flex-shrink-0" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8l-6-6zm-1 2l5 5h-5V4zM6 20V4h5v7h7v9H6z"/>
                                </svg>
                                <span class="break-all">{{ $data['data_dokumen']['surat_permohonan']['nama_file'] ?? '-' }}</span>
                            </div>
                        </td>
                    </tr>
                    <tr class="border-b border-gray-200">
                        <td class="px-4 py-4 text-[#1760C5] font-medium bg-gray-50">Surat Kuasa dari Desa</td>
                        <td class="px-4 py-4">
                            <div class="flex items-center gap-2 text-gray-700">
                                <svg class="w-5 h-5 text-[#1760C5] flex-shrink-0" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8l-6-6zm-1 2l5 5h-5V4zM6 20V4h5v7h7v9H6z"/>
                                </svg>
                                <span class="break-all">{{ $data['data_dokumen']['surat_kuasa']['nama_file'] ?? '-' }}</span>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="px-4 py-4 text-[#1760C5] font-medium bg-gray-50">Dasar Hukum Pembentukan Desa / Surat Pelantikan Kepala Desa</td>
                        <td class="px-4 py-4">
                            <div class="flex items-center gap-2 text-gray-700">
                                <svg class="w-5 h-5 text-[#1760C5] flex-shrink-0" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8l-6-6zm-1 2l5 5h-5V4zM6 20V4h5v7h7v9H6z"/>
                                </svg>
                                <span class="break-all">{{ $data['data_dokumen']['perda_pembentukan_desa']['nama_file'] ?? '-' }}</span>
                            </div>
                        </td>
                    </tr>
                </table>
            </div>

        </div>

        <form id="formPengajuan" action="{{ route('desa.pengajuan.submit') }}" method="POST">
            @csrf
            <div class="flex justify-end gap-3">
                <a href="{{ route('desa.pengajuan.dokumen') }}" class="px-7 py-3 border border-gray-300 rounded-lg text-gray-700 font-semibold hover:bg-gray-50 transition duration-150">
                    &lt; Kembali
                </a>
                <button type="submit" id="btnAjukan" class="px-7 py-3 bg-gradient-to-r from-[#109696] via-[#1A85A5] to-[#1760C5] hover:opacity-90 text-white font-semibold rounded-lg transition-all duration-200 shadow-lg flex items-center gap-2">
                    <span id="btnText">Submit</span>
                    <svg id="btnSpinner" class="animate-spin h-4 w-4 text-white hidden" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                    </svg>
                </button>
            </div>
        </form>

    </div>
</div>

<div id="modalSukses" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm hidden">
    <div id="modalContent" class="bg-white rounded-2xl shadow-2xl p-8 mx-4 w-full max-w-sm text-center transform transition-all duration-300 scale-95 opacity-0">
        <div class="mx-auto mb-5 w-20 h-20 bg-gradient-to-r from-[#109696]/10 via-[#1A85A5]/10 to-[#1760C5]/10 rounded-full flex items-center justify-center">
            <svg class="w-12 h-12 text-[#1760C5]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
            </svg>
        </div>
        <h2 class="text-2xl font-bold text-gray-800 mb-3">Sukses</h2>
        <p class="text-gray-600 text-sm leading-relaxed mb-8">
            Pengajuan pendaftaran nama domain berhasil dikirim dan akan ditinjau oleh admin Kominfo. 
        </p>
        <button onclick="redirectToVerifikasi()" class="w-full py-3 bg-gradient-to-r from-[#109696] via-[#1A85A5] to-[#1760C5] hover:opacity-90 text-white font-semibold rounded-lg transition duration-200 text-base shadow-lg">
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
            return response.json().then(err => {
                throw new Error(err.message || 'Terjadi kesalahan');
            });
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
        alert(error.message || 'Terjadi kesalahan saat mengirim pengajuan.');
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