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

    // Tandai pesan "Konfirmasi Pembayaran" dari admin sebagai sudah dibaca oleh desa
    \App\Models\Pesan::where('id_pengajuan', $id)
        ->where('id_user', auth()->id())
        ->where('judul', 'Konfirmasi Pembayaran')
        ->update(['is_read' => 1]);

    // Kirim balik pesan ke admin bahwa desa siap bayar
    \App\Models\Pesan::create([
        'id_user'       => \App\Models\User::where('role', 'admin')->value('id_user'),
        'id_pengajuan'  => $pengajuan->id_pengajuan,
        'judul'         => 'Konfirmasi Pembayaran Disetujui',
        'isi'           => 'Desa '.$pengajuan->nama_desa.' menyetujui konfirmasi pembayaran untuk domain '.$pengajuan->nama_domain.'.desa.id. Silakan buat dan kirim faktur.',
        'role_tujuan'   => 'admin'
    ]);

    return back()->with('success', 'Konfirmasi dikirim ke admin');
}
    public function notifikasiBuktiPembayaran($idPengajuan)
    {
        $pengajuan = Pengajuan::findOrFail($idPengajuan);

        // Bagian ini sudah benar, tidak ada foreach
        Pesan::create([
            'id_user'       => User::where('role', 'admin')->value('id_user'),
            'id_pengajuan'  => $pengajuan->id_pengajuan,
            'judul'         => 'Bukti Pembayaran',
            'isi'           => 'Desa ' . $pengajuan->nama_desa . ' telah mengunggah bukti pembayaran untuk domain ' . $pengajuan->nama_domain . '.desa.id.',
            'role_tujuan'   => 'admin'
        ]);
    }

    public function sendNotifikasiAktifasi($idPengajuan)
    {
        $pengajuan = Pengajuan::findOrFail($idPengajuan);

        // Kirim pesan ke Desa
        Pesan::create([
            'id_user'       => $pengajuan->id_user,
            'id_pengajuan'  => $pengajuan->id_pengajuan,
            'judul'         => 'Domain Aktif',
            'isi'           => 'Domain ' . $pengajuan->nama_domain . '.desa.id Anda telah diaktifkan',
            'role_tujuan'   => 'desa'
        ]);
    }
}