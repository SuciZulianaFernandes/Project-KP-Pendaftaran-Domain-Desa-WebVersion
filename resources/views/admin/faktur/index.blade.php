@extends('layouts.admin')
@section('title', 'Manajemen Faktur')

@section('content')
@include('components.inv-styles')

<div class="container-fluid" style="padding:0 24px;max-width:1400px">
    <div style="display:flex;align-items:flex-end;justify-content:space-between;margin-bottom:22px;flex-wrap:wrap;gap:10px">
        <div>
            <h1 style="font-size:22px;font-weight:800;margin:0;letter-spacing:-.5px">Manajemen Faktur</h1>
            <p style="font-size:14px;color:#64748b;margin:4px 0 0">Kelola semua faktur domain desa</p>
        </div>
    </div>

    <div class="inv-card">
        @if(session('success'))
            <div class="alert inv-alert inv-alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if(session('error'))
            <div class="alert inv-alert inv-alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @include('components.inv-search-filter', ['showBelumDibuat' => true])
        <div style="overflow-x:auto">
            <table class="inv-table" id="invTable">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>No Invoice</th>
                        <th>Nama Desa</th>
                        <th>Domain</th>
                        <th style="text-align:center">Tipe</th>
                        <th>Tanggal Konfirmasi</th>
                        <th style="text-align:center">Status</th>
                        <th style="text-align:center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                                @forelse($data as $i => $row)
                    @if($row->faktur->isEmpty() && !in_array($row->id_pengajuan, $perpanjanganBelumBuat))
                        {{-- JIKA DOMAIN INI BELUM PUNYA FAKTUR SAMA SEKALI DAN BUKAN PERPANJANGAN --}}
                        <tr data-status="belum_dibuat" style="animation-delay:{{$i*0.05}}s">
                            <td>{{ $data->firstItem() + $i }}</td>
                            <td><span class="inv-id">-</span></td>
                            <td>{{ $row->nama_desa }}</td>
                            <td><span class="inv-date">{{ $row->nama_domain }}.desa.id</span></td>
                            
                            {{-- PERBAIKAN: Ganti tanda "-" dengan Badge "Baru" --}}
                            <td style="text-align:center">
                                <span class="px-2 py-0.5 rounded text-xs bg-blue-100 text-blue-700 font-medium">Baru</span>
                            </td>

                            <td><span class="inv-date">-</span></td>

                            <td style="text-align:center">
                                <span class="inv-badge" style="background:#f1f5f9;color:#475569"><span class="d" style="background:#94a3b8"></span>Belum dibuat</span>
                            </td>

                            <td style="text-align:center">
                                <form action="{{ route('admin.faktur.store', $row->id_pengajuan) }}" method="POST" style="display:inline">
                                    @csrf
                                    <!-- Tambahkan class js-confirm-print di sini -->
                                    <button type="submit" class="inv-btn-d js-confirm-print"><i class="fas fa-plus"></i> Cetak Faktur</button>
                                </form>
                            </td>
                        </tr>
                    @else
                        {{-- JIKA DOMAIN INI PUNYA FAKTUR --}}
                        @foreach($row->faktur as $indexFaktur => $fakturItem)
                            <tr data-status="{{ $fakturItem->status }}" style="animation-delay:{{$i*0.05}}s">
                                <td>
                                    {{ $data->firstItem() + $i }}
                                    {{ $row->faktur->count() > 1 ? '.' . ($indexFaktur + 1) : '' }}
                                </td>
                                <td><span class="inv-id">{{ $fakturItem->no_invoice }}</span></td>
                                <td>{{ $row->nama_desa }}</td>
                                <td><span class="inv-date">{{ $row->nama_domain }}.desa.id</span></td>
                                
                                <td style="text-align:center">
                                    @if($fakturItem->tipe == 'perpanjangan')
                                        <span class="px-2 py-0.5 rounded text-xs bg-purple-100 text-purple-700 font-medium">Perpanjangan</span>
                                    @else
                                        <span class="px-2 py-0.5 rounded text-xs bg-blue-100 text-blue-700 font-medium">Baru</span>
                                    @endif
                                </td>

                                <td><span class="inv-date">{{ $fakturItem->tanggal_konfirmasi ? $fakturItem->tanggal_konfirmasi->format('d/m/Y') : '-' }}</span></td>

                                <td style="text-align:center">
                                    @if($fakturItem->status == 'sudah_bayar')
                                        <span class="inv-badge badge-green"><span class="d"></span>Sudah Dibayar</span>
                                    @elseif($fakturItem->status == 'belum_bayar')
                                        <span class="inv-badge badge-red"><span class="d"></span>Belum Dibayar</span>
                                    @elseif($fakturItem->status == 'kedaluarsa')
                                        <span class="inv-badge" style="background:#f1f5f9;color:#475569"><span class="d" style="background:#94a3b8"></span>Kedaluarsa</span>
                                    @endif
                                </td>

                                <td style="text-align:center">
                                    <a href="{{ route('admin.faktur.show', $fakturItem->id) }}" class="inv-btn-d"><i class="fas fa-eye"></i> Lihat</a>
                                </td>
                            </tr>
                        @endforeach

                        {{-- BARIS PERPANJANGAN YANG BELUM DIBUAT FAKTURNYA --}}
                        @if(in_array($row->id_pengajuan, $perpanjanganBelumBuat))
                            <tr data-status="belum_dibuat" style="animation-delay:{{$i*0.05}}s">
                                <td>
                                    {{ $data->firstItem() + $i }}.{{ $row->faktur->count() + 1 }}
                                </td>
                                <td><span class="inv-id">-</span></td>
                                <td>{{ $row->nama_desa }}</td>
                                <td><span class="inv-date">{{ $row->nama_domain }}.desa.id</span></td>
                                
                                <td style="text-align:center">
                                    <span class="px-2 py-0.5 rounded text-xs bg-purple-100 text-purple-700 font-medium">Perpanjangan</span>
                                </td>

                                <td><span class="inv-date">-</span></td>

                                <td style="text-align:center">
                                    <span class="inv-badge" style="background:#f1f5f9;color:#475569"><span class="d" style="background:#94a3b8"></span>Belum dibuat</span>
                                </td>

                                <td style="text-align:center">
                                    <form action="{{ route('admin.faktur.store', $row->id_pengajuan) }}" method="POST" style="display:inline">
                                        @csrf
                                        <!-- Tambahkan class js-confirm-print di sini -->
                                        <button type="submit" class="inv-btn-d js-confirm-print"><i class="fas fa-plus"></i> Buat Faktur</button>
                                    </form>
                                </td>
                            </tr>
                        @endif
                    @endif
                @empty
                    <tr class="inv-empty"><td colspan="8"><i class="fas fa-inbox"></i>Belum ada faktur</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>

        @include('components.inv-pagination', ['paginator' => $data])
    </div>
