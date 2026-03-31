<?php

namespace App\Http\Controllers\Admin;
use App\Models\Desa;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rules;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::orderBy('id_user', 'desc')->paginate(10);
        return view('admin.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.users.create');
    }

        // app/Http/Controllers/Admin/UserController.php

public function store(Request $request)
{
    $role = $request->role;

    DB::beginTransaction();
    try {
        if ($role === 'desa') {
            $validated = $request->validate([
                'nama_desa' => 'required|string|max:255',
                'username'   => 'required|string|max:255|unique:users,username',
                'password'   => 'required|string|min:8|confirmed',
            ]);

            // Buat email dummy yang unik
            $dummyEmail = $validated['username'] . '@desa.local';

            $user = User::create([
                'name'     => $validated['nama_desa'],
                'username' => $validated['username'],
                'email'    => $dummyEmail, // <-- SELALU ISI EMAIL
                'password' => Hash::make($validated['password']),
                'role'     => 'desa',
            ]);

            Desa::create([
                'id_user'   => $user->id_user,
                'nama_desa' => $validated['nama_desa'],
            ]);

        } else { // Jika role Admin
            $validated = $request->validate([
                'username' => 'required|string|max:255|unique:users,username',
                'name'     => 'required|string|max:255',
                'email'    => 'required|string|email|max:255|unique:users,email',
                'no_hp'    => 'nullable|string|max:20',
                'password' => ['required', 'confirmed', Rules\Password::defaults()],
                'role'     => 'required|string|in:admin,desa',
            ]);

            User::create([
                'username' => $validated['username'],
                'name'     => $validated['name'],
                'email'    => $validated['email'],
                'no_hp'    => $validated['no_hp'],
                'password' => Hash::make($validated['password']),
                'role'     => $validated['role'],
            ]);
        }

        DB::commit();

        return redirect()->route('admin.users.index')
            ->with('success', 'User berhasil dibuat.');

    } catch (\Exception $e) {
        DB::rollBack();
        Log::error('Gagal membuat user: ' . $e->getMessage());

        return redirect()->back()
            ->withInput()
            ->with('error', 'Terjadi kesalahan saat menyimpan user. Silakan coba lagi.');
    }
}
    public function show(User $user)
    {
        return view('admin.users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $role = $request->role;
        $oldRole = $user->getOriginal('role'); // Dapatkan role lama sebelum diupdate

        DB::beginTransaction();
        try {
            if ($role === 'desa') {
                $validated = $request->validate([
                    'username'   => 'required|string|max:255|unique:users,username,' . $user->id_user . ',id_user',
                    'nama_desa' => 'required|string|max:255',
                    'password'   => 'nullable|string|min:8|confirmed',
                ]);

                // Siapkan data untuk update user
                $userData = [
                    'name'     => $validated['nama_desa'],
                    'username' => $validated['username'],
                    'email'    => $validated['username'] . '@desa.local', // Selalu gunakan email dummy
                    'role'     => 'desa',
                ];

                // Update password jika diisi
                if (!empty($validated['password'])) {
                    $userData['password'] = Hash::make($validated['password']);
                }

                $user->update($userData);

                // Update atau buat data desa terkait
                $user->desa()->updateOrCreate(
                    ['id_user' => $user->id_user],
                    ['nama_desa' => $validated['nama_desa']]
                );

            } else { // Role Admin
                $validated = $request->validate([
                    'username' => 'required|string|max:255|unique:users,username,' . $user->id_user . ',id_user',
                    'name'     => 'required|string|max:255',
                    'email'    => 'required|string|email|max:255|unique:users,email,' . $user->id_user . ',id_user',
                    'no_hp'    => 'nullable|string|max:20',
                    'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
                ]);

                // Siapkan data untuk update user
                $userData = [
                    'username' => $validated['username'],
                    'name'     => $validated['name'],
                    'email'    => $validated['email'],
                    'no_hp'    => $validated['no_hp'],
                    'role'     => 'admin',
                ];
                
                // Update password jika diisi
                if (!empty($validated['password'])) {
                    $userData['password'] = Hash::make($validated['password']);
                }

                $user->update($userData);
            }

            // Jika role berubah dari 'desa' ke 'admin', hapus data desa lama
            if ($oldRole === 'desa' && $role === 'admin') {
                $user->desa()->delete();
            }

            DB::commit();

            return redirect()->route('admin.users.index')
                ->with('success', 'User berhasil diperbarui.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Gagal memperbarui user: ' . $e->getMessage());

            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat memperbarui user. Silakan coba lagi.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        DB::beginTransaction();
        try {
            // Hapus data desa terkait jika ada, untuk menghindari error foreign key
            if ($user->desa) {
                $user->desa()->delete();
            }

            // Hapus user
            $user->delete();

            DB::commit();

            return redirect()->route('admin.users.index')
                ->with('success', 'User berhasil dihapus.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Gagal menghapus user: ' . $e->getMessage());

            return redirect()->route('admin.users.index')
                ->with('error', 'Gagal menghapus user. Mungkin ada data terkait yang harus dihapus terlebih dahulu.');
        }
    }
}