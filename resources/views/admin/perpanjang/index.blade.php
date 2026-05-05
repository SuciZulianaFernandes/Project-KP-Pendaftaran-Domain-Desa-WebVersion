@extends('layouts.admin')

@section('title', 'Pengajuan Perpanjang Domain')

@section('content')
<div class="bg-white rounded-xl shadow p-6">
    <h2 class="text-xl font-bold mb-4">Pengajuan Perpanjang Domain</h2>

    @if(session('success'))
        <div class="bg-green-200 text-green-800 p-3 rounded mb-4 text-sm">
            {{ session('success') }}
        </div>
    @endif

    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left border-collapse">
            <thead class="bg-gray-100">
                <tr>
                    <th class="p-3 border">No</th>
                    <th class="p-3 border">Domain</th>
                    <th class="p-3 border">Status Domain</th> {{-- Diubah Judul --}}
                    <th class="p-3 border">Tipe</th>
                    <th class="p-3 border">Tgl Faktur</th>
                    <th class="p-3 border">Status Faktur</th>
                    <th class="p-3 border">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($fakturs as $i => $item)
                    @php
                        // Ambil data aktivasi untuk menampilkan tanggal
                        $aktivasi = $item->pengajuan ? $item->pengajuan->aktivasi : null;
                        $pengajuanStatus = $item->pengajuan ? $item->pengajuan->status_pengajuan : 'unknown';
                    @endphp
                    <tr class="border-b hover:bg-gray-50">
                        <td class="p-3 border">{{ $i + 1 }}</td>
                        
                        {{-- DOMAIN --}}
                        <td class="p-3 border font-semibold">{{ $item->nama_domain }}.desa.id</td>
                        
                        {{-- STATUS DOMAIN (SESUAI REQUEST: MENUNGGU AKTIVASI, DIPROSES, DLL) --}}
                        <td class="p-3 border">
                            @if($pengajuanStatus == 'menunggu_aktivasi')
                                <span class="px-2 py-1 rounded text-xs bg-orange-100 text-orange-800">Menunggu Aktivasi</span>
                            @elseif($pengajuanStatus == 'diproses')
                                <span class="px-2 py-1 rounded text-xs bg-blue-100 text-blue-800">Diproses</span>
                            @elseif($pengajuanStatus == 'aktif')
                                <span class="px-2 py-1 rounded text-xs bg-green-100 text-green-800">Aktif</span>
                            @else
                                <span class="px-2 py-1 rounded text-xs bg-gray-100 text-gray-800">{{ ucfirst(str_replace('_', ' ', $pengajuanStatus)) }}</span>
                            @endif
                        </td>

                        {{-- TIPE --}}
                        <td class="p-3 border">
                            <span class="px-2 py-0.5 rounded text-xs bg-purple-100 text-purple-700 font-medium">Perpanjangan</span>
                        </td>

                        {{-- TGL FAKTUR --}}
                        <td class="p-3 border">
                            {{ $item->created_at->format('d-m-Y') }}
                        </td>

                        {{-- STATUS FAKTUR --}}
                        <td class="p-3 border">
                            @if($item->status == 'belum_bayar')
                                <span class="px-2 py-1 rounded text-xs bg-yellow-100 text-yellow-800">Belum Bayar</span>
                            @elseif($item->status == 'sudah_bayar')
                                <span class="px-2 py-1 rounded text-xs bg-green-100 text-green-800">Sudah Bayar</span>
                            @else
                                <span class="px-2 py-1 rounded text-xs bg-red-100 text-red-800">Kadaluarsa</span>
                            @endif
                        </td>

                        {{-- AKSI (DIUBAH KE DETAIL PERPANJANGAN) --}}
                        <td class="p-3 border">
                            <a href="{{ route('admin.perpanjang.show', $item->id) }}" class="bg-blue-500 text-white px-3 py-1 rounded text-xs hover:bg-blue-600">
                                Detail
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center p-6 text-gray-500">
                            Belum ada pengajuan perpanjangan.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection