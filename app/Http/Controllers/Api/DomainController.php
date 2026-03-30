<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Domain;

class DomainController extends Controller
{
    public function checkDomain(Request $request)
    {
        $domain = $request->query('domain');

        if (!$domain) {
            return response()->json([
                'success' => false,
                'message' => 'Domain wajib diisi'
            ]);
        }

        $exists = Domain::where('nama_domain', $domain)->exists();

        return response()->json([
            'success' => true,
            'available' => !$exists
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_domain' => 'required',
            'organisasi' => 'required',
            'telepon' => 'required',
            'alamat' => 'required',
        ]);

        $domain = Domain::create([
            'nama_domain' => $request->nama_domain,
            'organisasi' => $request->organisasi,
            'telepon' => $request->telepon,
            'alamat' => $request->alamat,
            'provinsi' => $request->provinsi,
            'kota' => $request->kota,
            'kecamatan' => $request->kecamatan,
            'desa' => $request->desa,
            'kode_pos' => $request->kode_pos,
            'deskripsi' => $request->deskripsi,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Pendaftaran berhasil',
            'data' => $domain
        ]);
    }
}