<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pesan;
use App\Models\Pengajuan;
use App\Models\User;
use App\Models\Faktur;

class PesanControllerApi extends Controller
{
    // ================= LIST NOTIF USER =================
    public function index(Request $request)
    {
        $userId = $request->user()->id_user;

        $data = Pesan::where('id_user', $userId)
            ->where('role_tujuan', 'desa')
            ->latest()
            ->get();

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    // ================= KONFIRMASI PEMBAYARAN (USER KLIK "YA") =================
    public function konfirmasiPembayaran(Request $request, $id)
    {
        $pengajuan = Pengajuan::findOrFail($id);

        // ================= TANDAI PESAN SUDAH DIBACA =================
        Pesan::where('id_pengajuan', $id)
            ->where('id_user', $request->user()->id_user)
            ->where('judul', 'Konfirmasi Pembayaran')
            ->update(['is_read' => 1]);

        // ================= CEK: SUDAH ADA FAKTUR ATAU BELUM =================
        $existing = Faktur::where('id_pengajuan', $pengajuan->id_pengajuan)->first();

        if ($existing) {
            return response()->json([
                'success' => false,
                'message' => 'Faktur sudah dibuat sebelumnya'
            ]);
        }

        // ================= GENERATE NO INVOICE =================
        $noInvoice = 'INV-' . date('Ymd') . '-' . rand(1000, 9999);

        // ================= BUAT FAKTUR =================
        $faktur = Faktur::create([
            'id_pengajuan' => $pengajuan->id_pengajuan,
            'nama_desa'    => $pengajuan->nama_desa,
            'nama_domain'  => $pengajuan->nama_domain,
            'no_invoice'   => $noInvoice,
            'total'        => 100000, // bisa kamu ubah
            'status'       => 'menunggu_pembayaran',
            'tanggal_konfirmasi' => now(),
            'expired_at'   => now()->addDays(3),
            'catatan'      => 'Silakan lakukan pembayaran sebelum jatuh tempo',
        ]);

        // ================= KIRIM PESAN KE USER =================
        Pesan::create([
            'id_user'       => $pengajuan->id_user,
            'id_pengajuan'  => $pengajuan->id_pengajuan,
            'judul'         => 'Invoice Pembayaran',
            'isi'           => 'Invoice telah dibuat dengan nomor ' . $faktur->no_invoice . '. Silakan lakukan pembayaran.',
            'role_tujuan'   => 'desa'
        ]);

        // ================= KIRIM PESAN KE ADMIN =================
        Pesan::create([
            'id_user'       => User::where('role', 'admin')->value('id_user'),
            'id_pengajuan'  => $pengajuan->id_pengajuan,
            'judul'         => 'User Siap Bayar',
            'isi'           => 'Desa ' . $pengajuan->nama_desa . ' siap melakukan pembayaran untuk domain ' . $pengajuan->nama_domain,
            'role_tujuan'   => 'admin'
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Faktur berhasil dibuat',
            'data' => $faktur
        ]);
    }
}