<?php

namespace App\Http\Controllers;

use App\Models\Faktur;
use App\Models\Pengajuan;

class FakturController extends Controller
{
    public function store($id)
{
    $pengajuan = Pengajuan::findOrFail($id);

    // Cegah double faktur
    if (Faktur::where('id_pengajuan', $id)->exists()) {
        return back()->with('error', 'Faktur sudah ada!');
    }

    Faktur::create([
        'id_pengajuan' => $pengajuan->id_pengajuan,
        'nama_desa' => $pengajuan->nama_desa,
        'nama_domain' => $pengajuan->nama_domain,
        'no_invoice' => 'INV-' . time(),
        'total' => 50000,
        'status' => 'belum_bayar',
        'expired_at' => now()->addDays(3)
    ]);

    return back()->with('success', 'Faktur berhasil dibuat');
}

    // ADMIN VIEW
    public function index()
    {
        $data = Pengajuan::with('faktur')
    ->where('status_pengajuan', 'disetujui') // hanya pengajuan yang sudah disetujui
    ->latest()
    ->get();
        return view('admin.faktur.index', compact('data'));
    }
}
