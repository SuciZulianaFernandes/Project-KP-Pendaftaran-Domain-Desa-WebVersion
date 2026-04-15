@extends('layouts.admin')
@section('title', 'Daftar Domain Terdaftar')

@section('content')
<div class="bg-white p-6 rounded-xl shadow">
    <h2 class="text-xl font-bold mb-4">Daftar Domain Terdaftar (Aktif)</h2>
    <table class="w-full text-sm text-left">
        <thead class="bg-gray-100">
            <tr>
                <th class="p-3">No</th>
                <th>Nama Desa</th>
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
                <td>{{ $row->nama_desa }}</td>
                <td>{{ $row->nama_domain }}.desa.id</td>
                <td>{{ $row->aktivasi ? $row->aktivasi->tgl_aktivasi->format('d-m-Y') : '-' }}</td>
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
                <td>
                    <a href="{{ route('admin.pengajuan.detail', $row->id_pengajuan) }}" class="bg-blue-500 text-white px-3 py-1 rounded">Detail</a>
                </td>
            </tr>
        @empty
            <tr><td colspan="5" class="text-center p-4">Belum ada data aktif.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>
@endsection