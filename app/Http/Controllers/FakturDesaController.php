<?php

namespace App\Http\Controllers;

use App\Models\Faktur;
use App\Models\Pengajuan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\PesanController;

class FakturDesaController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $pengajuanIds = Pengajuan::where('id_user', $user->id_user)->pluck('id_pengajuan');

        $fakturs = Faktur::whereIn('id_pengajuan', $pengajuanIds)->latest()->get();

        if ($fakturs->isEmpty()) {
            return view('desa.faktur.empty');
        }

        return view('desa.faktur.index', compact('fakturs'));
    }

    public function show($id)
    {
        $user = Auth::user();
        $pengajuanIds = Pengajuan::where('id_user', $user->id_user)->pluck('id_pengajuan');

        $faktur = Faktur::where('id', $id)
            ->whereIn('id_pengajuan', $pengajuanIds)
            ->firstOrFail();

        return view('desa.faktur.show', compact('faktur'));
    }

    public function konfirmasiPembayaran(Request $request, $id)
    {
        $request->validate([
            'bukti_pembayaran' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $faktur = Faktur::findOrFail($id);
        $path = $request->file('bukti_pembayaran')->store('bukti_pembayaran', 'public');

        $faktur->bukti_pembayaran_path = $path;
        $faktur->status = 'sudah_bayar';
        $faktur->save();

 $pengajuan = Pengajuan::find($faktur->id_pengajuan);
        if ($pengajuan) {
            $pengajuan->status_pengajuan = 'menunggu_aktivasi';
            $pengajuan->save();
        }

         app(PesanController::class)->notifikasiBuktiPembayaran($faktur->id_pengajuan);

return redirect()->route('desa.faktur.index')
    ->with('success', 'Bukti pembayaran berhasil diunggah.');    }
}