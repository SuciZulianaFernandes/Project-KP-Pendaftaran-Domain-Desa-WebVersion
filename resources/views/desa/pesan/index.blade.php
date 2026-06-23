@extends('layouts.desa')
@section('title', 'Pesan')

@section('content')

<div class="bg-white p-6 rounded-xl shadow">

        <!-- HEADER -->
    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4 mb-6">
        <div>
            <h2 class="text-2xl font-bold text-slate-800">Pesan Masuk</h2>
            <p class="text-sm text-slate-400 mt-1">Notifikasi terkait domain, faktur, dan pembayaran</p>
        </div>

        <div class="flex items-center gap-3">
            @if($data->where('is_read', 0)->count() > 0)
            <form action="{{ route('desa.pesan.read-all') }}" method="POST">
                @csrf
                @method('PUT')
                <button type="submit"
                    class="inline-flex items-center gap-2 bg-emerald-50 hover:bg-emerald-100 text-emerald-600 border border-emerald-200 font-semibold py-2.5 px-5 rounded-xl transition text-sm">
                    <i class="fas fa-check-double text-xs"></i> Tandai Dibaca
                </button>
            </form>
            @endif

            <button type="button"
        id="selectAllButton"
        onclick="handleSelectAll()"
        class="hidden inline-flex items-center gap-2 bg-slate-50 hover:bg-slate-100 text-slate-600 border border-slate-200 font-semibold py-2 px-4 rounded-xl transition text-sm">
        <i class="far fa-square text-xs"></i> Batal hapus semua
    </button>

            <button type="button"
                id="deleteButton"
                onclick="handleDeleteButton()"
                class="bg-red-500 hover:bg-red-600 text-white w-10 h-10 rounded-full flex items-center justify-center transition shadow-sm">
                <i class="fas fa-trash text-sm"></i>
            </button>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- ========================================== -->
        <!-- KOLOM 1 : DOMAIN & FAKTUR -->
        <!-- ========================================== -->
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
            <div class="px-5 py-4 border-b border-slate-100 bg-slate-50/50 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-emerald-50 flex items-center justify-center">
                        <i class="fas fa-globe text-emerald-600 text-sm"></i>
                    </div>
                    <h3 class="text-sm font-bold text-slate-800">Domain & Faktur</h3>
                </div>
                @php 
                    $unreadDomain = $data->filter(function($item){
                        return str_contains($item->judul, 'Domain Aktif') || 
                               str_contains($item->judul, 'Faktur Telah Dibuat') || 
                               str_contains($item->judul, 'Faktur Perpanjangan Dibuat');
                    })->where('is_read', 0)->count(); 
                @endphp
                @if($unreadDomain > 0)
                    <span class="bg-red-500 text-white text-[10px] font-bold min-w-[20px] h-5 flex items-center justify-center rounded-full px-1.5">
                        {{ $unreadDomain }}
                    </span>
                @endif
            </div>

            <div class="p-4 space-y-3">
                @forelse( $data->filter(function($item){
                    return str_contains($item->judul, 'Domain Aktif') || 
                           str_contains($item->judul, 'Faktur Telah Dibuat') || 
                           str_contains($item->judul, 'Faktur Perpanjangan Dibuat');
                }) as $row )
                    <div class="relative {{ $row->is_read == 0 ? 'bg-emerald-50 border-emerald-400 border-l-4' : 'bg-emerald-50/50 border border-emerald-100' }} p-4 rounded-xl">
                        
                        <div class="hidden delete-checkbox absolute top-3 right-3">
                            <input type="checkbox" name="pesan_ids[]" value="{{ $row->id }}" class="w-4 h-4 accent-rose-500 rounded">
                        </div>

                        @if($row->is_read == 0)
                            <span class="inline-flex items-center gap-1 bg-red-500 text-white text-[9px] font-bold px-2 py-0.5 rounded-full mb-2 uppercase tracking-wider">
                                <span class="w-1.5 h-1.5 bg-white rounded-full animate-pulse"></span> Baru
                            </span>
                        @endif

                        <h3 class="font-bold text-sm text-emerald-800 pr-6">{{ $row->judul }}</h3>
                        <p class="text-sm text-slate-600 mt-1 leading-relaxed">{{ $row->isi }}</p>
                        <p class="text-xs text-slate-400 mt-3">{{ $row->created_at->format('d M Y, H:i') }}</p>

                        @if( str_contains($row->judul, 'Faktur Telah Dibuat') || str_contains($row->judul, 'Faktur Perpanjangan Dibuat') )
                            <div class="mt-3">
                                <a href="{{ route('desa.faktur.index') }}" class="inline-flex items-center gap-1.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold px-4 py-2 rounded-lg transition shadow-sm">
                                    <i class="fas fa-eye text-[10px]"></i> Lihat Faktur
                                </a>
                            </div>
                        @endif
                    </div>
                @empty
                    <div class="flex flex-col items-center gap-3 py-10">
                        <div class="w-12 h-12 rounded-full bg-slate-100 flex items-center justify-center"><i class="fas fa-inbox text-slate-300"></i></div>
                        <p class="text-slate-400 text-sm">Tidak ada pesan domain</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- ========================================== -->
        <!-- KOLOM 2 : KONFIRMASI PEMBAYARAN -->
        <!-- ========================================== -->
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
            <div class="px-5 py-4 border-b border-slate-100 bg-slate-50/50 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-amber-50 flex items-center justify-center">
                        <i class="fas fa-credit-card text-amber-600 text-sm"></i>
                    </div>
                    <h3 class="text-sm font-bold text-slate-800">Konfirmasi Pembayaran</h3>
                </div>
                @php 
                    $unreadKonfirmasi = $data->filter(function($item){ 
                        return str_contains($item->judul, 'Konfirmasi Pembayaran'); 
                    })->where('is_read', 0)->count(); 
                @endphp
                @if($unreadKonfirmasi > 0)
                    <span class="bg-red-500 text-white text-[10px] font-bold min-w-[20px] h-5 flex items-center justify-center rounded-full px-1.5">
                        {{ $unreadKonfirmasi }}
                    </span>
                @endif
            </div>

            <div class="p-4 space-y-3">
                @forelse( $data->filter(function($item){ 
                    return str_contains($item->judul, 'Konfirmasi Pembayaran'); 
                }) as $row )
                    <div class="relative {{ $row->is_read == 0 ? 'bg-amber-50 border-amber-400 border-l-4' : 'bg-amber-50/50 border border-amber-100' }} p-4 rounded-xl">
                        
                        <div class="hidden delete-checkbox absolute top-3 right-3">
                            <input type="checkbox" name="pesan_ids[]" value="{{ $row->id }}" class="w-4 h-4 accent-rose-500 rounded">
                        </div>

                        @if($row->is_read == 0)
                            <span class="inline-flex items-center gap-1 bg-red-500 text-white text-[9px] font-bold px-2 py-0.5 rounded-full mb-2 uppercase tracking-wider">
                                <span class="w-1.5 h-1.5 bg-white rounded-full animate-pulse"></span> Baru
                            </span>
                        @endif

                        <h3 class="font-bold text-sm text-amber-800 pr-6">{{ $row->judul }}</h3>
                        <p class="text-sm text-slate-600 mt-1 leading-relaxed">
                            @if($row->is_read == 1)
                                Silahkan tunggu faktur dari admin kominfo
                            @else
                                {{ $row->isi }}
                            @endif
                        </p>
                        <p class="text-xs text-slate-400 mt-3">{{ $row->created_at->format('d M Y, H:i') }}</p>

                        <div class="mt-3">
    @if($row->is_read == 0)
        <a href="{{ route('desa.verifikasi.detail', $row->id_pengajuan) }}"
           class="inline-flex items-center gap-1.5 bg-amber-600 hover:bg-amber-700 text-white text-xs font-bold px-4 py-2 rounded-lg transition shadow-sm">
            <i class="fas fa-check text-[10px]"></i> Detail Pengajuan
        </a>
    @else
        <a href="{{ route('desa.verifikasi.detail', $row->id_pengajuan) }}"
           class="inline-flex items-center gap-1.5 bg-slate-500 hover:bg-slate-600 text-white text-xs font-bold px-4 py-2 rounded-lg transition shadow-sm">
            <i class="fas fa-eye text-[10px]"></i> Lihat Detail
        </a>
    @endif
