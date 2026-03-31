<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pengajuan extends Model
{
    protected $table = 'pengajuan';
    protected $primaryKey = 'id_pengajuan';

    protected $fillable = [
    'id_user',
    'nama_domain',
    'status_pengajuan',
    'catatan_umum',
    'tgl_pengajuan',
    'tgl_verifikasi',

    'nama_desa',
    'telepon',
    'faksimili',
    'alamat',
    'provinsi',
    'kota_kabupaten',
    'kecamatan',
    'desa_kelurahan',
    'kode_pos'
];

    public $timestamps = true;


    // 🔥 RELASI KE DOKUMEN
    public function dokumenPersyaratan()
    {
        return $this->hasMany(DokumenPersyaratan::class, 'id_pengajuan');
    }

    // (Opsional tapi bagus)
    public function desa()
    {
        return $this->belongsTo(Desa::class, 'id_desa');
    }
}