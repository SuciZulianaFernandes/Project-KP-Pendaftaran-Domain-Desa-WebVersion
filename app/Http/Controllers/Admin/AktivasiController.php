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
            // Tetap 'aktif' sesuai enum yang ada
            $pengajuan->status_pengajuan = 'aktif';
            $pengajuan->save();

            $faktur->status = 'sudah_bayar';
            $faktur->save();

            // KONFIGURASI MASA BERLAKU
            $masaBerlaku = now()->addYears(); 

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
            return back()->with('success', 'Domain berhasil diaktifkan selama 1 tahun.');
            
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan sistem: ' . $e->getMessage());
        }
    }

    public function adminDaftarAktif(Request $request)
{
    // Update kadaluarsa otomatis
    $expiredDomains = Aktivasi::where('masa_berlaku', '<', now())
        ->where('status_akt', 'aktif')
        ->get();

    foreach ($expiredDomains as $aktivasi) {
        $aktivasi->status_akt = 'kadaluarsa';
        $aktivasi->save();
    }

    $statusFilter = $request->get('status', 'all');

    // FILTER KECAMATAN
    $kecamatanFilter = $request->get('kecamatan');

    $query = Pengajuan::with('faktur', 'aktivasi')
        ->where('status_pengajuan', 'aktif');

    // FILTER STATUS
    if ($statusFilter == 'aktif') {
        $query->whereHas('aktivasi', function ($q) {
            $q->where('status_akt', 'aktif');
        });
    } elseif ($statusFilter == 'kadaluarsa') {
        $query->whereHas('aktivasi', function ($q) {
            $q->where('status_akt', 'kadaluarsa');
        });
    }

    // FILTER KECAMATAN
    if (!empty($kecamatanFilter)) {
        $query->where('kecamatan', $kecamatanFilter);
    }

    $data = $query->latest()->paginate(10);

    // AGAR FILTER TIDAK HILANG SAAT PINDAH HALAMAN
    $data->appends([
        'status' => $statusFilter,
        'kecamatan' => $kecamatanFilter
    ]);

    // LIST KECAMATAN UNTUK DROPDOWN
    $kecamatanList = Pengajuan::select('kecamatan')
        ->distinct()
        ->orderBy('kecamatan')
        ->pluck('kecamatan');

    $baseQuery = Pengajuan::where('status_pengajuan', 'aktif');

    $totalDomain = $baseQuery->count();

    $totalAktif = (clone $baseQuery)
        ->whereHas('aktivasi', fn($q) => $q->where('status_akt', 'aktif'))
        ->count();

    $totalKadaluarsa = (clone $baseQuery)
        ->whereHas('aktivasi', fn($q) => $q->where('status_akt', 'kadaluarsa'))
        ->count();

    $totalNonaktif = $totalDomain - ($totalAktif + $totalKadaluarsa);

    return view('admin.domain_terdaftar', compact(
        'data',
        'statusFilter',
        'kecamatanFilter',
        'kecamatanList',
        'totalDomain',
        'totalAktif',
        'totalNonaktif',
        'totalKadaluarsa'
    ));
}

    /**
     * LIST DESA: Halaman perpanjang
     */
    public function desaPerpanjang()
    {
        // Update status kadaluarsa di tabel aktivasi saja
        $expiredDomains = Aktivasi::where('masa_berlaku', '<', now())
            ->where('status_akt', 'aktif')
            ->get();

        foreach ($expiredDomains as $aktivasi) {
            $aktivasi->status_akt = 'kadaluarsa';
            $aktivasi->save();
            
            // JANGAN update tabel pengajuan agar tetap 'aktif'
        }
        
        $query = Pengajuan::where('id_user', auth()->id())
            ->with('aktivasi')
            ->latest();

        $data = $query->paginate(10);

        $data->getCollection()->transform(function ($row) {
            $row->aktivasi_terakhir = $row->aktivasi()->latest()->first();

            // Cek Faktur Belum Bayar
            $row->ada_faktur_belum_bayar = \App\Models\Faktur::where('id_pengajuan', $row->id_pengajuan)
                ->where('status', 'belum_bayar')
                ->exists();

            // Cek Status Menunggu Faktur
            $latestPesan = \App\Models\Pesan::where('id_pengajuan', $row->id_pengajuan)
                ->where('judul', 'Permintaan Perpanjangan Domain')
                ->latest('created_at')
                ->first();

            $row->menunggu_faktur = false;
            
            if ($latestPesan) {
                // Cek apakah ada FAKTUR PERPANJANGAN yang dibuat SETELAH pesan ini
                $fakturAdaSetelahPesan = \App\Models\Faktur::where('id_pengajuan', $row->id_pengajuan)
                    ->where('tipe', 'perpanjangan') // Filter tipe perpanjangan agar akurat
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
     * DESA: Ajukan Perpanjang (LOGIKA DIPERBAIKI)
     */
    public function ajukanPerpanjang($id)
    {
        $pengajuan = Pengajuan::where('id_pengajuan', $id)
            ->where('id_user', auth()->id())
            ->firstOrFail();

        // 1. Cari pesan perpanjangan terakhir untuk domain ini
        $latestPesan = Pesan::where('id_pengajuan', $id)
            ->where('judul', 'Permintaan Perpanjangan Domain')
            ->latest()
            ->first();

        // 2. Jika ada pesan lama yang masih belum dibaca (is_read = 0)
        if ($latestPesan && $latestPesan->is_read == 0) {
            
            // Cek apakah admin sudah merespons dengan membuat Faktur Perpanjangan?
            // (Meski pesan belum diread, admin mungkin sudah membuat faktur)
            $fakturRespons = Faktur::where('id_pengajuan', $id)
                ->where('tipe', 'perpanjangan')
                ->where('created_at', '>', $latestPesan->created_at)
                ->exists();

            if (!$fakturRespons) {
                // Jika TIDAK ada faktur setelah pesan -> Admin belum merespons -> TOLAK request baru
                return redirect()->route('desa.perpanjang')
                    ->with('error', 'Permintaan perpanjangan sebelumnya masih diproses admin. Silakan tunggu.');
            } else {
                // Jika ADA faktur setelah pesan -> Admin sudah merespons.
                // Kita anggap request lama selesai. Update status pesan lama jadi 'read' agar rapi.
                $latestPesan->is_read = 1;
                $latestPesan->save();
            }
        }

        // 3. Jika aman (tidak ada pesan pending, atau pesan lama sudah direspon), buat pesan baru
        $adminId = User::where('role', 'admin')->value('id_user');
        
        if (!$adminId) {
            return redirect()->route('desa.perpanjang')->with('error', 'Admin tidak ditemukan.');
        }

        Pesan::create([
            'id_user'       => $adminId,
            'id_pengajuan'  => $pengajuan->id_pengajuan,
            'judul'         => 'Permintaan Perpanjangan Domain',
            'isi'           => 'Desa ' . $pengajuan->nama_desa . ' ingin melakukan perpanjangan domain ' . $pengajuan->nama_domain . '.desa.id, kirimkan faktur.',
            'role_tujuan'   => 'admin'
        ]);

        return redirect()->route('desa.perpanjang')
            ->with('success', 'Permintaan perpanjangan berhasil dikirim ke admin.');
    }

        /**
     * LIST ADMIN: Halaman index perpanjangan
     * LOGIKA DIUBAH: Agar bisa menampilkan status "Belum Dibuat"
     */
    /**
     * LIST ADMIN: Halaman index perpanjangan dengan Global Search & Filter Status
     */
    public function adminPerpanjangList(Request $request)
{
    $search = $request->get('search');
    $status = $request->get('status');
    $perPage = 10;

    // Mengambil ID pengajuan yang meminta perpanjangan tetapi fakturnya belum dibuat
    $perpanjanganBelumBuatBase = Pesan::where('judul', 'Permintaan Perpanjangan Domain')
        ->pluck('id_pengajuan')
        ->filter(function ($id_pengajuan) {
            return !Faktur::where('id_pengajuan', $id_pengajuan)
                ->where('tipe', 'perpanjangan')
                ->exists();
        })
        ->toArray();

    // Bangun Query Utama Pengajuan
    $query = Pengajuan::with([
        'faktur' => function ($query) {
            $query->latest();
        },
        'aktivasi'
    ]);

    // 1. FILTER SEARCH (Mencari berdasarkan nama_domain atau nama_desa)
    if (!empty($search)) {
        $query->where(function($q) use ($search) {
            $q->where('nama_domain', 'like', "%$search%")
              ->orWhere('nama_desa', 'like', "%$search%");
        });
    }

    // 2. FILTER STATUS (Filter tingkat database)
    if (!empty($status)) {
        if ($status === 'belum_dibuat') {
            // Hanya ambil data yang ID-nya masuk ke daftar belum membuat faktur perpanjangan
            $query->whereIn('id_pengajuan', $perpanjanganBelumBuatBase);
        } else {
            // Filter status berdasarkan relasi tabel Faktur ('belum_bayar' atau 'sudah_bayar')
            $query->whereHas('faktur', function($q) use ($status) {
                $q->where('tipe', 'perpanjangan')
                  ->where('status', $status);
            });
        }
    }

    // Get semua pengajuan (tanpa pagination dulu)
    $allPengajuan = $query->latest()->get();

    // Hitung total baris yang akan ditampilkan
    $totalRows = 0;
    foreach ($allPengajuan as $row) {
        // 1 baris untuk pengajuan "belum dibuat"
        if (in_array($row->id_pengajuan, $perpanjanganBelumBuatBase)) {
            $totalRows++;
        }
        // + baris untuk setiap faktur perpanjangan
        $totalRows += $row->faktur->where('tipe', 'perpanjangan')->count();
    }

    // Tentukan offset berdasarkan halaman
    $page = $request->get('page', 1);
    $offset = ($page - 1) * $perPage;

    // Manual pagination: ambil baris yang sesuai dengan offset & perPage
    $displayRows = [];
    $currentRow = 0;

    foreach ($allPengajuan as $row) {
        // Baris "Belum Dibuat"
        if (in_array($row->id_pengajuan, $perpanjanganBelumBuatBase)) {
            if ($currentRow >= $offset && count($displayRows) < $perPage) {
                $displayRows[] = [
                    'pengajuan' => $row,
                    'faktur' => null,
                    'type' => 'belum_dibuat'
                ];
            }
            $currentRow++;
        }

        // Baris Faktur Perpanjangan
        foreach ($row->faktur as $fakturItem) {
            if ($fakturItem->tipe == 'perpanjangan') {
                if ($currentRow >= $offset && count($displayRows) < $perPage) {
                    $displayRows[] = [
                        'pengajuan' => $row,
                        'faktur' => $fakturItem,
                        'type' => 'faktur'
                    ];
                }
                $currentRow++;
            }
        }

        if (count($displayRows) >= $perPage) {
            break;
        }
    }

    // Buat LengthAwarePaginator (punya lastPage())
    $data = new \Illuminate\Pagination\LengthAwarePaginator(
        $displayRows,
        $totalRows,
        $perPage,
        $page,
        [
            'path' => $request->url(),
            'query' => $request->query(),
        ]
    );

    // Kunci parameter pencarian agar tidak hilang saat navigasi page halaman
    $data->appends([
        'search' => $search,
        'status' => $status
    ]);

    // Sesuaikan variabel penampung baris "Belum Dibuat" agar tetap sinkron setelah di-filtered
    $perpanjanganBelumBuat = Pesan::where('judul', 'Permintaan Perpanjangan Domain')
        ->pluck('id_pengajuan')
        ->filter(function ($id_pengajuan) {
            return !Faktur::where('id_pengajuan', $id_pengajuan)
                ->where('tipe', 'perpanjangan')
                ->exists();
        })
        ->toArray();

    return view('admin.perpanjang.index', compact(
        'data',
        'perpanjanganBelumBuat'
    ));
}

    /**
     * DETAIL ADMIN: Halaman show perpanjangan
     * LOGIKA DIUBAH: Bisa menerima ID Faktur atau ID Pengajuan
     */
       public function adminPerpanjangDetail($id)
    {
        // PERBAIKAN LOGIKA:
        // 1. Cek dulu apakah ID tersebut milik Pengajuan (untuk kasus Belum Dibuat)
        $pengajuan = Pengajuan::find($id);

        if ($pengajuan) {
            // Jika ID ditemukan di tabel Pengajuan, gunakan data tersebut.
            // Cari faktur perpanjangan terbaru yang terkait (jika ada).
            $faktur = $pengajuan->faktur()->where('tipe', 'perpanjangan')->latest()->first();
            
            if (!$faktur) {
                $faktur = null; // Pastikan null jika belum ada faktur
            }
        } else {
            // 2. Jika tidak ada di Pengajuan, baru cari di tabel Faktur (untuk kasus Invoice Sudah Ada)
            $faktur = Faktur::find($id);
            
            if ($faktur) {
                $pengajuan = $faktur->pengajuan;
            } else {
                // Jika tidak ketemu di Pengajuan maupun Faktur
                abort(404, 'Data tidak ditemukan');
            }
        }

        // Load relasi yang dibutuhkan view
        $pengajuan->load('dokumenPersyaratan', 'faktur', 'aktivasi');

        return view('admin.perpanjang.show', compact('faktur', 'pengajuan'));
    }
}