</div>
                    </div>
                @empty
                    <div class="flex flex-col items-center gap-3 py-10">
                        <div class="w-12 h-12 rounded-full bg-slate-100 flex items-center justify-center"><i class="fas fa-inbox text-slate-300"></i></div>
                        <p class="text-slate-400 text-sm">Tidak ada konfirmasi pembayaran</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- ========================================== -->
        <!-- KOLOM 3 : PERLU PERBAIKAN -->
        <!-- ========================================== -->
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
            <div class="px-5 py-4 border-b border-slate-100 bg-slate-50/50 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-rose-50 flex items-center justify-center">
                        <i class="fas fa-tools text-rose-600 text-sm"></i>
                    </div>
                    <h3 class="text-sm font-bold text-slate-800">Perlu Perbaikan</h3>
                </div>
                @php 
                    $unreadPerbaikan = $data->filter(function($item){ 
                        return str_contains($item->judul, 'Perlu Perbaikan') || 
                               str_contains($item->judul, 'Ditolak'); 
                    })->where('is_read', 0)->count(); 
                @endphp
                @if($unreadPerbaikan > 0)
                    <span class="bg-red-500 text-white text-[10px] font-bold min-w-[20px] h-5 flex items-center justify-center rounded-full px-1.5">
                        {{ $unreadPerbaikan }}
                    </span>
                @endif
            </div>

            <div class="p-4 space-y-3">
                @forelse( $data->filter(function($item){ 
                    return str_contains($item->judul, 'Perlu Perbaikan') || 
                           str_contains($item->judul, 'Ditolak'); 
                }) as $row )
                    <div class="relative {{ $row->is_read == 0 ? 'bg-rose-50 border-rose-400 border-l-4' : 'bg-rose-50/50 border border-rose-100' }} p-4 rounded-xl">
                        
                        <div class="hidden delete-checkbox absolute top-3 right-3">
                            <input type="checkbox" name="pesan_ids[]" value="{{ $row->id }}" class="w-4 h-4 accent-rose-500 rounded">
                        </div>

                        @if($row->is_read == 0)
                            <span class="inline-flex items-center gap-1 bg-red-500 text-white text-[9px] font-bold px-2 py-0.5 rounded-full mb-2 uppercase tracking-wider">
                                <span class="w-1.5 h-1.5 bg-white rounded-full animate-pulse"></span> Baru
                            </span>
                        @endif

                        <h3 class="font-bold text-sm text-rose-800 pr-6">{{ $row->judul }}</h3>
                        <p class="text-sm text-slate-600 mt-1 leading-relaxed">{{ $row->isi }}</p>
                        <p class="text-xs text-slate-400 mt-3">{{ $row->created_at->format('d M Y, H:i') }}</p>
                    </div>
                @empty
                    <div class="flex flex-col items-center gap-3 py-10">
                        <div class="w-12 h-12 rounded-full bg-slate-100 flex items-center justify-center"><i class="fas fa-inbox text-slate-300"></i></div>
                        <p class="text-slate-400 text-sm">Tidak ada pesan perbaikan</p>
                    </div>
                @endforelse
            </div>
        </div>

    </div>

