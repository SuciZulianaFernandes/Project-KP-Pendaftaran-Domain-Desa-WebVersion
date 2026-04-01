<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>@yield('title') - DISKOMINFO</title>

<script src="https://cdn.tailwindcss.com"></script>
<script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
body{
    font-family:'Poppins',sans-serif;
}
[x-cloak]{
    display:none!important;
}
</style>

</head>

<body class="bg-slate-50 text-slate-800" x-data="{ sidebarOpen:false }">

<div class="flex h-screen overflow-hidden">

<!-- SIDEBAR -->
<aside 
:class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
class="fixed inset-y-0 left-0 z-50 w-72 bg-red-900 text-white transition-transform duration-300 ease-in-out md:relative md:translate-x-0 flex flex-col shadow-2xl">

<div class="p-8 border-b border-red-800">
<span class="text-xl font-bold tracking-widest uppercase">DISKOMINFO</span>
</div>

<nav class="flex-1 p-4 space-y-2 text-sm">

<p class="text-red-200 uppercase text-xs tracking-wider mb-2">Dashboard</p>

<a href="{{ url('/admin/dashboard') }}" class="flex items-center gap-3 p-3 rounded-lg hover:bg-red-700 transition">
<i class="fas fa-chart-pie w-5"></i>
Overview
</a>

<p class="text-red-200 uppercase text-xs tracking-wider mt-6 mb-2">Manajemen Domain</p>

<div x-data="{ open:false }">

<button @click="open=!open"
class="flex items-center justify-between w-full gap-3 p-3 rounded-lg hover:bg-red-700 transition">

<div class="flex items-center gap-3">
<i class="fas fa-globe w-5"></i>
<span>Manajemen Domain</span>
</div>

<i class="fas fa-chevron-up text-xs transition-transform"
:class="open ? 'rotate-180' : ''"></i>

</button>

<div x-show="open" x-cloak x-transition
class="ml-8 mt-2 space-y-1">

<a href="#" class="flex items-center gap-3 p-2 rounded hover:bg-red-700 transition">
<i class="fas fa-list w-5"></i>
Daftar Domain Terdaftar
</a>

<a href="#" class="flex items-center gap-3 p-2 rounded hover:bg-red-700 transition">
<i class="fas fa-plus-circle w-5"></i>
Pengajuan Domain
</a>

<a href="#" class="flex items-center gap-3 p-2 rounded hover:bg-red-700 transition">
<i class="fas fa-check-circle w-5"></i>
Verifikasi Dokumen
</a>

</div>

</div>

<!-- Manajemen User Menu -->
<p class="text-red-200 uppercase text-xs tracking-wider mt-6 mb-2">Manajemen User</p>

<div x-data="{ open:false }">

<button @click="open=!open"
class="flex items-center justify-between w-full gap-3 p-3 rounded-lg hover:bg-red-700 transition">

<div class="flex items-center gap-3">
<i class="fas fa-users w-5"></i>
<span>Manajemen User</span>
</div>

<i class="fas fa-chevron-up text-xs transition-transform"
:class="open ? 'rotate-180' : ''"></i>

</button>

<div x-show="open" x-cloak x-transition
class="ml-8 mt-2 space-y-1">

<a href="{{ url('/admin/users') }}" class="flex items-center gap-3 p-2 rounded hover:bg-red-700 transition">
<i class="fas fa-list w-5"></i>
Daftar User
</a>

<a href="{{ url('/admin/users/create') }}" class="flex items-center gap-3 p-2 rounded hover:bg-red-700 transition">
<i class="fas fa-plus-circle w-5"></i>
Tambah User
</a>

</div>

</div>

<p class="text-red-200 uppercase text-xs tracking-wider mt-6 mb-2">Menu</p>

<a href="#" class="flex items-center gap-3 p-2 rounded hover:bg-red-700 transition">
<i class="fas fa-envelope w-5"></i>
Kontak
</a>

<a href="{{ url('/admin/profile') }}" class="flex items-center gap-3 p-2 rounded hover:bg-red-700 transition">
<i class="fas fa-user w-5"></i>
Profil Instansi
</a>

<a href="#" class="flex items-center gap-3 p-2 rounded hover:bg-red-700 transition">
<i class="fas fa-file-invoice w-5"></i>
Faktur
</a>

<!-- Logout Button -->
<form action="{{ route('logout') }}" method="POST" class="mt-6">
    @csrf
    <button type="submit" class="flex items-center gap-3 w-full p-2 rounded hover:bg-red-700 transition text-left">
        <i class="fas fa-sign-out-alt w-5"></i> Keluar
    </button>
</form>

</nav>

<div class="p-6 bg-red-900/50 border-t border-red-700">
<div class="flex items-center gap-3">

<div class="w-10 h-10 rounded-full bg-red-700 flex items-center justify-center text-sm font-bold uppercase">
AD
</div>

<div class="text-sm">
<p class="font-bold uppercase tracking-wide">Administrator</p>
<p class="text-red-300">Sistem Informasi</p>
</div>

</div>
</div>

</aside>

<!-- MAIN CONTENT -->
<div class="flex-1 flex flex-col min-w-0 overflow-hidden">

<!-- HEADER -->
<header class="bg-white shadow-sm px-6 py-4">

<div class="flex items-center justify-between">

<button @click="sidebarOpen=!sidebarOpen"
class="md:hidden text-gray-600 text-xl">
<i class="fas fa-bars"></i>
</button>

<div class="flex space-x-1 border-b border-gray-200">
<button class="px-4 py-2 text-sm font-medium text-red-600 border-b-2 border-red-600">
@yield('title')
</button>
</div>

<div class="flex items-center space-x-4">

<div class="relative">
<input
type="text"
placeholder="Search..."
class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-red-400 focus:border-transparent"
/>

<div class="absolute inset-y-0 left-0 flex items-center pl-3">
<i class="fas fa-search text-gray-400"></i>
</div>
</div>

<div class="relative">

<button class="p-2 text-gray-600 hover:text-gray-900">
<i class="fas fa-bell text-xl"></i>
<span class="absolute top-0 right-0 block h-2 w-2 rounded-full bg-red-500"></span>
</button>

</div>

</div>

</div>

</header>

<!-- CONTENT -->
<main class="flex-1 overflow-y-auto p-6">
@yield('content')
</main>

</div>

</div>

</body>
</html>