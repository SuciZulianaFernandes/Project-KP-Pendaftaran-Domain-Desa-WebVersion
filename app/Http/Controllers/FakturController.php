<?php

namespace App\Http\Controllers;

use App\Models\Faktur;
use App\Models\Pengajuan;
use App\Models\Pesan;

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
    // Ambil Pengajuan beserta semua Faktur terkait, diurutkan dari terbaru
    $data = Pengajuan::with(['faktur' => function($query) {
        $query->latest();
    }])->latest()->paginate(10);

    return view('admin.faktur.index', compact('data'));
}
    
    public function show($id)
    {
        $faktur = Faktur::findOrFail($id);
        return view('admin.faktur.show', compact('faktur'));
    }

     public function storePerpanjangan($idPengajuan)
    {
        $pengajuan = Pengajuan::findOrFail($idPengajuan);

        // Cegah duplikat faktur perpanjangan yang masih aktif
        $fakturAktif = Faktur::where('id_pengajuan', $idPengajuan)
            ->where('tipe', 'perpanjangan')
            ->where('status', 'belum_bayar')
            ->exists();

        if ($fakturAktif) {
            return redirect()->route('admin.faktur.index')
                ->with('error', 'Faktur perpanjangan untuk domain ini masih aktif.');
        }

        // Tandai pesan admin sudah dibaca/diproses
        Pesan::where('id_pengajuan', $idPengajuan)
            ->where('judul', 'Permintaan Perpanjangan Domain')
            ->update(['is_read' => 1]);

        $date = now()->format('Ymd');
        $random = str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT);
        $noInvoice = "INV/{$date}/{$random}";

        // BUAT FAKTUR DENGAN TIPE PERPANJANGAN
        Faktur::create([
            'id_pengajuan' => $pengajuan->id_pengajuan,
            'nama_desa' => $pengajuan->nama_desa,
            'nama_domain' => $pengajuan->nama_domain,
            'no_invoice' => $noInvoice,
            'total' => 50000,
            'status' => 'belum_bayar',
            'tipe' => 'perpanjangan', // Sesuai enum di database
            'expired_at' => now()->addDays(7)
        ]);

        return redirect()->route('admin.faktur.index')
            ->with('success', 'Faktur perpanjangan berhasil dikirim ke desa.');
    }
    
    
}