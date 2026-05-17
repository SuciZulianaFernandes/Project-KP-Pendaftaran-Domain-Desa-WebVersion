<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pengajuan;
use App\Models\Pesan;
use App\Models\Faktur;
use App\Models\Aktivasi;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PerpanjanganApiController extends Controller
{

    // USER - LIST DOMAIN AKTIF
public function listDomain(Request $request)
{
    $data = Pengajuan::with([
        'aktivasi',
        'faktur' => function ($query) {
            $query->latest();
        }
    ])
    ->where('id_user', $request->id_user)
    ->where('status_pengajuan', 'aktif')
    ->latest()
    ->get();

    return response()->json([
        'success' => true,
        'data' => $data
    ]);
}
    // USER - AJUKAN PERPANJANGAN
    public function ajukan($id)
    {
        $pengajuan = Pengajuan::findOrFail($id);
        // CEK REQUEST SUDAH ADA?
        $cek = Pesan::where(
                'id_pengajuan',
                $id
            )
            ->where(
                'judul',
                'Permintaan Perpanjangan Domain'
            )
            ->where('is_read', 0)
            ->exists();

        if ($cek) {

            return response()->json([
                'success' => false,
                'message' =>
                    'Permintaan sudah dikirim'
            ]);
        }

        // PESAN KE ADMIN   
        Pesan::create([
            'id_user' => 1,
            'id_pengajuan' =>
                $pengajuan->id_pengajuan,

            'judul' =>
                'Permintaan Perpanjangan Domain',

            'isi' =>
                'Desa ' .
                $pengajuan->nama_desa .
                ' mengajukan perpanjangan domain ' .
                $pengajuan->nama_domain .
                '.desa.id',

            'role_tujuan' => 'admin',
            'is_read' => 0
        ]);

        return response()->json([
            'success' => true,
            'message' =>
                'Permintaan berhasil dikirim'
        ]);
    }

    // ADMIN - LIST REQUEST PERPANJANGAN
    public function adminList()
    {
        $data = Pesan::with('pengajuan')
            ->where(
                'judul',
                'Permintaan Perpanjangan Domain'
            )
            ->latest()
            ->get();

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    // ADMIN - GENERATE FAKTUR
    public function generateFaktur($id)
    {
        $pengajuan = Pengajuan::findOrFail($id);

        $date = now()->format('Ymd');

        $random = strtoupper(
            substr(md5(uniqid()), 0, 5)
        );

        $faktur = Faktur::create([

            'id_pengajuan' =>
                $pengajuan->id_pengajuan,

            'nama_desa' =>
                $pengajuan->nama_desa,

            'nama_domain' =>
                $pengajuan->nama_domain,

            'no_invoice' =>
                "INV/{$date}/{$random}",

            'total' => 50000,

            'status' =>
                'belum_bayar',

            'tipe' =>
                'perpanjangan',

            'expired_at' =>
                now()->addDays(7),
        ]);

        // PESAN KE USER
        Pesan::create([

            'id_user' =>
                $pengajuan->id_user,

            'id_pengajuan' =>
                $pengajuan->id_pengajuan,

            'judul' =>
                'Faktur Perpanjangan',

            'isi' =>
                'Invoice perpanjangan domain ' .
                $pengajuan->nama_domain .
                '.desa.id telah tersedia.',

            'role_tujuan' => 'desa',
            'is_read' => 0
        ]);

        return response()->json([
            'success' => true,
            'message' =>
                'Faktur berhasil dibuat',
            'data' => $faktur
        ]);
    }
    // ADMIN - AKTIVASI ULANG DOMAIN
    public function aktivasi($id)
    {
        $pengajuan = Pengajuan::findOrFail($id);

        $aktivasi =
            Aktivasi::where(
                'id_pengajuan',
                $pengajuan->id_pengajuan
            )->first();

        if (!$aktivasi) {

            return response()->json([
                'success' => false,
                'message' =>
                    'Data aktivasi tidak ditemukan'
            ]);
        }

        // TAMBAH 1 TAHUN DARI MASA BERLAKU LAMA
        $masaBaru = Carbon::parse(
            $aktivasi->masa_berlaku
        )->addDays(365);

        $aktivasi->update([
            'status_akt' => 'aktif',
            'masa_berlaku' => $masaBaru,
        ]);
        // PESAN KE USER
        Pesan::create([

            'id_user' =>
                $pengajuan->id_user,

            'id_pengajuan' =>
                $pengajuan->id_pengajuan,

            'judul' =>
                'Perpanjangan Berhasil',

            'isi' =>
                'Domain ' .
                $pengajuan->nama_domain .
                '.desa.id berhasil diperpanjang.',

            'role_tujuan' => 'desa',
            'is_read' => 0
        ]);

        return response()->json([
            'success' => true,
            'message' =>
                'Domain berhasil diperpanjang'
        ]);
    }
}