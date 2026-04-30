<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Desa;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors(),
            ], 422);
        }

        $user = User::with('desa')
            ->where('username', $request->username)
            ->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Username atau password salah',
            ], 401);
        }

        return response()->json([
            'success' => true,
            'message' => 'Login berhasil',
            'role' => $user->role,
            'user' => [
                'id_user' => $user->id_user,
                'name' => $user->name,
                'username' => $user->username,
                'email' => $user->email,
                'no_hp' => $user->no_hp,
                'desa' => $user->desa,
            ],
        ], 200);
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|string|max:20',
            'password' => 'required|min:6',
            'confirmPassword' => 'required|same:password',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors(),
            ], 422);
        }

        $user = User::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'no_hp' => $request->phone,
            'password' => Hash::make($request->password),
            'role' => 'desa',
        ]);

        Desa::create([
            'id_user' => $user->id_user,
            'nama_desa' => $request->name,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Registrasi berhasil',
            'user' => [
                'id_user' => $user->id_user,
                'name' => $user->name,
                'username' => $user->username,
                'email' => $user->email,
                'no_hp' => $user->no_hp,
            ],
        ], 201);
    }

    public function profile(Request $request)
    {
        $user = User::with('desa')
            ->where('id_user', $request->id_user)
            ->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User tidak ditemukan',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'user' => [
                'id_user' => $user->id_user,
                'name' => $user->name,
                'username' => $user->username,
                'email' => $user->email,
                'no_hp' => $user->no_hp,
                'desa' => $user->desa,
            ],
        ], 200);
    }

    public function updateProfile(Request $request)
    {
        $user = User::where('id_user', $request->id_user)->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User tidak ditemukan',
            ], 404);
        }

        $user->name = $request->name;
        $user->email = $request->email;
        $user->no_hp = $request->no_hp;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Profil berhasil diperbarui',
        ], 200);
    }

    public function instansi(Request $request)
    {
        $desa = Desa::where('id_user', $request->id_user)->first();

        return response()->json([
            'success' => true,
            'desa' => $desa,
        ], 200);
    }

    public function updateInstansi(Request $request)
    {
        $desa = Desa::updateOrCreate(
            [
                'id_user' => $request->id_user,
            ],
            [
                'nama_desa' => $request->nama_desa,
                'nama_kepala_desa' => $request->nama_kepala_desa,
                'nip_kepala_desa' => $request->nip_kepala_desa,
                'no_hp_kepala_desa' => $request->no_hp_kepala_desa,
                'alamat' => $request->alamat,
            ]
        );

        return response()->json([
            'success' => true,
            'message' => 'Informasi instansi berhasil disimpan',
            'desa' => $desa,
        ], 200);
    }
}