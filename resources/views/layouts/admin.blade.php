<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - DISKOMINFO</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        [x-cloak] { display: none !important; }
    </style>
</head>

<body class="bg-slate-50 text-slate-800" x-data="{ sidebarOpen: false }">

    <div class="flex h-screen overflow-hidden">
        
        <aside 
            :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
            class="fixed inset-y-0 left-0 z-50 w-72 bg-red-900 text-white transition-transform duration-300 ease-in-out md:relative md:translate-x-0 flex flex-col shadow-2xl">
            
            <div class="p-8 border-b border-red-800">
                <span class="text-xl font-bold tracking-widest uppercase">DISKOMINFO</span>
            </div>

            <nav class="flex-1 overflow-y-auto px-4 py-6 space-y-2">
                
                <a href="#" class="flex items-center px-4 py-3 rounded-lg hover:bg-red-800 transition-colors">
                    <span class="text-sm font-medium">Dashboard</span>
                </a>

                <div x-data="{ open: false }">
                    <button @click="open = !open" class="w-full flex items-center justify-between px-4 py-3 rounded-lg hover:bg-red-800 transition-colors">
                        <span class="text-sm font-medium">Manajemen Domain</span>
                        <svg class="w-4 h-4 transition-transform" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </button>
                    <div x-show="open" x-cloak x-transition class="mt-1 space-y-1 bg-red-950/30 rounded-lg overflow-hidden">
                        <a href="#" class="block px-10 py-2 text-sm text-red-100 hover:bg-red-800">Daftar Domain Terdaftar</a>
                        <a href="#" class="block px-10 py-2 text-sm text-red-100 hover:bg-red-800">Pengajuan Domain</a>
                        <a href="#" class="block px-10 py-2 text-sm text-red-100 hover:bg-red-800">Verifikasi Dokumen</a>
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

            <header class="h-16 bg-white border-b border-slate-200 px-6 flex justify-between items-center shadow-sm">
                <div class="flex items-center gap-4">
                    <button @click="sidebarOpen = !sidebarOpen" class="p-2 rounded hover:bg-slate-100 md:hidden">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                    </button>
                    <h1 class="font-semibold text-slate-700 uppercase tracking-tight">@yield('title')</h1>
                </div>

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

</body>
</html>