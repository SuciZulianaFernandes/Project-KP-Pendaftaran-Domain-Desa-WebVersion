<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pengajuan;
use App\Models\DokumenPersyaratan;
use Illuminate\Support\Facades\DB;

class PengajuanApiController extends Controller
{

    public function checkDomain(Request $request)
    {
        $request->validate([
            'nama_domain' => 'required|string|max:100'
        ]);

        $domain = strtolower($request->nama_domain);

        $exists = Pengajuan::where('nama_domain', $domain)->exists();

        return response()->json([
            'success' => true,
            'available' => !$exists
        ]);
    }

    public function submit(Request $request)
    {
        $request->validate([
            'nama_domain' => 'required|string|max:100',

            // informasi
            'nama_desa' => 'required|string',
            'telepon' => 'required|string',
            'faksimili' => 'nullable|string',
            'alamat' => 'required|string',
            'provinsi' => 'required|string',
            'kota_kabupaten' => 'required|string',
            'kecamatan' => 'required|string',
            'desa_kelurahan' => 'required|string',
            'kode_pos' => 'required|string',

            // dokumen
            'surat_permohonan' => 'required|file|mimes:pdf,jpg,jpeg,png|max:1024',
            'surat_kuasa' => 'required|file|mimes:pdf,jpg,jpeg,png|max:1024',
            'surat_penunjukan' => 'required|file|mimes:pdf,jpg,jpeg,png|max:1024',
            'kartu_pegawai' => 'required|file|mimes:pdf,jpg,jpeg,png|max:1024',
            'dasar_hukum' => 'required|file|mimes:pdf,jpg,jpeg,png|max:1024',
        ]);

        DB::beginTransaction();
        try {

            // 🔥 SIMPAN PENGAJUAN
            $pengajuan = Pengajuan::create([
                'id_user' => auth()->id() ?? 1, // sementara
                'nama_domain' => strtolower($request->nama_domain),
                'status_pengajuan' => 'ditinjau',
                'tgl_pengajuan' => now(),

                'nama_desa' => $request->nama_desa,
                'telepon' => $request->telepon,
                'faksimili' => $request->faksimili,
                'alamat' => $request->alamat,
                'provinsi' => $request->provinsi,
                'kota_kabupaten' => $request->kota_kabupaten,
                'kecamatan' => $request->kecamatan,
                'desa_kelurahan' => $request->desa_kelurahan,
                'kode_pos' => $request->kode_pos,
            ]);

            // 🔥 SIMPAN DOKUMEN
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
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
