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
<script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

<style>
body{ font-family:'Poppins',sans-serif; }
[x-cloak]{ display:none!important; }

.dropdown-content{ display:none; }
.dropdown-content.show{ display:block; }
.dropdown-arrow{ transition: transform 0.3s ease; }
.dropdown-arrow.rotate{ transform: rotate(180deg); }

/* penting: biar sidebar bisa discroll */
aside{ overflow-y:auto; }
</style>

</head>

<body class="bg-slate-50 text-slate-800" x-data="{ sidebarOpen:false }">

<div class="flex h-screen overflow-hidden">

<!-- SIDEBAR -->
<aside 
:class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
class="fixed inset-y-0 left-0 z-40 w-72 bg-red-900 text-white transition-transform duration-300 ease-in-out flex flex-col shadow-2xl">

<div class="p-8 border-b border-red-800">
<span class="text-xl font-bold tracking-widest uppercase">DISKOMINFO</span>
</div>

<nav class="flex-1 p-4 space-y-2 text-sm">

<p class="text-red-200 uppercase text-xs tracking-wider mb-2">Dashboard</p>

<a href="{{ url('/desa/dashboard') }}" class="flex items-center gap-3 p-3 rounded-lg hover:bg-red-600 transition {{ request()->is('desa/dashboard*') ? 'bg-red-700' : '' }}">
<i class="fas fa-chart-pie w-5"></i> Overview
</a>

<p class="text-red-200 uppercase text-xs tracking-wider mt-6 mb-2">Domain</p>

<div class="domain-dropdown">
<button id="domainDropdownBtn" class="flex items-center justify-between w-full gap-3 p-2 rounded hover:bg-red-700 transition {{ request()->is('desa/pengajuan*') ? 'bg-red-700' : '' }}">
<div class="flex items-center gap-3">
<i class="fas fa-globe w-5"></i> 
<span>Domain</span>
</div>
<i class="fas fa-chevron-up text-xs dropdown-arrow {{ request()->is('desa/pengajuan*') ? 'rotate' : '' }}" id="domainArrow"></i>
</button>

<div id="domainDropdown" class="dropdown-content ml-8 mt-2 space-y-1 {{ request()->is('desa/pengajuan*') ? 'show' : '' }}">
<a href="{{ url('/desa/pengajuan') }}" class="flex items-center gap-3 p-2 rounded hover:bg-red-700 transition {{ request()->is('desa/pengajuan*') ? 'bg-red-700' : '' }}">
<i class="fas fa-plus-circle w-5"></i> Pendaftaran Domain
</a>
<a href="{{ url('/desa/perpanjang') }}" class="flex items-center gap-3 p-2 rounded hover:bg-red-700 transition">
<i class="fas fa-clock w-5"></i> Perpanjang Domain
</a>
<a href="{{ url('/desa/verifikasi') }}" class="flex items-center gap-3 p-2 rounded hover:bg-red-700 transition">
<i class="fas fa-check-circle w-5"></i> Verifikasi Dokumen
</a>
</div>
</div>

<p class="text-red-200 uppercase text-xs tracking-wider mt-6 mb-2">Menu</p>

<a href="{{ url('/desa/pesan') }}" class="flex items-center gap-3 p-2 rounded hover:bg-red-700 transition">
<i class="fas fa-envelope w-5"></i> Pesan
</a>

<a href="#" class="flex items-center gap-3 p-2 rounded hover:bg-red-700 transition">
<i class="fas fa-user w-5"></i> Profile
</a>

<a href="{{ route('desa.faktur.index') }}" class="flex items-center gap-3 p-2 rounded hover:bg-red-700 transition">
<i class="fas fa-file-invoice w-5"></i> Faktur
</a>

<form action="{{ route('logout') }}" method="POST" class="mt-6">
@csrf
<button type="submit" class="flex items-center gap-3 w-full p-2 rounded hover:bg-red-700 transition text-left">
<i class="fas fa-sign-out-alt w-5"></i> Keluar
</button>
</form>

</nav>

<div class="p-6 bg-red-900/50 border-t border-red-700">
<div class="flex items-center gap-3">
<div class="w-10 h-10 rounded-full bg-red-700 flex items-center justify-center text-sm font-bold uppercase">AD</div>
<div class="text-sm">
<p class="font-bold uppercase tracking-wide">Administrator Desa</p>
<p class="text-red-300">Sistem Informasi</p>
</div>
</div>
</div>

</aside>

<!-- MAIN -->
<div class="flex-1 flex flex-col min-w-0 overflow-hidden">

<!-- HEADER -->
<header class="bg-white shadow-sm px-6 py-4 relative">

<!-- HAMBURGER FIXED -->
<button 
@click="sidebarOpen = !sidebarOpen"
class="fixed top-4 left-4 z-[999] text-xl":class="sidebarOpen ? 'text-white' : 'text-gray-600'">
<i class="fas fa-bars"></i>
</button>

<!-- TITLE CENTER -->
<div class="flex justify-center">
<div class="flex space-x-1 border-b border-gray-200">
<button class="px-4 py-2 text-sm font-medium text-red-600 border-b-2 border-red-600">
Dashboard
</button>
<button class="px-4 py-2 text-sm font-medium text-gray-500 hover:text-gray-700">
/ Default
</button>
</div>
</div>

</header>

<main class="flex-1 overflow-y-auto p-6">
@yield('content')
</main>

</div>

</div>

@stack('scripts')

<script>
document.addEventListener('DOMContentLoaded', function() {
const btn = document.getElementById('domainDropdownBtn');
const dropdown = document.getElementById('domainDropdown');
const arrow = document.getElementById('domainArrow');

btn.addEventListener('click', function() {
dropdown.classList.toggle('show');
arrow.classList.toggle('rotate');
});
});
</script>

</body>
</html>