</div>

<!-- SCRIPT HAPUS -->
<script>
let deleteMode = false;
let isAllSelected = false; // State pelacak status pilih semua

function handleDeleteButton() 
{
    const btn = document.getElementById('deleteButton');
    const selectAllBtn = document.getElementById('selectAllButton');
    const checkboxes = document.querySelectorAll('input[name="pesan_ids[]"]');

    if (!deleteMode) {
        deleteMode = true;
        
        // 1. Munculkan semua checkbox kotak centang
        document.querySelectorAll('.delete-checkbox').forEach(item => {
            item.classList.remove('hidden');
        });

        // 2. OTOMATIS LANGSUNG CENTANG SEMUA PESAN
        isAllSelected = true;
        checkboxes.forEach(cb => {
            cb.checked = true;
        });

        // 3. Munculkan tombol "Batal Pilih" pendamping
        selectAllBtn.classList.remove('hidden');

        // 4. Ubah icon tombol tong sampah menjadi icon check (konfirmasi)
        btn.innerHTML = '<i class="fas fa-check text-sm"></i>';
        btn.classList.remove('bg-red-500', 'hover:bg-red-600');
        btn.classList.add('bg-emerald-600', 'hover:bg-emerald-700');
        return;
    }

    // --- LOGIC EKSEKUSI HAPUS SAAT KLIK KEDUA ---
    const checked = document.querySelectorAll('input[name="pesan_ids[]"]:checked');

    if (checked.length === 0) {
        alert('Pilih pesan terlebih dahulu');
        return;
    }

    if (confirm('Apakah Anda yakin ingin menghapus ' + checked.length + ' pesan yang dipilih?')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = "{{ route('desa.pesan.hapus.selected') }}";

        const csrfInput = document.createElement('input');
        csrfInput.type = 'hidden';
        csrfInput.name = '_token';
        csrfInput.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        form.appendChild(csrfInput);

        const methodInput = document.createElement('input');
        methodInput.type = 'hidden';
        methodInput.name = '_method';
        methodInput.value = 'DELETE';
        form.appendChild(methodInput);

        checked.forEach((checkbox) => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'pesan_ids[]';
            input.value = checkbox.value;
            form.appendChild(input);
        });

        document.body.appendChild(form);
        form.submit();
        document.body.removeChild(form);
    }
}

// Fungsi alternatif jika user ingin uncheck massal atau check ulang secara manual
function handleSelectAll() 
{
    const checkboxes = document.querySelectorAll('input[name="pesan_ids[]"]');
    const selectAllBtn = document.getElementById('selectAllButton');
    
    isAllSelected = !isAllSelected;

    checkboxes.forEach(cb => {
        cb.checked = isAllSelected;
    });

    if (isAllSelected) {
        selectAllBtn.innerHTML = '<i class="far fa-square text-xs"></i> Batal Pilih';
    } else {
        selectAllBtn.innerHTML = '<i class="fas fa-check-square text-xs"></i> Pilih Semua';
    }
}
</script>
@endsection