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
use Carbon\Carbon;

class PerpanjanganApiController extends Controller
{
    // =========================
    // USER - LIST DOMAIN (dengan status perpanjangan)
    // =========================
    public function listDomain(Request $request)
    {
        $user = auth()->user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 401);
        }

        // Update status kadaluarsa otomatis
        $expiredDomains = Aktivasi::where('masa_berlaku', '<', now())
            ->where('status_akt', 'aktif')
            ->get();

        foreach ($expiredDomains as $aktivasi) {
            $aktivasi->status_akt = 'kadaluarsa';
            $aktivasi->save();
        }

        $data = Pengajuan::with(['aktivasi' => function ($query) {
            $query->orderByDesc('id_aktivasi');
        }])
        ->where('id_user', $user->id_user)
        ->where('status_pengajuan', 'aktif')
        ->latest()
        ->get();

        $data = $data->map(function ($pengajuan) {
            $semuaAktivasi = $pengajuan->aktivasi;
            $aktivasiTerakhir = $semuaAktivasi->first();
            
            $pengajuan->aktivasi_terakhir = $aktivasiTerakhir;
            
            // ✓ CEK STATUS PERPANJANGAN
            if ($aktivasiTerakhir) {
                $masaBerlaku = Carbon::parse($aktivasiTerakhir->masa_berlaku);
                $hariIni = Carbon::now();
                $hariSisa = $hariIni->diffInDays($masaBerlaku);
                
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
                
                // CEK FAKTUR BELUM BAYAR
                $pengajuan->ada_faktur_belum_bayar = Faktur::where('id_pengajuan', $pengajuan->id_pengajuan)
                    ->where('status', 'belum_bayar')
                    ->exists();
                
                // CEK STATUS MENUNGGU FAKTUR
                $latestPesan = Pesan::where('id_pengajuan', $pengajuan->id_pengajuan)
                    ->where('judul', 'Permintaan Perpanjangan Domain')
                    ->latest('created_at')
                    ->first();
                
                $pengajuan->menunggu_faktur = false;
                
                if ($latestPesan) {
                    $fakturAdaSetelahPesan = Faktur::where('id_pengajuan', $pengajuan->id_pengajuan)
                        ->where('tipe', 'perpanjangan')
                        ->where('created_at', '>=', $latestPesan->created_at)
                        ->exists();
                    
                    if (!$fakturAdaSetelahPesan) {
                        $pengajuan->menunggu_faktur = true;
                    }
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
    // USER - AJUKAN PERPANJANGAN (LOGIKA DIPERBAIKI)
    // =========================
    public function ajukan($id)
    {
        $user = auth()->user();
        
        $pengajuan = Pengajuan::where('id_pengajuan', $id)
            ->where('id_user', $user->id_user)
            ->firstOrFail();

        // 1. Cari pesan perpanjangan terakhir untuk domain ini
        $latestPesan = Pesan::where('id_pengajuan', $id)
            ->where('judul', 'Permintaan Perpanjangan Domain')
            ->latest()
            ->first();

        // 2. Jika ada pesan lama yang masih belum dibaca (is_read = 0)
        if ($latestPesan && $latestPesan->is_read == 0) {
            
            // Cek apakah admin sudah merespons dengan membuat Faktur Perpanjangan?
            $fakturRespons = Faktur::where('id_pengajuan', $id)
                ->where('tipe', 'perpanjangan')
                ->where('created_at', '>', $latestPesan->created_at)
                ->exists();

            if (!$fakturRespons) {
                // Admin belum merespons -> TOLAK request baru
                return response()->json([
                    'success' => false,
                    'message' => 'Permintaan perpanjangan sebelumnya masih diproses admin. Silakan tunggu.'
                ]);
            } else {
                // Admin sudah merespons. Update pesan lama jadi read.
                $latestPesan->is_read = 1;
                $latestPesan->save();
            }
        }

        // 3. Buat pesan baru ke admin
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
            'isi'           => 'Desa ' . $pengajuan->nama_desa . 
                                ' ingin melakukan perpanjangan domain ' . 
                                $pengajuan->nama_domain . '.desa.id, kirimkan faktur.',
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
        $data = Pesan::with('pengajuan')
            ->where('judul', 'Permintaan Perpanjangan Domain')
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
        $pengajuan = Pengajuan::findOrFail($id);

        // CEK SUDAH ADA FAKTUR PERPANJANGAN BELUM BAYAR
        $cek = Faktur::where('id_pengajuan', $pengajuan->id_pengajuan)
            ->where('tipe', 'perpanjangan')
            ->where('status', 'belum_bayar')
            ->exists();

        if ($cek) {
            return response()->json([
                'success' => false,
                'message' => 'Faktur perpanjangan masih ada dan belum dibayar'
            ]);
        }

        // GENERATE FAKTUR
        $faktur = FakturService::createFaktur($pengajuan, 'perpanjangan');

        // MARK PESAN LAMA SEBAGAI READ
        Pesan::where('id_pengajuan', $id)
            ->where('judul', 'Permintaan Perpanjangan Domain')
            ->update(['is_read' => 1]);

        // NOTIF USER
        Pesan::create([
            'id_user'       => $pengajuan->id_user,
            'id_pengajuan'  => $pengajuan->id_pengajuan,
            'judul'         => 'Faktur Perpanjangan',
            'isi'           => 'Faktur perpanjangan domain ' . $pengajuan->nama_domain .
                                '.desa.id telah tersedia. Silakan lakukan pembayaran dan upload bukti.',
            'role_tujuan'   => 'desa',
            'is_read'       => 0
        ]);

        // NOTIF ADMIN
        Pesan::create([
            'id_user'       => 1,
            'id_pengajuan'  => $pengajuan->id_pengajuan,
            'judul'         => 'Faktur Perpanjangan Dibuat',
            'isi'           => 'Faktur perpanjangan domain ' . $pengajuan->nama_domain .
                                '.desa.id telah dibuat. Menunggu pembayaran dari ' . $pengajuan->nama_desa,
            'role_tujuan'   => 'admin',
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
        $pengajuan = Pengajuan::findOrFail($id);

        $faktur = Faktur::where('id_pengajuan', $pengajuan->id_pengajuan)
            ->where('tipe', 'perpanjangan')
            ->where('status', 'belum_bayar')
            ->latest()
            ->first();

        if (!$faktur) {
            return response()->json([
                'success' => false,
                'message' => 'Faktur tidak ditemukan atau sudah dibayar'
            ]);
        }

        // VALIDASI FILE
        if (!$request->hasFile('bukti_pembayaran')) {
            return response()->json([
                'success' => false,
                'message' => 'File bukti pembayaran diperlukan'
            ], 422);
        }

        $path = UploadService::uploadDokumen(
            $request->file('bukti_pembayaran'),
            'bukti_pembayaran',
            'bukti_pembayaran'
        );

        $faktur->update([
            'bukti_pembayaran_path' => $path,
            'status' => 'menunggu_verifikasi',
            'tanggal_konfirmasi' => now(),
        ]);

        // NOTIF ADMIN
        Pesan::create([
            'id_user'       => 1,
            'id_pengajuan'  => $pengajuan->id_pengajuan,
            'judul'         => 'Bukti Pembayaran Perpanjangan',
            'isi'           => 'Desa ' . $pengajuan->nama_desa .
                                ' telah mengirim bukti pembayaran perpanjangan domain ' .
                                $pengajuan->nama_domain . '.desa.id. Silakan verifikasi.',
            'role_tujuan'   => 'admin',
            'is_read'       => 0
        ]);

        // NOTIF USER
        Pesan::create([
            'id_user'       => $pengajuan->id_user,
            'id_pengajuan'  => $pengajuan->id_pengajuan,
            'judul'         => 'Bukti Pembayaran Diterima',
            'isi'           => 'Bukti pembayaran Anda telah diterima. Menunggu verifikasi dari admin.',
            'role_tujuan'   => 'desa',
            'is_read'       => 0
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Bukti pembayaran berhasil dikirim'
        ]);
    }

    // =========================
    // ADMIN - VERIFIKASI PEMBAYARAN
    // =========================
    public function verifikasiPembayaran($id)
    {
        $pengajuan = Pengajuan::findOrFail($id);

        $faktur = Faktur::where('id_pengajuan', $pengajuan->id_pengajuan)
            ->where('tipe', 'perpanjangan')
            ->where('status', 'menunggu_verifikasi')
            ->latest()
            ->first();

        if (!$faktur) {
            return response()->json([
                'success' => false,
                'message' => 'Faktur tidak ditemukan atau sudah diverifikasi'
            ]);
        }

        // UPDATE STATUS FAKTUR
        $faktur->update([
            'status' => 'sudah_bayar'
        ]);

        // NOTIF USER
        Pesan::create([
            'id_user'       => $pengajuan->id_user,
            'id_pengajuan'  => $pengajuan->id_pengajuan,
            'judul'         => 'Pembayaran Terverifikasi',
            'isi'           => 'Pembayaran perpanjangan domain ' . $pengajuan->nama_domain .
                                '.desa.id telah diverifikasi. Domainmu akan diaktifkan kembali.',
            'role_tujuan'   => 'desa',
            'is_read'       => 0
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Pembayaran terverifikasi'
        ]);
    }

    // =========================
    // ADMIN - AKTIVASI ULANG DOMAIN (PERPANJANG)
    // =========================
    public function aktivasi($id)
    {
        $pengajuan = Pengajuan::findOrFail($id);

        // FAKTUR TERBARU
        $faktur = Faktur::where('id_pengajuan', $pengajuan->id_pengajuan)
            ->where('tipe', 'perpanjangan')
            ->where('status', 'sudah_bayar')
            ->latest()
            ->first();

        if (!$faktur) {
            return response()->json([
                'success' => false,
                'message' => 'Faktur pembayaran tidak ditemukan atau belum terverifikasi'
            ]);
        }

        // AMBIL AKTIVASI TERBARU
        $aktivasi = Aktivasi::where('id_pengajuan', $pengajuan->id_pengajuan)
            ->orderByDesc('id_aktivasi')
            ->first();

        if (!$aktivasi) {
            return response()->json([
                'success' => false,
                'message' => 'Data aktivasi domain tidak ditemukan'
            ]);
        }

        DB::beginTransaction();
        try {
            // ✓ PENTING: Tambah 1 TAHUN dari masa berlaku saat ini
            $masaBaru = Carbon::parse($aktivasi->masa_berlaku)->addYear();

            $aktivasi->update([
                'status_akt'   => 'aktif',
                'masa_berlaku' => $masaBaru,
            ]);

            // UPDATE STATUS FAKTUR MENJADI SELESAI
            $faktur->update([
                'status' => 'selesai'
            ]);

            // MARK PESAN LAMA SEBAGAI READ
            Pesan::where('id_pengajuan', $id)
                ->whereIn('judul', ['Bukti Pembayaran Perpanjangan', 'Pembayaran Terverifikasi'])
                ->update(['is_read' => 1]);

            // NOTIF USER
            Pesan::create([
                'id_user'       => $pengajuan->id_user,
                'id_pengajuan'  => $pengajuan->id_pengajuan,
                'judul'         => 'Perpanjangan Domain Berhasil',
                'isi'           => 'Domain ' . $pengajuan->nama_domain .
                                    '.desa.id berhasil diperpanjang hingga ' .
                                    $masaBaru->format('d-m-Y'),
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
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ]);
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
                'message' => 'Faktur tidak ditemukan'
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