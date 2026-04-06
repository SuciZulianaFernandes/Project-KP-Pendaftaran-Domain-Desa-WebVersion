<?php

namespace App\Http\Controllers;

use App\Models\Pesan;
use App\Models\Pengajuan;
use App\Models\User;
use App\Models\Faktur;

class PesanController extends Controller
{
    public function index()
    {
       $data = Pesan::where('id_user', auth()->id())
    ->where('role_tujuan', 'desa') 
    ->latest()
    ->get();
        return view('desa.pesan.index', compact('data'));
    }

    public function adminIndex()
{
     $data = Pesan::where('role_tujuan', 'admin')
    ->latest()
    ->get();

    return view('admin.pesan.index', compact('data'));
}

    public function konfirmasiPembayaran($id)
{
    $pengajuan = Pengajuan::findOrFail($id);

    // tandai pesan lama sebagai sudah diproses
    Pesan::where('id_pengajuan', $id)
        ->where('id_user', auth()->id())
        ->update(['is_read' => 1]);



 $admins = User::where('role', 'admin')->get();

foreach ($admins as $admin) {
    Pesan::create([
        'id_user' => $admin->id_user,
        'id_pengajuan' => $pengajuan->id_pengajuan,
        'judul' => 'Konfirmasi Pembayaran Disetujui',
        'isi' => 'Desa '.$pengajuan->nama_desa.' menyetujui konfirmasi pembayaran untuk domain '.$pengajuan->nama_domain.'.desa.id. Silakan buat dan kirim faktur.',
        'role_tujuan' => 'admin'
    ]);
}


    return back()->with('success', 'Konfirmasi dikirim ke admin');
}

}
