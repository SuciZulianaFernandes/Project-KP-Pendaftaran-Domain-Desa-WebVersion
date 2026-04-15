<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Aktivasi extends Model
{
    protected $table = 'aktivasi';
    protected $primaryKey = 'id_aktivasi';
    protected $fillable = ['id_pengajuan', 'status_akt', 'tgl_aktivasi'];
    protected $casts = [
        'tgl_aktivasi' => 'datetime',
    ];


    public function pengajuan()
    {
        return $this->belongsTo(Pengajuan::class, 'id_pengajuan', 'id_pengajuan');
    }
}
