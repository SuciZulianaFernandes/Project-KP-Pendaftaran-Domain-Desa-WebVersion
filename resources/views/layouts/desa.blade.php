<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>@yield('title')</title>

<script src="https://cdn.tailwindcss.com"></script>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<style>
body{
font-family: 'Poppins', sans-serif;
}
</style>

</head>

<body class="bg-gray-100">

<div class="flex h-screen overflow-hidden">

<!-- SIDEBAR -->
<div class="hidden md:flex md:flex-col w-64 bg-red-800 text-white">

<div class="p-6 text-xl font-semibold border-b border-red-700">
DISKOMINFO
</div>

<nav class="flex-1 p-4 space-y-2 text-sm">

<p class="text-red-200 uppercase text-xs tracking-wider mb-2">Dashboard</p>

<a href="#" class="flex items-center gap-3 p-3 rounded-lg bg-red-700 hover:bg-red-600 transition">
📊 Overview
</a>

<p class="text-red-200 uppercase text-xs tracking-wider mt-6 mb-2">Domain</p>

<a href="#" class="flex items-center gap-3 p-2 rounded hover:bg-red-700 transition">
🌐 Daftar Domain
</a>

<a href="#" class="flex items-center gap-3 p-2 rounded hover:bg-red-700 transition">
📝 Pendaftaran Domain
</a>

<a href="#" class="flex items-center gap-3 p-2 rounded hover:bg-red-700 transition">
✔️ Verifikasi Domain
</a>

<a href="#" class="flex items-center gap-3 p-2 rounded hover:bg-red-700 transition">
🔄 Perpanjang Domain
</a>

<p class="text-red-200 uppercase text-xs tracking-wider mt-6 mb-2">Menu</p>

<a href="#" class="flex items-center gap-3 p-2 rounded hover:bg-red-700 transition">
📞 Kontak
</a>

<a href="#" class="flex items-center gap-3 p-2 rounded hover:bg-red-700 transition">
👤 Profile
</a>

<a href="#" class="flex items-center gap-3 p-2 rounded hover:bg-red-700 transition">
🧾 Faktur
</a>

</nav>

</div>


<!-- MAIN -->
<div class="flex-1 flex flex-col overflow-hidden">

<!-- TOPBAR -->
<header class="bg-white shadow-sm px-6 py-4 flex justify-between items-center">

<div class="flex items-center gap-4">

<button class="md:hidden text-gray-600 text-xl">
☰
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
👤
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