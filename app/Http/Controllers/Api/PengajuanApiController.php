<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pengajuan;
use App\Models\Faktur;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PengajuanApiController extends Controller
{
    public function checkDomain(Request $request)
    {
        $domain = strtolower($request->nama_domain);

        $exists = Pengajuan::where('nama_domain', $domain)->exists();

        return response()->json([
            'success' => true,
            'available' => !$exists,
        ], 200);
    }

    public function submit(Request $request)
    {
        $request->validate([
            'id_user' => 'required',
            'nama_domain' => 'required|string|max:100|unique:pengajuan,nama_domain',

            'nama_desa' => 'required|string|max:150',
            'telepon' => 'required|string|max:20',
            'faksimili' => 'nullable|string|max:20',
            'alamat' => 'required|string',
            'provinsi' => 'required|string',
            'kota_kabupaten' => 'required|string',
            'kecamatan' => 'required|string',
            'desa_kelurahan' => 'required|string',
            'kode_pos' => 'required|string|max:10',

            'surat_permohonan' => 'required|file|mimes:pdf|max:2048',
            'perda_pembentukan_desa' => 'required|file|mimes:pdf|max:2048',
            'surat_kuasa' => 'required|file|mimes:pdf|max:2048',
            'surat_penunjukan_pejabat' => 'required|file|mimes:pdf|max:2048',
            'ktp_asn_pejabat' => 'required|file|mimes:pdf|max:2048',
        ]);

        DB::beginTransaction();

        try {
            $pengajuan = Pengajuan::create([
                'id_user' => $request->id_user,
                'nama_domain' => strtolower($request->nama_domain),
                'status_pengajuan' => 'ditinjau',
                'tgl_pengajuan' => now(),

                'nama_desa' => $request->nama_desa,
                'telepon' => $request->telepon,
                'faksimili' => $request->faksimili,
                'alamat' => $request->alamat,
                'provinsi' => $request->provinsi,
                'kota_kabupaten' => $request->kota_kabupaten,
                'kecamatan' => $request->kecamatan,
                'desa_kelurahan' => $request->desa_kelurahan,
                'kode_pos' => $request->kode_pos,
            ]);

            $files = [
                'surat_permohonan',
                'perda_pembentukan_desa',
                'surat_kuasa',
                'surat_penunjukan_pejabat',
                'ktp_asn_pejabat',
            ];

            foreach ($files as $jenis) {
                $file = $request->file($jenis);

                if (!$file || !$file->isValid()) {
                    throw new \Exception("File $jenis tidak valid");
                }

                $filename = $jenis . '_' . time() . '_' . Str::random(5) . '.pdf';

                $path = $file->storeAs(
                    'pengajuan/dokumen',
                    $filename,
                    'public'
                );

                $pengajuan->dokumenPersyaratan()->create([
                    'jenis_dokumen' => $jenis,
                    'nama_file' => $filename,
                    'path_file' => $path,
                ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Pengajuan berhasil dikirim',
                'data' => [
                    'id_pengajuan' => $pengajuan->id_pengajuan,
                    'nama_domain' => $pengajuan->nama_domain,
                ],
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('ERROR API PENGAJUAN', [
                'message' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function getPengajuanUser(Request $request)
    {
        try {
            $data = Pengajuan::with([
                'dokumenPersyaratan',
                'faktur',
            ])
                ->where('id_user', $request->id_user)
                ->latest()
                ->get();

            return response()->json([
                'success' => true,
                'data' => $data,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function riwayat(Request $request)
    {
        try {
            $data = Pengajuan::with([
                'dokumenPersyaratan',
                'faktur',
            ])
                ->where('id_user', $request->id_user)
                ->latest()
                ->get();

            return response()->json([
                'success' => true,
                'data' => $data,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $pengajuan = Pengajuan::with('dokumenPersyaratan')->findOrFail($id);

        if ($pengajuan->status_pengajuan != 'perlu_perbaikan') {
            return response()->json([
                'success' => false,
                'message' => 'Pengajuan tidak bisa diedit',
            ], 403);
        }

        DB::beginTransaction();

        try {
            $pengajuan->update([
                'nama_domain' => strtolower($request->nama_domain),
                'nama_desa' => $request->nama_desa,
                'telepon' => $request->telepon,
                'faksimili' => $request->faksimili,
                'alamat' => $request->alamat,
                'provinsi' => $request->provinsi,
                'kota_kabupaten' => $request->kota_kabupaten,
                'kecamatan' => $request->kecamatan,
                'desa_kelurahan' => $request->desa_kelurahan,
                'kode_pos' => $request->kode_pos,
                'status_pengajuan' => 'ditinjau',
                'catatan_umum' => null,
                'tgl_verifikasi' => null,
            ]);

            $files = [
                'surat_permohonan',
                'perda_pembentukan_desa',
                'surat_kuasa',
                'surat_penunjukan_pejabat',
                'ktp_asn_pejabat',
            ];

            foreach ($files as $jenis) {
                if ($request->hasFile($jenis)) {
                    $file = $request->file($jenis);

                    if (!$file || !$file->isValid()) {
                        throw new \Exception("File $jenis tidak valid");
                    }

                    $dokumen = $pengajuan->dokumenPersyaratan()
                        ->where('jenis_dokumen', $jenis)
                        ->first();

                    if ($dokumen && $dokumen->path_file) {
                        Storage::disk('public')->delete($dokumen->path_file);
                    }

                    $filename = $jenis . '_' . time() . '_' . Str::random(5) . '.pdf';

                    $path = $file->storeAs(
                        'pengajuan/dokumen',
                        $filename,
                        'public'
                    );

                    if ($dokumen) {
                        $dokumen->update([
                            'nama_file' => $filename,
                            'path_file' => $path,
                        ]);
                    } else {
                        $pengajuan->dokumenPersyaratan()->create([
                            'jenis_dokumen' => $jenis,
                            'nama_file' => $filename,
                            'path_file' => $path,
                        ]);
                    }
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Pengajuan berhasil diperbarui',
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('ERROR UPDATE API PENGAJUAN', [
                'message' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function uploadBuktiPembayaran(Request $request, $id)
    {
        $request->validate([
            'bukti_pembayaran' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        DB::beginTransaction();

        try {
            $pengajuan = Pengajuan::findOrFail($id);

            $faktur = Faktur::where('id_pengajuan', $pengajuan->id_pengajuan)->first();

            if (!$faktur) {
                $faktur = Faktur::create([
                    'id_pengajuan' => $pengajuan->id_pengajuan,
                    'nama_desa' => $pengajuan->nama_desa,
                    'nama_domain' => $pengajuan->nama_domain,
                    'no_invoice' => "INV/{$date}/{$random}",
                    'total' => 50000,
                    'status' => 'belum_bayar',
                    'tipe' => 'baru',
                    'expired_at' => now()->addDays(7),
                ]);
            }

            $file = $request->file('bukti_pembayaran');

            if (!$file || !$file->isValid()) {
                throw new \Exception('File bukti pembayaran tidak valid');
            }

            if ($faktur->bukti_pembayaran_path) {
                Storage::disk('public')->delete($faktur->bukti_pembayaran_path);
            }

            $extension = $file->getClientOriginalExtension();

            $filename = 'bukti_pembayaran_' .
                $pengajuan->id_pengajuan .
                '_' .
                time() .
                '_' .
                Str::random(5) .
                '.' .
                $extension;

            $path = $file->storeAs(
                'faktur/bukti_pembayaran',
                $filename,
                'public'
            );

            $faktur->bukti_pembayaran_path = $path;
            $faktur->status = 'sudah_bayar';
            $faktur->tanggal_konfirmasi = now();
            $faktur->save();

            $pengajuan->status_pengajuan = 'menunggu_aktivasi';
            $pengajuan->save();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Bukti pembayaran berhasil dikirim',
                'data' => $faktur,
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('ERROR UPLOAD BUKTI PEMBAYARAN', [
                'message' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}