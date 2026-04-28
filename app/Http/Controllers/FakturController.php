<?php

namespace App\Http\Controllers;

use App\Models\Faktur;
use App\Models\Pengajuan;
use App\Models\Pesan;

class FakturController extends Controller
{
        public function store($id)
    {
        $pengajuan = Pengajuan::findOrFail($id);

        // CEGAH DUPLIKAT: Jika sudah ada faktur 'baru' yang belum bayar
        if (Faktur::where('id_pengajuan', $id)->where('tipe', 'baru')->where('status', 'belum_bayar')->exists()) {
            return back()->with('error', 'Faktur aktif untuk domain ini sudah ada!');
        }

        // SISTEM OTOMATIS: Cek apakah ini pembayaran ke-2 (perpanjangan)
        $sudahPernahBayar = Faktur::where('id_pengajuan', $id)
            ->where('status', 'sudah_bayar')
            ->whereNotNull('bukti_pembayaran_path') // bukti pembayarannya terisi
            ->exists();

        // Jika sudah pernah bayar sebelumnya, paksa tipenya jadi 'perpanjangan'
        $tipeFaktur = $sudahPernahBayar ? 'perpanjangan' : 'baru';

        // Format nomor invoice
        $date = now()->format('Ymd');
        $random = str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT);
        $noInvoice = "INV/{$date}/{$random}";

        Faktur::create([
            'id_pengajuan' => $pengajuan->id_pengajuan,
            'nama_desa' => $pengajuan->nama_desa,
            'nama_domain' => $pengajuan->nama_domain,
            'no_invoice' => $noInvoice,
            'total' => 50000,
            'status' => 'belum_bayar',
            'tipe' => $tipeFaktur, // Menggunakan variabel otomatis di atas
            'expired_at' => now()->addDays(7)
        ]);

        // Pesan sukses disesuaikan dengan tipenya
        $pesanSukses = $tipeFaktur === 'perpanjangan' 
            ? 'Sistem mendeteksi perpanjangan. Faktur perpanjangan berhasil dibuat.' 
            : 'Faktur domain baru berhasil dibuat.';

        return back()->with('success', $pesanSukses);
    }

            public function index()
    {
        // ==========================================
        // SELF-HEALING: Koreksi Otomatis Tipe di Database
        // ==========================================
        $fakturBaru = Faktur::where('tipe', 'baru')->get();
        
        foreach ($fakturBaru as $f) {
            // Cek: Apakah untuk id_pengajuan ini, pernah ada faktur LAIN yang sudah_bayar 
            // DAN buktinya terisi SEBELUM faktur ini dibuat?
            $sudahBayarSebelumnya = Faktur::where('id_pengajuan', $f->id_pengajuan)
                ->where('status', 'sudah_bayar')
                ->whereNotNull('bukti_pembayaran_path')
                ->where('created_at', '<', $f->created_at)
                ->exists();

            // Jika iya, berarti ini salah ketik/bug, langsung ubah jadi perpanjangan
            if ($sudahBayarSebelumnya) {
                $f->update(['tipe' => 'perpanjangan']);
            }
        }
        // ==========================================

        // Lanjut mengambil data seperti biasa
        $data = Pengajuan::with(['faktur' => function($query) {
            $query->latest();
        }])->latest()->paginate(10);

        // Cari pengajuan yang minta perpanjangan tapi fakturnya belum dibuat
        $perpanjanganBelumBuat = Pesan::where('judul', 'Permintaan Perpanjangan Domain')
            ->pluck('id_pengajuan')
            ->filter(function($id_pengajuan) {
                return !Faktur::where('id_pengajuan', $id_pengajuan)
                    ->where('tipe', 'perpanjangan')
                    ->exists();
            })
            ->toArray();

        return view('admin.faktur.index', compact('data', 'perpanjanganBelumBuat'));
    }
    
    public function show($id)
    {
        $faktur = Faktur::findOrFail($id);
        return view('admin.faktur.show', compact('faktur'));
    }

    public function storePerpanjangan($idPengajuan)
    {
        $pengajuan = Pengajuan::findOrFail($idPengajuan);

        $fakturAktif = Faktur::where('id_pengajuan', $idPengajuan)
            ->where('tipe', 'perpanjangan')
            ->where('status', 'belum_bayar')
            ->exists();

        if ($fakturAktif) {
            return redirect()->route('admin.faktur.index')
                ->with('error', 'Faktur perpanjangan untuk domain ini masih aktif.');
        }

        // Tandai pesan sudah diproses
        Pesan::where('id_pengajuan', $idPengajuan)
            ->where('judul', 'Permintaan Perpanjangan Domain')
            ->update(['is_read' => 1]);

        $date = now()->format('Ymd');
        $random = str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT);
        $noInvoice = "INV/{$date}/{$random}";

        Faktur::create([
            'id_pengajuan' => $pengajuan->id_pengajuan,
            'nama_desa' => $pengajuan->nama_desa,
            'nama_domain' => $pengajuan->nama_domain,
            'no_invoice' => $noInvoice,
            'total' => 50000,
            'status' => 'belum_bayar',
            'tipe' => 'perpanjangan',
            'expired_at' => now()->addDays(7)
        ]);

        return redirect()->route('admin.faktur.index')
            ->with('success', 'Faktur perpanjangan berhasil dikirim ke desa.');
    }
}