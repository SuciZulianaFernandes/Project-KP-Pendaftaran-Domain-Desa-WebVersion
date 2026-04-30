<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pengajuan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PengajuanApiController extends Controller
{
    // ================= LIST =================
    public function index(Request $request)
    {
        $query = Pengajuan::query();

        if ($request->status) {
            $query->where('status_pengajuan', $request->status);
        }

        $data = $query->latest()->get();

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    // ================= DETAIL =================
    public function show($id)
    {
        $pengajuan = Pengajuan::with('dokumenPersyaratan')->find($id);

        if (!$pengajuan) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan'
            ]);
        }

      
        $dokumen = [];

        foreach ($pengajuan->dokumenPersyaratan as $doc) {
            $dokumen[$doc->jenis_dokumen] = Storage::url($doc->path_file);
        }

        return response()->json([
            'success' => true,
            'data' => [
                ...$pengajuan->toArray(),
                'dokumen_urls' => $dokumen
            ]
        ]);
    }

    // ================= VERIFIKASI =================
    public function verifikasi(Request $request, $id)
    {
        $pengajuan = Pengajuan::findOrFail($id);

        if ($request->status == 'diproses') {
            $pengajuan->status_pengajuan = 'diproses';
        } else {
            $pengajuan->status_pengajuan = 'perlu_perbaikan';
        }

        $pengajuan->catatan_umum = $request->catatan;
        $pengajuan->tgl_verifikasi = now();
        $pengajuan->save();

        return response()->json([
            'success' => true,
            'message' => 'Verifikasi berhasil'
        ]);
    }
}