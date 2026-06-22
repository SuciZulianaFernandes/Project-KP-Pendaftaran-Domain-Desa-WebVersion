@extends('layouts.admin')

@section('title', 'Manajemen User')

@section('content')

<div class="space-y-6">

    <!-- HEADER -->
    <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4">
        <div>
            <h1 class="text-xl font-extrabold text-slate-800 tracking-tight">Manajemen User</h1>
            <p class="text-sm text-slate-400 mt-1">Kelola data pengguna admin dan desa</p>
        </div>
        <a href="{{ route('admin.users.create') }}"
           class="inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-gradient-to-br from-[#109696] to-[#1A85A5] text-white text-sm font-semibold rounded-xl shadow-sm shadow-[#109696]/20 hover:shadow-md transition-all duration-200">
            <i class="fas fa-plus text-xs"></i> Tambah User
        </a>
    </div>

    <!-- WIDGET STATISTIK -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">

        <!-- Total User -->
        <div class="bg-white rounded-2xl border border-slate-100 p-5 flex items-center gap-4 shadow-sm hover:shadow-md transition-shadow">
            <div class="w-12 h-12 bg-gradient-to-br from-slate-700 to-slate-800 rounded-xl flex items-center justify-center shadow-sm shadow-slate-700/20 flex-shrink-0">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"/>
                </svg>
            </div>
            <div>
                <p class="text-slate-400 text-xs font-semibold uppercase tracking-wider">Total User</p>
                <h3 class="text-2xl font-extrabold text-slate-800 mt-0.5">{{ $totalUser }}</h3>
            </div>
        </div>

        <!-- Total Admin -->
        <div class="bg-white rounded-2xl border border-slate-100 p-5 flex items-center gap-4 shadow-sm hover:shadow-md transition-shadow">
            <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl flex items-center justify-center shadow-sm shadow-purple-500/20 flex-shrink-0">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z"/>
                </svg>
            </div>
            <div>
                <p class="text-slate-400 text-xs font-semibold uppercase tracking-wider">Total Admin</p>
                <h3 class="text-2xl font-extrabold text-purple-700 mt-0.5">{{ $totalAdmin }}</h3>
            </div>
        </div>

        <!-- Total Desa -->
        <div class="bg-white rounded-2xl border border-slate-100 p-5 flex items-center gap-4 shadow-sm hover:shadow-md transition-shadow">
            <div class="w-12 h-12 bg-gradient-to-br from-[#109696] to-[#1A85A5] rounded-xl flex items-center justify-center shadow-sm shadow-[#109696]/20 flex-shrink-0">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h12m-.75 4.5H21m-3.75 3h.008v.008h-.008v-.008zm0 3h.008v.008h-.008v-.008zm0 3h.008v.008h-.008v-.008z"/>
                </svg>
            </div>
            <div>
                <p class="text-slate-400 text-xs font-semibold uppercase tracking-wider">Total Desa</p>
                <h3 class="text-2xl font-extrabold text-[#1A85A5] mt-0.5">{{ $totalDesa }}</h3>
            </div>
        </div>

    </div>

    <!-- MAIN CARD -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm">

        {{-- Alert Notifikasi --}}
        @if(session('success'))
            <div class="mx-6 mt-6 bg-emerald-50 border border-emerald-200/60 text-emerald-700 text-sm font-medium px-4 py-3 rounded-xl flex items-center justify-between">
                <span><i class="fas fa-check-circle mr-2"></i>{{ session('success') }}</span>
                <button type="button" class="text-emerald-400 hover:text-emerald-600 transition" onclick="this.parentElement.remove()"><i class="fas fa-times"></i></button>
            </div>
        @endif
        @if(session('error'))
            <div class="mx-6 mt-6 bg-rose-50 border border-rose-200/60 text-rose-700 text-sm font-medium px-4 py-3 rounded-xl flex items-center justify-between">
                <span><i class="fas fa-exclamation-circle mr-2"></i>{{ session('error') }}</span>
                <button type="button" class="text-rose-400 hover:text-rose-600 transition" onclick="this.parentElement.remove()"><i class="fas fa-times"></i></button>
            </div>
        @endif

        {{-- Search & Filter --}}
        <div class="p-6 border-b border-slate-100">
            <form method="GET" action="{{ route('admin.users.index') }}" class="flex flex-col md:flex-row gap-4">

                <div class="relative flex-1">
                    <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                    <input type="text"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="Cari berdasarkan nama, username, atau email..."
                        class="w-full pl-11 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#109696]/20 focus:border-[#109696] transition">
                </div>

                <div class="relative flex-shrink-0 w-full md:w-48">
                    <select name="role"
                        class="appearance-none pl-4 pr-10 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#109696]/20 focus:border-[#109696] transition w-full cursor-pointer">
                        <option value="">Semua Role</option>
                        <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                        <option value="desa" {{ request('role') == 'desa' ? 'selected' : '' }}>Desa</option>
                    </select>
                    <i class="fas fa-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 text-xs pointer-events-none"></i>
                </div>

                <button type="submit"
                    class="px-5 py-2.5 bg-gradient-to-br from-[#109696] to-[#1A85A5] text-white text-sm font-semibold rounded-xl shadow-sm shadow-[#109696]/20 hover:shadow-md transition-all duration-200 flex-shrink-0">
                    Cari
                </button>

            </form>
        </div>

        {{-- TABLE --}}
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left" id="invTable">
                <thead>
                    <tr class="bg-slate-50/80 border-b border-slate-100">
                        <th class="px-5 py-3.5 font-semibold text-slate-500 uppercase text-xs tracking-wider">No</th>
                        <th class="px-5 py-3.5 font-semibold text-slate-500 uppercase text-xs tracking-wider sortable cursor-pointer select-none hover:bg-slate-100 transition" data-type="string">
                            Username <span class="sort-icon text-slate-300 ml-1"></span>
                        </th>
                        <th class="px-5 py-3.5 font-semibold text-slate-500 uppercase text-xs tracking-wider sortable cursor-pointer select-none hover:bg-slate-100 transition" data-type="string">
                            Nama Lengkap <span class="sort-icon text-slate-300 ml-1"></span>
                        </th>
                        <th class="px-5 py-3.5 font-semibold text-slate-500 uppercase text-xs tracking-wider sortable cursor-pointer select-none hover:bg-slate-100 transition" data-type="string">
                            Email <span class="sort-icon text-slate-300 ml-1"></span>
                        </th>
                        <th class="px-5 py-3.5 font-semibold text-slate-500 uppercase text-xs tracking-wider sortable cursor-pointer select-none hover:bg-slate-100 transition" data-type="string">
                            No. HP <span class="sort-icon text-slate-300 ml-1"></span>
                        </th>
                        <th class="px-5 py-3.5 font-semibold text-slate-500 uppercase text-xs tracking-wider sortable cursor-pointer select-none hover:bg-slate-100 transition" data-type="string">
                            Role <span class="sort-icon text-slate-300 ml-1"></span>
                        </th>
                        <th class="px-5 py-3.5 font-semibold text-slate-500 uppercase text-xs tracking-wider text-center" style="cursor:default">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">

                    @forelse($users as $indexUser => $user)
                    <tr data-role="{{ $user->role }}" class="hover:bg-slate-50/50 transition-colors">

                        <td class="px-5 py-4 text-slate-400 font-medium">{{ $users->firstItem() + $indexUser }}</td>

                        <td class="px-5 py-4 font-medium text-slate-700">{{ $user->username }}</td>
                        <td class="px-5 py-4 text-slate-600">{{ $user->name }}</td>
                        <td class="px-5 py-4 text-slate-500 italic">{{ $user->email }}</td>
                        <td class="px-5 py-4 text-slate-500">{{ $user->no_hp ?? '-' }}</td>

                        <td class="px-5 py-4">
                            @if($user->role === 'admin')
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-purple-50 text-purple-600 border border-purple-100">
                                    <span class="w-1.5 h-1.5 bg-purple-500 rounded-full"></span>
                                    Admin
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-sky-50 text-sky-600 border border-sky-100">
                                    <span class="w-1.5 h-1.5 bg-sky-500 rounded-full"></span>
                                    Desa
                                </span>
                            @endif
                        </td>

                        <td class="px-5 py-4">
                            <div class="flex items-center justify-center gap-2">

                                <a href="{{ route('admin.users.show', $user) }}"
                                   class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-[#109696]/10 text-[#109696] hover:bg-[#109696] hover:text-white transition-all duration-200"
                                   title="Lihat">
                                    <i class="fas fa-eye text-xs"></i>
                                </a>

                                <a href="{{ route('admin.users.edit', $user) }}"
                                   class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-amber-50 text-amber-600 hover:bg-amber-500 hover:text-white transition-all duration-200"
                                   title="Edit">
                                    <i class="fas fa-edit text-xs"></i>
                                </a>

                                <form action="{{ route('admin.users.destroy', $user) }}" method="POST" style="display:inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus user ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-rose-50 text-rose-500 hover:bg-rose-600 hover:text-white transition-all duration-200"
                                        title="Hapus">
                                        <i class="fas fa-trash text-xs"></i>
                                    </button>
                                </form>

                            </div>
                        </td>

                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-5 py-16 text-center">
                            <div class="flex flex-col items-center gap-3 text-slate-400">
                                <i class="fas fa-inbox text-4xl text-slate-300"></i>
                                <p class="font-medium">Tidak ada data user</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse

                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="px-6">
            @include('components.inv-pagination', ['paginator' => $users])
        </div>

    </div>
</div>

{{-- Sorting Script --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
    const sortHeaders = document.querySelectorAll('th.sortable');

    sortHeaders.forEach(header => {
        header.addEventListener('click', () => {
            const table = header.closest('table');
            const tbody = table.querySelector('tbody');
            const allRows = Array.from(tbody.querySelectorAll('tr[data-role]'));

            if (!allRows.length) return;

            const type = header.dataset.type;
            const icon = header.querySelector('.sort-icon');
            const colIndex = Array.from(header.parentNode.children).indexOf(header);

            document.querySelectorAll('th.sortable .sort-icon').forEach(i => i.textContent = '');

            let isAsc = !header.classList.contains('asc');

            sortHeaders.forEach(h => h.classList.remove('asc', 'desc'));
            header.classList.add(isAsc ? 'asc' : 'desc');
            icon.textContent = isAsc ? '▲' : '▼';

            allRows.sort((a, b) => {
                let aVal = a.cells[colIndex].textContent.trim();
                let bVal = b.cells[colIndex].textContent.trim();

                if (type === 'number') {
                    aVal = parseInt(aVal.replace(/\D/g, ''), 10);
                    bVal = parseInt(bVal.replace(/\D/g, ''), 10);
                    return isAsc ? aVal - bVal : bVal - aVal;
                }

                return isAsc ? aVal.localeCompare(bVal) : bVal.localeCompare(aVal);
            });

            allRows.forEach(row => tbody.appendChild(row));
        });
    });
});
</script>

@endsection