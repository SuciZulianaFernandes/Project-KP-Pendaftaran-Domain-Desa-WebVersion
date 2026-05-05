<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\PesanController;
use App\Models\Pengajuan;
use App\Models\Faktur;
use App\Models\Aktivasi;
use App\Models\Pesan; 
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AktivasiController extends Controller
{
    
    /**
     * PROSES ADMIN: Mengaktifkan domain setelah desa bayar
     */
    public function aktivasi($id)
    {
        $pengajuan = Pengajuan::findOrFail($id);
        $faktur = Faktur::where('id_pengajuan', $id)->first();

        if (!$faktur) {
            return back()->with('error', 'Data Faktur tidak ditemukan.');
        }

        if ($faktur->status !== 'sudah_bayar') {
            return back()->with('error', 'Gagal! Desa belum mengirim bukti pembayaran.');
        }

        DB::beginTransaction();
        try {
            // 1. Update Status Pengajuan
            $pengajuan->status_pengajuan = 'aktif';
            $pengajuan->save();

            // 2. Update Status Faktur
            $faktur->status = 'sudah_bayar';
            $faktur->save();

            // ============================================
            // KONFIGURASI MASA BERLAKU
            // TESTING: now()->addMinute()
            // PRODUCTION: now()->addDays(365)
            // ============================================
             $masaBerlaku = now()->addMinute();
            // $masaBerlaku = now()->addDays(365); 

            // 3. Catat ke Tabel Aktivasi
            Aktivasi::create([
                'id_pengajuan' => $pengajuan->id_pengajuan,
                'status_akt'   => 'aktif',
                'tgl_aktivasi' => now(),
                'masa_berlaku' => $masaBerlaku,
            ]);

            // 4. Kirim Notifikasi
            app(PesanController::class)->sendNotifikasiAktifasi($pengajuan->id_pengajuan);

            DB::commit();
            return back()->with('success', 'Domain berhasil diaktifkan selama 1 tahun.');
            
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan sistem.');
        }
    }

    /**
     * LIST ADMIN: Daftar domain yang sudah aktif
     */
    public function adminDaftarAktif()
    {
        $data = Pengajuan::where('status_pengajuan', 'aktif')
            ->with('faktur', 'aktivasi')
            ->latest()
            ->get();

        return view('admin.domain_terdaftar', compact('data'));
    }

    /**
     * LIST DESA: Halaman untuk melihat domain aktif & tombol perpanjang
     */
        public function desaPerpanjang()
{
    $data = Pengajuan::where('id_user', auth()->id())
        ->where('status_pengajuan', 'aktif')
        ->with('aktivasi')
        ->latest()
        ->get()
        ->map(function ($row) {

            // 1. Cek Faktur Belum Bayar (Prioritas 1 di View)
            $row->ada_faktur_belum_bayar = \App\Models\Faktur::where('id_pengajuan', $row->id_pengajuan)
                ->where('status', 'belum_bayar')
                ->exists();

            // 2. Cek Menunggu Faktur (Perbaikan Logika)
            // Ambil pesan perpanjangan terakhir
            $latestPesan = \App\Models\Pesan::where('id_pengajuan', $row->id_pengajuan)
                ->where('judul', 'Permintaan Perpanjangan Domain')
                ->latest('created_at')
                ->first();

            $row->menunggu_faktur = false;
            
            if ($latestPesan) {
                // Cek apakah ada faktur yang dibuat SETELAH pesan terakhir dikirim
                // Jika tidak ada faktur setelah pesan, berarti masih menunggu
                $fakturAdaSetelahPesan = \App\Models\Faktur::where('id_pengajuan', $row->id_pengajuan)
                    ->where('created_at', '>=', $latestPesan->created_at)
                    ->exists();

                if (!$fakturAdaSetelahPesan) {
                    $row->menunggu_faktur = true;
                }
            }

            return $row;
        });

    return view('desa.perpanjang', compact('data'));
}

    public function ajukanPerpanjang($id)
    {
        $pengajuan = Pengajuan::where('id_pengajuan', $id)
            ->where('id_user', auth()->id())
            ->firstOrFail();

        // Cegah spam: cek apakah pesan perpanjangan yang sama masih belum dibaca admin
        $pesanSudahAda = Pesan::where('id_pengajuan', $id)
            ->where('judul', 'Permintaan Perpanjangan Domain')
            ->where('is_read', 0)
            ->exists();

        if ($pesanSudahAda) {
            return redirect()->route('desa.perpanjang')
                ->with('error', 'Permintaan perpanjangan sudah terkirim, silakan tunggu admin memproses.');
        }

        // Kirim pesan ke admin
        Pesan::create([
            'id_user'       => User::where('role', 'admin')->value('id_user'),
            'id_pengajuan'  => $pengajuan->id_pengajuan,
            'judul'         => 'Permintaan Perpanjangan Domain',
            'isi'           => 'Desa ' . $pengajuan->nama_desa . ' ingin melakukan perpanjangan domain ' . $pengajuan->nama_domain . '.desa.id, kirimkan faktur.',            'role_tujuan'   => 'admin'
        ]);

        return redirect()->route('desa.perpanjang')
            ->with('success', 'Permintaan perpanjangan berhasil dikirim ke admin.');
    }
    public function adminPerpanjangList()
{
    // Ambil semua faktur yang tipenya perpanjangan
    $fakturs = Faktur::where('tipe', 'perpanjangan')
        ->with('pengajuan') // ambil relasi pengajuan untuk tau nama domain & desa
        ->latest()
        ->get();

    return view('admin.perpanjang.index', compact('fakturs'));
}

public function adminPerpanjangDetail($id)
{
    // Ambil data faktur perpanjangan
    $faktur = Faktur::with('pengajuan')->findOrFail($id);
    
    // Ambil data pengajuan utamanya
    $pengajuan = $faktur->pengajuan;
    
    // Load relasi yang dibutuhkan oleh view (dokumen, faktur, aktivasi)
    $pengajuan->load('dokumenPersyaratan', 'faktur', 'aktivasi');

    return view('admin.perpanjang.show', compact('faktur', 'pengajuan'));
}
}