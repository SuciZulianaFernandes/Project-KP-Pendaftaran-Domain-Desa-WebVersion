<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Concerns\HasUuidRouteKey;

class DokumenPersyaratan extends Model
{
    use HasUuidRouteKey;

    protected $table = 'dokumen_persyaratan';
    protected $primaryKey = 'id_dokumen';

    protected $fillable = [
        'id_pengajuan',
        'jenis_dokumen',
        'nama_file',
        'path_file'
    ];

    public $timestamps = true;

    // 🔥 RELASI KE PENGAJUAN
    public function pengajuan()
    {
        return $this->belongsTo(Pengajuan::class, 'id_pengajuan');
    }
}