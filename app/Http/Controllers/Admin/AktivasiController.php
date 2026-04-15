<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pengajuan;
use App\Models\Faktur;
use App\Models\Aktivasi; // Pastikan model ini ada
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AktivasiController extends Controller
{
    
    public function aktivasi($id)
    {
        $pengajuan = Pengajuan::findOrFail($id);
        
        // Cari faktur terkait
        $faktur = Faktur::where('id_pengajuan', $id)->first();
        

        if (!$faktur) {
            return back()->with('error', 'Data Faktur tidak ditemukan.');
        }

        // Validasi: Pastikan desa sudah upload bukti
        if ($faktur->status !== 'sudah_bayar') {
            return back()->with('error', 'Gagal! Desa belum mengirim bukti pembayaran.');
        }

        DB::beginTransaction();
        try {
            // 1. Update Status Pengajuan jadi AKTIF
            $pengajuan->status_pengajuan = 'aktif';
            $pengajuan->save();

            // 2. Update Status Faktur jadi LUNAS
            $faktur->status = 'sudah_bayar';
            $faktur->save();

            // 3. Catat ke Tabel Aktivasi (Sesuai permintaan No 4 sebelumnya)
            Aktivasi::create([
                'id_pengajuan' => $pengajuan->id_pengajuan,
                'status_akt' => 'aktif',
                'tgl_aktivasi' => now(),
            ]);

            DB::commit();
            return back()->with('success', 'Domain berhasil diaktifkan.');
            
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan sistem.');
        }
    }

    // List Admin (Daftar Domain Terdaftar)
    public function adminDaftarAktif()
    {
        $data = Pengajuan::where('status_pengajuan', 'aktif')
            ->with('faktur', 'aktivasi')
            ->latest()
            ->get();

        return view('admin.domain_terdaftar', compact('data'));
    }

    // List Desa (Menu Perpanjang)
    public function desaPerpanjang()
    {
        $data = Pengajuan::where('id_user', auth()->id())
            ->where('status_pengajuan', 'aktif')
            ->with('faktur', 'aktivasi')
            ->latest()
            ->get();

        return view('desa.perpanjang', compact('data'));
    }
}