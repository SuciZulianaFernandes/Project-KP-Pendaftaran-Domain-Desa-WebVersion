<?php

namespace App\Services;

use App\Models\Pesan;
use App\Models\User;

class PesanService
{
    // =========================
    // KIRIM PESAN KE USER
    // =========================
    public static function toUser(
        $idUser,
        $idPengajuan,
        $judul,
        $isi
    ) {

        return Pesan::create([
            'id_user'       => $idUser,
            'id_pengajuan'  => $idPengajuan,
            'judul'         => $judul,
            'isi'           => $isi,
            'role_tujuan'   => 'desa',
            'is_read'       => 0,
        ]);
    }

    // =========================
    // KIRIM PESAN KE ADMIN
    // =========================
    public static function toAdmin(
        $idPengajuan,
        $judul,
        $isi
    ) {

        $admin = User::where('role', 'admin')->first();

        if (!$admin) {
            return null;
        }

        return Pesan::create([
            'id_user'       => $admin->id_user,
            'id_pengajuan'  => $idPengajuan,
            'judul'         => $judul,
            'isi'           => $isi,
            'role_tujuan'   => 'admin',
            'is_read'       => 0,
        ]);
    }
}