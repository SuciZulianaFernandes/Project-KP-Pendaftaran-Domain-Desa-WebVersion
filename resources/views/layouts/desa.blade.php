<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - DISKOMINFO</title>

<script src="https://cdn.tailwindcss.com"></script>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<style>
body{
font-family: 'Poppins', sans-serif;
}

/* Custom styles for dropdown */
.dropdown-content {
    display: none;
}

.dropdown-content.show {
    display: block;
}

.dropdown-arrow {
    transition: transform 0.3s ease;
}

.dropdown-arrow.rotate {
    transform: rotate(180deg);
}

/* CSS untuk garis vertikal telah dihapus */
</style>

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        [x-cloak] { display: none !important; }
    </style>
</head>

<body class="bg-slate-50 text-slate-800" x-data="{ sidebarOpen: false }">

<div class="flex h-screen overflow-hidden">

<!-- SIDEBAR -->
<div class="hidden md:flex md:flex-col w-64 bg-red-800 text-white">

<div class="p-6 text-xl font-semibold border-b border-red-700">
DOMAININFO
</div>
    <div class="flex h-screen overflow-hidden">
        
        <aside 
            :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
            class="fixed inset-y-0 left-0 z-50 w-72 bg-red-900 text-white transition-transform duration-300 ease-in-out md:relative md:translate-x-0 flex flex-col shadow-2xl">
            
            <div class="p-8 border-b border-red-800">
                <span class="text-xl font-bold tracking-widest uppercase">DISKOMINFO</span>
            </div>

<nav class="flex-1 p-4 space-y-2 text-sm">

<p class="text-red-200 uppercase text-xs tracking-wider mb-2">Dashboard</p>

<a href="#" class="flex items-center gap-3 p-3 rounded-lg hover:bg-red-600 transition">
<i class="fas fa-chart-pie w-5"></i> Overview
</a>

<p class="text-red-200 uppercase text-xs tracking-wider mt-6 mb-2">Domain</p>

<!-- Dropdown Menu for Domain -->
<div class="domain-dropdown">
    <button id="domainDropdownBtn" class="flex items-center justify-between w-full gap-3 p-2 rounded hover:bg-red-700 transition {{ request()->is('desa/pengajuan*') ? 'bg-red-700' : '' }}">
        <div class="flex items-center gap-3">
            <i class="fas fa-globe w-5"></i> 
            <span>Domain</span>
        </div>
        <i class="fas fa-chevron-up text-xs dropdown-arrow {{ request()->is('desa/pengajuan*') ? 'rotate' : '' }}" id="domainArrow"></i>
    </button>
    
    <div id="domainDropdown" class="dropdown-content ml-8 mt-2 space-y-1 {{ request()->is('desa/pengajuan*') ? 'show' : '' }}">
        <!-- IKON TELAH DITAMBAHKAN KEMBALI -->
        <a href="{{ url('/desa/pengajuan') }}" class="flex items-center gap-3 p-2 rounded hover:bg-red-700 transition {{ request()->is('desa/pengajuan*') ? 'bg-red-700' : '' }}">
            <i class="fas fa-list w-5"></i> Daftar Domain
        </a>
        <a href="#" class="flex items-center gap-3 p-2 rounded hover:bg-red-700 transition">
            <i class="fas fa-plus-circle w-5"></i> Pendaftaran Domain
        </a>
        <a href="#" class="flex items-center gap-3 p-2 rounded hover:bg-red-700 transition">
            <i class="fas fa-check-circle w-5"></i> Verifikasi Domain
        </a>
        <a href="#" class="flex items-center gap-3 p-2 rounded hover:bg-red-700 transition">
            <i class="fas fa-clock w-5"></i> Perpanjang Domain
        </a>
    </div>
</div>

<p class="text-red-200 uppercase text-xs tracking-wider mt-6 mb-2">Menu</p>

<a href="#" class="flex items-center gap-3 p-2 rounded hover:bg-red-700 transition">
<i class="fas fa-envelope w-5"></i> Kontak
</a>

<a href="#" class="flex items-center gap-3 p-2 rounded hover:bg-red-700 transition">
<i class="fas fa-user w-5"></i> Profile
</a>

