<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pengajuan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PengajuanApiController extends Controller
{
    // ================= LIST =================
    public function index(Request $request)
    {
        $query = Pengajuan::query();

        if ($request->status) {
            $query->where('status_pengajuan', $request->status);
        }

        $data = $query->latest()->get();

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    // ================= DETAIL =================
    public function show($id)
    {
        $pengajuan = Pengajuan::with('dokumenPersyaratan')->find($id);

        if (!$pengajuan) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan'
            ]);
        }

      
        $dokumen = [];

        foreach ($pengajuan->dokumenPersyaratan as $doc) {
            $dokumen[$doc->jenis_dokumen] = Storage::url($doc->path_file);
        }

        return response()->json([
            'success' => true,
            'data' => [
                ...$pengajuan->toArray(),
                'dokumen_urls' => $dokumen
            ]
        ]);
    }

    public function verifikasi(Request $request, $id)
    {
        $pengajuan = Pengajuan::findOrFail($id);

        $status = $request->status;
        $catatan = $request->catatan;

        // update status
        $pengajuan->status_pengajuan = $status;
        $pengajuan->catatan_umum = $catatan;
        $pengajuan->tgl_verifikasi = now();

        $pengajuan->save();

        // =========================
        // PERLU PERBAIKAN
        // =========================
        if ($status == 'perlu_perbaikan') {

            \App\Models\Pesan::create([
                'id_user'       => $pengajuan->id_user,
                'id_pengajuan'  => $pengajuan->id_pengajuan,
                'judul'         => 'Perlu Perbaikan',
                'isi'           => 'Pengajuan domain '
                                    .$pengajuan->nama_domain.
                                    '.desa.id perlu perbaikan. Catatan: '
                                    .$catatan,
                'role_tujuan'   => 'desa'
            ]);
        }

        // =========================
        // DIPROSES
        // AUTO BUAT FAKTUR
        // =========================
        if ($status == 'diproses') {

            // buat faktur otomatis
            \App\Models\Faktur::firstOrCreate(
                [
                    'id_pengajuan' => $pengajuan->id_pengajuan,
                ],
                [
                    'nama_desa'    => $pengajuan->nama_desa,
                    'nama_domain'  => $pengajuan->nama_domain,
                    'no_invoice' => "INV/{$date}/{$random}",
                    'total'        => 50000,
                    'status'       => 'belum_bayar',
                    'tipe'         => 'baru',
                    'expired_at'   => now()->addDays(7),
                ]
            );

            // notif ke user
            \App\Models\Pesan::create([
                'id_user'       => $pengajuan->id_user,
                'id_pengajuan'  => $pengajuan->id_pengajuan,
                'judul'         => 'Faktur Baru',
                'isi'           => 'Faktur pembayaran domain '
                                    .$pengajuan->nama_domain.
                                    '.desa.id telah tersedia.',
                'role_tujuan'   => 'desa'
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Verifikasi berhasil'
        ]);
    }
    public function aktivasi($id)
{
    $pengajuan = Pengajuan::findOrFail($id);

    $pengajuan->status_pengajuan = 'aktif';
    $pengajuan->save();

    // kirim notifikasi
    \App\Models\Pesan::create([
        'id_user'       => $pengajuan->id_user,
        'id_pengajuan'  => $pengajuan->id_pengajuan,
        'judul'         => 'Domain Aktif',
        'isi'           => 'Domain '
                            .$pengajuan->nama_domain.
                            '.desa.id telah aktif.',
        'role_tujuan'   => 'desa'
    ]);

    return response()->json([
        'success' => true,
        'message' => 'Domain berhasil diaktifkan'
    ]);
}
}