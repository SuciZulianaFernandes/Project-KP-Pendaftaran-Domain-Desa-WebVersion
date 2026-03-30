@extends('layouts.desa')
@section('title', 'Daftar Pengajuan Verifikasi Dokumen')

@section('content')
<div class="bg-white rounded-xl shadow p-6">

    <h2 class="text-xl font-bold mb-4">Daftar Pengajuan Domain</h2>

    <table class="w-full text-sm text-left">
        <thead class="bg-gray-100">
            <tr>
                <th class="p-3">No</th>
                <th>Nama Domain</th>
                <th>Tanggal Pengajuan</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
        @forelse($data as $i => $row)
            <tr class="border-b">
                <td class="p-3">{{ $i+1 }}</td>
                <td>{{ $row->nama_domain }}.desa.id</td>
                <td>{{ $row->tgl_pengajuan }}</td>
                <td>
                    @if($row->status_pengajuan == 'disetujui')
                        <span class="bg-green-500 text-white px-3 py-1 rounded-full">Disetujui</span>
                    @elseif($row->status_pengajuan == 'ditinjau')
                        <span class="bg-orange-500 text-white px-3 py-1 rounded-full">Ditinjau</span>
                    @elseif($row->status_pengajuan == 'perlu_perbaikan')
                        <span class="bg-red-500 text-white px-3 py-1 rounded-full">Perlu Perbaikan</span>
                    @else
                        <span class="bg-gray-500 text-white px-3 py-1 rounded-full">Draft</span>
                    @endif
                </td>
                <td class="flex gap-2">

                    <!-- DETAIL -->
                   <a href="{{ route('verifikasi.detail', $row->id_pengajuan) }}"
                       class="bg-blue-500 text-white px-3 py-1 rounded">
                        Lihat
                    </a>

                    <!-- HAPUS -->
                    <form action="{{ route('verifikasi.destroy', $row->id_pengajuan) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button class="bg-red-500 text-white px-3 py-1 rounded"
                            onclick="return confirm('Yakin hapus?')">
                            Hapus
                        </button>
                    </form>

                </td>
            </tr>
        @empty
            <tr>
                <td colspan="5" class="text-center p-4">Belum ada pengajuan</td>
            </tr>
        @endforelse
        </tbody>
    </table>

</div>
@endsection