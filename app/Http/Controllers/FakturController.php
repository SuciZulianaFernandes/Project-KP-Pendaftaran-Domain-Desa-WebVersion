<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request; 
use App\Models\Faktur;
use App\Models\Pengajuan;
use App\Models\Pesan;

class FakturController extends Controller
{
    public function store($id)
    {
        $pengajuan = Pengajuan::findOrFail($id);

        // Cegah duplikat
        if (
            Faktur::where('id_pengajuan', $id)
                ->where('tipe', 'baru')
                ->where('status', 'belum_bayar')
                ->exists()
        ) {
            return back()->with('error', 'Faktur aktif untuk domain ini sudah ada!');
        }

        // CEK REQUEST PERPANJANGAN
        $requestPerpanjangan = Pesan::where('id_pengajuan', $id)
            ->where('judul', 'Permintaan Perpanjangan Domain')
            ->exists();

        // CEK TIPE
        if ($requestPerpanjangan) {

            $tipeFaktur = 'perpanjangan';

        } else {

            $sudahPernahBayar = Faktur::where('id_pengajuan', $id)
                ->where('status', 'sudah_bayar')
                ->whereNotNull('bukti_pembayaran_path')
                ->exists();

            $tipeFaktur = $sudahPernahBayar
                ? 'perpanjangan'
                : 'baru';
        }

        // NO INVOICE
        $date = now()->format('Ymd');
        $random = str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT);

        $noInvoice = "INV/{$date}/{$random}";

        Faktur::create([
            'id_pengajuan' => $pengajuan->id_pengajuan,
            'nama_desa'    => $pengajuan->nama_desa,
            'nama_domain'  => $pengajuan->nama_domain,
            'no_invoice'   => $noInvoice,
            'total'        => 50000,
            'status'       => 'belum_bayar',
            'tipe'         => $tipeFaktur,
            'expired_at'   => now()->addDays(7)
        ]);

        // =========================
        // KIRIM PESAN KE DESA
        // =========================
        app(PesanController::class)
            ->notifikasiFakturDibuat(
                $pengajuan->id_pengajuan,
                $tipeFaktur
            );

        // SUCCESS
        $pesanSukses = $tipeFaktur === 'perpanjangan'
            ? 'Sistem mendeteksi perpanjangan. Faktur perpanjangan berhasil dibuat.'
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
            $query->where(function($q) use ($search) {
                $q->where('nama_domain', 'like', "%$search%")
                  ->orWhere('nama_desa', 'like', "%$search%");
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

    public function storePerpanjangan($idPengajuan)
    {
        $pengajuan = Pengajuan::findOrFail($idPengajuan);

        $fakturAktif = Faktur::where('id_pengajuan', $idPengajuan)
            ->where('tipe', 'perpanjangan')
            ->where('status', 'belum_bayar')
            ->exists();

        if ($fakturAktif) {

            return redirect()
                ->route('admin.faktur.index')
                ->with(
                    'error',
                    'Faktur perpanjangan untuk domain ini masih aktif.'
                );
        }

        // PESAN SUDAH DIPROSES
        Pesan::where('id_pengajuan', $idPengajuan)
            ->where('judul', 'Permintaan Perpanjangan Domain')
            ->update([
                'is_read' => 1
            ]);

        // NO INVOICE
        $date = now()->format('Ymd');

        $random = str_pad(
            mt_rand(1, 99999),
            5,
            '0',
            STR_PAD_LEFT
        );

        $noInvoice = "INV/{$date}/{$random}";

        Faktur::create([
            'id_pengajuan' => $pengajuan->id_pengajuan,
            'nama_desa'    => $pengajuan->nama_desa,
            'nama_domain'  => $pengajuan->nama_domain,
            'no_invoice'   => $noInvoice,
            'total'        => 50000,
            'status'       => 'belum_bayar',
            'tipe'         => 'perpanjangan',
            'expired_at'   => now()->addDays(7)
        ]);

        // =========================
        // KIRIM PESAN KE DESA
        // =========================
        app(PesanController::class)
            ->notifikasiFakturDibuat(
                $pengajuan->id_pengajuan,
                'perpanjangan'
            );

        return redirect()
            ->route('admin.faktur.index')
            ->with(
                'success',
                'Faktur perpanjangan berhasil dikirim ke desa.'
            );
    }
}