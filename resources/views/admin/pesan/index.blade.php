@extends('layouts.admin')

@section('title', 'Pesan Admin')

@section('content')
<div class="space-y-6">

    <!-- HEADER -->
    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
        <div>
            <h1 class="text-2xl font-extrabold text-slate-800 tracking-tight">Pesan Admin</h1>
            <p class="text-sm text-slate-400 mt-1">Notifikasi dari desa terkait pengajuan, pembayaran, dan perpanjangan</p>
        </div>

        <div class="flex items-center gap-3">
            @php 
                $validUnread = $data->filter(function($item){
                    return str_contains($item->judul, 'Pengajuan Domain') || 
                           str_contains($item->judul, 'Konfirmasi Pembayaran') ||
                           str_contains($item->judul, 'Bukti Pembayaran') ||
                           str_contains($item->judul, 'Permintaan Perpanjangan');
                })->where('is_read', 0)->count(); 
            @endphp

            @if($validUnread > 0)
            <form action="{{ route('admin.pesan.read-all') }}" method="POST">
                @csrf
                @method('PUT')
                <button type="submit"
                    class="inline-flex items-center gap-2 bg-emerald-50 hover:bg-emerald-100 text-emerald-600 border border-emerald-200 font-semibold py-2.5 px-5 rounded-xl transition text-sm">
                    <i class="fas fa-check-double text-xs"></i> Tandai Semua Dibaca
                </button>
            </form>
            @endif

            <button type="button"
        id="selectAllButton"
        onclick="handleSelectAll()"
        class="hidden inline-flex items-center gap-2 bg-slate-50 hover:bg-slate-100 text-slate-600 border border-slate-200 font-semibold py-2.5 px-5 rounded-xl transition text-sm">
        <i class="fas fa-check-square text-xs"></i> Pilih Semua
    </button>

    <button type="button"
        id="deleteButton"
        onclick="handleDeleteButton()"
        class="inline-flex items-center gap-2 bg-rose-50 hover:bg-rose-100 text-rose-600 border border-rose-200 font-semibold py-2.5 px-5 rounded-xl transition text-sm">
        <i class="fas fa-trash-alt text-xs"></i> Hapus Pesan
    </button>
        </div>
    </div>

    <!-- FORM HAPUS -->
    <form action="{{ route('admin.pesan.hapus.selected') }}"
        method="POST"
        id="deleteForm">

        @csrf
        @method('DELETE')

        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6">

            <!-- KOLOM 1 : PENGAJUAN DOMAIN BARU -->
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
                <div class="px-5 py-4 border-b border-slate-100 bg-slate-50/50 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-xl bg-teal-50 flex items-center justify-center">
                            <i class="fas fa-globe text-teal-600 text-sm"></i>
                        </div>
                        <h3 class="text-sm font-bold text-slate-800">Pengajuan Baru</h3>
                    </div>
                    @php $unreadPengajuan = $data->where('judul', 'Pengajuan Domain Baru')->where('is_read', 0)->count(); @endphp
                    @if($unreadPengajuan > 0)
                        <span class="bg-red-500 text-white text-[10px] font-bold min-w-[20px] h-5 flex items-center justify-center rounded-full px-1.5">
                            {{ $unreadPengajuan }}
                        </span>
                    @endif
                </div>

                <div class="p-4 space-y-3">
                    @forelse($data->where('judul', 'Pengajuan Domain Baru') as $row)
                        <div class="relative {{ $row->is_read == 0 ? 'bg-teal-50 border-teal-300 border-l-4' : 'bg-teal-50/50 border border-teal-100' }} p-4 rounded-xl">

                            <div class="hidden delete-checkbox absolute top-3 right-3">
                                <input type="checkbox" name="pesan_ids[]" value="{{ $row->id }}" class="w-4 h-4 accent-rose-500 rounded">
                            </div>

                            @if($row->is_read == 0)
                                <span class="inline-flex items-center gap-1 bg-red-500 text-white text-[9px] font-bold px-2 py-0.5 rounded-full mb-2 uppercase tracking-wider">
                                    <span class="w-1.5 h-1.5 bg-white rounded-full animate-pulse"></span> Baru
                                </span>
                            @endif

                            <h4 class="font-bold text-sm text-teal-800 pr-6">{{ $row->judul }}</h4>
                            <p class="text-sm text-slate-600 mt-1 leading-relaxed">{{ $row->isi }}</p>
                            <p class="text-xs text-slate-400 mt-3">{{ $row->created_at->format('d M Y, H:i') }}</p>

                            <div class="mt-3">
                                <a href="{{ route('admin.pengajuan.detail', $row->id_pengajuan) }}"
                                    class="inline-flex items-center gap-1.5 bg-teal-600 hover:bg-teal-700 text-white text-xs font-bold px-4 py-2 rounded-lg transition shadow-sm">
                                    <i class="fas fa-eye text-[10px]"></i> Detail Pengajuan
                                </a>
                            </div>
                        </div>
                    @empty
                        <div class="flex flex-col items-center gap-3 py-10">
                            <div class="w-12 h-12 rounded-full bg-slate-100 flex items-center justify-center">
                                <i class="fas fa-inbox text-slate-300"></i>
                            </div>
                            <p class="text-slate-400 text-sm">Tidak ada pengajuan baru</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- KOLOM 2 : KONFIRMASI DARI DESA -->
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
                <div class="px-5 py-4 border-b border-slate-100 bg-slate-50/50 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-xl bg-sky-50 flex items-center justify-center">
                            <i class="fas fa-check-circle text-sky-600 text-sm"></i>
                        </div>
                        <h3 class="text-sm font-bold text-slate-800">Konfirmasi Desa</h3>
                    </div>
                    @php $unreadKonfirmasi = $data->where('judul', 'Konfirmasi Pembayaran Disetujui')->where('is_read', 0)->count(); @endphp
                    @if($unreadKonfirmasi > 0)
                        <span class="bg-red-500 text-white text-[10px] font-bold min-w-[20px] h-5 flex items-center justify-center rounded-full px-1.5">
                            {{ $unreadKonfirmasi }}
                        </span>
                    @endif
                </div>

                <div class="p-4 space-y-3">
                    @forelse($data->where('judul', 'Konfirmasi Pembayaran Disetujui') as $row)
                        <div class="relative {{ $row->is_read == 0 ? 'bg-sky-50 border-sky-300 border-l-4' : 'bg-sky-50/50 border border-sky-100' }} p-4 rounded-xl">

                            <div class="hidden delete-checkbox absolute top-3 right-3">
                                <input type="checkbox" name="pesan_ids[]" value="{{ $row->id }}" class="w-4 h-4 accent-rose-500 rounded">
                            </div>

                            @if($row->is_read == 0)
                                <span class="inline-flex items-center gap-1 bg-red-500 text-white text-[9px] font-bold px-2 py-0.5 rounded-full mb-2 uppercase tracking-wider">
                                    <span class="w-1.5 h-1.5 bg-white rounded-full animate-pulse"></span> Baru
                                </span>
                            @endif

                            <h4 class="font-bold text-sm text-sky-800 pr-6">{{ $row->judul }}</h4>
                            <p class="text-sm text-slate-600 mt-1 leading-relaxed">{{ $row->isi }}</p>
                            <p class="text-xs text-slate-400 mt-3">{{ $row->created_at->format('d M Y, H:i') }}</p>

                            <div class="mt-3">
                                <a href="{{ route('admin.pengajuan.detail', $row->id_pengajuan) }}"
                                    class="inline-flex items-center gap-1.5 bg-[#1760C5] hover:bg-[#1250a5] text-white text-xs font-bold px-4 py-2 rounded-lg transition shadow-sm">
                                    <i class="fas fa-eye text-[10px]"></i> Detail Pengajuan
                                </a>
                            </div>
                        </div>
                    @empty
                        <div class="flex flex-col items-center gap-3 py-10">
                            <div class="w-12 h-12 rounded-full bg-slate-100 flex items-center justify-center">
                                <i class="fas fa-inbox text-slate-300"></i>
                            </div>
                            <p class="text-slate-400 text-sm">Tidak ada konfirmasi dari desa</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- KOLOM 3 : BUKTI PEMBAYARAN -->
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
                <div class="px-5 py-4 border-b border-slate-100 bg-slate-50/50 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-xl bg-amber-50 flex items-center justify-center">
                            <i class="fas fa-image text-amber-600 text-sm"></i>
                        </div>
                        <h3 class="text-sm font-bold text-slate-800">Bukti Pembayaran</h3>
                    </div>
                    @php $unreadBukti = $data->where('judul', 'Bukti Pembayaran')->where('is_read', 0)->count(); @endphp
                    @if($unreadBukti > 0)
                        <span class="bg-red-500 text-white text-[10px] font-bold min-w-[20px] h-5 flex items-center justify-center rounded-full px-1.5">
                            {{ $unreadBukti }}
                        </span>
                    @endif
                </div>

                <div class="p-4 space-y-3">
                    @forelse($data->where('judul', 'Bukti Pembayaran') as $row)
                        <div class="relative {{ $row->is_read == 0 ? 'bg-amber-50 border-amber-300 border-l-4' : 'bg-amber-50/50 border border-amber-100' }} p-4 rounded-xl">

                            <div class="hidden delete-checkbox absolute top-3 right-3">
                                <input type="checkbox" name="pesan_ids[]" value="{{ $row->id }}" class="w-4 h-4 accent-rose-500 rounded">
                            </div>

                            @if($row->is_read == 0)
                                <span class="inline-flex items-center gap-1 bg-red-500 text-white text-[9px] font-bold px-2 py-0.5 rounded-full mb-2 uppercase tracking-wider">
                                    <span class="w-1.5 h-1.5 bg-white rounded-full animate-pulse"></span> Baru
                                </span>
                            @endif

                            <h4 class="font-bold text-sm text-amber-800 pr-6">{{ $row->judul }}</h4>
                            <p class="text-sm text-slate-600 mt-1 leading-relaxed">{{ $row->isi }}</p>
                            <p class="text-xs text-slate-400 mt-3">{{ $row->created_at->format('d M Y, H:i') }}</p>
                        </div>
                    @empty
                        <div class="flex flex-col items-center gap-3 py-10">
                            <div class="w-12 h-12 rounded-full bg-slate-100 flex items-center justify-center">
                                <i class="fas fa-inbox text-slate-300"></i>
                            </div>
                            <p class="text-slate-400 text-sm">Tidak ada bukti pembayaran</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- KOLOM 4 : PERPANJANGAN -->
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
                <div class="px-5 py-4 border-b border-slate-100 bg-slate-50/50 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-xl bg-purple-50 flex items-center justify-center">
                            <i class="fas fa-sync-alt text-purple-600 text-sm"></i>
                        </div>
                        <h3 class="text-sm font-bold text-slate-800">Perpanjangan</h3>
                    </div>
                    @php $unreadPerpanjangan = $data->where('judul', 'Permintaan Perpanjangan Domain')->where('is_read', 0)->count(); @endphp
                    @if($unreadPerpanjangan > 0)
                        <span class="bg-red-500 text-white text-[10px] font-bold min-w-[20px] h-5 flex items-center justify-center rounded-full px-1.5">
                            {{ $unreadPerpanjangan }}
                        </span>
                    @endif
                </div>

                <div class="p-4 space-y-3">
                    @forelse($data->where('judul', 'Permintaan Perpanjangan Domain') as $row)
                        <div class="relative {{ $row->is_read == 0 ? 'bg-purple-50 border-purple-300 border-l-4' : 'bg-purple-50/50 border border-purple-100' }} p-4 rounded-xl">

                            <div class="hidden delete-checkbox absolute top-3 right-3">
                                <input type="checkbox" name="pesan_ids[]" value="{{ $row->id }}" class="w-4 h-4 accent-rose-500 rounded">
                            </div>

                            @if($row->is_read == 0)
                                <span class="inline-flex items-center gap-1 bg-red-500 text-white text-[9px] font-bold px-2 py-0.5 rounded-full mb-2 uppercase tracking-wider">
                                    <span class="w-1.5 h-1.5 bg-white rounded-full animate-pulse"></span> Baru
                                </span>
                            @endif

                            <h4 class="font-bold text-sm text-purple-800 pr-6">{{ $row->judul }}</h4>
                            <p class="text-sm text-slate-600 mt-1 leading-relaxed">{{ $row->isi }}</p>
                            <p class="text-xs text-slate-400 mt-3">{{ $row->created_at->format('d M Y, H:i') }}</p>

                            <div class="mt-3">
                                <a href="{{ route('admin.faktur.index') }}"
                                    class="inline-flex items-center gap-1.5 bg-purple-600 hover:bg-purple-700 text-white text-xs font-bold px-4 py-2 rounded-lg transition shadow-sm">
                                    <i class="fas fa-file-invoice-dollar text-[10px]"></i> Manajemen Faktur
                                </a>
                            </div>
                        </div>
                    @empty
                        <div class="flex flex-col items-center gap-3 py-10">
                            <div class="w-12 h-12 rounded-full bg-slate-100 flex items-center justify-center">
                                <i class="fas fa-inbox text-slate-300"></i>
                            </div>
                            <p class="text-slate-400 text-sm">Tidak ada permintaan perpanjangan</p>
                        </div>
                    @endforelse
                </div>
            </div>

        </div>

    </form>
