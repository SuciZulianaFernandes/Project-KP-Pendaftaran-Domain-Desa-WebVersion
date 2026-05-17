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

        html,
        body{
            overflow-x:hidden;
        }

        img{
            max-width:100%;
            height:auto;
        }

        .table-responsive{
            width:100%;
            overflow-x:auto;
        }

        aside{
            overflow-y:auto;
        }
    </style>
</head>

<body
    class="bg-slate-50 text-slate-800"
    x-data="{ sidebarOpen:false }"
    x-cloak
>

<div class="flex h-screen overflow-hidden">

    <!-- OVERLAY MOBILE -->
    <div
        x-show="sidebarOpen"
        x-transition.opacity
        @click="sidebarOpen=false"
        class="fixed inset-0 bg-black/50 z-30 md:hidden"
    ></div>

    <!-- SIDEBAR -->
    <aside
        :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
        class="fixed inset-y-0 left-0 z-40 w-72 bg-red-900 text-white transform transition-transform duration-300 md:translate-x-0 md:static flex flex-col shadow-2xl"
    >

        <!-- LOGO -->
        <div class="p-6 border-b border-red-800 flex items-center justify-between">

            <span class="text-lg md:text-xl font-bold tracking-widest uppercase">
                DISKOMINFO
            </span>

            <button
                @click="sidebarOpen=false"
                class="md:hidden text-white text-xl"
            >
                <i class="fas fa-times"></i>
            </button>

        </div>

        <!-- MENU -->
        <nav class="flex-1 p-4 space-y-2 text-sm">

            <!-- DASHBOARD -->
            <p class="text-red-200 uppercase text-xs tracking-wider mb-2">
                Dashboard
            </p>

            <a
                href="{{ url('/admin/dashboard') }}"
                class="flex items-center gap-3 p-3 rounded-lg transition {{ request()->is('admin/dashboard*') ? 'bg-red-700 text-white' : 'hover:bg-red-700' }}"
            >
                <i class="fas fa-chart-pie w-5"></i>
                Overview
            </a>

            <!-- DOMAIN -->
            <p class="text-red-200 uppercase text-xs tracking-wider mt-6 mb-2">
                Manajemen Domain
            </p>

            <div x-data="{ open:true }">

                <button
                    @click="open=!open"
                    class="flex items-center justify-between w-full gap-3 p-3 rounded-lg transition {{ request()->is('admin/domain_terdaftar*') || request()->is('admin/pengajuan*') || request()->is('admin/perpanjang*') ? 'bg-red-700 text-white' : 'hover:bg-red-700' }}"
                >

                    <div class="flex items-center gap-3">
                        <i class="fas fa-globe w-5"></i>
                        <span>Manajemen Domain</span>
                    </div>

                    <i
                        class="fas fa-chevron-down text-xs transition-transform"
                        :class="open ? 'rotate-180' : ''"
                    ></i>

                </button>

                <div
                    x-show="open"
                    x-transition
                    class="ml-8 mt-2 space-y-1"
                >

                    <a
                        href="{{ url('/admin/domain_terdaftar') }}"
                        class="flex items-center gap-3 p-2 rounded transition {{ request()->is('admin/domain_terdaftar*') ? 'bg-red-700 text-white' : 'hover:bg-red-700' }}"
                    >
                        <i class="fas fa-list w-5"></i>
                        Daftar Domain
                    </a>

                    <a
                        href="{{ url('/admin/pengajuan') }}"
                        class="flex items-center gap-3 p-2 rounded transition {{ request()->is('admin/pengajuan*') ? 'bg-red-700 text-white' : 'hover:bg-red-700' }}"
                    >
                        <i class="fas fa-plus-circle w-5"></i>
                        Pengajuan Domain
                    </a>

                    <a
                        href="{{ url('/admin/perpanjang') }}"
                        class="flex items-center gap-3 p-2 rounded transition {{ request()->is('admin/perpanjang*') ? 'bg-red-700 text-white' : 'hover:bg-red-700' }}"
                    >
                        <i class="fas fa-check-circle w-5"></i>
                        Perpanjang Domain
                    </a>

                </div>

            </div>

            <!-- USER -->
            <p class="text-red-200 uppercase text-xs tracking-wider mt-6 mb-2">
                Manajemen User
            </p>

            <div x-data="{ open:true }">

                <button
                    @click="open=!open"
                    class="flex items-center justify-between w-full gap-3 p-3 rounded-lg transition {{ request()->is('admin/users*') ? 'bg-red-700 text-white' : 'hover:bg-red-700' }}"
                >

                    <div class="flex items-center gap-3">
                        <i class="fas fa-users w-5"></i>
                        <span>Manajemen User</span>
                    </div>

                    <i
                        class="fas fa-chevron-down text-xs transition-transform"
                        :class="open ? 'rotate-180' : ''"
                    ></i>

                </button>

                <div
                    x-show="open"
                    x-transition
                    class="ml-8 mt-2 space-y-1"
                >

                    <a
                        href="{{ url('/admin/users') }}"
                        class="flex items-center gap-3 p-2 rounded transition {{ request()->is('admin/users') ? 'bg-red-700 text-white' : 'hover:bg-red-700' }}"
                    >
                        <i class="fas fa-list w-5"></i>
                        Daftar User
                    </a>

                    <a
                        href="{{ url('/admin/users/create') }}"
                        class="flex items-center gap-3 p-2 rounded transition {{ request()->is('admin/users/create') ? 'bg-red-700 text-white' : 'hover:bg-red-700' }}"
                    >
                        <i class="fas fa-plus-circle w-5"></i>
                        Tambah User
                    </a>

                </div>

            </div>

            <!-- MENU -->
            <p class="text-red-200 uppercase text-xs tracking-wider mt-6 mb-2">
                Menu
            </p>

            <a
                href="{{ url('/admin/pesan') }}"
                class="flex items-center gap-3 p-2 rounded transition {{ request()->is('admin/pesan*') ? 'bg-red-700 text-white' : 'hover:bg-red-700' }}"
            >
                <i class="fas fa-envelope w-5"></i>
                Pesan
            </a>

            <a
                href="{{ url('/admin/profile') }}"
                class="flex items-center gap-3 p-2 rounded transition {{ request()->is('admin/profile*') ? 'bg-red-700 text-white' : 'hover:bg-red-700' }}"
            >
                <i class="fas fa-user w-5"></i>
                Profil Instansi
            </a>

            <a
                href="{{ url('/admin/faktur') }}"
                class="flex items-center gap-3 p-2 rounded transition {{ request()->is('admin/faktur*') ? 'bg-red-700 text-white' : 'hover:bg-red-700' }}"
            >
                <i class="fas fa-file-invoice w-5"></i>
                Manajemen Faktur
            </a>

            <!-- LOGOUT -->
            <form action="{{ route('logout') }}" method="POST" class="mt-6">
                @csrf

                <button
                    type="submit"
                    class="flex items-center gap-3 w-full p-2 rounded hover:bg-red-700 transition text-left"
                >
                    <i class="fas fa-sign-out-alt w-5"></i>
                    Keluar
                </button>

            </form>

        </nav>

        <!-- FOOTER -->
        <div class="p-5 bg-red-900/50 border-t border-red-700">

            <div class="flex items-center gap-3">

                <div class="w-10 h-10 rounded-full bg-red-700 flex items-center justify-center text-sm font-bold uppercase">
                    AD
                </div>

                <div class="text-sm">
                    <p class="font-bold uppercase tracking-wide">
                        Administrator
                    </p>

                    <p class="text-red-300">
                        Sistem Informasi
                    </p>
                </div>

            </div>

        </div>

    </aside>

    <!-- CONTENT -->
    <div class="flex-1 flex flex-col min-w-0 overflow-hidden">

        <!-- HEADER -->
        <header class="bg-white shadow-sm px-4 md:px-6 py-4">

            <div class="flex items-center justify-between gap-4">

                <button
                    @click="sidebarOpen=true"
                    class="text-gray-700 text-xl md:hidden"
                >
                    <i class="fas fa-bars"></i>
                </button>

            </div>

        </header>

        <!-- MAIN -->
        <main class="flex-1 overflow-y-auto p-4 md:p-6">
            @yield('content')
        </main>

    </div>

</div>

</body>
</html>