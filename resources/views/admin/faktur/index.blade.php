@extends('layouts.admin')
@section('title', 'Manajemen Faktur')

@section('content')
<div class="bg-white p-6 rounded-xl shadow">

    <h2 class="text-xl font-bold mb-4">Manajemen Faktur</h2>

    <table class="w-full text-sm text-left">
        <thead class="bg-gray-100">
            <tr>
                <th class="p-3">No</th>
                <th>Nama Desa</th>
                <th>Domain</th>
                <th>No Invoice</th>
                <th>Tanggal Konfirmasi</th>
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
<td>{{ $row->faktur->no_invoice ?? '-' }}</td>
<td>{{ $row->faktur->tanggal_konfirmasi ?? '-' }}</td>

                <td>
                    @if($row->faktur)
    @if($row->faktur->status == 'sudah_bayar')
        <span class="bg-green-500 text-white px-3 py-1 rounded-full">Sudah Dibayar</span>
    @elseif($row->faktur->status == 'belum_bayar')
        <span class="bg-yellow-500 text-white px-3 py-1 rounded-full">Belum Bayar</span>
    @elseif($row->faktur->status == 'kedaluarsa')
        <span class="bg-gray-500 text-white px-3 py-1 rounded-full">Kedaluarsa</span>
    @endif
@else
    <span class="bg-gray-300 text-black px-3 py-1 rounded-full">Belum dibuat</span>
@endif
                </td>

                <td>
    @if(!$row->faktur)
    <form action="{{ route('admin.faktur.store', $row->id_pengajuan) }}" method="POST">
        @csrf
        <button class="bg-blue-500 text-white px-3 py-1 rounded">
            Buat Faktur
        </button>
    </form>
@else
    <span class="text-green-600 font-semibold">Sudah dibuat</span>
@endif
</td>
            </tr>
        @empty
            <tr>
                <td colspan="6" class="text-center p-4">Belum ada faktur</td>
            </tr>
        @endforelse
        </tbody>
    </table>

</div>
@endsection