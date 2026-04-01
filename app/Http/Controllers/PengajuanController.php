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
        return redirect()->route('desa.pengajuan.dokumen');
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

    DB::beginTransaction();
    try {

        // LANGSUNG SIMPAN KE PENGAJUAN (tanpa desa)
        $pengajuan = Pengajuan::create([
             'id_user' => auth()->id(),
            'nama_domain' => $allData['nama_domain'],
            'status_pengajuan' => 'ditinjau',
            'tgl_pengajuan' => now(),

            // simpan info desa ke sini aja
              'nama_desa' => $allData['data_desa']['nama_desa'],
    'telepon' => $allData['data_desa']['Telepon'],
    'faksimili' => $allData['data_desa']['Faksimili'],
    'alamat' => $allData['data_desa']['alamat'],
    'provinsi' => $allData['data_desa']['provinsi'],
    'kota_kabupaten' => $allData['data_desa']['kota_kabupaten'],
    'kecamatan' => $allData['data_desa']['kecamatan'],
    'desa_kelurahan' => $allData['data_desa']['desa_kelurahan'],
    'kode_pos' => $allData['data_desa']['kode_pos'],
        ]);

        // SIMPAN DOKUMEN
        foreach ($allData['data_dokumen'] as $jenis => $dok) {
            $pengajuan->dokumenPersyaratan()->create([
                'jenis_dokumen' => $jenis,
                'nama_file' => $dok['nama_file'],
                'path_file' => $dok['path_file'],
            ]);
        }

        DB::commit();
        session()->forget('pengajuan');

        return redirect()->route('desa.pengajuan.index')
            ->with('success', 'Pengajuan domain berhasil dikirim!');

    } catch (\Exception $e) {
        DB::rollBack();
        dd($e->getMessage());
        }
    }

    public function daftar()
{
    $data = Pengajuan::where('id_user', auth()->id())
        ->latest()
        ->get();

    return view('desa.verifikasi.daftar', compact('data'));
}

public function show($id)
{
    $pengajuan = Pengajuan::with('dokumenPersyaratan')->findOrFail($id);

    return view('desa.verifikasi.detail', compact('pengajuan'));
}

public function destroy($id)
{
    $pengajuan = Pengajuan::findOrFail($id);

    // hapus file juga (opsional tapi bagus)
    foreach ($pengajuan->dokumenPersyaratan as $dok) {
        \Storage::disk('public')->delete($dok->path_file);
    }

    $pengajuan->delete();

    return back()->with('success', 'Pengajuan berhasil dihapus');
}

public function updateDokumen(Request $request, $id)
{
    $dok = DokumenPersyaratan::findOrFail($id);

    if ($request->hasFile('file')) {
        // hapus lama
        \Storage::disk('public')->delete($dok->path_file);

        // simpan baru
        $path = $request->file('file')->store('pengajuan/dokumen', 'public');

        $dok->update([
            'nama_file' => $request->file('file')->getClientOriginalName(),
            'path_file' => $path,
        ]);
    }

    return back()->with('success', 'Dokumen berhasil diperbarui');
}

}