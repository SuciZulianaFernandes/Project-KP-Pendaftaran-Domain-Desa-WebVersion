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
        $fakturs = Faktur::with('pengajuan')
            ->where('status', 'sudah_bayar')
            ->where('tipe', 'baru')
            ->latest()
            ->get();

        foreach ($fakturs as $faktur) {

            if (
                $faktur->pengajuan &&
                $faktur->pengajuan->status_pengajuan == 'diproses'
            ) {

                $faktur->pengajuan->status_pengajuan =
                    'menunggu_aktivasi';

                $faktur->pengajuan->save();
            }
        }

        return response()->json([
            'success' => true,
            'data' => $fakturs
        ]);
    }
    // =========================
    // AKTIVASI DOMAIN
    // =========================
    public function aktivasi($id)
{
    $pengajuan = Pengajuan::findOrFail($id);

    if (
        $pengajuan->status_pengajuan
        != 'menunggu_aktivasi'
    ) {

        return response()->json([
            'success' => false,
            'message' =>
                'Domain belum siap diaktivasi'
        ], 400);
    }

    DB::beginTransaction();

    try {

        // UPDATE STATUS
        $pengajuan->status_pengajuan =
            'aktif';

        $pengajuan->save();

        // CEK AKTIVASI
        $cek = Aktivasi::where(
            'id_pengajuan',
            $pengajuan->id_pengajuan
        )->first();

        if ($cek) {

            // TAMBAH 1 TAHUN
            $cek->status_akt = 'aktif';

            $cek->tgl_aktivasi = now();

            $cek->masa_berlaku =
                \Carbon\Carbon::parse(
                    $cek->masa_berlaku
                )->addYear();

            $cek->save();

        } else {

            // AKTIVASI BARU
            Aktivasi::create([

                'id_pengajuan' =>
                    $pengajuan->id_pengajuan,

                'status_akt' =>
                    'aktif',

                'tgl_aktivasi' =>
                    now(),

                'masa_berlaku' =>
                    now()->addYear(),
            ]);
        }

        // NOTIF USER
        Pesan::create([

            'id_user' =>
                $pengajuan->id_user,

            'id_pengajuan' =>
                $pengajuan->id_pengajuan,

            'judul' =>
                'Domain Aktif',

            'isi' =>
                'Domain ' .
                $pengajuan->nama_domain .
                '.desa.id berhasil diaktifkan.',

            'role_tujuan' => 'desa'
        ]);

        DB::commit();

        return response()->json([
            'success' => true,
            'message' =>
                'Domain berhasil diaktifkan'
        ]);

    } catch (\Exception $e) {

        DB::rollBack();

        return response()->json([
            'success' => false,
            'message' =>
                'Gagal aktivasi domain'
        ], 500);
    }
}
    public function fakturMobile()
{
    $data = Faktur::with('pengajuan')
        ->whereNotNull('no_invoice')
        ->latest()
        ->get();

    return response()->json([

        'success' => true,

        'data' => $data->map(function ($item) {

            return [

                'id' => $item->id,

                'no_invoice' =>
                    $item->no_invoice,

                'nama_desa' =>
                    $item->pengajuan->nama_desa ?? '-',

                'nama_domain' =>
                    $item->pengajuan->nama_domain ?? '-',

                'status' =>
                    $item->status,

                'tipe' =>
                    $item->tipe,

                'tanggal_konfirmasi' =>
                    optional(
                        $item->tanggal_konfirmasi
                    )?->format('Y-m-d'),
            ];
        })
    ]);
}
public function detailFakturMobile($id)
{
    $item = Faktur::with('pengajuan')
        ->findOrFail($id);

    return response()->json([

        'success' => true,

        'data' => [

            'id' => $item->id,

            'no_invoice' => $item->no_invoice,

            'nama_desa' =>
                $item->pengajuan->nama_desa ?? '-',

            'nama_domain' =>
                $item->pengajuan->nama_domain ?? '-',

            'status' => $item->status,

            'tipe' => $item->tipe,

            'tanggal_konfirmasi' =>
                optional($item->tanggal_konfirmasi)
                    ?->format('Y-m-d'),

            'bukti_pembayaran' =>
                $item->bukti_pembayaran_path
                    ? asset('storage/' . $item->bukti_pembayaran_path)
                    : null,
        ]
    ]);
}
    
}