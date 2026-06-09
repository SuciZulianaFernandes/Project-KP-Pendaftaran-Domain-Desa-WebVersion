<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pengajuan extends Model
{
    protected $table = 'pengajuan';
    protected $primaryKey = 'id_pengajuan';

    protected $fillable = [
        'id_user',
        'id_pengajuan',
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
        'kode_pos',
    ];

    public $timestamps = true;

    public function dokumenPersyaratan()
    {
        return $this->hasMany(
            DokumenPersyaratan::class,
            'id_pengajuan',
            'id_pengajuan'
        );
    }

    public function desa()
    {
        return $this->belongsTo(Desa::class, 'id_desa');
    }

    public function pesan()
    {
        return $this->hasMany(
            Pesan::class,
            'id_pengajuan',
            'id_pengajuan'
        );
    }

    // TETAP hasMany supaya halaman admin web tidak error
    public function faktur()
    {
        return $this->hasMany(
            Faktur::class,
            'id_pengajuan',
            'id_pengajuan'
        );
    }

    public function fakturBaru()
    {
        return $this->hasMany(
            Faktur::class,
            'id_pengajuan',
            'id_pengajuan'
        )->where('tipe', 'baru');
    }

    public function fakturPerpanjangan()
    {
        return $this->hasMany(
            Faktur::class,
            'id_pengajuan',
            'id_pengajuan'
        )->where('tipe', 'perpanjangan');
    }

    public function aktivasi()
    {
        return $this->hasOne(
            Aktivasi::class,
            'id_pengajuan',
            'id_pengajuan'
        )->latestOfMany('id_aktivasi'); // <-- TAMBAHKAN INI
    }
}