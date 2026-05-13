@extends('layouts.admin')

@section('title', 'Manajemen User')

@section('content')

@include('components.inv-styles')

<div class="container-fluid" style="padding:0 24px;max-width:1400px">
    <div style="display:flex;align-items:flex-end;justify-content:space-between;margin-bottom:22px;flex-wrap:wrap;gap:10px">
        <div>
            <h1 style="font-size:22px;font-weight:800;margin:0;letter-spacing:-.5px">Manajemen User</h1>
            <p style="font-size:14px;color:#64748b;margin:4px 0 0">Kelola data pengguna admin dan desa</p>
        </div>
        <a href="{{ route('admin.users.create') }}" class="btn" style="background:#dc2626;color:white;font-weight:600;padding:8px 16px;border-radius:6px;text-decoration:none;box-shadow:0 1px 2px rgba(0,0,0,0.05);">
            <i class="fas fa-plus mr-2"></i> Tambah User
        </a>
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

        {{-- Search & Filter --}}
        <div style="padding: 16px; border-bottom: 1px solid #e2e8f0; display: flex; gap: 10px; align-items: center;">
            <div style="position:relative;flex:1">
                <input type="text" id="invSearch" placeholder="Cari berdasarkan nama, username, atau email..." 
                    style="width:100%;padding:10px 16px;padding-left:40px;border:1px solid #cbd5e1;border-radius:8px;outline:none;font-size:14px;transition:all .2s">
                <i class="fas fa-search" style="position:absolute;left:14px;top:13px;color:#94a3b8"></i>
            </div>
            <div style="width: 150px;">
                <select id="invFilter" style="width:100%;padding:10px;border:1px solid #cbd5e1;border-radius:8px;background:white;cursor:pointer;">
                    <option value="">Semua Role</option>
                    <option value="admin">Admin</option>
                    <option value="desa">Desa</option>
                </select>
            </div>
        </div>

        <div style="overflow-x:auto">
            <table class="inv-table" id="invTable">
                <thead>
                    <tr>
                        {{-- Kolom No (Non-sortable) --}}
                        <th>No</th>
                        
                        <th data-type="string" class="sortable">Username <i class="sort-icon"></i></th>
                        <th data-type="string" class="sortable">Nama Lengkap <i class="sort-icon"></i></th>
                        <th data-type="string" class="sortable">Email <i class="sort-icon"></i></th>
                        <th data-type="string" class="sortable">No. HP <i class="sort-icon"></i></th>
                        <th data-type="string" class="sortable">Role <i class="sort-icon"></i></th>
                        <th style="text-align:center; cursor: default;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $indexUser => $user)
                        <tr data-role="{{ $user->role }}" style="animation-delay:{{$indexUser*0.05}}s">
                            
                            {{-- Nomor Urut --}}
                            <td>{{ $users->firstItem() + $indexUser }}</td>
                            
                            <td style="font-weight:500;color:#334155">{{ $user->username }}</td>
                            <td>{{ $user->name }}</td>
                            <td><span class="inv-date" style="font-style:italic">{{ $user->email }}</span></td>
                            <td>{{ $user->no_hp ?? '-' }}</td>
                            
                            <td>
                                @if($user->role === 'admin')
                                    <span class="px-2 py-1 rounded text-xs font-semibold bg-purple-100 text-purple-700 border border-purple-200">
                                        Admin
                                    </span>
                                @else
                                    <span class="px-2 py-1 rounded text-xs font-semibold bg-blue-100 text-blue-700 border border-blue-200">
                                        Desa
                                    </span>
                                @endif
                            </td>

                            <td style="text-align:center">
                                <div style="display:flex;justify-content:center;gap:8px;">
                                    <a href="{{ route('admin.users.show', $user) }}" class="inv-btn-d" title="Lihat"><i class="fas fa-eye"></i></a>
                                    <a href="{{ route('admin.users.edit', $user) }}" class="inv-btn-d" title="Edit"><i class="fas fa-edit"></i></a>
                                    
                                    <form action="{{ route('admin.users.destroy', $user) }}" method="POST" style="display:inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus user ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inv-btn-d" style="color:#dc2626" title="Hapus"><i class="fas fa-trash"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr class="inv-empty"><td colspan="7"><i class="fas fa-inbox"></i> Tidak ada data user</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @include('components.inv-pagination', ['paginator' => $users])
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded',function(){
    // --- LOGIC SEARCH & FILTER (Original Logic) ---
    var s=document.getElementById('invSearch'),
        f=document.getElementById('invFilter'),
        rows=Array.from(document.querySelectorAll('#invTable tbody tr[data-role]')),
        empty=document.querySelector('.inv-empty');

    function filter(){
        var q=s.value.trim().toLowerCase(), v=f.value, n=0;
        rows.forEach(function(r){
            var textMatch = (!q || r.textContent.toLowerCase().includes(q));
            var roleMatch = (!v || r.dataset.role === v);
            var show = textMatch && roleMatch;
            r.style.display=show?'':'none';
            if(show)n++;
        });
        if(empty)empty.style.display=n?'none':'';
    }
    if(s) s.addEventListener('input',filter);
    if(f) f.addEventListener('change',filter);

    // --- LOGIC SORTING (New: Client Side Instant Sort) ---
    const sortHeaders = document.querySelectorAll('th.sortable');
    
    sortHeaders.forEach(header => {
        header.style.cursor = 'pointer';
        
        // Tambahkan hover effect sederhana
        header.addEventListener('mouseenter', () => header.style.backgroundColor = '#f8fafc');
        header.addEventListener('mouseleave', () => header.style.backgroundColor = '');

        header.addEventListener('click', () => {
            const table = header.closest('table');
            const tbody = table.querySelector('tbody');
            const allRows = Array.from(tbody.querySelectorAll('tr'));
            
            // Ambil tipe data (number/string) dari atribut th
            const type = header.dataset.type;
            const icon = header.querySelector('.sort-icon');
            // colIndex dikurangi 1 karena kolom pertama (No) tidak sortable
            const colIndex = Array.from(header.parentNode.children).indexOf(header);

            // 1. Reset icon di header lain
            document.querySelectorAll('th.sortable .sort-icon').forEach(i => i.textContent = '');
            
            // 2. Tentukan arah urutan (toggle asc/desc)
            let isAsc = !header.classList.contains('asc');
            
            // 3. Reset kelas di semua header
            sortHeaders.forEach(h => h.classList.remove('asc', 'desc'));
            
            // 4. Set state ke header yang diklik
            header.classList.add(isAsc ? 'asc' : 'desc');
            icon.textContent = isAsc ? ' ▲' : ' ▼';

            // 5. Proses Sorting
            allRows.sort((a, b) => {
                let aVal = a.cells[colIndex].textContent.trim();
                let bVal = b.cells[colIndex].textContent.trim();

                // Sorting String biasa (abaikan huruf besar/kecil)
                // Catatan: Tipe 'number' hanya tersedia jika ditambahkan di atribut th.
                // Untuk kolom No, sorting dinonaktifkan.
                if (type === 'number') {
                     aVal = parseInt(aVal.replace(/\D/g, ''), 10);
                     bVal = parseInt(bVal.replace(/\D/g, ''), 10);
                     return isAsc ? aVal - bVal : bVal - aVal;
                }

                return isAsc ? aVal.localeCompare(bVal) : bVal.localeCompare(aVal);
            });

            // 6. Re-append baris yang sudah terurut ke tabel
            allRows.forEach(row => tbody.appendChild(row));
        });
    });
});
</script>
@endsection