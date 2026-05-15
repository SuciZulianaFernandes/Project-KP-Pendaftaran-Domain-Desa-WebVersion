<?php

namespace App\Services;

use App\Models\Pengajuan;
use Illuminate\Support\Facades\DB;

class PengajuanService
{
    public static function createPengajuan($request)
    {
        return Pengajuan::create([
            'id_user' => $request->id_user,
            'nama_domain' => strtolower($request->nama_domain),
            'status_pengajuan' => 'ditinjau',
            'tgl_pengajuan' => now(),

            'nama_desa' => $request->nama_desa,
            'telepon' => $request->telepon,
            'faksimili' => $request->faksimili,
            'alamat' => $request->alamat,
            'provinsi' => $request->provinsi,
            'kota_kabupaten' => $request->kota_kabupaten,
            'kecamatan' => $request->kecamatan,
            'desa_kelurahan' => $request->desa_kelurahan,
            'kode_pos' => $request->kode_pos,
        ]);
    }

    public static function getUserPengajuan($idUser)
    {
        return Pengajuan::with([
            'dokumenPersyaratan',
            'faktur' => function ($query) {
                $query->whereNotNull('no_invoice');
            }
        ])
        ->where('id_user', $idUser)
        ->latest()
        ->get();
    }
}