</div>

<!-- MODAL HAPUS (tetap sama) -->
<div id="deleteModal" class="hidden fixed inset-0 bg-slate-900/60 backdrop-blur-sm h-full w-full z-50 flex items-center justify-center p-4">
    <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-sm transform transition-all">
        <div class="p-6 text-center">
            <div class="mx-auto flex items-center justify-center h-14 w-14 rounded-full bg-rose-50 mb-4">
                <i class="fas fa-exclamation-triangle text-rose-500 text-xl"></i>
            </div>
            <h3 class="text-lg font-bold text-slate-800 mb-2">Hapus Pesan?</h3>
            <p id="deleteModalMessage" class="text-sm text-slate-500 leading-relaxed">Apakah anda yakin?</p>
        </div>
        <div class="px-6 pb-6 flex items-center justify-center gap-3">
            <button id="deleteModalNoBtn" class="flex-1 py-2.5 bg-slate-100 text-slate-600 text-sm font-semibold rounded-xl hover:bg-slate-200 transition">Batal</button>
            <button id="deleteModalYesBtn" class="flex-1 py-2.5 bg-rose-600 text-white text-sm font-semibold rounded-xl hover:bg-rose-700 transition shadow-sm">Ya, Hapus</button>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const deleteModal = document.getElementById('deleteModal');
    const deleteYesBtn = document.getElementById('deleteModalYesBtn');
    const deleteNoBtn = document.getElementById('deleteModalNoBtn');

    deleteYesBtn.addEventListener('click', function() {
        document.getElementById('deleteForm').submit();
    });

    deleteNoBtn.addEventListener('click', closeDeleteModal);
    deleteModal.addEventListener('click', function(e) { if (e.target === deleteModal) closeDeleteModal(); });

    function closeDeleteModal() {
        deleteModal.classList.add('hidden');
        deleteModal.classList.remove('flex');
    }

    window.closeDeleteModal = closeDeleteModal;
});

