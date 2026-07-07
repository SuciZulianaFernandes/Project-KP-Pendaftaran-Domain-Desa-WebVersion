<?php

namespace App\Http\Controllers;

use App\Models\Faktur;
use App\Models\Pengajuan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\PesanController;

class FakturDesaController extends Controller
{
        public function index(Request $request) // Tambahkan Request $request
    {
        $user = Auth::user();
        $pengajuanIds = Pengajuan::where('id_user', $user->id_user)->pluck('id_pengajuan');

        // 1. AMBIL INPUT PENCARIAN
        $search = $request->get('search');

        // 2. QUERY FAKTUR
        $query = Faktur::whereIn('id_pengajuan', $pengajuanIds);

        // 3. LOGIKA PENCARIAN (Cari No Invoice atau Nama Domain)
        if (!empty($search)) {
            $query->where(function($q) use ($search) {
                $q->where('no_invoice', 'like', "%$search%")
                  ->orWhereHas('pengajuan', function($pq) use ($search) {
                      $pq->where('nama_domain', 'like', "%$search%");
                  });
            });
        }

        // 4. UBAH GET() MENJADI PAGINATE(10)
        $fakturs = $query->latest()->paginate(10);

        // 5. APPEND PARAMETER
        $fakturs->appends(['search' => $search]);

        // Hapus pengecekan isEmpty() di controller biar flow paginationnya rapi
        // Empty state sudah ditangani di Blade (@forelse @empty)
        return view('desa.faktur.index', compact('fakturs'));
    }

        public function ajukanFaktur(Request $request, $id)
    {
        $request->validate([
            'durasi_tahun' => 'required|integer|min:1|max:5'
        ]);

        $pengajuan = Pengajuan::where('uuid', $id)
            ->where('id_user', auth()->id())
            ->firstOrFail();

        $adminId = \App\Models\User::where('role', 'admin')->value('id_user');

        \App\Models\Pesan::create([
            'id_user'       => $adminId,
            'id_pengajuan'  => $pengajuan->id_pengajuan,
            'judul'         => 'Konfirmasi Pembayaran Disetujui', // Sesuaikan dengan yang dicari admin
            'isi'           => 'Desa mengajukan permintaan faktur perpanjangan domain.',
            'role_tujuan'   => 'admin',
            'is_read'       => 0,
            'durasi_tahun'  => $request->durasi_tahun // Simpan pilihan tahun
        ]);

        return back()->with('success', 'Permintaan faktur berhasil dikirim ke admin.');
    }

    public function show($id)
    {
        $user = Auth::user();
        $pengajuanIds = Pengajuan::where('id_user', $user->id_user)->pluck('id_pengajuan');

        $faktur = Faktur::where('uuid', $id)
            ->whereIn('id_pengajuan', $pengajuanIds)
            ->firstOrFail();

        return view('desa.faktur.show', compact('faktur'));
    }

        public function konfirmasiPembayaran(Request $request, $id)
    {
        $request->validate([
            'bukti_pembayaran' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $user = Auth::user();
        $pengajuanIds = Pengajuan::where('id_user', $user->id_user)->pluck('id_pengajuan');

        $faktur = Faktur::where('uuid', $id)
            ->whereIn('id_pengajuan', $pengajuanIds)
            ->firstOrFail();

        $path = $request->file('bukti_pembayaran')->store('bukti_pembayaran', 'public');

        $faktur->bukti_pembayaran_path = $path;
        $faktur->status = 'sudah_bayar';
        $faktur->tanggal_konfirmasi = now(); // <--- TAMBAHKAN BARIS INI
        $faktur->save();

        $pengajuan = Pengajuan::find($faktur->id_pengajuan);
        if ($pengajuan) {
            $pengajuan->status_pengajuan = 'menunggu_aktivasi';
            $pengajuan->save();
        }

        app(PesanController::class)->notifikasiBuktiPembayaran($faktur->id_pengajuan);

        return redirect()->route('desa.faktur.index')
            ->with('success', 'Bukti pembayaran berhasil diunggah.');    
    }}