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
             $masaBerlaku = now()->addMinutes(3); 
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
          public function adminDaftarAktif(Request $request)
    {
        // --- 1. UPDATE STATUS KADALUARSA ---
        // Gunakan get() agar SEMUA data kadaluarsa terupdate, bukan cuma 10 data pertama
        $expiredDomains = Aktivasi::where('masa_berlaku', '<', now())
            ->where('status_akt', 'aktif')
            ->get();

        foreach ($expiredDomains as $aktivasi) {
            $aktivasi->status_akt = 'kadaluarsa';
            $aktivasi->save();
        }

        $statusFilter = $request->get('status', 'all');

        // --- 2. QUERY DATA TABEL ---
        $query = Pengajuan::with('faktur', 'aktivasi')
            ->latest()
            ->where('status_pengajuan', 'aktif'); // Hanya domain yang terdaftar

        if ($statusFilter == 'aktif') {
            $query->whereHas('aktivasi', function ($q) {
                $q->where('status_akt', 'aktif');
            });
        } elseif ($statusFilter == 'kadaluarsa') {
            $query->whereHas('aktivasi', function ($q) {
                $q->where('status_akt', 'kadaluarsa');
            });
        }

        $data = $query->latest()->paginate(10);

        // --- 3. PERHITUNGAN WIDGET (DIKOREKSI) ---
        // Kita hitung berdasarkan Pengajuan agar sesuai dengan jumlah Domain (Unik)
        // Bukan berdasarkan jumlah baris riwayat di tabel Aktivasi
        
        $baseQuery = Pengajuan::where('status_pengajuan', 'aktif');

        // Total Domain = Semua pengajuan aktif (Menghindari duplikat riwayat perpanjangan)
        $totalDomain = $baseQuery->count();

        // Total Aktif = Pengajuan aktif yang status_aktivasi = aktif
        $totalAktif = (clone $baseQuery)->whereHas('aktivasi', function ($q) {
            $q->where('status_akt', 'aktif');
        })->count();

        // Total Kadaluarsa = Pengajuan aktif yang status_aktivasi = kadaluarsa
        $totalKadaluarsa = (clone $baseQuery)->whereHas('aktivasi', function ($q) {
            $q->where('status_akt', 'kadaluarsa');
        })->count();

        // Total Nonaktif = Sisa dari total (misal: nonaktif manual atau status lain)
        $totalNonaktif = $totalDomain - ($totalAktif + $totalKadaluarsa);

        return view('admin.domain_terdaftar', compact('data', 'statusFilter', 'totalDomain', 'totalAktif', 'totalNonaktif', 'totalKadaluarsa'));
    }
    /**
     * LIST DESA: Halaman untuk melihat domain aktif & tombol perpanjang
     */
              public function desaPerpanjang()
    {
        // --- UPDATE STATUS KADALUARSA ---
        $expiredDomains = Aktivasi::where('masa_berlaku', '<', now())
            ->where('status_akt', 'aktif')
            ->get();

        foreach ($expiredDomains as $aktivasi) {
            $aktivasi->status_akt = 'kadaluarsa';
            $aktivasi->save();
            
            $pengajuan = Pengajuan::find($aktivasi->id_pengajuan);
            if ($pengajuan) {
                $pengajuan->status_pengajuan = 'kadaluarsa';
                $pengajuan->save();
            }
        }

        $data = Pengajuan::where('id_user', auth()->id())
            ->with('aktivasi')
            ->latest()
            ->get()
            ->map(function ($row) {
                // PERBAIKAN ERROR SORTBYDESC
                // Kita ambil aktivasi TERAKHIR secara langsung menggunakan query relasi
                $row->aktivasi_terakhir = $row->aktivasi()->latest()->first();

                // 1. Cek Faktur Belum Bayar
                $row->ada_faktur_belum_bayar = \App\Models\Faktur::where('id_pengajuan', $row->id_pengajuan)
                    ->where('status', 'belum_bayar')
                    ->exists();

                // 2. Cek Menunggu Faktur
                $latestPesan = \App\Models\Pesan::where('id_pengajuan', $row->id_pengajuan)
                    ->where('judul', 'Permintaan Perpanjangan Domain')
                    ->latest('created_at')
                    ->first();

                $row->menunggu_faktur = false;
                
                if ($latestPesan) {
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
        ->paginate(10);

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