<?php

namespace App\Http\Controllers;

use App\Models\Faktur;
use App\Models\Pengajuan;

class FakturController extends Controller
{
    public function store($id)
    {
        $pengajuan = Pengajuan::findOrFail($id);

        if (Faktur::where('id_pengajuan', $id)->exists()) {
            return back()->with('error', 'Faktur sudah ada!');
        }

        // Format nomor invoice baru: INV/YYYYMMDD/XXXXX
        $date = now()->format('Ymd');
        $random = str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT);
        $noInvoice = "INV/{$date}/{$random}";

        Faktur::create([
            'id_pengajuan' => $pengajuan->id_pengajuan,
            'nama_desa' => $pengajuan->nama_desa,
            'nama_domain' => $pengajuan->nama_domain,
            'no_invoice' => $noInvoice,
            'total' => 50000,
            'status' => 'belum_bayar', // Status awal
            'expired_at' => now()->addDays(7) // Batas pembayaran 7 hari
        ]);

        return back()->with('success', 'Faktur berhasil dibuat');
    }

    public function index()
    {
        $data = Pengajuan::with('faktur')
            ->where('status_pengajuan', 'disetujui')
            ->latest()
            ->get();
        return view('admin.faktur.index', compact('data'));
    }
    
    public function show($id)
    {
        $faktur = Faktur::findOrFail($id);
        return view('admin.faktur.show', compact('faktur'));
    }
    
    public function kirimFaktur($id)
    {
        $faktur = Faktur::findOrFail($id);
        $faktur->status = 'dikirim'; // Status baru setelah dikirim
        $faktur->save();
        
        return back()->with('success', 'Faktur berhasil dikirim ke pihak desa.');
    }
}