<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{

public function edit()
{
return view('admin.profile');
}

public function update(Request $request)
{
    $user = Auth::user();

    // 1. Validasi data diri utama
    $request->validate([
        'name' => 'required|string|max:255',
        // Tambahkan pengecualian id_user saat ini biar nggak error unique saat save data sendiri
        'username' => 'required|string|max:255|unique:users,username,' . $user->id_user . ',id_user',
        'email' => 'required|email|max:255|unique:users,email,' . $user->id_user . ',id_user',
        'no_hp' => 'nullable|string|max:15',
        'password_baru' => 'nullable|min:8|confirmed'
    ]);

    // 2. Logika jika mau ganti password
    if ($request->filled('password_baru')) {
        // Cek dulu apakah password lama diisi
        if (!$request->filled('password_lama')) {
            return back()->with('error', 'Password lama wajib diisi jika ingin mengganti password baru.');
        }

        // Cek apakah password lama yang dimasukkan sesuai dengan di database
        if (!Hash::check($request->password_lama, $user->password)) {
            return back()->with('error', 'Password lama salah.');
        }

        // Kalau aman, timpa password-nya
        $user->password = Hash::make($request->password_baru);
    }

    // 3. Update data diri
    $user->name = $request->name;
    $user->username = $request->username;
    $user->email = $request->email;
    $user->no_hp = $request->no_hp;

    $user->save();

    return back()->with('success', 'Profil berhasil diperbarui');
}

}