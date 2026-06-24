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
    $user = Auth::user();

    // 1. TAMBAHKAN VALIDASI UNTUK name, username, DAN email
    $request->validate([
        'name'             => 'required|string|max:255',
        'username'         => 'required|string|max:255|unique:users,username,' . $user->id_user . ',id_user',
        'email'            => 'required|email|max:255|unique:users,email,' . $user->id_user . ',id_user',
        'nama_desa'        => 'required|string|max:100',
        'nama_kepala_desa' => 'nullable|string|max:100',
        'nip_kepala_desa'  => 'nullable|string|max:30',
        'alamat'           => 'nullable|string',
        'id_prov'          => 'nullable|string|max:10',
        'id_kab'           => 'nullable|string|max:10',
        'password'         => 'nullable|min:6|confirmed',
    ]);

    // 2. UPDATE DATA USER (Nama Admin, Username, Email, & Password)
    $user->name = $request->name;
    $user->username = $request->username;
    $user->email = $request->email;

    if ($request->filled('password')) {
        $user->password = Hash::make($request->password);
    }
    $user->save(); // Simpan ke tabel users

    // 3. UPDATE DATA WILAYAH DESA
    $desa = Desa::where('id_user', $user->id_user)->first();

    if (!$desa) {
        $desa = new Desa();
        $desa->id_user = $user->id_user;
    }

    $desa->nama_desa = $request->nama_desa;
    $desa->nama_kepala_desa = $request->nama_kepala_desa;
    $desa->nip_kepala_desa = $request->nip_kepala_desa;
    $desa->alamat = $request->alamat;
    $desa->id_prov = $request->id_prov;
    $desa->id_kab = $request->id_kab;
    $desa->save(); // Simpan ke tabel desa

    return redirect()
        ->back()
        ->with('success', 'Profile akun dan data desa berhasil diperbarui');
}
}