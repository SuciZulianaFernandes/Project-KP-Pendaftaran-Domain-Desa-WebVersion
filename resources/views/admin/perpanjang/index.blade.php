@extends('layouts.admin') <!-- Sesuaikan layout admin kamu -->

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
                    <th class="p-3 border">Tgl Aktivasi</th>
                    <th class="p-3 border">Masa Berlaku</th>
                    <th class="p-3 border">Status</th>
                    <th class="p-3 border">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($fakturs as $i => $item)
                    @php
                        $aktivasi = $item->pengajuan ? $item->pengajuan->aktivasi : null;
                    @endphp
                    <tr class="border-b hover:bg-gray-50">
                        <td class="p-3 border">{{ $i + 1 }}</td>
                        
                        {{-- DOMAIN --}}
                        <td class="p-3 border font-semibold">{{ $item->nama_domain }}.desa.id</td>
                        
                        {{-- TGL AKTIVASI --}}
                        <td class="p-3 border">
                            {{ $aktivasi && $aktivasi->tgl_aktivasi ? $aktivasi->tgl_aktivasi->format('d-m-Y') : '-' }}
                        </td>

                        {{-- MASA BERLAKU --}}
                        <td class="p-3 border">
                            @if($aktivasi && $aktivasi->masa_berlaku)
                                <span class="{{ $aktivasi->is_kadaluarsa ? 'text-red-600 font-bold' : 'text-green-700' }}">
                                    {{ $aktivasi->masa_berlaku->format('d-m-Y') }}
                                </span>
                            @else
                                <span class="text-gray-400">-</span>
                            @endif
                        </td>

                        {{-- STATUS INVOICE --}}
                        <td class="p-3 border">
                            @if($item->status == 'belum_bayar')
                                <span class="px-2 py-1 rounded text-xs bg-yellow-100 text-yellow-800">Belum Bayar</span>
                            @elseif($item->status == 'sudah_bayar')
                                <span class="px-2 py-1 rounded text-xs bg-green-100 text-green-800">Sudah Bayar</span>
                            @else
                                <span class="px-2 py-1 rounded text-xs bg-red-100 text-red-800">Kadaluarsa</span>
                            @endif
                        </td>

                        {{-- Aksi --}}
                        <td class="p-3 border">
                            <a href="{{ route('admin.faktur.show', $item->id) }}" class="bg-blue-500 text-white px-3 py-1 rounded text-xs hover:bg-blue-600">
                                Detail
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center p-6 text-gray-500">
                            Belum ada pengajuan perpanjangan.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection