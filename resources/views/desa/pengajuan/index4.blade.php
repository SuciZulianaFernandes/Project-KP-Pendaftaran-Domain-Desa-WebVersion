@extends('layouts.desa')
@section('title', 'Pratinjau Pengajuan')
@section('content')
<div class="bg-white rounded-xl shadow p-10">
    @include('desa.pengajuan._steps', ['currentStep' => 4])
    <div class="flex justify-center mt-12">
        <div class="w-full max-w-3xl">
            <h3 class="font-semibold text-gray-700 mb-5 text-center">Pratinjau Pengajuan</h3>
            
            <div class="space-y-6">
                <!-- Bagian Domain (Gaya Disamakan) -->
                <div class="bg-gray-50 p-4 rounded-lg">
                    <h4 class="font-semibold text-gray-800 mb-3">Domain</h4>
                    <p class="text-sm text-gray-700">{{ $data['nama_domain'] }}.desa.id</p>
                </div>

                <!-- Bagian Informasi Instansi (Dirapikan) -->
                <div class="bg-gray-50 p-4 rounded-lg">
                    <h4 class="font-semibold text-gray-800 mb-3">Informasi Instansi</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-4 gap-y-2 text-sm text-gray-700">
                        <p><strong>Nama Lembaga:</strong> {{ $data['data_desa']['nama_desa'] ?? '-' }}</p>
                        <p><strong>Provinsi:</strong> {{ $data['data_desa']['provinsi'] ?? '-' }}</p>
                        <p><strong>Kabupaten/Kota:</strong> {{ $data['data_desa']['kota_kabupaten'] ?? '-' }}</p>
                        <p><strong>Kecamatan:</strong> {{ $data['data_desa']['kecamatan'] ?? '-' }}</p>
                        <p><strong>Desa:</strong> {{ $data['data_desa']['desa_kelurahan'] ?? '-' }}</p>
                        <p><strong>Kode Pos:</strong> {{ $data['data_desa']['kode_pos'] ?? '-' }}</p>
                        <p><strong>Telepon:</strong> {{ $data['data_desa']['Telepon'] ?? '-' }}</p>
                        <p><strong>Faksimili:</strong> {{ $data['data_desa']['Faksimili'] ?? '-' }}</p>
                        <p><strong>Email:</strong> {{ $data['data_desa']['email'] ?? '-' }}</p>
                        
                        <!-- Alamat memenuhi lebar penuh -->
                        <div class="md:col-span-2">
                            <p class="block"><strong>Alamat Lengkap:</strong></p>
                            <p class="whitespace-pre-wrap">{{ $data['data_desa']['alamat'] ?? '-' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Bagian Dokumen (Gaya Disamakan) -->
                <div class="bg-gray-50 p-4 rounded-lg">
                    <h4 class="font-semibold text-gray-800 mb-3">Dokumen yang Diunggah</h4>
                    <ul class="text-sm text-gray-700 list-disc list-inside space-y-1">
                        @foreach($data['data_dokumen'] as $dok)
                            <li>{{ $dok['nama_file'] }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
            
            <!-- Form Aksi -->
            <form id="formPengajuan" action="{{ route('desa.pengajuan.submit') }}" method="POST" class="mt-8">
                @csrf
                <div class="flex justify-between">
                    <a href="{{ route('desa.pengajuan.dokumen') }}" class="px-6 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 transition">Kembali</a>
                    <button type="submit" id="btnAjukan" class="px-8 py-2 bg-green-600 text-white font-semibold rounded-md hover:bg-green-700 transition duration-150 flex items-center gap-2">
                        <span id="btnText">Ajukan Domain</span>
                        <svg id="btnSpinner" class="animate-spin h-5 w-5 text-white hidden" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Sukses -->
<div id="modalSukses" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm hidden">
    <div class="bg-white rounded-2xl shadow-2xl p-8 mx-4 w-full max-w-sm text-center transform transition-all duration-300 scale-95 opacity-0" id="modalContent">
        <!-- Ikon Sukses -->
        <div class="mx-auto mb-5 w-20 h-20 bg-green-100 rounded-full flex items-center justify-center">
            <svg class="w-12 h-12 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
            </svg>
        </div>
        
        <!-- Teks Sukses -->
        <h2 class="text-2xl font-bold text-gray-800 mb-3">Sukses</h2>
        <p class="text-gray-600 text-sm leading-relaxed mb-8">Pengajuan domain berhasil dikirim. Silakan cek status verifikasi Anda.</p>
        
        <!-- Tombol OK -->
        <button onclick="redirectToVerifikasi()" class="w-full py-3 bg-orange-500 hover:bg-orange-600 text-white font-semibold rounded-lg transition duration-200 text-base">
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
    
    // Disable button dan tampilkan loading
    btnAjukan.disabled = true;
    btnAjukan.classList.add('opacity-75', 'cursor-not-allowed');
    btnAjukan.classList.remove('hover:bg-green-700');
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
        // Tampilkan modal sukses
        showModalSukses();
    })
    .catch(error => {
        console.error('Error:', error);
        // Reset button jika gagal
        btnAjukan.disabled = false;
        btnAjukan.classList.remove('opacity-75', 'cursor-not-allowed');
        btnAjukan.classList.add('hover:bg-green-700');
        btnText.textContent = 'Ajukan Domain';
        btnSpinner.classList.add('hidden');
        
        alert('Terjadi kesalahan saat mengirim pengajuan. Silakan coba lagi.');
    });
});

function showModalSukses() {
    const modal = document.getElementById('modalSukses');
    const content = document.getElementById('modalContent');
    
    modal.classList.remove('hidden');
    
    // Trigger animation
    setTimeout(() => {
        content.classList.remove('scale-95', 'opacity-0');
        content.classList.add('scale-100', 'opacity-100');
    }, 10);
}

function redirectToVerifikasi() {
    window.location.href = '{{ url('/desa/verifikasi') }}';
}

// Close modal on backdrop click (optional)
document.getElementById('modalSukses').addEventListener('click', function(e) {
    if (e.target === this) {
        redirectToVerifikasi();
    }
});
</script>
@endpush
@endsection