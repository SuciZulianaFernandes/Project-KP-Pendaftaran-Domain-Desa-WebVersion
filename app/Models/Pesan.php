<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pesan extends Model
{
protected $table = 'pesan';

    protected $fillable = [
    'id_user',
    'id_pengajuan',
    'role_tujuan',
    'judul',
    'isi',
    'is_read'
];
}
