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

    public function index()
    {
        // SELF HEALING
        $fakturBaru = Faktur::where('tipe', 'baru')->get();

        foreach ($fakturBaru as $f) {

            $sudahBayarSebelumnya = Faktur::where('id_pengajuan', $f->id_pengajuan)
                ->where('status', 'sudah_bayar')
                ->whereNotNull('bukti_pembayaran_path')
                ->where('created_at', '<', $f->created_at)
                ->exists();

            if ($sudahBayarSebelumnya) {
                $f->update([
                    'tipe' => 'perpanjangan'
                ]);
            }
        }

        $data = Pengajuan::with([
            'faktur' => function ($query) {
                $query->latest();
            }
        ])->latest()->paginate(10);

        $perpanjanganBelumBuat = Pesan::where('judul', 'Permintaan Perpanjangan Domain')
            ->pluck('id_pengajuan')
            ->filter(function ($id_pengajuan) {

                return !Faktur::where('id_pengajuan', $id_pengajuan)
                    ->where('tipe', 'perpanjangan')
                    ->exists();

            })
            ->toArray();

        return view('admin.faktur.index', compact(
            'data',
            'perpanjanganBelumBuat'
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