<a href="#" class="flex items-center gap-3 p-2 rounded hover:bg-red-700 transition">
<i class="fas fa-file-invoice w-5"></i> Faktur
</a>
            <nav class="flex-1 overflow-y-auto px-4 py-6 space-y-2">
                
                <a href="#" class="flex items-center px-4 py-3 rounded-lg hover:bg-red-800 transition-colors">
                    <span class="text-sm font-medium">Dashboard</span>
                </a>

                <div x-data="{ open: false }">
                    <button @click="open = !open" class="w-full flex items-center justify-between px-4 py-3 rounded-lg hover:bg-red-800 transition-colors">
                        <span class="text-sm font-medium">Layanan Domain</span>
                        <svg class="w-4 h-4 transition-transform" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </button>
                    <div x-show="open" x-cloak x-transition class="mt-1 space-y-1 bg-red-950/30 rounded-lg overflow-hidden">
                        <a href="#" class="block px-10 py-2 text-sm text-red-100 hover:bg-red-800">Daftar Domain</a>
                        <a href="#" class="block px-10 py-2 text-sm text-red-100 hover:bg-red-800">Pendaftaran Domain</a>
                        <a href="#" class="block px-10 py-2 text-sm text-red-100 hover:bg-red-800">Verifikasi Dokumen</a>
                        <a href="#" class="block px-10 py-2 text-sm text-red-100 hover:bg-red-800">Perpanjangan Masa Aktif Domain</a>
                    </div>
                </div>

                <a href="#" class="flex items-center px-4 py-3 rounded-lg hover:bg-red-800 transition-colors">
                    <span class="text-sm font-medium">Kontak</span>
                </a>
                <a href="#" class="flex items-center px-4 py-3 rounded-lg hover:bg-red-800 transition-colors">
                    <span class="text-sm font-medium">Profil Instansi</span>
                </a>
                <a href="#" class="flex items-center px-4 py-3 rounded-lg hover:bg-red-800 transition-colors">
                    <span class="text-sm font-medium">Faktur</span>
                </a>

            </nav>

            <div class="p-6 bg-red-950/50">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded bg-red-700 flex items-center justify-center text-xs font-bold uppercase">AD</div>
                    <div class="text-xs">
                        <p class="font-bold uppercase tracking-wide">Administrator</p>
                        <p class="text-red-400">Sistem Informasi</p>
                    </div>
                </div>
            </div>
        </aside>

        <div class="flex-1 flex flex-col min-w-0 overflow-hidden">

<!-- TOPBAR -->
<header class="bg-white shadow-sm px-6 py-4 flex justify-between items-center">

<div class="flex items-center gap-4">

<button class="md:hidden text-gray-600 text-xl">
<i class="fas fa-bars"></i>
</button>

<h1 class="font-semibold text-gray-700 text-lg">
@yield('title')
</h1>

</div>
            <header class="h-16 bg-white border-b border-slate-200 px-6 flex justify-between items-center shadow-sm">
                <div class="flex items-center gap-4">
                    <button @click="sidebarOpen = !sidebarOpen" class="p-2 rounded hover:bg-slate-100 md:hidden">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                    </button>
                    <h1 class="font-semibold text-slate-700 uppercase tracking-tight">@yield('title')</h1>
                </div>

<div class="flex items-center gap-4">

<input
type="text"
placeholder="Search..."
class="border rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-400"
/>

<div class="w-9 h-9 rounded-full bg-gray-200 flex items-center justify-center text-sm">
<i class="fas fa-user text-gray-600"></i>
</div>

</div>

</header>

@yield('content')

</main>
                <div class="flex items-center gap-6">
                    <div class="hidden sm:block">
                        <input type="text" placeholder="Cari..." class="bg-slate-100 border-none rounded px-4 py-1.5 text-sm focus:ring-1 focus:ring-red-500 outline-none w-64">
                    </div>
                    <div class="flex items-center gap-2 border-l pl-6 border-slate-200 uppercase text-[10px] font-bold tracking-widest text-slate-400">
                        Pusat Kendali Data
                    </div>
                </div>
            </header>

            <main class="flex-1 overflow-y-auto p-6 lg:p-10">
                <div class="bg-white rounded-lg shadow-sm border border-slate-200 min-h-full p-6">
                    @yield('content')
                </div>
            </main>

</div>

</div>
@stack('scripts')

<script>
// Dropdown functionality for Domain menu
document.addEventListener('DOMContentLoaded', function() {
    const domainDropdownBtn = document.getElementById('domainDropdownBtn');
    const domainDropdown = document.getElementById('domainDropdown');
    const domainArrow = document.getElementById('domainArrow');
    
    domainDropdownBtn.addEventListener('click', function() {
        domainDropdown.classList.toggle('show');
        domainArrow.classList.toggle('rotate');
    });
});
</script>
        </div>
    </div>

</body>
</html>