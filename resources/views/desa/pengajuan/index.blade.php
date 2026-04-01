@extends('layouts.desa')

@section('title','Pendaftaran Domain')

@section('content')

<div class="bg-white rounded-xl shadow p-10">
    <div class="flex justify-center mb-12">
</div>

    <h2 class="text-xl font-semibold mb-10 text-gray-700">
        Pendaftaran Domain
    </h2>

    <!-- STEP -->
    <div class="flex justify-center mb-12">
        <div class="flex items-center w-full max-w-4xl">
            <div class="flex flex-col items-center">
                <div class="w-10 h-10 bg-red-700 text-white rounded-full flex items-center justify-center font-semibold">1</div>
                <span class="text-sm mt-2">Cari Nama Domain</span>
            </div>
            <div class="flex-1 h-1 bg-gray-300 mx-4"></div>
            <div class="flex flex-col items-center">
                <div class="w-10 h-10 bg-gray-300 text-white rounded-full flex items-center justify-center">2</div>
                <span class="text-sm mt-2 text-gray-500">Informasi Instansi</span>
            </div>
            <div class="flex-1 h-1 bg-gray-300 mx-4"></div>
            <div class="flex flex-col items-center">
                <div class="w-10 h-10 bg-gray-300 text-white rounded-full flex items-center justify-center">3</div>
                <span class="text-sm mt-2 text-gray-500">Persyaratan Domain</span>
            </div>
            <div class="flex-1 h-1 bg-gray-300 mx-4"></div>
            <div class="flex flex-col items-center">
                <div class="w-10 h-10 bg-gray-300 text-white rounded-full flex items-center justify-center">4</div>
                <span class="text-sm mt-2 text-gray-500">Pratinjau</span>
            </div>
        </div>
    </div>

    <!-- FORM -->
    <div class="flex justify-center">
        <div class="w-full max-w-md">
            <h3 class="font-semibold text-gray-700 mb-5 text-center">
                Cari Nama Domain
            </h3>

            <div class="space-y-3">
                <!-- Input dan Suffix -->
                <div class="flex border rounded-lg overflow-hidden">
                    <input
                        type="text"
                        id="domain-input"
                        placeholder="Nama Domain"
                            value="{{ old('nama_domain', session('pengajuan.nama_domain')) }}" 
                        class="flex-1 px-4 py-3 focus:outline-none"
                        autocomplete="off"
                    />
                    <span class="px-4 flex items-center bg-gray-100 text-gray-600">
                        .desa.id
                    </span>
                </div>

                <!-- Tombol Cari -->
                <button id="btn-cari" type="button" class="w-36 bg-red-800 hover:bg-red-700 text-white font-semibold py-3 rounded-lg transition-colors duration-200 mx-auto block">
    Cari
</button>
            </div>

            <!-- Area untuk pesan status dan tombol Daftar -->
            <div id="domain-result" class="mt-6 hidden">
                <!-- Kartu Abu-Abu -->
                <div class="bg-gray-100 p-4 rounded-lg border border-gray-200 flex justify-between items-center">
                    <!-- Teks Status -->
                    <p id="domain-status" class="text-sm font-medium"></p>
                    <!-- Tombol Daftar -->
                    <a id="btn-daftar" href="{{ url('/desa/pengajuan') }}" class="inline-block px-8 py-2 rounded-lg text-white font-medium transition-colors duration-200">
                        Daftar
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const domainInput = document.getElementById('domain-input');
    const btnCari = document.getElementById('btn-cari');
    const domainResult = document.getElementById('domain-result');
    const domainStatus = document.getElementById('domain-status');
    const btnDaftar = document.getElementById('btn-daftar');

    domainInput.addEventListener('keydown', function(event) {
        const key = event.key;
        const regex = /^[a-zA-Z0-9]$/;
        const allowedSpecialKeys = ['Backspace', 'Delete', 'Tab', 'ArrowLeft', 'ArrowRight', 'ArrowUp', 'ArrowDown'];

        if (key === 'Enter') {
            event.preventDefault();
            checkDomain();
            return;
        }

        if (regex.test(key) || allowedSpecialKeys.includes(key)) {
            return;
        }

        event.preventDefault();
    });


    const checkDomain = function() {
        const domain = domainInput.value.toLowerCase().replace(/\s+/g, '');

        if (domain.length < 3) {
            domainResult.classList.add('hidden');
            return;
        }

        btnCari.disabled = true;
        btnCari.textContent = 'Memeriksa...';

        fetch('{{ route("desa.api.check.domain") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ nama_domain: domain })
        })
        .then(response => response.json())
        .then(data => {
            btnCari.disabled = false;
            btnCari.textContent = 'Cari';

            domainResult.classList.remove('hidden');
            if (data.available) {
                domainStatus.textContent = `${domain}.desa.id Tersedia`;
                domainStatus.className = 'text-sm font-medium text-blue-600';
                
                btnDaftar.textContent = 'Daftar';
                btnDaftar.href = `/desa/pengajuan/informasi?domain=${domain}`;
                btnDaftar.className = 'inline-block px-8 py-2 rounded-lg text-white font-medium bg-red-700 hover:bg-red-600 transition-colors duration-200';
                btnDaftar.removeAttribute('disabled');

            } else {
                domainStatus.textContent = `${domain}.desa.id Tidak Tersedia`;
                domainStatus.className = 'text-sm font-medium text-red-600';

                btnDaftar.textContent = 'Tidak Tersedia';
                btnDaftar.href = '#';
                btnDaftar.className = 'inline-block px-8 py-2 rounded-lg text-white font-medium bg-gray-400 cursor-not-allowed';
                btnDaftar.setAttribute('disabled', 'disabled');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            btnCari.disabled = false;
            btnCari.textContent = 'Cari';
        });
    };

    btnCari.addEventListener('click', checkDomain);
});
</script>
@endpush

@endsection