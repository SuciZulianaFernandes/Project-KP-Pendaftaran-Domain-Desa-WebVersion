@extends('layouts.desa')
@section('title', 'Perpanjang Domain')

@section('content')
<div class="bg-white rounded-xl shadow p-6">
    <h2 class="text-xl font-bold mb-4">Domain Aktif (Perpanjang)</h2>

    @if(session('success'))
        <div class="bg-green-200 text-green-800 p-3 rounded mb-4">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="bg-red-200 text-red-800 p-3 rounded mb-4">{{ session('error') }}</div>
    @endif

    <table class="w-full text-sm text-left">
        <thead class="bg-gray-100">
            <tr>
                <th class="p-3">No</th>
                <th>Domain</th>
                <th>Tgl Aktivasi</th>
                <th>Masa Berlaku</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
        @forelse($data as $i => $row)
            @php
                $kadaluarsa = ($row->aktivasi && $row->aktivasi->masa_berlaku) ? $row->aktivasi->is_kadaluarsa : false;
                
                $bisaPerpanjang = false;
                if ($row->aktivasi && $row->aktivasi->tgl_aktivasi) {
                    // TESTING: 30 detik SETELAH tanggal aktivasi
                    $batasAwal = $row->aktivasi->tgl_aktivasi->copy()->addSeconds(30);
                    
                    // PRODUCTION: 60 hari SEBELUM masa berlaku habis
                    // $batasAwal = $row->aktivasi->masa_berlaku->copy()->subDays(60);
                    
                    $bisaPerpanjang = \Carbon\Carbon::now() >= $batasAwal;
                }
            @endphp

            <tr class="border-b">
                <td class="p-3">{{ $i+1 }}</td>
                <td>{{ $row->nama_domain }}.desa.id</td>
                <td>{{ $row->aktivasi ? $row->aktivasi->tgl_aktivasi->format('d-m-Y H:i') : '-' }}</td>
                
                <td>
                    @if($row->aktivasi && $row->aktivasi->masa_berlaku)
                        <span class="{{ $kadaluarsa ? 'text-red-600 font-bold' : 'text-green-700' }}">
                            {{ $row->aktivasi->masa_berlaku->format('d-m-Y H:i') }}
                        </span>
                    @else
                        <span class="text-gray-400">-</span>
                    @endif
                </td>

                <td>
                    @if($kadaluarsa)
                        <span class="px-3 py-1 rounded bg-red-100 text-red-700 text-xs font-medium inline-block">
                            <i></i> Kadaluarsa
                        </span>
                    @elseif($bisaPerpanjang)
                        <span class="px-3 py-1 rounded bg-orange-100 text-orange-700 text-xs font-medium inline-block">
                            <i></i> Siap Diperpanjang
                        </span>
                    @else
                        <span class="px-3 py-1 rounded bg-green-100 text-green-700 text-xs font-medium inline-block">
                            <i></i> Aktif
                        </span>
                    @endif
                </td>

                                <td>
                    @php
                        // Cek apakah desa sudah pernah kirim permintaan tapi admin belum proses
                        $menungguFaktur = \App\Models\Pesan::where('id_pengajuan', $row->id_pengajuan)
                            ->where('judul', 'Permintaan Perpanjangan Domain')
                            ->where('is_read', 0)
                            ->exists();
                    @endphp

                    @if($menungguFaktur)
                        {{-- LABEL MENUNGGU FAKTUR --}}
                        <span class="px-3 py-1 rounded bg-blue-100 text-blue-700 text-xs font-medium inline-block">
                            <i class="fas fa-clock mr-1"></i> Menunggu Faktur
                        </span>
                    @elseif($bisaPerpanjang)
                        {{-- TOMBOL PERPANJANG --}}
                        <a href="{{ url('/desa/perpanjang/ajukan/' . $row->id_pengajuan) }}" 
                           class="bg-yellow-500 text-white px-3 py-1 rounded hover:bg-yellow-600"
                           onclick="return confirm('Apakah anda ingin perpanjang masa aktif domain? Admin akan mengirimkan faktur untuk perpanjang masa aktif domain anda.')">
                           Perpanjang
                        </a>
                    @else
                        {{-- TOMBOL DISABLED --}}
                        <button disabled class="bg-gray-400 text-white px-3 py-1 rounded cursor-not-allowed">
                            Perpanjang
                        </button>
                    @endif
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="6" class="text-center p-4 text-gray-500">Belum ada domain aktif.</td>
            </tr>
        @endforelse
        </tbody>
    </table>
</div>
@endsection