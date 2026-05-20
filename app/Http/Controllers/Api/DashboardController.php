<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pengajuan;
use App\Models\Aktivasi;

class DashboardController extends Controller
{
    public function index()
    {
        return response()->json([
            'success' => true,

            'data' => [

                // domain aktif
                'domain_aktif' =>
                    Aktivasi::where('status_akt', 'aktif')->count(),

                // total pengajuan
                'tahap_proses' =>
                    Pengajuan::where(
                        'status_pengajuan',
                        'diproses'
                    )->count(),

                // menunggu pembayaran
                'menunggu_aktivasi' =>
                    Pengajuan::where(
                        'status_pengajuan',
                        'menunggu_aktivasi'
                    )->count(),

                // perlu verifikasi
                'perlu_verifikasi' =>
                    Pengajuan::where(
                        'status_pengajuan',
                        'ditinjau'
                    )->count(),
            ]
        ]);
    }
}