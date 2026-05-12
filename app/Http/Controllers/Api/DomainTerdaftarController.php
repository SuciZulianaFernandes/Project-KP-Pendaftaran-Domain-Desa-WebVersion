<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Aktivasi;
use App\Models\Pengajuan;
class DomainTerdaftarController extends Controller
{
    public function index()
    {
        $data = Aktivasi::with('pengajuan')
            ->latest()
            ->get();

        return response()->json([
            'success' => true,

            'data' => $data->map(function ($item) {

                return [

                    'id_pengajuan' =>
                        $item->pengajuan->id_pengajuan,

                    'nama_desa' =>
                        $item->pengajuan->nama_desa,

                    'nama_domain' =>
                        $item->pengajuan->nama_domain,

                    'status_akt' =>
                        $item->status_akt,

                    'tgl_aktivasi' =>
                        optional($item->tgl_aktivasi)
                            ?->format('Y-m-d'),

                    'masa_berlaku' =>
                        optional($item->masa_berlaku)
                            ?->format('Y-m-d'),
                ];
            })
        ]);
    }
}