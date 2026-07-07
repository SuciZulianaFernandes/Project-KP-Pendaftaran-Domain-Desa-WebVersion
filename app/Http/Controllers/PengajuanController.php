<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pengajuan;
use App\Models\Desa;
use App\Models\DokumenPersyaratan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PengajuanController extends Controller
{
    public function index()
    {
        return view('desa.pengajuan.index');
    }

    // --- METHOD BARU UNTUK MULTI-STEP ---

    public function showInformasiForm(Request $request)
    {
        if ($request->has('nama_domain')) {
            // Bersihkan dan simpan ke session
            $domain = strtolower(preg_replace('/\s+/', '', $request->nama_domain));
            session(['pengajuan.nama_domain' => $domain]);
        }
        
        if (!session('pengajuan.nama_domain')) {
            return redirect()->route('desa.pengajuan.index');
        }
        
        $data_desa = session('pengajuan.data_desa', []);
        return view('desa.pengajuan.index2', compact('data_desa'));
    }

    public function storeInformasiForm(Request $request)
    {
        $data = $request->validate([
            'nama_desa' => 'required|string|max:255',
            'Telepon' => 'required|string|max:50',
            'Faksimili' => 'nullable|string|max:50',
            'alamat' => 'required|string',
            'provinsi' => 'required|string|max:255',
            'kota_kabupaten' => 'required|string|max:255',
            'kecamatan' => 'required|string|max:255',
            'desa_kelurahan' => 'required|string|max:255',
            'kode_pos' => 'required|string|max:10',
        ]);

        $data['nama_desa'] = strip_tags(trim($data['nama_desa']));

        session(['pengajuan.data_desa' => $data]);
        return redirect()->route('desa.pengajuan.dokumen');
    }

    public function showDokumenForm()
    {
        if (!session('pengajuan.data_desa')) {
            return redirect()->route('desa.pengajuan.informasi');
        }
        return view('desa.pengajuan.index3');
    }

    public function storeDokumenForm(Request $request)
{
    // Validasi HANYA 3 File
    $request->validate([
        'surat_permohonan'         => 'required|file|mimes:pdf|max:2048',
        'surat_kuasa'              => 'required|file|mimes:pdf|max:2048',
        'perda_pembentukan_desa'  => 'required|file|mimes:pdf|max:2048',
    ]);

    // CEK FILE TIDAK BOLEH SAMA (Menggunakan Hash)
    $files = [
        $request->file('surat_permohonan'),
        $request->file('surat_kuasa'),
        $request->file('perda_pembentukan_desa'),
    ];

    $hashes = [];
    foreach ($files as $file) {
        $hash = md5_file($file->getRealPath());
        if (in_array($hash, $hashes)) {
            return back()->withErrors([
                'file' => 'Semua file harus berbeda, tidak boleh upload file yang sama.'
            ])->withInput();
        }
        $hashes[] = $hash;
    }

    $dokumen = [];
    $inputs = $request->only([
        'surat_permohonan', 
        'surat_kuasa', 
        'perda_pembentukan_desa'
    ]);

    foreach ($inputs as $jenis => $file) {
        if ($request->hasFile($jenis)) {
            $path = $request->file($jenis)->store('/pengajuan/dokumen');
            $dokumen[$jenis] = [
                'nama_file' => $file->getClientOriginalName(),
                'path_file' => $path,
            ];
        }
    }

    session(['pengajuan.data_dokumen' => $dokumen]);
    return redirect()->route('desa.pengajuan.tinjau');
}

    public function showTinjauForm()
    {
        if (!session('pengajuan.data_dokumen')) {
            return redirect()->route('desa.pengajuan.dokumen');
        }
        
        $data = session('pengajuan');
        return view('desa.pengajuan.index4', compact('data'));
    }

    public function submitPengajuan(Request $request)
    {
        $allData = session('pengajuan');

        // Cek duplikat domain di database saat submit
        $cekDomain = Pengajuan::where('nama_domain', $allData['nama_domain'])->first();
        if ($cekDomain) {
            return response()->json([
                'success' => false,
                'message' => 'Domain ' . $allData['nama_domain'] . '.desa.id sudah terdaftar dalam sistem.'
            ], 422);
        }

        DB::beginTransaction();
        try {
            $pengajuan = Pengajuan::create([
                'id_user' => auth()->id(),
                'nama_domain' => $allData['nama_domain'],
                'status_pengajuan' => 'ditinjau',
                'tgl_pengajuan' => now(),
                'nama_desa' => $allData['data_desa']['nama_desa'],
                'telepon' => $allData['data_desa']['Telepon'],
                'faksimili' => $allData['data_desa']['Faksimili'] ?? null,
                'alamat' => $allData['data_desa']['alamat'],
                'provinsi' => $allData['data_desa']['provinsi'],
                'kota_kabupaten' => $allData['data_desa']['kota_kabupaten'],
                'kecamatan' => $allData['data_desa']['kecamatan'],
                'desa_kelurahan' => $allData['data_desa']['desa_kelurahan'],
                'kode_pos' => $allData['data_desa']['kode_pos'],
            ]);

            foreach ($allData['data_dokumen'] as $jenis => $dok) {
                $pengajuan->dokumenPersyaratan()->create([
                    'jenis_dokumen' => $jenis,
                    'nama_file' => $dok['nama_file'],
                    'path_file' => $dok['path_file'],
                ]);
            }

            DB::commit();

\App\Models\Pesan::create([
            'id_user'       => \App\Models\User::where('role', 'admin')->value('id_user'),
            'id_pengajuan'  => $pengajuan->id_pengajuan,
            'judul'         => 'Pengajuan Domain Baru',
            'isi'           => 'Desa ' . $pengajuan->nama_desa . ' mengajukan pendaftaran domain ' . $pengajuan->nama_domain . '.desa.id. Silakan verifikasi pengajuan.',
            'role_tujuan'   => 'admin'
        ]);

            session()->forget('pengajuan');

            return response()->json([
                'success' => true,
                'message' => 'Pengajuan domain berhasil dikirim!'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    // --- BAGIAN DESA: DAFTAR PENGAJUAN ---
    // --- BAGIAN DESA: DAFTAR PENGAJUAN ---
public function daftar(Request $request)
{
    $search = $request->get('search');
    $status = $request->get('status'); // 1. Tangkap inputan status dari filter desa

    $query = Pengajuan::where('id_user', auth()->id())
        ->where('status_pengajuan', '!=', 'aktif');

    // 2. Logika pencarian berdasarkan keyword nama domain
    if (!empty($search)) {
        $query->where('nama_domain', 'like', "%$search%");
    }

    // 3. Logika filter berdasarkan status pengajuan
    if (!empty($status)) {
        $query->where('status_pengajuan', $status);
    }

    $data = $query->latest()->paginate(10);
    
    // 4. Pastikan status & search tetap terbawa saat ganti halaman pagination
    $data->appends(['search' => $search, 'status' => $status]);

    // 5. Kirim variabel $status ke view blade desa
    return view('desa.verifikasi.daftar', compact('data', 'search', 'status'));
}

    public function show($id)
    {
        $pengajuan = Pengajuan::with('dokumenPersyaratan')->findOrFail($id);
        return view('desa.verifikasi.detail', compact('pengajuan'));
    }

    public function destroy($id)
    {
        $pengajuan = Pengajuan::findOrFail($id);
        foreach ($pengajuan->dokumenPersyaratan as $dok) {
            Storage::disk('local')->delete($dok->path_file);
        }
        $pengajuan->delete();
        return back()->with('success', 'Pengajuan berhasil dihapus');
    }

    public function updateDokumen(Request $request, $id)
    {
        $dok = DokumenPersyaratan::findOrFail($id);
        if ($request->hasFile('file')) {
            \Storage::disk('public')->delete($dok->path_file);
            $path = $request->file('file')->store('/pengajuan/dokumen');
            $dok->update([
                'nama_file' => $request->file('file')->getClientOriginalName(),
                'path_file' => $path,
            ]);
        }
        return back()->with('success', 'Dokumen berhasil diperbarui');
    }

    public function kirimUlang($id)
    {
        $pengajuan = Pengajuan::findOrFail($id);

        if ($pengajuan->id_user !== auth()->id()) {
            abort(403);
        }

        if ($pengajuan->status_pengajuan !== 'perlu_perbaikan') {
            return back()->with('error', 'Pengajuan ini bukan dalam status perlu perbaikan.');
        }

        $pengajuan->status_pengajuan = 'ditinjau';
        $pengajuan->save();

        \App\Models\Pesan::create([
            'id_user'      => \App\Models\User::where('role', 'admin')->value('id_user'),
            'id_pengajuan' => $pengajuan->id_pengajuan,
            'judul'        => 'Perbaikan Dokumen Selesai',
            'isi'          => 'Desa ' . $pengajuan->nama_desa . ' telah memperbaiki dokumen untuk pengajuan domain ' . $pengajuan->nama_domain . '.desa.id. Silakan verifikasi ulang.',
            'role_tujuan'  => 'admin',
        ]);

        return redirect()->route('desa.verifikasi.detail', $pengajuan->id_pengajuan)
            ->with('success', 'Pengajuan berhasil dikirim ulang untuk ditinjau admin.');
    }

    // --- BAGIAN ADMIN: DAFTAR PENGAJUAN ---
    public function adminIndex(Request $request) 
    {
        $search = $request->get('search');
        $status = $request->get('status');

        $query = Pengajuan::where('status_pengajuan', '!=', 'aktif')
            ->whereDoesntHave('faktur', function ($query) {
                $query->where('tipe', 'perpanjangan');
            });

        if (!empty($search)) {
            $query->where(function($q) use ($search) {
                $q->where('nama_domain', 'like', "%$search%")
                  ->orWhere('nama_desa', 'like', "%$search%");
            });
        }

        if (!empty($status)) {
            $query->where('status_pengajuan', $status);
        }

        $data = $query->latest()->paginate(10);
        $data->appends(['search' => $search, 'status' => $status]);

        $totalDitinjau = Pengajuan::where('status_pengajuan', 'ditinjau')->count();
        $totalPerbaikan = Pengajuan::where('status_pengajuan', 'perlu_perbaikan')->count();
        $totalDiproses = Pengajuan::where('status_pengajuan', 'diproses')->count();
        $totalAktivasi = Pengajuan::where('status_pengajuan', 'menunggu_aktivasi')->count();

        return view('admin.pengajuan.index', compact(
            'data', 'search', 'totalDitinjau', 'totalPerbaikan', 'totalDiproses', 'totalAktivasi'
        ));
    }

    public function adminDetail($id)
    {
        $pengajuan = Pengajuan::with('dokumenPersyaratan', 'pesan', 'faktur')->findOrFail($id);
        return view('admin.pengajuan.detail', compact('pengajuan'));
    }

    public function verifikasi(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:diproses,perlu_perbaikan'
        ]);

        $pengajuan = Pengajuan::findOrFail($id);

        $status = $request->status;
        $catatan = $request->catatan;

        $pengajuan->status_pengajuan = $status;
        $pengajuan->catatan_umum = $catatan;
        $pengajuan->save();

        if ($status == 'diproses') {

            // Cek duplikat: jangan buat jika sudah ada yang belum dibaca
            $sudahAda = \App\Models\Pesan::where('id_pengajuan', $pengajuan->id_pengajuan)
                ->where('judul', 'Konfirmasi Pembayaran')
                ->where('role_tujuan', 'desa')
                ->where('is_read', 0)
                ->exists();

            if (!$sudahAda) {
                \App\Models\Pesan::create([
                    'id_user'       => $pengajuan->id_user,
                    'id_pengajuan'  => $pengajuan->id_pengajuan,
                    'judul'         => 'Konfirmasi Pembayaran',
                    'isi'           => 'Pengajuan domain ' . $pengajuan->nama_domain . '.desa.id telah disetujui. Silakan konfirmasi pembayaran dengan memilih masa berlaku domain di halaman detail.',
                    'role_tujuan'   => 'desa',
                    'is_read'       => 0
                ]);
            }

        } else {
            // perlu_perbaikan
            \App\Models\Pesan::create([
                'id_user'       => $pengajuan->id_user,
                'id_pengajuan'  => $pengajuan->id_pengajuan,
                'judul'         => 'Perlu Perbaikan',
                'isi'           => 'Pengajuan domain ' . $pengajuan->nama_domain . '.desa.id perlu perbaikan. Catatan: ' . $catatan,
                'role_tujuan'   => 'desa'
            ]);
        }

        return redirect()->route('admin.pengajuan.index')
            ->with('success', 'Berhasil verifikasi pengajuan');
    }

    public function lihatDokumen($id)
    {
        $dokumen = DokumenPersyaratan::findOrFail($id);
        $pengajuan = $dokumen->pengajuan;

        if (auth()->user()->role !== 'admin' && $pengajuan->id_user !== auth()->id()) {
            abort(403);
        }

        $file = storage_path('app/private/' . $dokumen->path_file);
        abort_unless(file_exists($file), 404);

        $response = response()->file($file);
        $response->headers->set('Content-Disposition', $response->headers->makeDisposition(
            \Symfony\Component\HttpFoundation\ResponseHeaderBag::DISPOSITION_INLINE,
            $dokumen->nama_file
        ));

        return $response;
    }
}