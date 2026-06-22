@extends('layouts.admin')

@section('title', 'Detail User')

@section('content')
<div class="flex flex-col lg:flex-row gap-6">

    <!-- SIDEBAR KIRI -->
    <div class="w-full lg:w-80 flex-shrink-0 space-y-4">

        <!-- PROFIL USER -->
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
            <div class="px-5 py-4 border-b border-slate-100 bg-slate-50/50">
                <h3 class="font-bold text-slate-800 text-xs uppercase tracking-widest">Profil User</h3>
            </div>

            <div class="p-5 space-y-5">
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-[#109696] to-[#1760C5] flex items-center justify-center text-white text-lg font-extrabold flex-shrink-0">
                        {{ strtoupper(substr($user->username, 0, 2)) }}
                    </div>
                    <div class="min-w-0">
                        <p class="font-bold text-slate-800 truncate">{{ $user->name ?? $user->username }}</p>
                        <p class="text-sm text-slate-400">@{{ $user->username }}</p>
                    </div>
                </div>

                <div class="flex items-center gap-3 p-3.5 rounded-xl border
                    {{ $user->role === 'admin' ? 'bg-purple-50 border-purple-100' : 'bg-emerald-50 border-emerald-100' }}">
                    <span class="w-2.5 h-2.5 rounded-full flex-shrink-0
                        {{ $user->role === 'admin' ? 'bg-purple-500' : 'bg-emerald-500' }}"></span>
                    <span class="text-sm font-bold capitalize
                        {{ $user->role === 'admin' ? 'text-purple-700' : 'text-emerald-700' }}">
                        {{ ucfirst($user->role) }}
                    </span>
                </div>

                <div class="space-y-3 pt-1">
                    <div class="flex items-center gap-3 text-sm">
                        <div class="w-8 h-8 rounded-lg bg-slate-50 flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-envelope text-slate-400 text-xs"></i>
                        </div>
                        <span class="text-slate-600 truncate">{{ $user->email }}</span>
                    </div>
                    @if($user->role === 'admin' && $user->no_hp)
                    <div class="flex items-center gap-3 text-sm">
                        <div class="w-8 h-8 rounded-lg bg-slate-50 flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-phone text-slate-400 text-xs"></i>
                        </div>
                        <span class="text-slate-600">{{ $user->no_hp }}</span>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- AKTIVITAS AKUN -->
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
            <div class="px-5 py-4 border-b border-slate-100 bg-slate-50/50">
                <h3 class="font-bold text-slate-800 text-xs uppercase tracking-widest">Aktivitas</h3>
            </div>
            <div class="p-5 space-y-4">
                <div>
                    <p class="text-slate-400 text-[10px] font-bold uppercase tracking-widest mb-0.5">Dibuat</p>
                    <p class="text-sm font-medium text-slate-700">{{ $user->created_at->format('d M Y') }}</p>
                    <p class="text-xs text-slate-400">{{ $user->created_at->format('H:i') }} WIB</p>
                </div>
                <div class="border-t border-slate-100 pt-4">
                    <p class="text-slate-400 text-[10px] font-bold uppercase tracking-widest mb-0.5">Diperbarui</p>
                    <p class="text-sm font-medium text-slate-700">{{ $user->updated_at->format('d M Y') }}</p>
                    <p class="text-xs text-slate-400">{{ $user->updated_at->format('H:i') }} WIB</p>
                </div>
            </div>
        </div>

    </div>

    <!-- CONTENT KANAN -->
    <div class="flex-1 min-w-0">

        <div class="bg-white p-6 md:p-8 rounded-2xl border border-slate-100 shadow-sm">

            <!-- HEADER -->
            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4 mb-8">
                <div>
                    <h2 class="text-2xl font-extrabold text-slate-800 tracking-tight">Detail User</h2>
                    <p class="text-sm text-slate-400 mt-1">Informasi lengkap akun user</p>
                </div>
                <a href="{{ route('admin.users.index') }}" 
                   class="inline-flex items-center gap-2 bg-slate-100 hover:bg-slate-200 text-slate-600 font-semibold py-2.5 px-5 rounded-xl transition text-sm">
                    <i class="fas fa-arrow-left text-xs"></i> Kembali
                </a>
            </div>

            <!-- INFORMASI AKUN -->
            <div class="mb-8">
                <h3 class="text-sm font-bold text-slate-800 uppercase tracking-wider mb-4 flex items-center gap-2">
                    <span class="w-1.5 h-5 bg-gradient-to-b from-[#109696] to-[#1760C5] rounded-full"></span>
                    Informasi Akun
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-4 text-sm bg-slate-50/50 p-5 rounded-xl border border-slate-100">
                    <div class="flex flex-col">
                        <span class="text-slate-400 text-xs font-semibold mb-0.5">ID User</span>
                        <span class="text-slate-700 font-mono font-medium">{{ $user->id_user }}</span>
                    </div>
                    <div class="flex flex-col">
                        <span class="text-slate-400 text-xs font-semibold mb-0.5">Username</span>
                        <span class="text-slate-700 font-medium">{{ $user->username }}</span>
                    </div>
                    <div class="flex flex-col">
                        <span class="text-slate-400 text-xs font-semibold mb-0.5">Email</span>
                        <span class="text-slate-700 font-medium">{{ $user->email }}</span>
                    </div>
                    <div class="flex flex-col">
                        <span class="text-slate-400 text-xs font-semibold mb-0.5">Role</span>
                        <span class="mt-1">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold uppercase
                                {{ $user->role === 'admin' ? 'bg-purple-50 text-purple-700' : 'bg-emerald-50 text-emerald-700' }}">
                                <span class="w-1.5 h-1.5 rounded-full 
                                    {{ $user->role === 'admin' ? 'bg-purple-500' : 'bg-emerald-500' }}"></span>
                                {{ ucfirst($user->role) }}
                            </span>
                        </span>
                    </div>
                </div>
            </div>

            <!-- DETAIL ADMIN -->
            @if($user->role === 'admin')
            <div class="mb-8">
                <h3 class="text-sm font-bold text-slate-800 uppercase tracking-wider mb-4 flex items-center gap-2">
                    <span class="w-1.5 h-5 bg-gradient-to-b from-[#109696] to-[#1760C5] rounded-full"></span>
                    Informasi Pribadi
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-4 text-sm bg-slate-50/50 p-5 rounded-xl border border-slate-100">
                    <div class="flex flex-col">
                        <span class="text-slate-400 text-xs font-semibold mb-0.5">Nama Lengkap</span>
                        <span class="text-slate-700 font-medium">{{ $user->name ?? '-' }}</span>
                    </div>
                    <div class="flex flex-col">
                        <span class="text-slate-400 text-xs font-semibold mb-0.5">No. HP</span>
                        <span class="text-slate-700 font-medium">{{ $user->no_hp ?? '-' }}</span>
                    </div>
                </div>
            </div>
            @endif

            <!-- DETAIL DESA -->
            @if($user->role === 'desa' && $user->desa)
            <div class="mb-8">
                <h3 class="text-sm font-bold text-slate-800 uppercase tracking-wider mb-4 flex items-center gap-2">
                    <span class="w-1.5 h-5 bg-gradient-to-b from-[#109696] to-[#1760C5] rounded-full"></span>
                    Informasi Desa
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-4 text-sm bg-slate-50/50 p-5 rounded-xl border border-slate-100">
                    <div class="flex flex-col">
                        <span class="text-slate-400 text-xs font-semibold mb-0.5">Nama Desa</span>
                        <span class="text-slate-700 font-medium">{{ $user->desa->nama_desa ?? '-' }}</span>
                    </div>
                    <div class="flex flex-col">
                        <span class="text-slate-400 text-xs font-semibold mb-0.5">Kepala Desa</span>
                        <span class="text-slate-700 font-medium">{{ $user->desa->nama_kepala_desa ?? '-' }}</span>
                    </div>
                    <div class="flex flex-col">
                        <span class="text-slate-400 text-xs font-semibold mb-0.5">NIP Kepala Desa</span>
                        <span class="text-slate-700 font-mono font-medium">{{ $user->desa->nip_kepala_desa ?? '-' }}</span>
                    </div>
                    <div class="flex flex-col">
                        <span class="text-slate-400 text-xs font-semibold mb-0.5">ID Provinsi</span>
                        <span class="text-slate-700 font-mono font-medium">{{ $user->desa->id_prov ?? '-' }}</span>
                    </div>
                    <div class="flex flex-col">
                        <span class="text-slate-400 text-xs font-semibold mb-0.5">ID Kabupaten</span>
                        <span class="text-slate-700 font-mono font-medium">{{ $user->desa->id_kab ?? '-' }}</span>
                    </div>
                    <div class="flex flex-col md:col-span-2">
                        <span class="text-slate-400 text-xs font-semibold mb-0.5">Alamat</span>
                        <span class="text-slate-700 font-medium leading-relaxed">{{ $user->desa->alamat ?? '-' }}</span>
                    </div>
                </div>
            </div>
            @endif

            <hr class="my-8 border-slate-100">

            <!-- AKSI -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-end gap-3">
                <a href="{{ route('admin.users.edit', $user) }}" 
                   class="inline-flex items-center justify-center gap-2 bg-[#109696] hover:bg-[#0d7a7a] text-white font-bold py-2.5 px-6 rounded-xl shadow-sm transition text-sm">
                    <i class="fas fa-pencil-alt text-xs"></i> Edit User
                </a>
                <form action="{{ route('admin.users.destroy', $user) }}" method="POST" id="deleteForm">
                    @csrf
                    @method('DELETE')
                    <button type="button"
                            class="js-confirm-btn inline-flex items-center justify-center gap-2 bg-rose-50 hover:bg-rose-100 text-rose-600 border border-rose-200 font-bold py-2.5 px-6 rounded-xl transition text-sm"
                            data-confirm-message="Yakin ingin menghapus user {{ $user->username }}? Tindakan ini tidak dapat dibatalkan."
                            data-form-id="deleteForm">
                        <i class="fas fa-trash-alt text-xs"></i> Hapus User
                    </button>
                </form>
            </div>

        </div>
    </div>
</div>

<!-- MODAL KONFIRMASI -->
<div id="confirmationModal" class="hidden fixed inset-0 bg-slate-900/60 backdrop-blur-sm h-full w-full z-50 flex items-center justify-center p-4">
    <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-sm transform transition-all">
        <div class="p-6 text-center">
            <div class="mx-auto flex items-center justify-center h-14 w-14 rounded-full bg-rose-50 mb-4">
                <i class="fas fa-exclamation-triangle text-rose-500 text-xl"></i>
            </div>
            <h3 class="text-lg font-bold text-slate-800 mb-2">Konfirmasi Aksi</h3>
            <p id="modalConfirmMessage" class="text-sm text-slate-500 leading-relaxed">Apakah anda yakin?</p>
        </div>
        <div class="px-6 pb-6 flex items-center justify-center gap-3">
            <button id="modalNoBtn" class="flex-1 py-2.5 bg-slate-100 text-slate-600 text-sm font-semibold rounded-xl hover:bg-slate-200 transition">Batal</button>
            <button id="modalYesBtn" class="flex-1 py-2.5 bg-rose-600 text-white text-sm font-semibold rounded-xl hover:bg-rose-700 transition shadow-sm">Ya, Lanjutkan</button>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('confirmationModal');
    const yesBtn = document.getElementById('modalYesBtn');
    const noBtn = document.getElementById('modalNoBtn');
    const modalMessage = document.getElementById('modalConfirmMessage');
    const confirmBtns = document.querySelectorAll('.js-confirm-btn');
    let formToSubmit = null;

    confirmBtns.forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const formId = this.getAttribute('data-form-id');
            formToSubmit = document.getElementById(formId);
            modalMessage.textContent = this.getAttribute('data-confirm-message') || 'Apakah anda yakin?';
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        });
    });

    yesBtn.addEventListener('click', function() {
        if (formToSubmit) formToSubmit.submit();
        closeModal();
    });

    noBtn.addEventListener('click', closeModal);
    modal.addEventListener('click', function(e) { if (e.target === modal) closeModal(); });

    function closeModal() {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        formToSubmit = null;
    }
});
</script>
@endsection