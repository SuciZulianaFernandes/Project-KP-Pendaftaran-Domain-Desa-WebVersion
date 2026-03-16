<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pengajuan;

class PengajuanController extends Controller
{

    public function index()
    {
        return view('desa.pengajuan.index');
    }


    public function cekDomain(Request $request)
    {

        $request->validate([
            'nama_domain' => 'required|string|max:100'
        ]);

        $domain = strtolower($request->nama_domain);

        $cek = Pengajuan::where('nama_domain', $domain)->first();

        if ($cek) {
            return back()->with([
                'status' => 'tidak_tersedia',
                'domain' => $domain
            ]);
        }

        return back()->with([
            'status' => 'tersedia',
            'domain' => $domain
        ]);
    }
    public function checkAvailabilityApi(Request $request)
    {
        $domain = strtolower($request->nama_domain);

        // Gunakan exists() untuk performa lebih baik, karena kita hanya butahu tahu ada atau tidak
        $isExists = Pengajuan::where('nama_domain', $domain)->exists();

        // Kirim respons JSON
        return response()->json([
            'available' => !$isExists // Jika ada di DB, maka tidak tersedia (false)
        ]);
    }

}