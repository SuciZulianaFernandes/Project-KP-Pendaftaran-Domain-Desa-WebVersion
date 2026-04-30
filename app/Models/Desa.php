<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Desa extends Model
{
    use HasFactory;

    protected $table = 'desa';
    protected $primaryKey = 'id_desa';

    public $incrementing = true;
    public $timestamps = true;

    protected $fillable = [
        'id_user',
        'nama_desa',
        'nama_kepala_desa',
        'nip_kepala_desa',
        'no_hp_kepala_desa',
        'klasifikasi_instansi',
        'telepon',
        'faksimili',
        'alamat',
        'provinsi',
        'kota_kabupaten',
        'kecamatan',
        'desa_kelurahan',
        'kode_pos',
        'id_prov',
        'id_kab',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    public function pengajuans()
    {
        return $this->hasMany(Pengajuan::class, 'id_desa', 'id_desa');
    }
}