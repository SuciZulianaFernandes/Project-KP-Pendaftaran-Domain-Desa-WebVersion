<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Pengajuan;
use App\Models\Faktur;

use App\Services\PengajuanService;
use App\Services\FakturService;
use App\Services\UploadService;
use App\Services\PesanService;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class PengajuanApiController extends Controller
{
    // =========================
    // CEK DOMAIN
    // =========================
    public function checkDomain(Request $request)
    {
        $domain = strtolower($request->nama_domain);

        $exists = Pengajuan::where(
            'nama_domain',
            $domain
        )->exists();

        return response()->json([
            'success' => true,
            'available' => !$exists,
        ]);
    }

    // =========================
    // SUBMIT PENGAJUAN
    // =========================
    public function submit(Request $request)
    {
        DB::beginTransaction();

        try {

            $pengajuan =
                PengajuanService::createPengajuan($request);

            $files = [
                'surat_permohonan',
                'perda_pembentukan_desa',
                'surat_kuasa',
                'surat_penunjukan_pejabat',
                'ktp_asn_pejabat',
            ];

            foreach ($files as $jenis) {

                $file = $request->file($jenis);

                $path = UploadService::uploadDokumen(
                    $file,
                    'pengajuan/dokumen',
                    $jenis
                );

                $pengajuan
                    ->dokumenPersyaratan()
                    ->create([
                        'jenis_dokumen' => $jenis,
                        'nama_file' => basename($path),
                        'path_file' => $path,
                    ]);
            }

            PesanService::toAdmin(
                $pengajuan->id_pengajuan,
                'Pengajuan Baru',
                'Pengajuan domain baru dari desa '
                . $pengajuan->nama_desa
            );

            DB::commit();
            // ================= NOTIF ADMIN =================
            PesanService::toAdmin(
                $pengajuan->id_pengajuan,
                'Pengajuan Baru',
                $pengajuan->nama_desa .
                ' mengajukan domain ' .
                $pengajuan->nama_domain
            );

            return response()->json([
                'success' => true,
                'message' => 'Pengajuan berhasil dikirim',
                'data' => $pengajuan
            ]);

        } catch (\Exception $e) {

            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    // =========================
    // DATA USER
    // =========================
    public function getPengajuanUser(Request $request)
{
    try {

        $user = auth()->user();

        $data = Pengajuan::with([
            'faktur' => function ($query) {
                $query->latest();
            }
        ])
        ->where('id_user', $user->id_user)
        ->latest()
        ->get();

        $data = $data->map(function ($item) {

            $faktur = $item->faktur->first();

            $item->no_invoice =
                $faktur->no_invoice ?? '';

            $item->faktur_status =
                $faktur->status ?? '';

            $item->total_faktur =
                $faktur->total ?? '';

            $item->bukti_pembayaran_url =
                $faktur && $faktur->bukti_pembayaran_path
                    ? asset('storage/' . $faktur->bukti_pembayaran_path)
                    : '';

            $item->tipe_faktur =
                $faktur->tipe ?? '';

            return $item;
        });

        return response()->json([
            'success' => true,
            'data' => $data,
        ]);

    } catch (\Exception $e) {

        return response()->json([
            'success' => false,
            'message' => $e->getMessage(),
        ], 500);
    }
}

    // =========================
    // RIWAYAT
    // =========================
    public function riwayat(Request $request)
    {
        return $this->getPengajuanUser($request);
    }

    // =========================
    // LANJUTKAN PEMBAYARAN
    // =========================
public function lanjutkanPembayaran($id)
{
    try {
        $pengajuan = Pengajuan::findOrFail($id);

        // ✓ Pastikan tipe 'baru' untuk pengajuan awal
        $faktur = FakturService::createFaktur($pengajuan, 'baru');

        PesanService::toUser(
            $pengajuan->id_user,
            $pengajuan->id_pengajuan,
            'Faktur Baru',
            'Invoice pembayaran domain ' . $pengajuan->nama_domain . ' telah tersedia.'
        );

        PesanService::toAdmin(
            $pengajuan->id_pengajuan,
            'Faktur Baru',
            'Faktur baru dibuat untuk domain ' . $pengajuan->nama_domain
        );

        return response()->json([
            'success' => true,
            'data' => $faktur
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => $e->getMessage()
        ], 500);
    }
}

    // =========================
    // UPLOAD BUKTI
    // =========================
    public function uploadBuktiPembayaran(Request $request, $id)
    {
        DB::beginTransaction();

        try {
            $user = auth()->user();
            $pengajuan = Pengajuan::findOrFail($id);

            // ✅ Validasi: Hanya pemilik yang bisa upload
            if ($pengajuan->id_user !== $user->id_user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda tidak memiliki izin untuk upload bukti'
                ], 403);
            }

            // ✅ PENTING: Hanya ambil faktur PENGAJUAN AWAL (tipe='baru')
            $faktur = Faktur::where('id_pengajuan', $pengajuan->id_pengajuan)
                ->where('tipe', 'baru')  // ← TAMBAH INI
                ->where('status', 'belum_bayar')
                ->latest()
                ->first();

            if (!$faktur) {
                return response()->json([
                    'success' => false,
                    'message' => 'Faktur pengajuan tidak ditemukan'
                ], 404);
            }

            // ✅ Validasi file
            $request->validate([
                'bukti_pembayaran' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120'
            ]);

            if (!$request->hasFile('bukti_pembayaran')) {
                return response()->json([
                    'success' => false,
                    'message' => 'File bukti pembayaran diperlukan'
                ], 422);
            }

            if ($faktur->bukti_pembayaran_path) {
                UploadService::deleteFile($faktur->bukti_pembayaran_path);
            }

            $path = UploadService::uploadDokumen(
                $request->file('bukti_pembayaran'),
                'bukti_pembayaran',
                'bukti_pembayaran'
            );

            $faktur->update([
                'bukti_pembayaran_path' => $path,
                'status' => 'sudah_bayar',
                'tanggal_konfirmasi' => now(),
            ]);

            $pengajuan->update([
                'status_pengajuan' => 'menunggu_aktivasi'
            ]);

            PesanService::toAdmin(
                $pengajuan->id_pengajuan,
                'Pembayaran Baru',
                'Desa ' . $pengajuan->nama_desa .
                ' telah upload bukti pembayaran'
            );

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Bukti pembayaran berhasil dikirim',
                'data' => $faktur
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('uploadBuktiPembayaran error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
    // ================= PENGAJUAN - GET DETAIL FAKTUR =================
    public function detailFakturPengajuan($id)
    {
        $user = auth()->user();
        $pengajuan = Pengajuan::findOrFail($id);

        // ✅ Validasi: Hanya pemilik
        if ($pengajuan->id_user !== $user->id_user) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized',
                'data' => null
            ], 403);
        }

        // ✅ HANYA ambil faktur PENGAJUAN AWAL (tipe='baru')
        $faktur = Faktur::where('id_pengajuan', $id)
            ->where('tipe', 'baru')  // ← PENTING
            ->latest()
            ->first();

        if (!$faktur) {
            return response()->json([
                'success' => false,
                'message' => 'Faktur pengajuan tidak ditemukan',
                'data' => null
            ]);
        }

        return response()->json([
            'success' => true,
            'data' => $faktur
        ]);
    }
}