<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>@yield('title')</title>

<script src="https://cdn.tailwindcss.com"></script>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<meta name="csrf-token" content="{{ csrf_token() }}">

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

</head>

<body class="bg-gray-100">

<div class="flex h-screen overflow-hidden">

<!-- SIDEBAR -->
<div class="hidden md:flex md:flex-col w-64 bg-red-800 text-white">

<div class="p-6 text-xl font-semibold border-b border-red-700">
DOMAININFO
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

</nav>

</div>


<!-- MAIN -->
<div class="flex-1 flex flex-col overflow-hidden">

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

</body>
</html>