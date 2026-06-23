<?php

namespace App\Http\Controllers;

use App\Models\Pesan;
use App\Models\Pengajuan;
use App\Models\User;
use App\Models\Faktur;
use Illuminate\Http\Request;

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
        // SEKALIGUS ubah isi pesannya sesuai permintaan
        \App\Models\Pesan::where('id_pengajuan', $id)
            ->where('id_user', auth()->id())
            ->where('judul', 'Konfirmasi Pembayaran')
            ->update([
                'is_read' => 1, 
                'isi'     => 'Silahkan tunggu faktur dari admin kominfo' // Kalimat diubah di sini
            ]);

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

    public function notifikasiFakturDibuat($id_pengajuan, $tipe = 'baru')
{
    $pengajuan = Pengajuan::findOrFail($id_pengajuan);

    // CEK TIPE FAKTUR
    if ($tipe == 'perpanjangan') {

        $judul = 'Faktur Perpanjangan Dibuat';

        $isi = 'Faktur perpanjangan domain untuk desa '
            . $pengajuan->nama_desa .
            ' telah dibuat. Silakan lakukan pembayaran dan kirim bukti pembayaran melalui menu faktur.';

    } else {

        $judul = 'Faktur Telah Dibuat';

        $isi = 'Faktur domain untuk desa '
            . $pengajuan->nama_desa .
            ' telah dibuat. Silakan lakukan pembayaran dan kirim bukti pembayaran melalui menu faktur.';
    }

    // KIRIM PESAN KE DESA
    Pesan::create([
        'id_user'       => $pengajuan->id_user,
        'id_pengajuan'  => $pengajuan->id_pengajuan,
        'judul'         => $judul,
        'isi'           => $isi,
        'role_tujuan'   => 'desa',
        'is_read'       => 0
    ]);
}
public function destroy($id)
{
    $pesan = Pesan::findOrFail($id);

    // KEAMANAN:
    // Desa hanya boleh hapus pesan miliknya
    if (
        auth()->user()->role == 'desa' &&
        $pesan->id_user != auth()->id()
    ) {
        abort(403);
    }

    $pesan->delete();

    return back()->with('success', 'Pesan berhasil dihapus.');
}

public function destroySelected(Request $request)
{
    $ids = $request->pesan_ids;

    if (!$ids || count($ids) == 0) {
        return back()->with('error', 'Tidak ada pesan yang dipilih.');
    }

    // Desa hanya boleh hapus miliknya
    if (auth()->user()->role == 'desa') {

        Pesan::whereIn('id', $ids)
            ->where('id_user', auth()->id())
            ->delete();

    } else {

        // Admin
        Pesan::whereIn('id', $ids)->delete();
    }

    return back()->with('success', 'Pesan berhasil dihapus.');
}

public function markAllRead()
{
    Pesan::where('role_tujuan', 'admin')
        ->where('is_read', 0)
        ->update(['is_read' => 1]);

    return back()->with('success', 'Semua pesan ditandai sudah dibaca');
}

public function markAllReadDesa()
{
    // Tandai SEMUA pesan desa sebagai dibaca
    Pesan::where('id_user', auth()->id())
        ->where('role_tujuan', 'desa')
        ->where('is_read', 0)
        ->update(['is_read' => 1]);

    return back()->with('success', 'Semua pesan ditandai sudah dibaca');
}

// TAMBAHKAN FUNGSI INI
public function notifikasiPengajuanBaru($idPengajuan)
{
    $pengajuan = Pengajuan::findOrFail($idPengajuan);

    Pesan::create([
        'id_user'       => User::where('role', 'admin')->value('id_user'),
        'id_pengajuan'  => $pengajuan->id_pengajuan,
        'judul'         => 'Pengajuan Domain Baru',
        'isi'           => 'Desa ' . $pengajuan->nama_desa . ' mengajukan domain baru: ' . $pengajuan->nama_domain . '.desa.id. Silakan proses pengajuan.',
        'role_tujuan'   => 'admin'
    ]);
}

}