@extends('layouts.desa')
@section('title', 'Perpanjang Domain')

@section('content')
<div class="bg-white rounded-xl shadow p-6">
    <h2 class="text-xl font-bold mb-4">Domain Aktif (Perpanjang)</h2>

    {{-- ALERT --}}
    @if(session('success'))
        <div class="bg-green-200 text-green-800 p-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-200 text-red-800 p-3 rounded mb-4">
            {{ session('error') }}
        </div>
    @endif

    <table class="w-full text-sm text-left">
        <thead class="bg-gray-100">
            <tr>
                <th class="p-3">No</th>
                <th>Domain</th>
                <th>Tgl Aktivasi</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>

        <tbody>
        @forelse($data as $i => $row)
            <tr class="border-b">
                <td class="p-3">{{ $i+1 }}</td>

                <td>{{ $row->nama_domain }}.desa.id</td>

                {{-- TANGGAL --}}
                <td>
                    {{ $row->aktivasi ? $row->aktivasi->tgl_aktivasi->format('d-m-Y') : '-' }}
                </td>
 {{-- STATUS --}}
                <td>
                    <span class="px-2 py-1 rounded text-white text-xs
                        @if($row->status_pengajuan == 'aktif') bg-green-600
                        @elseif($row->status_pengajuan == 'nonaktif') bg-gray-500
                        @elseif($row->status_pengajuan == 'kadaluarsa') bg-red-600
                        @endif
                    ">
                        {{ ucfirst($row->status_pengajuan) }}
                    </span>
                </td>
                {{-- AKSI --}}
                <td>
                    <button 
                        class="bg-yellow-500 text-white px-3 py-1 rounded hover:bg-yellow-600
                        @if($row->status_pengajuan != 'aktif') opacity-50 cursor-not-allowed @endif"
                        @if($row->status_pengajuan != 'aktif') disabled @endif
                    >
                        Perpanjang
                    </button>
                </td>
            </tr>

        @empty
            <tr>
                <td colspan="5" class="text-center p-4">
                    Belum ada domain aktif.
                </td>
            </tr>
        @endforelse
        </tbody>
    </table>
</div>
@endsection