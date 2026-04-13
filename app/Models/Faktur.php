<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Faktur extends Model
{
  protected $fillable = [
    'id_pengajuan',
    'nama_desa',
    'nama_domain',
    'no_invoice',
    'total',
    'status',
    'tanggal_konfirmasi',
    'expired_at',
    'catatan',
];
protected $casts = [
        'tanggal_konfirmasi' => 'datetime',
        'expired_at' => 'datetime',
    ];
 public function pengajuan()
    {
        return $this->belongsTo(Pengajuan::class, 'id_pengajuan');
    }
}
