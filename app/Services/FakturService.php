<?php

namespace App\Services;

use App\Models\Faktur;

class FakturService
{
    public static function generateInvoice()
    {
        $date = now()->format('Ymd');

        $random = strtoupper(
            substr(md5(uniqid()), 0, 5)
        );

        return "INV-".$date."-".rand(10000,99999);
    }

    public static function createFaktur(
        $pengajuan,
        $tipe = 'baru'
    ) {
        // =========================
        // CEK FAKTUR BELUM BAYAR DULU
        // =========================
        // ✓ PENTING: Tambahkan filter 'tipe' agar hanya cek faktur dengan tipe SAMA
        $existing = Faktur::where('id_pengajuan', $pengajuan->id_pengajuan)
            ->where('tipe', $tipe)  // ← TAMBAH INI
            ->where('status', 'belum_bayar')
            ->latest()
            ->first();

        if ($existing) {
            return $existing; 
        }

        // =========================
        // BUAT FAKTUR BARU
        // =========================
        return Faktur::create([
            'id_pengajuan' => $pengajuan->id_pengajuan,
            'nama_desa'    => $pengajuan->nama_desa,
            'nama_domain'  => $pengajuan->nama_domain,
            'no_invoice'   => self::generateInvoice(),
            'total'        => 50000,
            'status'       => 'belum_bayar',
            'tipe'         => $tipe,
            'expired_at'   => now()->addDays(3),
        ]);
    }
}