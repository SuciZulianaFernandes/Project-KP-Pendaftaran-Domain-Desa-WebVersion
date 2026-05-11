<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pengajuan;
use App\Models\Pesan;
use App\Models\Aktivasi;
use App\Models\Faktur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class PengajuanApiController extends Controller
{
    // =========================
    // LIST PENGAJUAN
    // =========================
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

    // =========================
    // DETAIL PENGAJUAN
    // =========================
    public function show($id)
    {
        $pengajuan = Pengajuan::with([
            'dokumenPersyaratan',
            'faktur'
        ])->find($id);

        if (!$pengajuan) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan'
            ], 404);
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

    // =========================
    // VERIFIKASI PENGAJUAN
    // =========================
    public function verifikasi(Request $request, $id)
    {
        $request->validate([
            'status' => 'required',
            'catatan' => 'nullable|string'
        ]);

        $pengajuan = Pengajuan::findOrFail($id);

        if ($pengajuan->status_pengajuan == $request->status) {
            return response()->json([
                'success' => false,
                'message' => 'Status sudah sama'
            ], 400);
        }

        $status = $request->status;
        $catatan = $request->catatan;

        $pengajuan->status_pengajuan = $status;
        $pengajuan->catatan_umum = $catatan;
        $pengajuan->tgl_verifikasi = now();
        $pengajuan->save();

        // =========================
        // PERLU PERBAIKAN
        // =========================
        if ($status == 'perlu_perbaikan') {
            Pesan::create([
                'id_user'      => $pengajuan->id_user,
                'id_pengajuan' => $pengajuan->id_pengajuan,
                'judul'        => 'Perlu Perbaikan',
                'isi'          => 'Pengajuan domain '
                                    . $pengajuan->nama_domain .
                                    '.desa.id memerlukan perbaikan. '
                                    . 'Catatan: ' . $catatan,
                'role_tujuan'  => 'desa'
            ]);
        }

        // =========================
        // DIPROSES
        // =========================
        if ($status == 'diproses') {
            Pesan::create([
                'id_user'      => $pengajuan->id_user,
                'id_pengajuan' => $pengajuan->id_pengajuan,
                'judul'        => 'Konfirmasi Pembayaran',
                'isi'          => 'Pengajuan domain '
                                    . $pengajuan->nama_domain .
                                    '.desa.id telah disetujui. '
                                    . 'Apakah Anda ingin melanjutkan proses pembayaran? '
                                    . 'Jika ya, sistem akan membuatkan invoice pembayaran.',
                'role_tujuan'  => 'desa'
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Verifikasi berhasil'
        ]);
    }

    // =========================
    // LIST PEMBAYARAN
    // =========================
    public function pembayaran()
    {
        /**
         * LOGIKA DIPERBAIKI:
         * Saat user sudah upload bukti bayar,
         * otomatis ubah status pengajuan jadi menunggu_aktivasi
         */

        $fakturs = Faktur::with('pengajuan')
            ->where('status', 'sudah_bayar')
            ->latest()
            ->get();

        foreach ($fakturs as $faktur) {
            if ($faktur->pengajuan &&
                $faktur->pengajuan->status_pengajuan == 'diproses') {

                $faktur->pengajuan->status_pengajuan = 'menunggu_aktivasi';
                $faktur->pengajuan->save();
            }
        }

        return response()->json([
            'success' => true,
            'data' => $fakturs
        ]);
    }

    // =========================
    // VERIFIKASI PEMBAYARAN
    // =========================
    public function verifikasiPembayaran($id)
    {
        $faktur = Faktur::findOrFail($id);

        if ($faktur->tanggal_konfirmasi != null) {
            return response()->json([
                'success' => false,
                'message' => 'Pembayaran sudah diverifikasi'
            ], 400);
        }

        $faktur->tanggal_konfirmasi = now();
        $faktur->save();

        $pengajuan = Pengajuan::findOrFail($faktur->id_pengajuan);

        $pengajuan->status_pengajuan = 'menunggu_aktivasi';
        $pengajuan->save();

        Pesan::create([
            'id_user'      => $pengajuan->id_user,
            'id_pengajuan' => $pengajuan->id_pengajuan,
            'judul'        => 'Pembayaran Berhasil Diverifikasi',
            'isi'          => 'Pembayaran domain '
                                . $pengajuan->nama_domain .
                                '.desa.id telah berhasil diverifikasi. '
                                . 'Domain siap untuk diaktivasi.',
            'role_tujuan'  => 'desa'
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Pembayaran berhasil diverifikasi'
        ]);
    }

    // =========================
    // AKTIVASI DOMAIN
    // =========================
    public function aktivasi($id)
    {
        $pengajuan = Pengajuan::findOrFail($id);

        if ($pengajuan->status_pengajuan != 'menunggu_aktivasi') {
            return response()->json([
                'success' => false,
                'message' => 'Domain belum siap diaktivasi'
            ], 400);
        }

        DB::beginTransaction();

        try {

            // update pengajuan
            $pengajuan->status_pengajuan = 'aktif';
            $pengajuan->save();

            // cek sudah ada aktivasi?
            $cek = Aktivasi::where(
                'id_pengajuan',
                $pengajuan->id_pengajuan
            )->first();

            $masaBerlaku = now()->addDays(365);

            if ($cek) {

                $cek->status_akt = 'aktif';
                $cek->tgl_aktivasi = now();
                $cek->masa_berlaku = $masaBerlaku;
                $cek->save();

            } else {

                Aktivasi::create([
                    'id_pengajuan' => $pengajuan->id_pengajuan,
                    'status_akt'   => 'aktif',
                    'tgl_aktivasi' => now(),
                    'masa_berlaku' => $masaBerlaku,
                ]);
            }

            // notif user
            Pesan::create([
                'id_user'      => $pengajuan->id_user,
                'id_pengajuan' => $pengajuan->id_pengajuan,
                'judul'        => 'Domain Aktif',
                'isi'          => 'Domain '
                                    .$pengajuan->nama_domain.
                                    '.desa.id berhasil diaktifkan.',
                'role_tujuan'  => 'desa'
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Domain berhasil diaktifkan'
            ]);

        } catch (\Exception $e) {

            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Gagal aktivasi domain'
            ], 500);
        }
    }
}