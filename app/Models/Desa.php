<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Desa extends Model
{
    use HasFactory;

    /**
     * Nama tabel yang digunakan oleh model.
     * Sesuaikan jika nama tabel Anda bukan 'desa'.
     */
    protected $table = 'desa';

    /**
     * Primary key dari tabel.
     */
    protected $primaryKey = 'id_desa';


    /**
     * Menonaktifkan incrementing integer jika primary key bukan tipe int auto-increment.
     * Karena 'bigint' biasanya auto-increment, kita biarkan false.
     */
    public $incrementing = true;

    /**
     * Kolom-kolom yang dapat diisi secara massal (mass-assignable).
     * Ini SANGAT PENTING untuk keamanan dan agar Desa::create() berjalan.
     */
    protected $fillable = [
        'id_user',
        'nama_desa',
        'nama_kepala_desa',
        'nip_kepala_desa',
        'klasifikasi_instansi',
        'telepon',
        'faksimili',
        'alamat',
        'provinsi',
        'kota_kabupaten',
        'kecamatan',
        'desa_kelurahan',
        'kode_pos',
    ];

    /**
     * Relasi: Desa dimiliki oleh satu User.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    /**
     * Relasi: Desa bisa memiliki banyak Pengajuan.
     */
    public function pengajuans()
    {
        return $this->hasMany(Pengajuan::class, 'id_desa', 'id_desa');
    }
}