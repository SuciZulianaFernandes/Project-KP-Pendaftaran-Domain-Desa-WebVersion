<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Pengajuan;

class Pesan extends Model
{
    protected $table = 'pesan';

    protected $fillable = [
        'id_user',
        'id_pengajuan',
        'role_tujuan',
        'judul',
        'isi',
        'is_read',
        'durasi_tahun'
    ];

    public function pengajuan()
    {
        return $this->belongsTo(
            Pengajuan::class,
            'id_pengajuan',
            'id_pengajuan'
        );
    }
}