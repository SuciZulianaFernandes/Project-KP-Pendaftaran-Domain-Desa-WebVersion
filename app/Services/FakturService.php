<?php

namespace App\Services;

use App\Models\Faktur;

class FakturService
{
    public static function generateInvoice()
    {
        $date = now()->format('Ymd');
        $random = strtoupper(substr(md5(uniqid()), 0, 5));

        return "INV/{$date}/{$random}";
    }

    public static function createFaktur($pengajuan)
    {
        return Faktur::firstOrCreate(
            [
                'id_pengajuan' => $pengajuan->id_pengajuan,
            ],
            [
                'nama_desa'    => $pengajuan->nama_desa,
                'nama_domain'  => $pengajuan->nama_domain,
                'no_invoice'   => self::generateInvoice(),
                'total'        => 50000,
                'status'       => 'belum_bayar',
                'tipe'         => 'baru',
                'expired_at'   => now()->addDays(7),
            ]
        );
    }
}