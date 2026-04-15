@extends('layouts.admin')
@section('title', 'Pengajuan Domain')

@section('content')
<div class="bg-white p-6 rounded-xl shadow">

    <h2 class="text-xl font-bold mb-4">Manajemen Pengajuan Domain</h2>

    <table class="w-full text-sm text-left">
        <thead class="bg-gray-100">
            <tr>
                <th class="p-3">No</th>
                <th>Nama Desa</th>
                <th>Domain</th>
                <th>Tanggal</th>
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
                <td>{{ $row->tgl_pengajuan }}</td>

                <td>
                    @if($row->status_pengajuan == 'ditinjau')
                        <span class="bg-yellow-500 text-white px-3 py-1 rounded-full">Ditinjau</span>
                    @elseif($row->status_pengajuan == 'perlu_perbaikan')
                        <span class="bg-red-500 text-white px-3 py-1 rounded-full">Perlu Perbaikan</span>
                    @elseif($row->status_pengajuan == 'diproses')
                        <!-- Ubah warna jadi Biru agar beda sama Aktif -->
                        <span class="bg-blue-500 text-white px-3 py-1 rounded-full">Diproses</span>
                    @elseif($row->status_pengajuan == 'menunggu_aktivasi')
                        <!-- TAMBAHKAN INI: Status menunggu aktivasi -->
                        <span class="bg-orange-500 text-white px-3 py-1 rounded-full">Menunggu Aktivasi</span>
                    @elseif($row->status_pengajuan == 'aktif')
                        <!-- TAMBAHKAN INI: Status aktif -->
                        <span class="bg-green-600 text-white px-3 py-1 rounded-full">Aktif</span>
                    @else
                        <span class="bg-gray-500 text-white px-3 py-1 rounded-full">Draft</span>
                    @endif
                </td>

                <td>
                    <a href="{{ route('admin.pengajuan.detail', $row->id_pengajuan) }}"
                       class="bg-blue-500 text-white px-3 py-1 rounded">
                        Detail
                    </a>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="6" class="text-center p-4">Belum ada data</td>
            </tr>
        @endforelse
        </tbody>
    </table>

</div>
@endsection