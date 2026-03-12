<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>@yield('title')</title>

    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">

<div class="flex h-screen">

    {{-- SIDEBAR --}}
    <div class="w-64 bg-red-800 text-white flex flex-col">

        <div class="p-6 text-lg font-bold border-b border-red-700">
            DISKOMINFO
        </div>

        <nav class="flex-1 p-4 space-y-2">

            <p class="text-sm text-red-200 mb-2">Dashboards</p>

            <a href="#" class="flex items-center p-3 rounded-lg bg-red-700">
                Overview
            </a>

            <p class="text-sm text-red-200 mt-4">Domain</p>

            <div class="space-y-1 ml-2">
                <a href="#" class="block p-2 rounded hover:bg-red-700">Daftar Domain</a>
                <a href="#" class="block p-2 rounded hover:bg-red-700">Pendaftaran Domain</a>
                <a href="#" class="block p-2 rounded hover:bg-red-700">Verifikasi Domain</a>
                <a href="#" class="block p-2 rounded hover:bg-red-700">Pengajuan Perpanjang</a>
            </div>

            <p class="text-sm text-red-200 mt-4">Menu</p>

            <a href="#" class="block p-2 rounded hover:bg-red-700">Kontak</a>
            <a href="#" class="block p-2 rounded hover:bg-red-700">Profile</a>
            <a href="#" class="block p-2 rounded hover:bg-red-700">Faktur</a>

        </nav>

    </div>


    {{-- MAIN CONTENT --}}
    <div class="flex-1 flex flex-col">

        {{-- TOPBAR --}}
        <div class="bg-white shadow p-4 flex justify-between items-center">

            <div class="font-semibold text-gray-700">
                @yield('title')
            </div>

            <input type="text"
                   placeholder="Search"
                   class="border rounded-lg px-3 py-1 text-sm">

        </div>


        {{-- CONTENT --}}
        <div class="p-6">
            @yield('content')
        </div>

    </div>

</div>

</body>
</html>