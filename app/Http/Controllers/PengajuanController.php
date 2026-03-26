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

    public function cekDomain(Request $request)
    {
        // Method ini bisa dihapus jika tidak digunakan lagi, karena API sudah menanganinya.
        $request->validate(['nama_domain' => 'required|string|max:100']);
        $domain = strtolower($request->nama_domain);
        $cek = Pengajuan::where('nama_domain', $domain)->first();
        if ($cek) {
            return back()->with(['status' => 'tidak_tersedia', 'domain' => $domain]);
        }
        return back()->with(['status' => 'tersedia', 'domain' => $domain]);
    }

    public function checkAvailabilityApi(Request $request)
    {
        $domain = strtolower($request->nama_domain);
        $isExists = Pengajuan::where('nama_domain', $domain)->exists();
        return response()->json(['available' => !$isExists]);
    }

    // --- METHOD BARU UNTUK MULTI-STEP ---

    public function showInformasiForm(Request $request)
    {
        if ($request->has('domain')) {
            session(['pengajuan.nama_domain' => $request->query('domain')]);
        }
        if (!session('pengajuan.nama_domain')) {
            return redirect()->route('pengajuan.index');
        }
        
        // Kirim data desa dari session ke view untuk mengisi form kembali
        $data_desa = session('pengajuan.data_desa', []);
        return view('desa.pengajuan.index2', compact('data_desa'));
    }

    public function storeInformasiForm(Request $request)
    {
        $data = $request->validate([
            'nama_desa' => 'required|string|max:255',
            'klasifikasi_instansi' => 'required|string|max:255',
            'Telepon' => 'required|string|max:50',
            'Faksimili' => 'required|string|max:50',
            'alamat' => 'required|string',
            'provinsi' => 'required|string|max:255',
            'kota_kabupaten' => 'required|string|max:255',
            'kecamatan' => 'required|string|max:255',
            'desa_kelurahan' => 'required|string|max:255',
            'kode_pos' => 'required|string|max:10',
        ]);

        session(['pengajuan.data_desa' => $data]);
        return redirect()->route('pengajuan.dokumen');
    }

    public function showDokumenForm()
    {
        if (!session('pengajuan.data_desa')) {
            return redirect()->route('pengajuan.informasi');
        }
        return view('desa.pengajuan.index3');
    }

    public function storeDokumenForm(Request $request)
    {
        $request->validate([
    'surat_permohonan' => 'required|file|mimes:pdf|max:2048',
    'perda_pembentukan_desa' => 'required|file|mimes:pdf|max:2048',
    'surat_kuasa' => 'required|file|mimes:pdf|max:2048',
]);

// CEK FILE TIDAK BOLEH SAMA
$files = [
    $request->file('surat_permohonan'),
    $request->file('perda_pembentukan_desa'),
    $request->file('surat_kuasa'),
];

// Ambil hash tiap file (biar akurat, bukan cuma nama)
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
        $files = $request->only(['surat_permohonan', 'perda_pembentukan_desa', 'surat_kuasa']);
        foreach ($files as $jenis => $file) {
            if ($request->hasFile($jenis)) {
                $path = $request->file($jenis)->store('pengajuan/dokumen', 'public');
                $dokumen[$jenis] = [
                    'nama_file' => $file->getClientOriginalName(),
                    'path_file' => $path,
                ];
            }
        }

        session(['pengajuan.data_dokumen' => $dokumen]);
        return redirect()->route('pengajuan.tinjau');
    }

    public function showTinjauForm()
    {
        if (!session('pengajuan.data_dokumen')) {
            return redirect()->route('pengajuan.dokumen');
        }
        
        $data = session('pengajuan');
        return view('desa.pengajuan.index4', compact('data'));
    }

    public function submitPengajuan(Request $request)
    {
        $allData = session('pengajuan');
        // Asumsikan user sudah login, jika tidak, sesuaikan
        $idUser = auth()->id(); 

        DB::beginTransaction();
        try {
            // 1. Buat data Desa
            $desa = Desa::create(array_merge(
                $allData['data_desa'],
                ['id_user' => $idUser]
            ));

            // 2. Buat data Pengajuan, gunakan id_desa dari langkah 1
            $pengajuan = $desa->pengajuans()->create([
                'nama_domain' => $allData['nama_domain'],
                'status_pengajuan' => 'ditinjau', // Status awal
                'tgl_pengajuan' => now(),
            ]);

            // 3. Buat data Dokumen Persyaratan, gunakan id_pengajuan dari langkah 2
            foreach ($allData['data_dokumen'] as $jenis => $dok) {
                $pengajuan->dokumenPersyaratan()->create([
                    'jenis_dokumen' => $jenis,
                    'nama_file' => $dok['nama_file'],
                    'path_file' => $dok['path_file'],
                ]);
            }

            DB::commit();
            session()->forget('pengajuan');
            return redirect()->route('pengajuan.index')->with('success', 'Pengajuan domain berhasil dikirim!');

        } catch (\Exception $e) {
            DB::rollBack();
            // Log error untuk debugging: \Log::error($e->getMessage());
            return redirect()->route('pengajuan.tinjau')->with('error', 'Terjadi kesalahan saat menyimpan data. Silakan coba lagi.');
        }
    }
}