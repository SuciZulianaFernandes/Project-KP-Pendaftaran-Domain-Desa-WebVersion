<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pengajuan;
use App\Models\DokumenPersyaratan;
use Illuminate\Support\Facades\DB;

class PengajuanApiController extends Controller
{
    public function submit(Request $request)
    {
        // 1. Validasi (Ditambah field Akun dan Detail Desa)
        $request->validate([
            'nama_domain' => 'required|string|max:100',

            // === DATA AKUN (ADMIN) ===
            'username' => 'required|string|unique:users,username', // Cek agar username unik
            'password' => 'required|string|min:6',
            'name' => 'required|string', // Nama lengkap admin
            'email' => 'required|email|unique:users,email',
            'no_hp' => 'required|string',

            // === DATA DESA ===
            'nama_desa' => 'required|string',
            'nama_kepala_desa' => 'nullable|string',
            'nip_kepala_desa' => 'nullable|string',
            'klasifikasi_instansi' => 'nullable|string',
            'telepon' => 'required|string',
            'faksimili' => 'nullable|string',
            'alamat' => 'required|string',
            'provinsi' => 'required|string',
            'kota_kabupaten' => 'required|string',
            'kecamatan' => 'required|string',
            'desa_kelurahan' => 'required|string',
            'kode_pos' => 'required|string',

            // === DOKUMEN ===
            'surat_permohonan' => 'required|file|mimes:pdf,jpg,jpeg,png|max:1024',
            'surat_kuasa' => 'required|file|mimes:pdf,jpg,jpeg,png|max:1024',
            'surat_penunjukan' => 'required|file|mimes:pdf,jpg,jpeg,png|max:1024',
            'kartu_pegawai' => 'required|file|mimes:pdf,jpg,jpeg,png|max:1024',
            'dasar_hukum' => 'required|file|mimes:pdf,jpg,jpeg,png|max:1024',
        ]);

        DB::beginTransaction();
        try {
            // 2. Simpan Data Pengajuan (Termasuk Data Akun Sementara)
            $pengajuan = Pengajuan::create([
                'id_user' => null, // Null karena yang mendaftar belum punya akun (Public)
                'nama_domain' => strtolower($request->nama_domain),
                'status_pengajuan' => 'ditinjau',
                'tgl_pengajuan' => now(),

                // Simpan Data Desa
                'nama_desa' => $request->nama_desa,
                'nama_kepala_desa' => $request->nama_kepala_desa,
                'nip_kepala_desa' => $request->nip_kepala_desa,
                'klasifikasi_instansi' => $request->klasifikasi_instansi,
                'telepon' => $request->telepon,
                'faksimili' => $request->faksimili,
                'alamat' => $request->alamat,
                'provinsi' => $request->provinsi,
                'kota_kabupaten' => $request->kota_kabupaten,
                'kecamatan' => $request->kecamatan,
                'desa_kelurahan' => $request->desa_kelurahan,
                'kode_pos' => $request->kode_pos,

                // Simpan Data Akun (Sementara di tabel pengajuan untuk dilihat admin)
                'username' => $request->username,
                'password' => $request->password, // Admin akan lihat ini untuk membuat akun User nanti
                'name_user' => $request->name,
                'email' => $request->email,
                'no_hp_user' => $request->no_hp,
            ]);

            // 3. Simpan Dokumen
            $files = [
                'surat_permohonan',
                'surat_kuasa',
                'surat_penunjukan',
                'kartu_pegawai',
                'dasar_hukum'
            ];

            foreach ($files as $jenis) {
                if ($request->hasFile($jenis)) {
                    $file = $request->file($jenis);
                    // Simpan file di storage/app/public/pengajuan/dokumen
                    $path = $file->store('pengajuan/dokumen', 'public');

                    $pengajuan->dokumenPersyaratan()->create([
                        'jenis_dokumen' => $jenis,
                        'nama_file' => $file->getClientOriginalName(),
                        'path_file' => $path,
                    ]);
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Pengajuan berhasil dikirim'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
}