</div>

<!-- MODAL POPUP CONFIRMATION CETAK FAKTUR -->
<!-- Hidden by default -->
<div id="printConfirmationModal" class="hidden fixed inset-0 bg-gray-900 bg-opacity-50 overflow-y-auto h-full w-full z-50 flex items-center justify-center">
    <!-- Modal Content -->
    <div class="relative mx-auto p-5 border w-96 shadow-lg rounded-xl bg-white">
        
        <div class="mt-3 text-center">
            <!-- Icon -->
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-blue-100 mb-4">
                <i class="fas fa-print text-blue-600 text-xl"></i>
            </div>
            
            <!-- Pesan Sesuai Request -->
            <h3 class="text-lg leading-6 font-medium text-gray-900">Konfirmasi</h3>
            <div class="mt-2 px-7 py-3">
                <p class="text-sm text-gray-500">
                    Apakah Anda yakin ingin mencetak faktur?
                </p>
            </div>
        </div>
        
        <!-- Buttons -->
        <div class="items-center px-4 py-3 flex justify-center gap-3">
            <button id="printModalNoBtn" class="px-4 py-2 bg-gray-200 text-gray-800 text-base font-medium rounded-md shadow-sm hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-300">
                Batal
            </button>
            <button id="printModalYesBtn" class="px-4 py-2 bg-blue-600 text-white text-base font-medium rounded-md shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-300">
                Ya, Lanjutkan
            </button>
        </div>
    </div>
</div>

<!-- Script Filter Asli -->
<script>
document.addEventListener('DOMContentLoaded',function(){
    var s=document.getElementById('invSearch'),
        f=document.getElementById('invFilter'),
        rows=Array.from(document.querySelectorAll('#invTable tbody tr[data-status]')),
        empty=document.querySelector('.inv-empty');

    function filter(){
        var q=s.value.trim().toLowerCase(), v=f.value, n=0;
        rows.forEach(function(r){
            var show=(!q||r.textContent.toLowerCase().includes(q))&&(!v||r.dataset.status===v);
            r.style.display=show?'':'none';
            if(show)n++;
        });
        if(empty)empty.style.display=n?'none':'';
    }
    s.addEventListener('input',filter);
    f.addEventListener('change',filter);
});
</script>

<!-- Script Tambahan untuk Popup Konfirmasi -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('printConfirmationModal');
    const yesBtn = document.getElementById('printModalYesBtn');
    const noBtn = document.getElementById('printModalNoBtn');
    const confirmBtns = document.querySelectorAll('.js-confirm-print');
    
    let formToSubmit = null;

    // Tampilkan modal saat tombol cetak diklik
    confirmBtns.forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault(); // Mencegah form submit langsung
            formToSubmit = this.closest('form'); // Simpan referensi form
            
            // Tampilkan modal
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        });
    });

    // Tombol Ya
    yesBtn.addEventListener('click', function() {
        if (formToSubmit) {
            formToSubmit.submit(); // Submit form
        }
        closeModal();
    });

    // Tombol Batal
    noBtn.addEventListener('click', function() {
        closeModal();
    });

    // Tutup jika klik di luar area modal
    modal.addEventListener('click', function(e) {
        if (e.target === modal) {
            closeModal();
        }
    });

    function closeModal() {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        formToSubmit = null;
    }
});
</script>
@endsection