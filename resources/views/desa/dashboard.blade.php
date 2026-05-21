@extends('layouts.desa')

@section('title', 'Dashboard Desa')

@section('content')

<div class="space-y-8 animate-fade-in-up">
    
    <div class="bg-gradient-to-r from-red-800 to-red-900 rounded-3xl p-8 text-white shadow-xl relative overflow-hidden">
        {{-- Dekorasi Background Abstrak --}}
        <div class="absolute top-0 right-0 -mt-10 -mr-10 w-64 h-64 bg-white opacity-5 rounded-full blur-3xl"></div>
        <div class="absolute bottom-0 left-0 -mb-10 -ml-10 w-40 h-40 bg-red-500 opacity-20 rounded-full blur-2xl"></div>
        
        <div class="relative z-10">
            <h1 class="text-3xl font-bold mb-2">Halo, {{ auth()->user()->name }}! 👋</h1>
            <p class="text-red-100 text-lg">Kelola domain desa Anda dengan mudah dan cepat.</p>
            <div class="mt-4 inline-block bg-white/10 backdrop-blur-sm px-4 py-1.5 rounded-full text-sm font-medium border border-white/20">
                📅 {{ now()->translatedFormat('l, d F Y') }}
            </div>
        </div>
    </div>

    {{-- PERINGATAN KADALUARSA (Hanya muncul jika ada data) --}}
    @if($totalKadaluarsa > 0)
    <div class="bg-red-50 border border-red-200 rounded-3xl p-6 flex flex-col md:flex-row items-center justify-between gap-4 shadow-sm animate-pulse-slow">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center text-2xl">
                ⚠️
            </div>
            <div>
                <h3 class="font-bold text-red-800 text-lg">Perhatian! Domain Kadaluarsa</h3>
                <p class="text-red-600 text-sm">Anda memiliki <span class="font-bold">{{ $totalKadaluarsa }}</span> domain yang masa berlakunya telah habis. Layanan mungkin terganggu.</p>
            </div>
        </div>
        <a href="{{ route('desa.perpanjang') }}" class="bg-red-600 hover:bg-red-700 text-white px-6 py-3 rounded-xl font-semibold shadow-lg shadow-red-200 transition-all transform hover:-translate-y-1 whitespace-nowrap">
            Perpanjang Sekarang
        </a>
    </div>
    @endif

    {{-- STATISTIK BARIS 1 --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">

        {{-- 1. TOTAL DOMAIN --}}
        <div class="group bg-white rounded-3xl shadow-sm border border-slate-100 p-6 relative overflow-hidden transition-all duration-300 hover:-translate-y-2 hover:shadow-2xl">
            <div class="absolute -right-4 -top-4 w-24 h-24 bg-red-50 rounded-full group-hover:scale-150 transition-transform duration-500 ease-out z-0"></div>
            
            <div class="relative z-10">
                <div class="flex justify-between items-center h-full">
                    <div>
                        <p class="text-slate-400 text-xs font-bold uppercase tracking-wider">Total Domain</p>
                        <h2 class="text-4xl font-extrabold text-slate-800 mt-1 tracking-tight">{{ $totalDomain }}</h2>
                    </div>
                    <div class="bg-gradient-to-br from-red-100 to-red-200 text-red-600 p-3 rounded-2xl shadow-sm group-hover:rotate-12 transition-transform duration-300">
                        🌐
                    </div>
                </div>
            </div>
        </div>

        {{-- 2. DOMAIN AKTIF --}}
        <div class="group bg-white rounded-3xl shadow-sm border border-slate-100 p-6 relative overflow-hidden transition-all duration-300 hover:-translate-y-2 hover:shadow-2xl">
            <div class="absolute -right-4 -top-4 w-24 h-24 bg-green-50 rounded-full group-hover:scale-150 transition-transform duration-500 ease-out z-0"></div>
            
            <div class="relative z-10">
                <div class="flex justify-between items-center h-full">
                    <div>
                        <p class="text-slate-400 text-xs font-bold uppercase tracking-wider">Domain Aktif</p>
                        <h2 class="text-4xl font-extrabold text-slate-800 mt-1 tracking-tight">{{ $totalAktif }}</h2>
                    </div>
                    <div class="bg-gradient-to-br from-green-100 to-emerald-200 text-green-600 p-3 rounded-2xl shadow-sm group-hover:rotate-12 transition-transform duration-300">
                        ✅
                    </div>
                </div>
            </div>
        </div>

        {{-- 3. SEDANG DITINJAU --}}
        <div class="group bg-white rounded-3xl shadow-sm border border-slate-100 p-6 relative overflow-hidden transition-all duration-300 hover:-translate-y-2 hover:shadow-2xl">
            <div class="absolute -right-4 -top-4 w-24 h-24 bg-yellow-50 rounded-full group-hover:scale-150 transition-transform duration-500 ease-out z-0"></div>
            
            <div class="relative z-10">
                <div class="flex justify-between items-center h-full">
                    <div>
                        <p class="text-slate-400 text-xs font-bold uppercase tracking-wider">Sedang Ditinjau</p>
                        <h2 class="text-4xl font-extrabold text-slate-800 mt-1 tracking-tight">{{ $totalDitinjau }}</h2>
                    </div>
                    <div class="bg-gradient-to-br from-yellow-100 to-amber-200 text-yellow-600 p-3 rounded-2xl shadow-sm group-hover:rotate-12 transition-transform duration-300">
                        👀
                    </div>
                </div>
            </div>
        </div>

        {{-- 4. SEDANG DIPROSES --}}
        <div class="group bg-white rounded-3xl shadow-sm border border-slate-100 p-6 relative overflow-hidden transition-all duration-300 hover:-translate-y-2 hover:shadow-2xl">
            <div class="absolute -right-4 -top-4 w-24 h-24 bg-indigo-50 rounded-full group-hover:scale-150 transition-transform duration-500 ease-out z-0"></div>
            
            <div class="relative z-10">
                <div class="flex justify-between items-center h-full">
                    <div>
                        <p class="text-slate-400 text-xs font-bold uppercase tracking-wider">Sedang Diproses</p>
                        <h2 class="text-4xl font-extrabold text-slate-800 mt-1 tracking-tight">{{ $totalDiproses }}</h2>
                    </div>
                    <div class="bg-gradient-to-br from-indigo-100 to-blue-200 text-indigo-600 p-3 rounded-2xl shadow-sm group-hover:rotate-12 transition-transform duration-300">
                        ⚙️
                    </div>
                </div>
            </div>
        </div>

    </div>

    {{-- STATISTIK BARIS 2 --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

        {{-- 5. PERLU PERBAIKAN --}}
        <div class="group bg-white rounded-3xl shadow-sm border border-slate-100 p-6 relative overflow-hidden transition-all duration-300 hover:-translate-y-2 hover:shadow-2xl">
            <div class="absolute -right-4 -top-4 w-24 h-24 bg-orange-50 rounded-full group-hover:scale-150 transition-transform duration-500 ease-out z-0"></div>
            
            <div class="relative z-10">
                <div class="flex justify-between items-center h-full">
                    <div>
                        <p class="text-slate-400 text-xs font-bold uppercase tracking-wider">Perlu Perbaikan</p>
                        <h2 class="text-4xl font-extrabold text-slate-800 mt-1 tracking-tight">{{ $totalPerbaikan }}</h2>
                    </div>
                    <div class="bg-gradient-to-br from-orange-100 to-orange-200 text-orange-600 p-3 rounded-2xl shadow-sm group-hover:rotate-12 transition-transform duration-300">
                        🛠️
                    </div>
                </div>
            </div>
        </div>

        {{-- 6. KADALUARSA --}}
        <div class="group bg-white rounded-3xl shadow-sm border border-slate-100 p-6 relative overflow-hidden transition-all duration-300 hover:-translate-y-2 hover:shadow-2xl">
            <div class="absolute -right-4 -top-4 w-24 h-24 bg-red-50 rounded-full group-hover:scale-150 transition-transform duration-500 ease-out z-0"></div>
            
            <div class="relative z-10">
                <div class="flex justify-between items-start mb-2">
                    <div>
                        <p class="text-slate-400 text-xs font-bold uppercase tracking-wider">Kadaluarsa</p>
                        <h2 class="text-4xl font-extrabold text-slate-800 mt-1 tracking-tight">{{ $totalKadaluarsa }}</h2>
                    </div>
                    <div class="bg-gradient-to-br from-red-100 to-rose-200 text-red-600 p-3 rounded-2xl shadow-sm group-hover:rotate-12 transition-transform duration-300">
                        ⏰
                    </div>
                </div>
                
                @if($totalKadaluarsa > 0)
                <div class="mt-4 flex items-center text-xs text-red-500 font-medium bg-red-50 inline-flex px-3 py-1.5 rounded-lg border border-red-100">
                    <span class="mr-1.5">⚠️</span> Aksi diperlukan
                </div>
                @endif
            </div>
        </div>

    </div>

</div>

@endsection