<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pengajuan;
use Illuminate\Http\Request;

class PengajuanApiController extends Controller
{
    // ================= LIST =================
    public function index()
    {
        $data = Pengajuan::latest()->get();

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

        return response()->json([
            'success' => true,
            'data' => $pengajuan
        ]);
    }

    // ================= VERIFIKASI =================
    public function verifikasi(Request $request, $id)
    {
        $pengajuan = Pengajuan::findOrFail($id);

        $status = $request->status; // disetujui / perbaikan
        $catatan = $request->catatan;

        if ($status == 'disetujui') {
            $pengajuan->status_pengajuan = 'diproses';
        } else {
            $pengajuan->status_pengajuan = 'perbaikan';
        }

        $pengajuan->catatan_umum = $catatan;
        $pengajuan->tgl_verifikasi = now();
        $pengajuan->save();

        return response()->json([
            'success' => true,
            'message' => 'Verifikasi berhasil'
        ]);
    }
}