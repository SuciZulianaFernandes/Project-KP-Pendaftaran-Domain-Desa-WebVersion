<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pengajuan;
use App\Models\DokumenPersyaratan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class PengajuanApiController extends Controller
{
    public function submit(Request $request)
    {
        $request->validate([
            'nama_domain' => 'required|string|max:100|unique:pengajuan,nama_domain',

            // DATA DESA
            'nama_desa' => 'required|string|max:150',
            'telepon' => 'required|string|max:20',
            'faksimili' => 'nullable|string|max:20',
            'alamat' => 'required|string',
            'provinsi' => 'required|string',
            'kota_kabupaten' => 'required|string',
            'kecamatan' => 'required|string',
            'desa_kelurahan' => 'required|string',
            'kode_pos' => 'required|string|max:10',

            // FILE (HARUS SESUAI ENUM DB)
            'surat_permohonan' => 'required|file|mimes:pdf|max:2048',
            'perda_pembentukan_desa' => 'required|file|mimes:pdf|max:2048',
            'surat_kuasa' => 'required|file|mimes:pdf|max:2048',
            'surat_penunjukan_pejabat' => 'required|file|mimes:pdf|max:2048',
            'ktp_asn_pejabat' => 'required|file|mimes:pdf|max:2048',
        ]);

        DB::beginTransaction();

        try {
            $pengajuan = Pengajuan::create([
                'id_user' => auth()->id(),
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

            $files = [
                'surat_permohonan',
                'perda_pembentukan_desa',
                'surat_kuasa',
                'surat_penunjukan_pejabat',
                'ktp_asn_pejabat',
            ];

            foreach ($files as $jenis) {

                $file = $request->file($jenis);

                if (!$file || !$file->isValid()) {
                    throw new \Exception("File $jenis tidak valid");
                }

                $filename = $jenis . '_' . time() . '_' . Str::random(5) . '.pdf';
                $path = $file->storeAs('pengajuan/dokumen', $filename, 'public');

                $pengajuan->dokumenPersyaratan()->create([
                    'jenis_dokumen' => $jenis,
                    'nama_file' => $filename,
                    'path_file' => $path,
                ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Pengajuan berhasil dikirim',
                'data' => [
                    'id_pengajuan' => $pengajuan->id_pengajuan,
                    'nama_domain' => $pengajuan->nama_domain
                ]
            ], 201);

        } catch (\Exception $e) {

            DB::rollBack();

            Log::error('ERROR API PENGAJUAN', [
                'message' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
    public function riwayat(Request $request)
    {
        try {
            $idUser = auth()->id();
            $data = Pengajuan::with('dokumenPersyaratan')
                ->where('id_user', $idUser)
                ->latest()
                ->get();

            return response()->json([
                'success' => true,
                'data' => $data
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
            // ================= UPDATE (PERBAIKAN) =================
    public function update(Request $request, $id)
    {
        $pengajuan = Pengajuan::findOrFail($id);

        if ($pengajuan->status_pengajuan != 'perlu_perbaikan') {
            return response()->json([
                'success' => false,
                'message' => 'Tidak bisa diedit'
            ], 403);
        }

        $pengajuan->update($request->all());
        $pengajuan->status_pengajuan = 'ditinjau';
        $pengajuan->save();

        return response()->json([
            'success' => true,
            'message' => 'Berhasil update'
        ]);
    }
}