<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Aktivasi extends Model
{
    protected $table = 'aktivasi';
    protected $primaryKey = 'id_aktivasi';
    protected $fillable = ['id_pengajuan', 'status_akt', 'tgl_aktivasi', 'masa_berlaku'];
    
    protected $casts = [
        'tgl_aktivasi' => 'datetime',
        'masa_berlaku' => 'datetime',
    ];

    // Otomatis cek kadaluarsa atau tidak
    public function getIsKadaluarsaAttribute()
    {
        if (!$this->masa_berlaku) return false;
        return $this->masa_berlaku->isPast(); // true jika sudah lewat dari masa_berlaku
    }

    public function pengajuan()
    {
        return $this->belongsTo(Pengajuan::class, 'id_pengajuan', 'id_pengajuan');
    }
}