let deleteMode = false;
let isAllSelected = false; // State untuk tracking pilih semua

function handleDeleteButton() {
    if (!deleteMode) {
        deleteMode = true;
        
        // Tampilkan semua checkbox
        document.querySelectorAll('.delete-checkbox').forEach(item => {
            item.classList.remove('hidden');
        });

        // Tampilkan tombol "Pilih Semua"
        document.getElementById('selectAllButton').classList.remove('hidden');

        // Ubah tampilan tombol Hapus
        const btn = document.getElementById('deleteButton');
        btn.innerHTML = '<i class="fas fa-check text-xs"></i> Konfirmasi Hapus';
        btn.classList.remove('bg-rose-50', 'text-rose-600', 'border-rose-200', 'hover:bg-rose-100');
        btn.classList.add('bg-rose-600', 'text-white', 'border-rose-600', 'hover:bg-rose-700');

        return;
    }

    const checked = document.querySelectorAll('input[name="pesan_ids[]"]:checked');

    if (checked.length === 0) {
        document.getElementById('deleteModalMessage').textContent = 'Pilih pesan terlebih dahulu.';
        document.getElementById('deleteModal').classList.remove('hidden');
        document.getElementById('deleteModal').classList.add('flex');
        
        // Sembunyikan tombol Yes jika tidak ada yang dipilih (hanya info peringatan)
        document.getElementById('deleteModalYesBtn').classList.add('hidden');
        document.getElementById('deleteModalNoBtn').textContent = 'Tutup';
        return;
    }

    // Tampilkan kembali tombol Yes jika sebelumnya disembunyikan
    document.getElementById('deleteModalYesBtn').classList.remove('hidden');
    document.getElementById('deleteModalNoBtn').textContent = 'Batal';
    
    document.getElementById('deleteModalMessage').textContent = 'Yakin ingin menghapus ' + checked.length + ' pesan yang dipilih?';
    document.getElementById('deleteModal').classList.remove('hidden');
    document.getElementById('deleteModal').classList.add('flex');
}

// Fungsi baru untuk Select All / Deselect All
function handleSelectAll() {
    const checkboxes = document.querySelectorAll('input[name="pesan_ids[]"]');
    const selectAllBtn = document.getElementById('selectAllButton');
    
    isAllSelected = !isAllSelected;

    checkboxes.forEach(cb => {
        cb.checked = isAllSelected;
    });

    // Ubah teks dan icon tombol sesuai state
    if(isAllSelected) {
        selectAllBtn.innerHTML = '<i class="far fa-square text-xs"></i> Batal Pilih';
    } else {
        selectAllBtn.innerHTML = '<i class="fas fa-check-square text-xs"></i> Pilih Semua';
    }
}
</script>
@endsection