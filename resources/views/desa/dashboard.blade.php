@extends('layouts.desa')

@section('title', 'Dashboard Desa')

@section('content')

<div class="space-y-8 animate-fade-in-up">
    
    {{-- HEADER WELCOME CARD --}}
    <div class="bg-gradient-to-r from-[#109696] via-[#1A85A5] to-[#1760C5] rounded-3xl p-8 text-white shadow-xl relative overflow-hidden">
        {{-- Dekorasi Background Abstrak --}}
        <div class="absolute top-0 right-0 -mt-10 -mr-10 w-64 h-64 bg-white opacity-5 rounded-full blur-3xl"></div>
        <div class="absolute bottom-0 left-0 -mb-10 -ml-10 w-40 h-40 bg-[#109696] opacity-20 rounded-full blur-2xl"></div>
        <div class="absolute top-1/2 right-1/4 w-32 h-32 border border-white/10 rounded-full"></div>
        <div class="absolute top-10 right-20 w-16 h-16 border border-white/10 rounded-full"></div>
        
        <div class="relative z-10 flex items-center justify-between">
            <div class="flex-1">
                <div class="flex items-center gap-2 mb-2">
                    <div class="w-2 h-2 bg-emerald-400 rounded-full animate-pulse"></div>
                    <span class="text-white/70 text-sm font-medium">Portal Desa</span>
                </div>
                <h1 class="text-3xl font-bold mb-2">Halo, {{ auth()->user()->name }}!</h1>
                <p class="text-white/70 text-lg">Kelola domain desa Anda dengan mudah dan cepat.</p>
                <div class="mt-5 inline-flex items-center gap-2 bg-white/10 backdrop-blur-sm px-4 py-2 rounded-xl text-sm font-medium border border-white/20">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    {{ now()->translatedFormat('l, d F Y') }}
                </div>
            </div>
            
            {{-- LOGO DI KANAN --}}
            <div class="hidden md:flex items-center justify-center ml-8">
                <div class="relative">
                    <!-- Glow effect di belakang logo -->
                    <div class="absolute inset-0 bg-white/10 rounded-3xl blur-2xl scale-110"></div>
                    <!-- Background semi-transparan -->
                    <div class="relative bg-white/10 backdrop-blur-md rounded-3xl p-6 border border-white/20">
                        <img src="{{ asset('storage/images/logo.png') }}" alt="Logo" class="w-36 h-36 object-contain drop-shadow-lg">
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- STATISTIK BARIS 1 --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">

        {{-- 1. TOTAL DOMAIN --}}
        <div class="group bg-white rounded-2xl shadow-sm border border-slate-100 p-6 relative overflow-hidden transition-all duration-300 hover:-translate-y-1 hover:shadow-lg hover:border-[#109696]/20">
            <div class="absolute -right-6 -top-6 w-28 h-28 bg-gradient-to-br from-[#109696]/5 to-transparent rounded-full group-hover:scale-110 transition-transform duration-500"></div>
            
            <div class="relative z-10">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-[#109696] to-[#1A85A5] rounded-xl flex items-center justify-center shadow-sm shadow-[#109696]/20">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9.004 9.004 0 008.716-6.747M12 21a9.004 9.004 0 01-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3m0 0a8.997 8.997 0 017.843 4.582M12 3a8.997 8.997 0 00-7.843 4.582m15.686 0A11.953 11.953 0 0112 10.5c-2.998 0-5.74-1.1-7.843-2.918m15.686 0A8.959 8.959 0 0121 12c0 .778-.099 1.533-.284 2.253m0 0A17.919 17.919 0 0112 16.5c-3.162 0-6.133-.815-8.716-2.247m0 0A9.015 9.015 0 013 12c0-1.605.42-3.113 1.157-4.418"/>
                        </svg>
                    </div>
                    <svg class="w-5 h-5 text-slate-300 group-hover:text-[#109696] transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75M15 10.5a3 3 0 11-6 0 3 3 0 016 0zm3 0h.008v.008H18V10.5zm-12 0h.008v.008H6V10.5z"/>
                    </svg>
                </div>
                <p class="text-slate-400 text-xs font-semibold uppercase tracking-wider">Total Domain</p>
                <h2 class="text-3xl font-bold text-slate-800 mt-1">{{ $totalDomain }}</h2>
                <div class="mt-3 flex items-center gap-1 text-xs text-[#109696] font-medium">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18L9 11.25l4.306 4.307a11.95 11.95 0 015.814-5.519l2.74-1.22m0 0l-5.94-2.28m5.94 2.28l-2.28 5.941"/>
                    </svg>
                    Domain yang diajukan
                </div>
            </div>
        </div>

        {{-- 2. DOMAIN AKTIF --}}
        <div class="group bg-white rounded-2xl shadow-sm border border-slate-100 p-6 relative overflow-hidden transition-all duration-300 hover:-translate-y-1 hover:shadow-lg hover:border-emerald-200">
            <div class="absolute -right-6 -top-6 w-28 h-28 bg-gradient-to-br from-emerald-500/5 to-transparent rounded-full group-hover:scale-110 transition-transform duration-500"></div>
            
            <div class="relative z-10">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-xl flex items-center justify-center shadow-sm shadow-emerald-500/20">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div class="flex items-center gap-1 bg-emerald-50 text-emerald-600 px-2 py-0.5 rounded-full">
                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd"/>
                        </svg>
                        <span class="text-xs font-semibold">Live</span>
                    </div>
                </div>
                <p class="text-slate-400 text-xs font-semibold uppercase tracking-wider">Domain Aktif</p>
                <h2 class="text-3xl font-bold text-slate-800 mt-1">{{ $totalAktif }}</h2>
                <div class="mt-3 flex items-center gap-1 text-xs text-emerald-600 font-medium">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/>
                    </svg>
                    Berjalan normal
                </div>
            </div>
        </div>

        {{-- 3. SEDANG DITINJAU --}}
        <div class="group bg-white rounded-2xl shadow-sm border border-slate-100 p-6 relative overflow-hidden transition-all duration-300 hover:-translate-y-1 hover:shadow-lg hover:border-amber-200">
            <div class="absolute -right-6 -top-6 w-28 h-28 bg-gradient-to-br from-amber-500/5 to-transparent rounded-full group-hover:scale-110 transition-transform duration-500"></div>
            
            <div class="relative z-10">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-amber-400 to-amber-500 rounded-xl flex items-center justify-center shadow-sm shadow-amber-500/20">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>
                    <div class="w-8 h-8 bg-amber-50 rounded-lg flex items-center justify-center">
                        <div class="w-2 h-2 bg-amber-400 rounded-full animate-pulse"></div>
                    </div>
                </div>
                <p class="text-slate-400 text-xs font-semibold uppercase tracking-wider">Sedang Ditinjau</p>
                <h2 class="text-3xl font-bold text-slate-800 mt-1">{{ $totalDitinjau }}</h2>
                <div class="mt-3 flex items-center gap-1 text-xs text-amber-600 font-medium">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Menunggu review admin
                </div>
            </div>
        </div>

        {{-- 4. SEDANG DIPROSES --}}
        <div class="group bg-white rounded-2xl shadow-sm border border-slate-100 p-6 relative overflow-hidden transition-all duration-300 hover:-translate-y-1 hover:shadow-lg hover:border-[#1A85A5]/20">
            <div class="absolute -right-6 -top-6 w-28 h-28 bg-gradient-to-br from-[#1A85A5]/5 to-transparent rounded-full group-hover:scale-110 transition-transform duration-500"></div>
            
            <div class="relative z-10">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-[#1A85A5] to-[#1760C5] rounded-xl flex items-center justify-center shadow-sm shadow-[#1A85A5]/20">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12a7.5 7.5 0 0015 0m-15 0a7.5 7.5 0 1115 0m-15 0H3m16.5 0H21m-1.5 0H12m-8.457 3.077l1.41-.513m14.095-5.13l1.41-.513M5.106 17.785l1.15-.964m11.49-9.642l1.149-.964M7.501 19.795l.75-1.3m7.5-12.99l.75-1.3m-6.063 16.658l.26-1.477m2.605-14.772l.26-1.477m0 17.726l-.26-1.477M10.698 4.614l-.26-1.477M16.5 19.794l-.75-1.299M7.5 4.205L6.75 2.906m9.944 18.366l-.26-1.477M10.698 4.614l-.26-1.477"/>
                        </svg>
                    </div>
                    <svg class="w-5 h-5 text-slate-300 group-hover:text-[#1A85A5] transition-colors animate-spin" style="animation-duration: 3s;" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182"/>
                    </svg>
                </div>
                <p class="text-slate-400 text-xs font-semibold uppercase tracking-wider">Sedang Diproses</p>
                <h2 class="text-3xl font-bold text-slate-800 mt-1">{{ $totalDiproses }}</h2>
                <div class="mt-3 flex items-center gap-1 text-xs text-[#1A85A5] font-medium">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 13.5l10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75z"/>
                    </svg>
                    Dalam proses setup
                </div>
            </div>
        </div>

    </div>

    {{-- STATISTIK BARIS 2 --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

        {{-- 5. PERLU PERBAIKAN --}}
        <div class="group bg-white rounded-2xl shadow-sm border border-slate-100 p-6 relative overflow-hidden transition-all duration-300 hover:-translate-y-1 hover:shadow-lg hover:border-orange-200">
            <div class="absolute -right-6 -top-6 w-28 h-28 bg-gradient-to-br from-orange-500/5 to-transparent rounded-full group-hover:scale-110 transition-transform duration-500"></div>
            
            <div class="relative z-10">
                <div class="flex items-start justify-between mb-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-orange-400 to-orange-500 rounded-xl flex items-center justify-center shadow-sm shadow-orange-500/20">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M11.42 15.17l-5.1-5.1m0 0L11.42 4.97m-5.1 5.1H21M3 21h18"/>
                        </svg>
                    </div>
                    @if($totalPerbaikan > 0)
                    <div class="flex items-center gap-1 bg-orange-50 text-orange-600 px-2.5 py-1 rounded-lg">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"/>
                        </svg>
                        <span class="text-xs font-semibold">Perlu Aksi</span>
                    </div>
                    @endif
                </div>
                <p class="text-slate-400 text-xs font-semibold uppercase tracking-wider">Perlu Perbaikan</p>
                <h2 class="text-3xl font-bold text-slate-800 mt-1">{{ $totalPerbaikan }}</h2>
                <div class="mt-3 flex items-center gap-1 text-xs text-orange-600 font-medium">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z"/>
                    </svg>
                    Dokumen tidak lengkap
                </div>
            </div>
        </div>

        {{-- 6. KADALUARSA --}}
        <div class="group bg-white rounded-2xl shadow-sm border border-slate-100 p-6 relative overflow-hidden transition-all duration-300 hover:-translate-y-1 hover:shadow-lg hover:border-rose-200">
            <div class="absolute -right-6 -top-6 w-28 h-28 bg-gradient-to-br from-rose-500/5 to-transparent rounded-full group-hover:scale-110 transition-transform duration-500"></div>
            
            <div class="relative z-10">
                <div class="flex items-start justify-between mb-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-rose-400 to-rose-500 rounded-xl flex items-center justify-center shadow-sm shadow-rose-500/20">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    @if($totalKadaluarsa > 0)
                    <div class="flex items-center gap-1 bg-rose-50 text-rose-600 px-2.5 py-1 rounded-lg">
                        <div class="w-1.5 h-1.5 bg-rose-500 rounded-full animate-ping"></div>
                        <span class="text-xs font-semibold">Perhatian</span>
                    </div>
                    @endif
                </div>
                <p class="text-slate-400 text-xs font-semibold uppercase tracking-wider">Kadaluarsa</p>
                <h2 class="text-3xl font-bold text-slate-800 mt-1">{{ $totalKadaluarsa }}</h2>
                <div class="mt-3 flex items-center gap-1 text-xs text-rose-500 font-medium">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z"/>
                    </svg>
                    Segera perpanjang domain
                </div>
            </div>
        </div>

    </div>

</div>

@endsection