<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pengajuan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class PengajuanApiController extends Controller
{
    public function submit(Request $request)
    {
        // ================= VALIDASI =================
        $request->validate([
            'nama_domain' => 'required|string|max:100',

            // === DATA AKUN ===
            'username' => 'required|string|unique:users,username',
            'password' => 'required|string|min:6',
            'name' => 'required|string',
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
            // ================= SIMPAN PENGAJUAN =================
            $pengajuan = Pengajuan::create([
                'id_user' => null,
                'nama_domain' => strtolower($request->nama_domain),
                'status_pengajuan' => 'ditinjau',
                'tgl_pengajuan' => now(),

                // DATA DESA
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

                // DATA AKUN (sementara)
                'username' => $request->username,
                'password' => Hash::make($request->password), // 🔐 AMAN
                'name_user' => $request->name,
                'email' => $request->email,
                'no_hp_user' => $request->no_hp,
            ]);

            // ================= FILE =================
            $files = [
                'surat_permohonan',
                'surat_kuasa',
                'surat_penunjukan',
                'kartu_pegawai',
                'dasar_hukum'
            ];

            foreach ($files as $jenis) {

                if (!$request->hasFile($jenis)) {
                    DB::rollBack();
                    return response()->json([
                        'success' => false,
                        'message' => "File $jenis tidak ditemukan"
                    ], 400);
                }

                $file = $request->file($jenis);

                if (!$file->isValid()) {
                    DB::rollBack();
                    return response()->json([
                        'success' => false,
                        'message' => "File $jenis tidak valid"
                    ], 400);
                }

                $path = $file->store('pengajuan/dokumen', 'public');

                $pengajuan->dokumenPersyaratan()->create([
                    'jenis_dokumen' => $jenis,
                    'nama_file' => $file->getClientOriginalName(),
                    'path_file' => $path,
                ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Pengajuan berhasil dikirim'
            ]);

        } catch (\Exception $e) {

            DB::rollBack();

            // 🔥 logging biar gampang debug
            Log::error('ERROR PENGAJUAN API:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan pada server'
            ], 500);
        }
    }
}