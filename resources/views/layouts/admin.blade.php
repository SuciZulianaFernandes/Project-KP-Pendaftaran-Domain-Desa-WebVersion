<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - DISKOMINFO</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: { DEFAULT: '#109696', light: '#1A85A5', dark: '#1760C5' },
                        dark: { DEFAULT: '#0F172A' }
                    },
                    fontFamily: { sans: ['Inter', 'sans-serif'] }
                }
            }
        }
    </script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        [x-cloak] { display: none !important; }
        html, body { overflow-x: hidden; }
        aside { overflow-y: auto; scrollbar-width: thin; scrollbar-color: rgba(255,255,255,0.1) transparent; }
        img { max-width: 100%; height: auto; }
        .table-responsive { width: 100%; overflow-x: auto; }
        
        /* Custom Scrollbar for Main Content */
        main::-webkit-scrollbar { width: 6px; }
        main::-webkit-scrollbar-track { background: transparent; }
        main::-webkit-scrollbar-thumb { background: #CBD5E1; border-radius: 10px; }
        main::-webkit-scrollbar-thumb:hover { background: #94A3B8; }
    </style>
</head>

<body
    class="bg-slate-50 text-slate-700 font-sans antialiased"
    x-data="{ sidebarOpen: false }"
    x-cloak
>

<div class="flex h-screen overflow-hidden bg-slate-100">

    <!-- OVERLAY MOBILE -->
    <div
        x-show="sidebarOpen"
        x-transition.opacity.duration.200ms
        @click="sidebarOpen = false"
        class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-30 lg:hidden"
    ></div>

    <!-- SIDEBAR -->
    <aside
        :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
        class="fixed inset-y-0 left-0 z-40 w-[280px] bg-gradient-to-b from-primary via-primary-light to-primary-dark text-white transform transition-transform duration-300 ease-in-out lg:translate-x-0 lg:static flex flex-col shadow-2xl"
    >

        <!-- LOGO -->
        <div class="p-6 border-b border-white/10 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-white/15 backdrop-blur-sm flex items-center justify-center border border-white/20">
                    <i class="fas fa-network-wired text-white/90"></i>
                </div>
                <span class="text-lg font-extrabold tracking-wide uppercase">
                    DISKOMINFO
                </span>
            </div>

            <button
                @click="sidebarOpen = false"
                class="lg:hidden text-white/70 hover:text-white text-xl transition"
            >
                <i class="fas fa-times"></i>
            </button>
        </div>

        <!-- MENU NAVIGATION -->
        <nav class="flex-1 p-4 space-y-1 text-sm overflow-y-auto">

            <!-- DASHBOARD -->
            <p class="text-white/40 uppercase text-[10px] font-bold tracking-widest mb-3 mt-2 px-3">
                Dashboard
            </p>

            <a
                href="{{ url('/admin/dashboard') }}"
                class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all duration-200 {{ request()->is('admin/dashboard*') ? 'bg-white/15 text-white shadow-lg shadow-black/5' : 'text-white/70 hover:bg-white/10 hover:text-white' }}"
            >
                <i class="fas fa-chart-pie w-5 text-center"></i>
                <span class="font-medium">Overview</span>
            </a>

            <!-- DOMAIN -->
            <p class="text-white/40 uppercase text-[10px] font-bold tracking-widest mb-3 mt-6 px-3">
                Manajemen Domain
            </p>

            <div x-data="{ open: {{ request()->is('admin/domain_terdaftar*') || request()->is('admin/pengajuan*') || request()->is('admin/perpanjang*') ? 'true' : 'false' }} }">

                <button
                    @click="open = !open"
                    class="flex items-center justify-between w-full gap-3 px-3 py-2.5 rounded-xl transition-all duration-200 {{ request()->is('admin/domain_terdaftar*') || request()->is('admin/pengajuan*') || request()->is('admin/perpanjang*') ? 'bg-white/15 text-white' : 'text-white/70 hover:bg-white/10 hover:text-white' }}"
                >
                    <div class="flex items-center gap-3">
                        <i class="fas fa-globe w-5 text-center"></i>
                        <span class="font-medium">Manajemen Domain</span>
                    </div>
                    <i
                        class="fas fa-chevron-down text-[10px] transition-transform duration-300"
                        :class="open ? 'rotate-180' : ''"
                    ></i>
                </button>

                <div
                    x-show="open"
                    x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 -translate-y-2"
                    x-transition:enter-end="opacity-100 translate-y-0"
                    x-transition:leave="transition ease-in duration-150"
                    x-transition:leave-start="opacity-100 translate-y-0"
                    x-transition:leave-end="opacity-0 -translate-y-2"
                    class="ml-4 mt-1 space-y-1 border-l border-white/10 pl-4"
                >
                    <a
                        href="{{ url('/admin/domain_terdaftar') }}"
                        class="flex items-center gap-3 px-3 py-2 rounded-lg transition-all duration-200 {{ request()->is('admin/domain_terdaftar*') ? 'bg-white/15 text-white font-medium' : 'text-white/60 hover:bg-white/10 hover:text-white' }}"
                    >
                        <i class="fas fa-list w-4 text-center text-xs"></i>
                        Daftar Domain
                    </a>

                    <a
                        href="{{ url('/admin/pengajuan') }}"
                        class="flex items-center gap-3 px-3 py-2 rounded-lg transition-all duration-200 {{ request()->is('admin/pengajuan*') ? 'bg-white/15 text-white font-medium' : 'text-white/60 hover:bg-white/10 hover:text-white' }}"
                    >
                        <i class="fas fa-plus-circle w-4 text-center text-xs"></i>
                        Pengajuan Domain
                    </a>

                    <a
                        href="{{ url('/admin/perpanjang') }}"
                        class="flex items-center gap-3 px-3 py-2 rounded-lg transition-all duration-200 {{ request()->is('admin/perpanjang*') ? 'bg-white/15 text-white font-medium' : 'text-white/60 hover:bg-white/10 hover:text-white' }}"
                    >
                        <i class="fas fa-check-circle w-4 text-center text-xs"></i>
                        Perpanjang Domain
                    </a>
                </div>

            </div>

            <!-- USER -->
            <p class="text-white/40 uppercase text-[10px] font-bold tracking-widest mb-3 mt-6 px-3">
                Manajemen User
            </p>

            <div x-data="{ open: {{ request()->is('admin/users*') ? 'true' : 'false' }} }">

                <button
                    @click="open = !open"
                    class="flex items-center justify-between w-full gap-3 px-3 py-2.5 rounded-xl transition-all duration-200 {{ request()->is('admin/users*') ? 'bg-white/15 text-white' : 'text-white/70 hover:bg-white/10 hover:text-white' }}"
                >
                    <div class="flex items-center gap-3">
                        <i class="fas fa-users w-5 text-center"></i>
                        <span class="font-medium">Manajemen User</span>
                    </div>
                    <i
                        class="fas fa-chevron-down text-[10px] transition-transform duration-300"
                        :class="open ? 'rotate-180' : ''"
                    ></i>
                </button>

                <div
                    x-show="open"
                    x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 -translate-y-2"
                    x-transition:enter-end="opacity-100 translate-y-0"
                    x-transition:leave="transition ease-in duration-150"
                    x-transition:leave-start="opacity-100 translate-y-0"
                    x-transition:leave-end="opacity-0 -translate-y-2"
                    class="ml-4 mt-1 space-y-1 border-l border-white/10 pl-4"
                >
                    <a
                        href="{{ url('/admin/users') }}"
                        class="flex items-center gap-3 px-3 py-2 rounded-lg transition-all duration-200 {{ request()->is('admin/users') ? 'bg-white/15 text-white font-medium' : 'text-white/60 hover:bg-white/10 hover:text-white' }}"
                    >
                        <i class="fas fa-list w-4 text-center text-xs"></i>
                        Daftar User
                    </a>

                    <a
                        href="{{ url('/admin/users/create') }}"
                        class="flex items-center gap-3 px-3 py-2 rounded-lg transition-all duration-200 {{ request()->is('admin/users/create') ? 'bg-white/15 text-white font-medium' : 'text-white/60 hover:bg-white/10 hover:text-white' }}"
                    >
                        <i class="fas fa-plus-circle w-4 text-center text-xs"></i>
                        Tambah User
                    </a>
                </div>

            </div>

            <!-- MENU -->
            <p class="text-white/40 uppercase text-[10px] font-bold tracking-widest mb-3 mt-6 px-3">
                Menu
            </p>

            <a
                href="{{ url('/admin/pesan') }}"
                class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all duration-200 {{ request()->is('admin/pesan*') ? 'bg-white/15 text-white shadow-lg shadow-black/5' : 'text-white/70 hover:bg-white/10 hover:text-white' }}"
            >
                <i class="fas fa-envelope w-5 text-center"></i>
                <span class="font-medium">Pesan</span>
            </a>

            <a
                href="{{ url('/admin/profile') }}"
                class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all duration-200 {{ request()->is('admin/profile*') ? 'bg-white/15 text-white shadow-lg shadow-black/5' : 'text-white/70 hover:bg-white/10 hover:text-white' }}"
            >
                <i class="fas fa-building w-5 text-center"></i>
                <span class="font-medium">Profil Instansi</span>
            </a>

            <a
                href="{{ url('/admin/faktur') }}"
                class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all duration-200 {{ request()->is('admin/faktur*') ? 'bg-white/15 text-white shadow-lg shadow-black/5' : 'text-white/70 hover:bg-white/10 hover:text-white' }}"
            >
                <i class="fas fa-file-invoice-dollar w-5 text-center"></i>
                <span class="font-medium">Manajemen Faktur</span>
            </a>

            <!-- LOGOUT -->
            <div class="pt-6 mt-4 border-t border-white/10">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button
                        type="submit"
                        class="flex items-center gap-3 w-full px-3 py-2.5 rounded-xl text-white/60 hover:bg-red-500/20 hover:text-red-200 transition-all duration-200 text-left group"
                    >
                        <i class="fas fa-sign-out-alt w-5 text-center group-hover:translate-x-0.5 transition-transform"></i>
                        <span class="font-medium">Keluar</span>
                    </button>
                </form>
            </div>

        </nav>

        <!-- FOOTER SIDEBAR -->
        <div class="p-4 border-t border-white/10 bg-black/10">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-white/20 backdrop-blur-sm flex items-center justify-center text-xs font-bold uppercase border border-white/20">
                    {{ strtoupper(substr(Auth::user()->username, 0, 2)) }}
                </div>
                <div class="text-sm flex-1 min-w-0">
                    <p class="font-semibold text-white truncate">
                        {{ Auth::user()->name }}
                    </p>
                    <p class="text-white/50 text-xs truncate">
                        {{ Auth::user()->username }}
                    </p>
                </div>
            </div>
        </div>

    </aside>

    <!-- CONTENT AREA -->
    <div class="flex-1 flex flex-col min-w-0 overflow-hidden">

        <!-- HEADER -->
        <header class="bg-white/80 backdrop-blur-md border-b border-slate-200/60 px-4 md:px-8 py-4 z-10">
            <div class="flex items-center justify-between">
                <button
                    @click="sidebarOpen = true"
                    class="text-slate-500 hover:text-slate-800 text-xl lg:hidden transition"
                >
                    <i class="fas fa-bars"></i>
                </button>

                <!-- Breadcrumb or Header Extra can go here -->
                <div class="hidden lg:flex items-center gap-2 text-sm text-slate-500">
                    <i class="fas fa-circle text-[6px] text-primary"></i>
                    <span>{{ request()->segment(2) ? ucfirst(request()->segment(2)) : 'Dashboard' }}</span>
                </div>
            </div>
        </header>

        <!-- MAIN CONTENT -->
        <main class="flex-1 overflow-y-auto p-4 md:p-8 bg-slate-50">
            @yield('content')
        </main>

    </div>

</div>

</body>
</html>