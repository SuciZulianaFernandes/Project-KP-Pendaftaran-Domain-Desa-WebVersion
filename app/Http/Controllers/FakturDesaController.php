<?php

namespace App\Http\Controllers;

use App\Models\Faktur;
use App\Models\Pengajuan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class FakturDesaController extends Controller
{
    /**
     * Tampilkan faktur yang dikirim untuk user desa yang sedang login.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Ambil user yang sedang login
        $user = Auth::user();
        
        // Cari pengajuan yang dimiliki oleh user ini
        $pengajuan = Pengajuan::where('id_user', $user->id)->first();
        
        // Jika tidak ada pengajuan, tampilkan halaman kosong
        if (!$pengajuan) {
            return view('desa.faktur.empty');
        }

        // Cari faktur yang sudah dikirim (status 'dikirim') untuk pengajuan ini
        $faktur = Faktur::where('id_pengajuan', $pengajuan->id_pengajuan)
                         ->where('status', 'dikirim')
                         ->first();

        // Jika tidak ada faktur yang dikirim, tampilkan halaman kosong
        if (!$faktur) {
            return view('desa.faktur.empty');
        }

        // Jika faktur ditemukan, tampilkan detailnya
        return view('desa.faktur.index', compact('faktur'));
    }

    /**
     * Proses upload bukti pembayaran dari user desa.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function konfirmasiPembayaran(Request $request, $id)
    {
        // Validasi input: pastikan file ada, berupa gambar, dan ukurannya maksimal 2MB
        $request->validate([
            'bukti_pembayaran' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Cari faktur berdasarkan ID, jika tidak ada akan otomatis menampilkan 404
        $faktur = Faktur::findOrFail($id);

        // Simpan file bukti pembayaran ke folder 'storage/app/public/bukti_pembayaran'
        $path = $request->file('bukti_pembayaran')->store('bukti_pembayaran', 'public');

        // Update data faktur
        $faktur->bukti_pembayaran_path = $path;
        $faktur->status = 'menunggu_verifikasi'; // Ubah status menunggu verifikasi admin
        $faktur->save();

        // Kembalikan ke halaman sebelumnya dengan pesan sukses
        return back()->with('success', 'Bukti pembayaran berhasil diunggah. Menunggu verifikasi dari admin.');
    }
}