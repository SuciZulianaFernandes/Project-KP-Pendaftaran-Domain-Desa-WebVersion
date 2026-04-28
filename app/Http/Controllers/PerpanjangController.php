<?php

namespace App\Http\Controllers;

use App\Models\Pengajuan;
use App\Models\Faktur;
use App\Models\Pesan;
use App\Models\User;
use Illuminate\Http\Request;

class PerpanjangController extends Controller
{
    public function index()
    {
        $data = Pengajuan::where('id_user', auth()->id())
            ->where('status_pengajuan', 'aktif')
            ->with('faktur', 'aktivasi')
            ->latest()
            ->get();

        return view('desa.perpanjang', compact('data'));
    }

    public function requestPerpanjang($id)
    {
        $pengajuan = Pengajuan::where('id_user', auth()->id())->findOrFail($id);

        // 1. Cek apakah SUDAH ADA faktur perpanjangan yang belum dibayar
        $fakturPending = Faktur::where('id_pengajuan', $id)
            ->where('tipe', 'perpanjangan')
            ->where('status', 'belum_bayar')
            ->exists();

        if ($fakturPending) {
            return back()->with('error', 'Anda sudah memiliki faktur perpanjangan yang belum dibayar.');
        }

        // 2. Cek masa berlaku dari aktivasi terakhir
        $aktivasiTerakhir = $pengajuan->aktivasi()->latest()->first();
        if (!$aktivasiTerakhir || !$aktivasiTerakhir->masa_berlaku) {
            return back()->with('error', 'Data masa berlaku tidak ditemukan.');
        }

        // 3. Validasi Waktu (TES: <= 2 menit)
        $selisihMenit = now()->diffInMinutes(\Carbon\Carbon::parse($aktivasiTerakhir->masa_berlaku), false);
        if ($selisihMenit > 2) {
            return back()->with('error', 'Domain belum memasuki masa perpanjangan (sisa > 2 menit).');
        }

        // 4. Kirim pesan ke Admin
        Pesan::create([
            'id_user'       => User::where('role', 'admin')->value('id_user'),
            'id_pengajuan'  => $pengajuan->id_pengajuan,
            'judul'         => 'Permintaan Faktur Perpanjangan',
            'isi'           => 'Desa ' . $pengajuan->nama_desa . ' meminta faktur perpanjangan untuk domain ' . $pengajuan->nama_domain . '.desa.id.',
            'role_tujuan'   => 'admin'
        ]);

        return back()->with('success', 'Permintaan perpanjangan berhasil dikirim ke Admin.');
    }
}