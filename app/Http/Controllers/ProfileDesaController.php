<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Desa;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileDesaController extends Controller
{
    public function index()
    {
        $desa = Desa::where('id_user', Auth::id())->first();

        return view('desa.profile.index', compact('desa'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'nama_desa' => 'required|string|max:100',
            'nama_kepala_desa' => 'nullable|string|max:100',
            'nip_kepala_desa' => 'nullable|string|max:30',
            'alamat' => 'nullable|string',
            'id_prov' => 'nullable|string|max:10',
            'id_kab' => 'nullable|string|max:10',
            'password' => 'nullable|min:6|confirmed',
        ]);

        $desa = Desa::where('id_user', Auth::id())->first();

        // kalau data desa belum ada
        if (!$desa) {
            $desa = new Desa();
            $desa->id_user = Auth::id();
        }

        $desa->nama_desa = $request->nama_desa;
        $desa->nama_kepala_desa = $request->nama_kepala_desa;
        $desa->nip_kepala_desa = $request->nip_kepala_desa;
        $desa->alamat = $request->alamat;
        $desa->id_prov = $request->id_prov;
        $desa->id_kab = $request->id_kab;

        $desa->save();

        // update password user
        if ($request->filled('password')) {
            $user = Auth::user();

            $user->password = Hash::make($request->password);
            $user->save();
        }

        return redirect()
            ->back()
            ->with('success', 'Profile berhasil diperbarui');
    }
}