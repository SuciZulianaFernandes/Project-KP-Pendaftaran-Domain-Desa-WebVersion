<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pengajuan extends Model
{
    protected $table = 'pengajuan';
    protected $primaryKey = 'id_pengajuan';

    protected $fillable = [
        'id_desa',
        'nama_domain',
        'status_pengajuan',
        'catatan_umum',
        'tgl_pengajuan',
        'tgl_verifikasi'
    ];

    public $timestamps = true;
}