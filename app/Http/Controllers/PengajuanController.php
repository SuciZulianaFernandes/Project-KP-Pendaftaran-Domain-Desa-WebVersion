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
            'Telepon' => 'required|string|max:50',
            'Faksimili' => 'nullable|string|max:50',
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
    // Validasi 5 File
    $request->validate([
        'surat_permohonan'         => 'required|file|mimes:pdf|max:2048',
        'perda_pembentukan_desa'   => 'required|file|mimes:pdf|max:2048',
        'surat_kuasa'              => 'required|file|mimes:pdf|max:2048',
        'surat_penunjukan_pejabat' => 'required|file|mimes:pdf|max:2048',
        'ktp_asn_pejabat'          => 'required|file|mimes:pdf|max:2048',
    ]);

    // CEK FILE TIDAK BOLEH SAMA (Menggunakan Hash)
    $files = [
        $request->file('surat_permohonan'),
        $request->file('perda_pembentukan_desa'),
        $request->file('surat_kuasa'),
        $request->file('surat_penunjukan_pejabat'),
        $request->file('ktp_asn_pejabat'),
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
    // Define mapping input name ke label yang lebih bersih jika diperlukan, 
    // tapi di sini kita simpan sesuai nama kolom database.
    $inputs = $request->only([
        'surat_permohonan', 
        'perda_pembentukan_desa', 
        'surat_kuasa', 
        'surat_penunjukan_pejabat', 
        'ktp_asn_pejabat'
    ]);

    foreach ($inputs as $jenis => $file) {
        if ($request->hasFile($jenis)) {
            $path = $request->file($jenis)->store('pengajuan/dokumen', 'public');
            $dokumen[$jenis] = [
                'nama_file' => $file->getClientOriginalName(), // Nama asli file
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

        // Return JSON untuk AJAX
        return response()->json([
            'success' => true,
            'message' => 'Pengajuan domain berhasil dikirim!'
        ]);

    } catch (\Exception $e) {
        DB::rollBack();
        
        // Return JSON error
        return response()->json([
            'success' => false,
            'message' => 'Terjadi kesalahan: ' . $e->getMessage()
        ], 500);
    }
}

    public function daftar()
{
    $data = Pengajuan::where('id_user', auth()->id())
    ->where('status_pengajuan', '!=', 'aktif')
        ->latest()
        ->paginate(10);

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

        public function adminIndex()
    {
        $data = Pengajuan::where('status_pengajuan', '!=', 'aktif')
            ->whereDoesntHave('faktur', function ($query) {
                $query->where('tipe', 'perpanjangan');
            })
            ->latest()
            ->paginate(10);

        return view('admin.pengajuan.index', compact('data'));
    }

public function adminDetail($id)
{
    // Tambahkan 'pesan' dan 'faktur' dalam with()
    $pengajuan = Pengajuan::with('dokumenPersyaratan', 'pesan', 'faktur')->findOrFail($id);
    
    return view('admin.pengajuan.detail', compact('pengajuan'));
}

public function verifikasi(Request $request, $id)
{
    $pengajuan = Pengajuan::findOrFail($id);
    
    $status = $request->status;
    $catatan = $request->catatan;

    $pengajuan->status_pengajuan = $status;
    $pengajuan->catatan_umum = $catatan;
    $pengajuan->save();

    // PERBAIKAN: Gunakan in_array agar aman, apapun value tombol prosesnya
    if (in_array($status, ['disetujui', 'diproses', 'proses'])) {
        
        // Kirim pesan: Minta Faktur
        \App\Models\Pesan::create([
            'id_user'       => $pengajuan->id_user,
            'id_pengajuan'  => $pengajuan->id_pengajuan,
            'judul'         => 'Konfirmasi Pembayaran',
            'isi'           => 'Pengajuan domain '.$pengajuan->nama_domain.'.desa.id telah disetujui. Silakan klik tombol untuk mengirimkan faktur.',
            'role_tujuan'   => 'desa'
        ]);

    } else {
        
        // Kirim pesan: Perlu Perbaikan
        \App\Models\Pesan::create([
            'id_user'       => $pengajuan->id_user,
            'id_pengajuan'  => $pengajuan->id_pengajuan,
            'judul'         => 'Perlu Perbaikan',
            'isi'           => 'Pengajuan domain '.$pengajuan->nama_domain.'.desa.id perlu perbaikan. Catatan: ' . $catatan,
            'role_tujuan'   => 'desa'
        ]);
    }

    return redirect()->route('admin.pengajuan.index')
        ->with('success', 'Berhasil verifikasi pengajuan');
}

}