<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {

        $request->validate([
            'nama' => 'required',
            'username' => 'required|unique:users',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed|min:6'
        ]);

        User::create([
            'nama_lengkap' => $request->nama,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'desa' // default desa
        ]);

        return redirect('/login')->with('success','Registrasi berhasil');
    }
}