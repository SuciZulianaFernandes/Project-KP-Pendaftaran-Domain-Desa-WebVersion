<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Concerns\HasUuidRouteKey;

class Faktur extends Model
{
    use HasFactory, HasUuidRouteKey;

    public $incrementing = true;

    protected $keyType = 'int';

    protected $fillable = [
        'id_pengajuan',
        'nama_desa',
        'nama_domain',
        'no_invoice',
        'total',
        'subtotal',    // ✅ TAMBAHKAN
    'ppn',         // ✅ TAMBAHKAN
        'status',
        'tipe',
        'tanggal_konfirmasi',
        'expired_at',
        'catatan',
        'bukti_pembayaran_path',
        'durasi_tahun'
    ];

    protected $casts = [
        'tanggal_konfirmasi' => 'datetime',
        'expired_at' => 'datetime',
    ];

    public function pengajuan()
    {
        return $this->belongsTo(Pengajuan::class, 'id_pengajuan', 'id_pengajuan');
    }
    
}