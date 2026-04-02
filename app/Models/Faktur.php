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
    'expired_at'
];
}
