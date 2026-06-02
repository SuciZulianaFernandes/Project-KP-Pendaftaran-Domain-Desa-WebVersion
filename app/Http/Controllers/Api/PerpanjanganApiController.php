<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pengajuan;
use App\Models\Pesan;
use App\Models\Faktur;
use App\Models\Aktivasi;
use App\Models\User;
use App\Services\FakturService;
use App\Services\UploadService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class PerpanjanganApiController extends Controller
{
    public function listDomain(Request $request)
    {
        $user = auth()->user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 401);
        }

        // UPDATE STATUS KADALUARSA
        Aktivasi::where('masa_berlaku', '<', now())
            ->where('status_akt', 'aktif')
            ->update([
                'status_akt' => 'kadaluarsa'
            ]);

        // AMBIL DATA DOMAIN USER
        $data = Pengajuan::with('aktivasi')
            ->where('id_user', $user->id_user)
            ->where('status_pengajuan', 'aktif')
            ->latest()
            ->get();

        // FORMAT DATA
        $data = $data->map(function ($pengajuan) {
            $aktivasi = $pengajuan->aktivasi;
            $pengajuan->aktivasi_terakhir = $aktivasi;

            $pengajuan->status_perpanjangan = 'tidak_aktif';
            $pengajuan->hari_sisa = 0;
            $pengajuan->menunggu_faktur = false;
            $pengajuan->ada_faktur_belum_bayar = false;

            if ($aktivasi) {
                $masaBerlaku = Carbon::parse($aktivasi->masa_berlaku);
                $hariSisa = now()->diffInDays($masaBerlaku, false);

                if ($hariSisa <= 0) {
                    $pengajuan->status_perpanjangan = 'kadaluarsa';
                    $pengajuan->hari_sisa = 0;
                } elseif ($hariSisa <= 60) {
                    $pengajuan->status_perpanjangan = 'akan_kadaluarsa';
                    $pengajuan->hari_sisa = $hariSisa;
                } else {
                    $pengajuan->status_perpanjangan = 'aktif';
                    $pengajuan->hari_sisa = $hariSisa;
                }
            }

            // CEK FAKTUR YANG BELUM SELESAI
            $fakturPerpanjangan = Faktur::where('id_pengajuan', $pengajuan->id_pengajuan)
                ->where('tipe', 'perpanjangan')
                ->latest()
                ->first();

            if ($fakturPerpanjangan) {
                if ($fakturPerpanjangan->status == 'belum_bayar') {
                    $pengajuan->ada_faktur_belum_bayar = true;
                } elseif ($fakturPerpanjangan->status == 'sudah_bayar' && $pengajuan->status_perpanjangan != 'aktif') {
                    $pengajuan->ada_faktur_belum_bayar = true;
                }
            }

            // CEK MENUNGGU FAKTUR
            $latestPesan = Pesan::where('id_pengajuan', $pengajuan->id_pengajuan)
                ->where('judul', 'Permintaan Perpanjangan Domain')
                ->latest()
                ->first();

            if ($latestPesan) {
                $fakturAda = Faktur::where('id_pengajuan', $pengajuan->id_pengajuan)
                    ->where('tipe', 'perpanjangan')
                    ->where('created_at', '>=', $latestPesan->created_at)
                    ->exists();

                if (!$fakturAda) {
                    $pengajuan->menunggu_faktur = true;
                }
            }

            return $pengajuan;
        });

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    // =========================
    // USER - AJUKAN PERPANJANGAN
    // =========================
    public function ajukan($id)
    {
        $user = auth()->user();
        
        $pengajuan = Pengajuan::where('id_pengajuan', $id)
            ->where('id_user', $user->id_user)
            ->firstOrFail();

        $latestPesan = Pesan::where('id_pengajuan', $id)
            ->where('judul', 'Permintaan Perpanjangan Domain')
            ->latest()
            ->first();

        if ($latestPesan && $latestPesan->is_read == 0) {
            $fakturRespons = Faktur::where('id_pengajuan', $id)
                ->where('tipe', 'perpanjangan')
                ->where('created_at', '>', $latestPesan->created_at)
                ->exists();

            if (!$fakturRespons) {
                return response()->json([
                    'success' => false,
                    'message' => 'Permintaan perpanjangan sebelumnya masih diproses admin. Silakan tunggu.'
                ]);
            } else {
                $latestPesan->is_read = 1;
                $latestPesan->save();
            }
        }

        $adminId = User::where('role', 'admin')->value('id_user');
        if (!$adminId) {
            return response()->json([
                'success' => false,
                'message' => 'Admin tidak ditemukan'
            ]);
        }

        Pesan::create([
            'id_user'       => $adminId,
            'id_pengajuan'  => $pengajuan->id_pengajuan,
            'judul'         => 'Permintaan Perpanjangan Domain',
            'isi'           => 'Desa ' . $pengajuan->nama_desa . ' ingin melakukan perpanjangan domain ' . $pengajuan->nama_domain . ', kirimkan faktur.',
            'role_tujuan'   => 'admin',
            'is_read'       => 0
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Permintaan perpanjangan berhasil dikirim ke admin'
        ]);
    }

    // =========================
    // ADMIN - LIST REQUEST PERPANJANGAN
    // =========================
    public function adminList()
    {
        ///PERBAIKAN: Gunakan whereIn untuk memanggil 2 jenis pesan yang belum dibaca
        $data = Pesan::with('pengajuan')
            ->whereIn('judul', ['Permintaan Perpanjangan Domain', 'Bukti Pembayaran Perpanjangan'])
            ->where('is_read', 0)
            ->latest()
            ->get();

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    // =========================
    // ADMIN - BUAT FAKTUR PERPANJANGAN
    // =========================
    public function buatFaktur($id)
    {
        $user = auth()->user();
        if ($user->role !== 'admin') {
            return response()->json([
                'success' => false,
                'message' => 'Hanya admin yang dapat membuat faktur'
            ], 403);
        }

        $pengajuan = Pengajuan::findOrFail($id);

        // Cek apakah ada faktur perpanjangan yang menggantung
        $fakturTerakhir = Faktur::where('id_pengajuan', $pengajuan->id_pengajuan)
            ->where('tipe', 'perpanjangan')
            ->latest()
            ->first();

        if ($fakturTerakhir && $fakturTerakhir->status == 'belum_bayar') {
            return response()->json([
                'success' => false,
                'message' => 'Faktur perpanjangan sebelumnya sudah ada dan belum dibayar.'
            ]);
        }

        $faktur = FakturService::createFaktur($pengajuan, 'perpanjangan');

        Pesan::where('id_pengajuan', $id)
            ->where('judul', 'Permintaan Perpanjangan Domain')
            ->update(['is_read' => 1]);

        Pesan::create([
            'id_user'       => $pengajuan->id_user,
            'id_pengajuan'  => $pengajuan->id_pengajuan,
            'judul'         => 'Faktur Perpanjangan',
            'isi'           => 'Faktur perpanjangan domain ' . $pengajuan->nama_domain . '.desa.id telah tersedia. Silakan lakukan pembayaran dan upload bukti.',
            'role_tujuan'   => 'desa',
            'is_read'       => 0
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Faktur berhasil dibuat',
            'data' => $faktur
        ]);
    }

    // =========================
    // USER - UPLOAD BUKTI PEMBAYARAN
    // =========================
    public function uploadBukti(Request $request, $id)
    {
        $user = auth()->user();
        $pengajuan = Pengajuan::findOrFail($id);
        if ($pengajuan->id_user !== $user->id_user) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki izin untuk upload bukti domain ini'
            ], 403);
        }

        // Cari faktur perpanjangan terakhir
        $faktur = Faktur::where('id_pengajuan', $pengajuan->id_pengajuan)
            ->where('tipe', 'perpanjangan')
            ->where('status', 'belum_bayar')
            ->latest()
            ->first();

        if (!$faktur) {
            return response()->json([
                'success' => false,
                'message' => 'Faktur perpanjangan tidak ditemukan atau sudah dibayar.'
            ], 404);
        }

        $request->validate([
            'bukti_pembayaran' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120'
        ]);

        if (!$request->hasFile('bukti_pembayaran')) {
            return response()->json([
                'success' => false,
                'message' => 'File bukti pembayaran diperlukan'
            ], 422);
        }

        try {
            // Hapus file lama
            if ($faktur->bukti_pembayaran_path) {
                UploadService::deleteFile($faktur->bukti_pembayaran_path);
            }

            // Upload file baru
            $path = UploadService::uploadDokumen(
                $request->file('bukti_pembayaran'),
                'bukti_pembayaran',
                'bukti_pembayaran'
            );

            // Update faktur
            $faktur->update([
                'bukti_pembayaran_path' => $path,
                'status' => 'sudah_bayar',
                'tanggal_konfirmasi' => now(),
            ]);

            // Notif admin
            Pesan::create([
                'id_user'       => 1,
                'id_pengajuan'  => $pengajuan->id_pengajuan,
                'judul'         => 'Bukti Pembayaran Perpanjangan',
                'isi'           => 'Desa ' . $pengajuan->nama_desa . ' telah mengirim bukti pembayaran perpanjangan domain ' . $pengajuan->nama_domain . '.desa.id. Silakan aktivasi domain.',
                'role_tujuan'   => 'admin',
                'is_read'       => 0
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Bukti pembayaran berhasil dikirim. Menunggu aktivasi admin.'
            ]);
        } catch (\Exception $e) {
            Log::error('uploadBukti perpanjangan error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Gagal upload bukti: ' . $e->getMessage()
            ], 500);
        }
    }

    // =========================
    // ADMIN - VERIFIKASI PEMBAYARAN (OPTIONAL - bisa dihapus jika tidak perlu)
    // =========================
    public function verifikasiPembayaran($id)
    {
        $user = auth()->user();

        if ($user->role !== 'admin') {
            return response()->json([
                'success' => false,
                'message' => 'Hanya admin yang dapat verifikasi'
            ], 403);
        }

        $pengajuan = Pengajuan::findOrFail($id);

        $faktur = Faktur::where('id_pengajuan', $pengajuan->id_pengajuan)
            ->where('tipe', 'perpanjangan')
            ->where('status', 'sudah_bayar') 
            ->latest()
            ->first();

        if (!$faktur) {
            return response()->json([
                'success' => false,
                'message' => 'Faktur dengan bukti pembayaran tidak ditemukan'
            ], 404);
        }

        Pesan::create([
            'id_user'       => $pengajuan->id_user,
            'id_pengajuan'  => $pengajuan->id_pengajuan,
            'judul'         => 'Pembayaran Terverifikasi',
            'isi'           => 'Pembayaran perpanjangan domain ' . $pengajuan->nama_domain . ' telah diverifikasi oleh admin. Silakan tunggu aktivasi ulang domain Anda.',
            'role_tujuan'   => 'desa',
            'is_read'       => 0
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Pembayaran berhasil diverifikasi'
        ]);
    }

    // =========================
    // ADMIN - AKTIVASI ULANG DOMAIN (PERPANJANG)
    // =========================
    public function aktivasi($id)
    {
        $user = auth()->user();

        // ✅ Validasi: Hanya admin yang bisa aktivasi
        if ($user->role !== 'admin') {
            return response()->json([
                'success' => false,
                'message' => 'Hanya admin yang dapat melakukan aktivasi domain'
            ], 403);
        }

        $pengajuan = Pengajuan::findOrFail($id);

        // ✅ Cari faktur perpanjangan dengan status 'sudah_bayar'
        $faktur = Faktur::where('id_pengajuan', $pengajuan->id_pengajuan)
            ->where('tipe', 'perpanjangan')
            ->where('status', 'sudah_bayar')
            ->latest()
            ->first();

        if (!$faktur) {
            return response()->json([
                'success' => false,
                'message' => 'Faktur perpanjangan dengan status sudah bayar tidak ditemukan'
            ], 404);
        }

        $aktivasi = Aktivasi::where('id_pengajuan', $pengajuan->id_pengajuan)
            ->orderByDesc('id_aktivasi')
            ->first();

        if (!$aktivasi) {
            return response()->json([
                'success' => false,
                'message' => 'Data aktivasi domain tidak ditemukan'
            ], 404);
        }

        DB::beginTransaction();
        try {
            // ✅ Tambah 1 TAHUN dari masa berlaku saat ini
            $masaBaru = Carbon::parse($aktivasi->masa_berlaku)->addYear();

            $aktivasi->update([
                'status_akt'   => 'aktif',
                'masa_berlaku' => $masaBaru,
            ]);

            // Mark pesan sebagai read
            Pesan::where('id_pengajuan', $id)
                ->whereIn('judul', ['Bukti Pembayaran Perpanjangan', 'Pembayaran Terverifikasi'])
                ->update(['is_read' => 1]);

            // Notif user
            Pesan::create([
                'id_user'       => $pengajuan->id_user,
                'id_pengajuan'  => $pengajuan->id_pengajuan,
                'judul'         => 'Perpanjangan Domain Berhasil',
                'isi'           => 'Domain ' . $pengajuan->nama_domain . '.desa.id berhasil diperpanjang hingga ' . $masaBaru->format('d-m-Y'),
                'role_tujuan'   => 'desa',
                'is_read'       => 0
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Domain berhasil diperpanjang',
                'data' => [
                    'masa_berlaku_baru' => $masaBaru->format('d-m-Y')
                ]
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('aktivasi perpanjangan error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    // =========================
    // REMINDER - NOTIFIKASI 60 HARI SEBELUM KADALUARSA
    // =========================
    public function cekReminder()
    {
        $data = Aktivasi::with('pengajuan')
            ->where('status_akt', 'aktif')
            ->whereDate('masa_berlaku', '<=', now()->addDays(60))
            ->whereDate('masa_berlaku', '>', now())
            ->get();

        foreach ($data as $item) {
            Pesan::firstOrCreate([
                'id_pengajuan' => $item->id_pengajuan,
                'judul' => 'Pengingat Perpanjangan Domain',
            ], [
                'id_user' => $item->pengajuan->id_user,
                'isi' => 'Domain ' . $item->pengajuan->nama_domain .
                            '.desa.id akan berakhir pada ' .
                            Carbon::parse($item->masa_berlaku)->format('d-m-Y') .
                            '. Silakan perpanjang sekarang untuk menghindari kehilangan domain.',
                'role_tujuan' => 'desa',
                'is_read' => 0
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Reminder dikirim'
        ]);
    }

    // =========================
    // GET DETAIL FAKTUR
    // =========================
    public function detailFaktur($id)
    {
        $faktur = Faktur::where('id_pengajuan', $id)
            ->where('tipe', 'perpanjangan')
            ->latest()
            ->first();

        if (!$faktur) {
            return response()->json([
                'success' => false,
                'message' => 'Faktur perpanjangan tidak ditemukan',
                'data' => null
            ]);
        }

        return response()->json([
            'success' => true,
            'data' => $faktur
        ]);
    }

    // =========================
    // ADMIN - LIST FAKTUR PERPANJANGAN
    // =========================
    public function adminListFaktur()
    {
        $fakturs = Faktur::where('tipe', 'perpanjangan')
            ->with('pengajuan')
            ->latest()
            ->get();

        return response()->json([
            'success' => true,
            'data' => $fakturs
        ]);
    }
}