<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request; 
use App\Models\Faktur;
use App\Models\Pengajuan;
use App\Models\Pesan;
use Carbon\Carbon;
use App\Models\Aktivasi;

class FakturController extends Controller
{
        // TAMBAHKAN Request $request
    public function store(Request $request, $id)
{
    $pengajuan = Pengajuan::findOrFail($id);

    // Cegah duplikat faktur belum bayar
    if (
        Faktur::where('id_pengajuan', $id)
            ->where('tipe', 'baru')
            ->where('status', 'belum_bayar')
            ->exists()
    ) {
        return back()->with('error', 'Faktur aktif untuk domain ini sudah ada!');
    }

    // ===================================================
    // ✅ LOGIKA BARU: PENENTUAN TIPE FAKTUR YANG BENAR
    // ===================================================
    
    // 1. Cek apakah domain ini SUDAH PERNAH AKTIF sebelumnya
    $sudahPernahAktif = Aktivasi::where('id_pengajuan', $id)
        ->where('status_akt', 'aktif')
        ->exists();
    
    // 2. Cek apakah ada pesan PERPANJANGAN untuk pengajuan INI
    $adaPermintaanPerpanjangan = Pesan::where('id_pengajuan', $id)
        ->where('judul', 'Permintaan Perpanjangan Domain')  // ✅ HANYA INI
        ->whereNotNull('durasi_tahun')
        ->exists();

    // 3. Tentukan tipe faktur
    if ($adaPermintaanPerpanjangan || $sudahPernahAktif) {
        // Jika ada permintaan perpanjangan ATAU domain sudah pernah aktif → Perpanjangan
        $tipeFaktur = 'perpanjangan';
    } else {
        // Jika domain BELUM PERNAH AKTIF → Baru
        $tipeFaktur = 'baru';
    }

    // ===================================================

    // NO INVOICE
    $date = now()->format('Ymd');
    $random = str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT);
    $noInvoice = "INV/{$date}/{$random}";

    // AMBIL DURASI TAHUN
    if ($tipeFaktur === 'perpanjangan') {
        $pesanPerpanjangan = Pesan::where('id_pengajuan', $id)
            ->where('judul', 'Permintaan Perpanjangan Domain')  // ✅ HANYA INI
            ->whereNotNull('durasi_tahun')
            ->latest()
            ->first();
        
        $durasiTahun = $pesanPerpanjangan->durasi_tahun ?? 1;
    } else {
        $durasiTahun = $request->durasi_tahun ?? 1;
    }
    
    // PERHITUNGAN HARGA DENGAN PPN 11%
    $hargaDasarPerTahun = 50000;
    $ppnPersen = 0.11;
    $subtotal = $durasiTahun * $hargaDasarPerTahun;
    $ppn = $subtotal * $ppnPersen;
    $totalHarga = $subtotal + $ppn;

    Faktur::create([
        'id_pengajuan' => $pengajuan->id_pengajuan,
        'nama_desa'    => $pengajuan->nama_desa,
        'nama_domain'  => $pengajuan->nama_domain,
        'no_invoice'   => $noInvoice,
        'total'        => $totalHarga,
        'status'       => 'belum_bayar',
        'tipe'         => $tipeFaktur,
        'expired_at'   => now()->addDays(7),
        'durasi_tahun' => $durasiTahun,
        'subtotal'     => $subtotal,
        'ppn'          => $ppn
    ]);

    app(PesanController::class)
        ->notifikasiFakturDibuat(
            $pengajuan->id_pengajuan,
            $tipeFaktur
        );

    $pesanSukses = $tipeFaktur === 'perpanjangan'
        ? "Faktur perpanjangan {$durasiTahun} tahun berhasil dibuat."
        : 'Faktur domain baru berhasil dibuat.';

