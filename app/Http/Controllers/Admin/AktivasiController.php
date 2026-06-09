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
     * PROSES ADMIN: Mengaktifkan domain setelah desa bayar (MASALAH 1 SELESAI)
     */
    public function aktivasi($id)
    {
        $pengajuan = Pengajuan::findOrFail($id);
        $faktur = Faktur::where('id_pengajuan', $id)->where('status', 'sudah_bayar')->latest()->first();

        if (!$faktur) {
            $faktur = Faktur::where('id_pengajuan', $id)->latest()->first();
        }

        if (!$faktur) {
            return back()->with('error', 'Data Faktur tidak ditemukan.');
        }

        if ($faktur->status !== 'sudah_bayar') {
            return back()->with('error', 'Gagal! Desa belum mengirim bukti pembayaran.');
        }

        DB::beginTransaction();
        try {
            $pengajuan->status_pengajuan = 'aktif';
            $pengajuan->save();

            // Ambil data aktivasi terakhir untuk domain ini
            $aktivasiTerakhir = Aktivasi::where('id_pengajuan', $pengajuan->id_pengajuan)
                ->orderBy('masa_berlaku', 'desc')
                ->first();

            // LOGIKA PENAMBAHAN TAHUN:
            // Jika ada data lama dan tanggal kadaluarsanya masih di masa depan, tambahkan dari tanggal tersebut.
            // Jika tidak ada atau sudah kadaluarsa, tambahkan dari waktu sekarang (now()).
            if ($aktivasiTerakhir && Carbon::parse($aktivasiTerakhir->masa_berlaku)->isFuture()) {
                $baseDate = Carbon::parse($aktivasiTerakhir->masa_berlaku);
            } else {
                $baseDate = now();
            }

            $masaBerlaku = $baseDate->addYear(); // Tambah 1 tahun secara akurat

            Aktivasi::create([
                'id_pengajuan' => $pengajuan->id_pengajuan,
                'status_akt'   => 'aktif',
                'tgl_aktivasi' => now(),
                'masa_berlaku' => $masaBerlaku,
            ]);

            if (class_exists(PesanController::class)) {
                app(PesanController::class)->sendNotifikasiAktifasi($pengajuan->id_pengajuan);
            }

            DB::commit();
            return back()->with('success', 'Domain berhasil diaktifkan. Masa berlaku diperpanjang hingga ' . $masaBerlaku->format('d-m-Y'));
            
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan sistem: ' . $e->getMessage());
        }
    }

    public function adminDaftarAktif(Request $request)
    {
        // Update kadaluarsa otomatis
        Aktivasi::where('masa_berlaku', '<', now())
            ->where('status_akt', 'aktif')
            ->update(['status_akt' => 'kadaluarsa']);

        $statusFilter = $request->get('status', 'all');
        $kecamatanFilter = $request->get('kecamatan');

        $query = Pengajuan::with('faktur', 'aktivasi')->where('status_pengajuan', 'aktif');

        if ($statusFilter == 'aktif') {
            $query->whereHas('aktivasi', fn($q) => $q->where('status_akt', 'aktif'));
        } elseif ($statusFilter == 'kadaluarsa') {
            $query->whereHas('aktivasi', fn($q) => $q->where('status_akt', 'kadaluarsa'));
        }

        if (!empty($kecamatanFilter)) {
            $query->where('kecamatan', $kecamatanFilter);
        }

        $data = $query->latest()->paginate(10)->appends([
            'status' => $statusFilter,
            'kecamatan' => $kecamatanFilter
        ]);

        $kecamatanList = Pengajuan::select('kecamatan')->distinct()->orderBy('kecamatan')->pluck('kecamatan');
        $baseQuery = Pengajuan::where('status_pengajuan', 'aktif');

        $totalDomain = $baseQuery->count();
        $totalAktif = (clone $baseQuery)->whereHas('aktivasi', fn($q) => $q->where('status_akt', 'aktif'))->count();
        $totalKadaluarsa = (clone $baseQuery)->whereHas('aktivasi', fn($q) => $q->where('status_akt', 'kadaluarsa'))->count();
        $totalNonaktif = $totalDomain - ($totalAktif + $totalKadaluarsa);

        return view('admin.domain_terdaftar', compact(
            'data', 'statusFilter', 'kecamatanFilter', 'kecamatanList',
            'totalDomain', 'totalAktif', 'totalNonaktif', 'totalKadaluarsa'
        ));
    }

    public function desaPerpanjang()
    {
        Aktivasi::where('masa_berlaku', '<', now())
            ->where('status_akt', 'aktif')
            ->update(['status_akt' => 'kadaluarsa']);
        
        $data = Pengajuan::where('id_user', auth()->id())->with('aktivasi')->latest()->paginate(10);

        $data->getCollection()->transform(function ($row) {
            $row->aktivasi_terakhir = $row->aktivasi()->latest()->first();

            $row->ada_faktur_belum_bayar = \App\Models\Faktur::where('id_pengajuan', $row->id_pengajuan)
                ->where('status', 'belum_bayar')
                ->exists();

            // Cek apakah ada permintaan perpanjangan yang benar-benar belum direspon faktur oleh admin
            $latestPesan = \App\Models\Pesan::where('id_pengajuan', $row->id_pengajuan)
                ->where('judul', 'Permintaan Perpanjangan Domain')
                ->latest()
                ->first();

            $row->menunggu_faktur = false;
            if ($latestPesan) {
                $fakturAdaSetelahPesan = \App\Models\Faktur::where('id_pengajuan', $row->id_pengajuan)
                    ->where('tipe', 'perpanjangan')
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

    /**
     * DESA: Ajukan Perpanjang (MASALAH 2 SELESAI)
     */
    /**
     * DESA: Ajukan Perpanjang (DIKUNCI: Cuma bisa 1 kali per siklus)
     */
    public function ajukanPerpanjang($id)
    {
        $pengajuan = Pengajuan::where('id_pengajuan', $id)
            ->where('id_user', auth()->id())
            ->firstOrFail();

        // 1. KUNCI jika ada pesan permintaan yang belum direspon faktur oleh admin
        $latestPesan = Pesan::where('id_pengajuan', $id)
            ->where('judul', 'Permintaan Perpanjangan Domain')
            ->latest()
            ->first();

        if ($latestPesan) {
            $fakturRespons = Faktur::where('id_pengajuan', $id)
                ->where('tipe', 'perpanjangan')
                ->where('created_at', '>', $latestPesan->created_at)
                ->exists();

            if (!$fakturRespons) {
                return redirect()->route('desa.perpanjang')
                    ->with('error', 'Permintaan perpanjangan sebelumnya masih diproses admin. Silakan tunggu faktur dibuat.');
            }

            if ($latestPesan->is_read == 0) {
                $latestPesan->is_read = 1;
                $latestPesan->save();
            }
        }

        // Ambil data faktur perpanjangan paling terakhir
        $fakturTerakhir = Faktur::where('id_pengajuan', $id)
            ->where('tipe', 'perpanjangan')
            ->latest()
            ->first();

        if ($fakturTerakhir) {
            // 2. KUNCI jika faktur perpanjangan terakhir masih belum dibayar
            if ($fakturTerakhir->status === 'belum_bayar') {
                return redirect()->route('desa.perpanjang')
                    ->with('error', 'Faktur perpanjangan sebelumnya sudah ada dan belum dibayar. Silakan cek menu Invoice.');
            }

            // 3. KUNCI TOTAL jika faktur perpanjangan terakhir sudah dibayar (artinya sudah beres perpanjangannya)
            if ($fakturTerakhir->status === 'sudah_bayar') {
                return redirect()->route('desa.perpanjang')
                    ->with('error', 'Anda sudah melakukan pengajuan perpanjangan untuk periode ini. Tidak bisa mengajukan lagi.');
            }
        }

        $adminId = User::where('role', 'admin')->value('id_user');
        
        if (!$adminId) {
            return redirect()->route('desa.perpanjang')->with('error', 'Admin tidak ditemukan.');
        }

        // Jika lolos semua pengecekan di atas, baru boleh bikin pesan pengajuan baru
        Pesan::create([
            'id_user'       => $adminId,
            'id_pengajuan'  => $pengajuan->id_pengajuan,
            'judul'         => 'Permintaan Perpanjangan Domain',
            'isi'           => 'Desa ' . $pengajuan->desa_kelurahan . ' ingin melakukan perpanjangan domain ' . $pengajuan->nama_domain . '.desa.id, kirimkan faktur.',
            'role_tujuan'   => 'admin',
            'is_read'       => 0
        ]);

        return redirect()->route('desa.perpanjang')
            ->with('success', 'Permintaan perpanjangan berhasil dikirim ke admin.');
    }

    public function adminPerpanjangList(Request $request)
    {
        $search = $request->get('search');
        $status = $request->get('status');
        $perPage = 10;

        $perpanjanganBelumBuatBase = Pesan::where('judul', 'Permintaan Perpanjangan Domain')
            ->pluck('id_pengajuan')
            ->filter(function ($id_pengajuan) {
                return !Faktur::where('id_pengajuan', $id_pengajuan)
                    ->where('tipe', 'perpanjangan')
                    ->exists();
            })
            ->toArray();

        $query = Pengajuan::with(['faktur' => fn($q) => $q->latest(), 'aktivasi']);

        if (!empty($search)) {
            $query->where(function($q) use ($search) {
                $q->where('nama_domain', 'like', "%$search%")
                  ->orWhere('nama_desa', 'like', "%$search%");
            });
        }

        if (!empty($status)) {
            if ($status === 'belum_dibuat') {
                $query->whereIn('id_pengajuan', $perpanjanganBelumBuatBase);
            } else {
                $query->whereHas('faktur', fn($q) => $q->where('tipe', 'perpanjangan')->where('status', $status));
            }
        }

        $allPengajuan = $query->latest()->get();
        $totalRows = 0;
        foreach ($allPengajuan as $row) {
            if (in_array($row->id_pengajuan, $perpanjanganBelumBuatBase)) {
                $totalRows++;
            }
            $totalRows += $row->faktur->where('tipe', 'perpanjangan')->count();
        }

        $page = $request->get('page', 1);
        $offset = ($page - 1) * $perPage;
        $displayRows = [];
        $currentRow = 0;

        foreach ($allPengajuan as $row) {
            if (in_array($row->id_pengajuan, $perpanjanganBelumBuatBase)) {
                if ($currentRow >= $offset && count($displayRows) < $perPage) {
                    $displayRows[] = ['pengajuan' => $row, 'faktur' => null, 'type' => 'belum_dibuat'];
                }
                $currentRow++;
            }

            foreach ($row->faktur as $fakturItem) {
                if ($fakturItem->tipe == 'perpanjangan') {
                    if ($currentRow >= $offset && count($displayRows) < $perPage) {
                        $displayRows[] = ['pengajuan' => $row, 'faktur' => $fakturItem, 'type' => 'faktur'];
                    }
                    $currentRow++;
                }
            }

            if (count($displayRows) >= $perPage) {
                break;
            }
        }

        $data = new \Illuminate\Pagination\LengthAwarePaginator(
            $displayRows, $totalRows, $perPage, $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        $data->appends(['search' => $search, 'status' => $status]);

        $perpanjanganBelumBuat = Pesan::where('judul', 'Permintaan Perpanjangan Domain')
            ->pluck('id_pengajuan')
            ->filter(fn($id) => !Faktur::where('id_pengajuan', $id)->where('tipe', 'perpanjangan')->exists())
            ->toArray();

        return view('admin.perpanjang.index', compact('data', 'perpanjanganBelumBuat'));
    }

    public function adminPerpanjangDetail($id)
    {
        $pengajuan = Pengajuan::find($id);

        if ($pengajuan) {
            $faktur = $pengajuan->faktur()->where('tipe', 'perpanjangan')->latest()->first();
        } else {
            $faktur = Faktur::find($id);
            if ($faktur) {
                $pengajuan = $faktur->pengajuan;
            } else {
                abort(404, 'Data tidak ditemukan');
            }
        }

        $pengajuan->load('dokumenPersyaratan', 'faktur', 'aktivasi');
        return view('admin.perpanjang.show', compact('faktur', 'pengajuan'));
    }
}