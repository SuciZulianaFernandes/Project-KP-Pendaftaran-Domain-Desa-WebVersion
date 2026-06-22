@extends('layouts.admin')

@section('title', 'Pesan Admin')

@section('content')
<div class="space-y-6">

    <!-- HEADER -->
    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
        <div>
            <h1 class="text-2xl font-extrabold text-slate-800 tracking-tight">Pesan Admin</h1>
            <p class="text-sm text-slate-400 mt-1">Notifikasi dari desa terkait pembayaran dan perpanjangan</p>
        </div>

        <button type="button"
            id="deleteButton"
            onclick="handleDeleteButton()"
            class="inline-flex items-center gap-2 bg-rose-50 hover:bg-rose-100 text-rose-600 border border-rose-200 font-semibold py-2.5 px-5 rounded-xl transition text-sm">
            <i class="fas fa-trash-alt text-xs"></i> Hapus Pesan
        </button>
    </div>

    <!-- FORM HAPUS (STATIS) -->
    <form action="{{ route('admin.pesan.hapus.selected') }}"
        method="POST"
        id="deleteForm">

        @csrf
        @method('DELETE')

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            <!-- KOLOM 1 : KONFIRMASI DARI DESA -->
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
                <div class="px-5 py-4 border-b border-slate-100 bg-slate-50/50 flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-sky-50 flex items-center justify-center">
                        <i class="fas fa-check-circle text-sky-600 text-sm"></i>
                    </div>
                    <h3 class="text-sm font-bold text-slate-800">Konfirmasi Desa</h3>
                </div>

                <div class="p-4 space-y-3">
                    @forelse($data->where('judul', 'Konfirmasi Pembayaran Disetujui') as $row)
                        <div class="relative bg-sky-50/50 border border-sky-100 p-4 rounded-xl">

                            <div class="hidden delete-checkbox absolute top-3 right-3">
                                <input type="checkbox" name="pesan_ids[]" value="{{ $row->id }}" class="w-4 h-4 accent-rose-500 rounded">
                            </div>

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

            <!-- KOLOM 2 : BUKTI PEMBAYARAN -->
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
                <div class="px-5 py-4 border-b border-slate-100 bg-slate-50/50 flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-amber-50 flex items-center justify-center">
                        <i class="fas fa-image text-amber-600 text-sm"></i>
                    </div>
                    <h3 class="text-sm font-bold text-slate-800">Bukti Pembayaran Masuk</h3>
                </div>

                <div class="p-4 space-y-3">
                    @forelse($data->where('judul', 'Bukti Pembayaran') as $row)
                        <div class="relative bg-amber-50/50 border border-amber-100 p-4 rounded-xl">

                            <div class="hidden delete-checkbox absolute top-3 right-3">
                                <input type="checkbox" name="pesan_ids[]" value="{{ $row->id }}" class="w-4 h-4 accent-rose-500 rounded">
                            </div>

                            <h4 class="font-bold text-sm text-amber-800 pr-6">{{ $row->judul }}</h4>
                            <p class="text-sm text-slate-600 mt-1 leading-relaxed">{{ $row->isi }}</p>
                            <p class="text-xs text-slate-400 mt-3">{{ $row->created_at->format('d M Y, H:i') }}</p>
                        </div>
                    @empty
                        <div class="flex flex-col items-center gap-3 py-10">
                            <div class="w-12 h-12 rounded-full bg-slate-100 flex items-center justify-center">
                                <i class="fas fa-inbox text-slate-300"></i>
                            </div>
                            <p class="text-slate-400 text-sm">Tidak ada bukti pembayaran masuk</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- KOLOM 3 : PERPANJANGAN -->
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
                <div class="px-5 py-4 border-b border-slate-100 bg-slate-50/50 flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-purple-50 flex items-center justify-center">
                        <i class="fas fa-sync-alt text-purple-600 text-sm"></i>
                    </div>
                    <h3 class="text-sm font-bold text-slate-800">Permintaan Perpanjangan</h3>
                </div>

                <div class="p-4 space-y-3">
                    @forelse($data->where('judul', 'Permintaan Perpanjangan Domain') as $row)
                        <div class="relative bg-purple-50/50 border border-purple-100 p-4 rounded-xl">

                            <div class="hidden delete-checkbox absolute top-3 right-3">
                                <input type="checkbox" name="pesan_ids[]" value="{{ $row->id }}" class="w-4 h-4 accent-rose-500 rounded">
                            </div>

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

<!-- MODAL HAPUS -->
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

    // Expose ke global biar bisa dipanggil dari onclick
    window.closeDeleteModal = closeDeleteModal;
});

let deleteMode = false;

function handleDeleteButton() {
    if (!deleteMode) {
        deleteMode = true;
        document.querySelectorAll('.delete-checkbox').forEach(item => {
            item.classList.remove('hidden');
        });

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
        return;
    }

    document.getElementById('deleteModalMessage').textContent = 'Yakin ingin menghapus ' + checked.length + ' pesan yang dipilih?';
    document.getElementById('deleteModal').classList.remove('hidden');
    document.getElementById('deleteModal').classList.add('flex');
}
</script>
@endsection