    return back()->with('success', $pesanSukses);
}

            public function index(Request $request)
    {
        // SELF HEALING (Logika tetap sama)
        $fakturBaru = Faktur::where('tipe', 'baru')->get();
        foreach ($fakturBaru as $f) {
            $sudahBayarSebelumnya = Faktur::where('id_pengajuan', $f->id_pengajuan)
                ->where('status', 'sudah_bayar')
                ->whereNotNull('bukti_pembayaran_path')
                ->where('created_at', '<', $f->created_at)
                ->exists();

            if ($sudahBayarSebelumnya) {
                $f->update(['tipe' => 'perpanjangan']);
            }
        }

        // 1. AMBIL PARAMETER PENCARIAN
        $search = $request->get('search');
        $statusFilter = $request->get('status'); // Filter Status (belum_bayar, dll)

        // 2. QUERY UTAMA
        $query = Pengajuan::with(['faktur' => function ($query) {
            $query->latest();
        }]);

        // 3. LOGIKA PENCARIAN TEKS
        if (!empty($search)) {

    $query->where(function ($q) use ($search) {

        $q->where('nama_domain', 'like', "%{$search}%")
          ->orWhere('nama_desa', 'like', "%{$search}%")

          ->orWhereHas('faktur', function ($faktur) use ($search) {

              $faktur->where('no_invoice', 'like', "%{$search}%")
                     ->orWhere('tipe', 'like', "%{$search}%")
                     ->orWhere('status', 'like', "%{$search}%");

              // Format input: dd/mm/yyyy
              if (preg_match('/^\d{2}\/\d{2}\/\d{4}$/', $search)) {

                  $tanggal = \Carbon\Carbon::createFromFormat(
                      'd/m/Y',
                      $search
                  )->format('Y-m-d');

                  $faktur->orWhereDate(
                      'tanggal_konfirmasi',
                      $tanggal
                  );
              }

          });

    });

}

        // 4. LOGIKA FILTER STATUS (SERVER SIDE)
        if ($statusFilter == 'belum_bayar') {
            // Hanya tampilkan yang ada faktur 'belum_bayar'
            $query->whereHas('faktur', function($q) {
                $q->where('status', 'belum_bayar');
            });
        } elseif ($statusFilter == 'sudah_bayar') {
            // Hanya tampilkan yang ada faktur 'sudah_bayar'
            $query->whereHas('faktur', function($q) {
                $q->where('status', 'sudah_bayar');
            });
        } elseif ($statusFilter == 'belum_dibuat') {
            // Logika khusus untuk 'belum_dibuat' (perpanjangan belum difakturkan)
            $query->whereHas('pesan', function($q) {
                $q->where('judul', 'Permintaan Perpanjangan Domain');
            })->whereDoesntHave('faktur', function($q) {
                $q->where('tipe', 'perpanjangan');
            });
        }

        // 5. EKSEKUSI PAGINATION
        $data = $query->latest()->paginate(10);

        // 6. APPEND PARAMETER (PENTING AGAR PAGINATION JALAN)
        $data->appends([
            'search' => $search, 
            'status' => $statusFilter
        ]);

        // 7. LOGIKA PERPANJANGAN BELUM DIBUAT (Tetap diperlukan untuk View)
        $perpanjanganBelumBuat = Pesan::where('judul', 'Permintaan Perpanjangan Domain')
            ->pluck('id_pengajuan')
            ->filter(function ($id_pengajuan) {
                return !Faktur::where('id_pengajuan', $id_pengajuan)
                    ->where('tipe', 'perpanjangan')
                    ->exists();
            })
            ->toArray();

        // ======================
// WIDGET FAKTUR
// ======================

$totalFaktur = Faktur::count();

$totalBelumBayar = Faktur::where('status', 'belum_bayar')->count();

$totalSudahBayar = Faktur::where('status', 'sudah_bayar')->count();

$totalBelumDibuat = count($perpanjanganBelumBuat);

// Kirim ke view
return view('admin.faktur.index', compact(
    'data',
    'perpanjanganBelumBuat',
    'totalFaktur',
    'totalBelumBayar',
    'totalSudahBayar',
    'totalBelumDibuat'
));
    }

    public function show($id)
    {
        $faktur = Faktur::findOrFail($id);

        return view('admin.faktur.show', compact('faktur'));
    }

    public function storePerpanjangan(Request $request, $idPengajuan)
{
    $pengajuan = Pengajuan::findOrFail($idPengajuan);

    $fakturAktif = Faktur::where('id_pengajuan', $idPengajuan)
        ->where('tipe', 'perpanjangan')
        ->where('status', 'belum_bayar')
        ->exists();

    if ($fakturAktif) {
        return redirect()
            ->route('admin.faktur.index')
            ->with('error', 'Faktur perpanjangan untuk domain ini masih aktif.');
    }

    // ✅ HANYA CARI PESAN PERPANJANGAN (BUKAN KONFIRMASI PEMBAYARAN)
    $pesanPerpanjangan = Pesan::where('id_pengajuan', $idPengajuan)
        ->where('judul', 'Permintaan Perpanjangan Domain')  // ✅ HANYA INI
        ->whereNotNull('durasi_tahun')
        ->latest()
        ->first();
    
    $durasiTahun = $pesanPerpanjangan->durasi_tahun ?? 1;
    
    // PERHITUNGAN HARGA DENGAN PPN 11%
    $hargaDasarPerTahun = 50000;
    $ppnPersen = 0.11;
    $subtotal = $durasiTahun * $hargaDasarPerTahun;
    $ppn = $subtotal * $ppnPersen;
    $totalHarga = $subtotal + $ppn;

    // Tandai pesan sudah diproses
    Pesan::where('id_pengajuan', $idPengajuan)
        ->where('judul', 'Permintaan Perpanjangan Domain')  // ✅ HANYA INI
        ->whereNotNull('durasi_tahun')
        ->update(['is_read' => 1]);

    // NO INVOICE
    $date = now()->format('Ymd');
    $random = str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT);
    $noInvoice = "INV/{$date}/{$random}";

    Faktur::create([
        'id_pengajuan' => $pengajuan->id_pengajuan,
        'nama_desa'    => $pengajuan->nama_desa,
        'nama_domain'  => $pengajuan->nama_domain,
        'no_invoice'   => $noInvoice,
        'total'        => $totalHarga,
        'status'       => 'belum_bayar',
        'tipe'         => 'perpanjangan',
        'expired_at'   => now()->addDays(7),
        'durasi_tahun' => $durasiTahun,
        'subtotal'     => $subtotal,
        'ppn'          => $ppn
    ]);

    app(PesanController::class)
        ->notifikasiFakturDibuat(
            $pengajuan->id_pengajuan,
            'perpanjangan'
        );

    return redirect()
        ->route('admin.faktur.index')
        ->with('success', "Faktur perpanjangan {$durasiTahun} tahun (Rp " . number_format($totalHarga,0,',','.') . ") berhasil dikirim ke desa.");
}
}