<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pengajuan;
use App\Models\Aktivasi;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        /*
        |--------------------------------------------------------------------------
        | QUERY DASAR
        |--------------------------------------------------------------------------
        | Jika admin -> semua data
        | Jika desa  -> hanya data milik user login
        */

        $pengajuanQuery = Pengajuan::query();

        if ($user->role !== 'admin') {
            $pengajuanQuery->where('id_user', $user->id_user);
        }

        /*
        |--------------------------------------------------------------------------
        | TOTAL STATUS PENGAJUAN
        |--------------------------------------------------------------------------
        */

        $totalDomain = (clone $pengajuanQuery)->count();

        $totalDitinjau = (clone $pengajuanQuery)
            ->where('status_pengajuan', 'ditinjau')
            ->count();

        $totalDiproses = (clone $pengajuanQuery)
            ->whereIn('status_pengajuan', ['diproses', 'proses', 'disetujui'])
            ->count();

        $totalPerbaikan = (clone $pengajuanQuery)
            ->where('status_pengajuan', 'perlu_perbaikan')
            ->count();

        /*
        |--------------------------------------------------------------------------
        | TOTAL DOMAIN AKTIF
        |--------------------------------------------------------------------------
        */

        $totalAktif = (clone $pengajuanQuery)
            ->where('status_pengajuan', 'aktif')
            ->whereHas('aktivasi', function ($q) {
                $q->where('status_akt', 'aktif');
            })
            ->count();

        /*
        |--------------------------------------------------------------------------
        | TOTAL DOMAIN KADALUARSA
        |--------------------------------------------------------------------------
        */

        $totalKadaluarsa = (clone $pengajuanQuery)
            ->where('status_pengajuan', 'aktif')
            ->whereHas('aktivasi', function ($q) {
                $q->where('status_akt', 'kadaluarsa');
            })
            ->count();

        /*
        |--------------------------------------------------------------------------
        | VIEW BERDASARKAN ROLE
        |--------------------------------------------------------------------------
        */

        if ($user->role === 'admin') {
            return view('admin.dashboard', compact(
                'totalDomain',
                'totalAktif',
                'totalDitinjau',
                'totalDiproses',
                'totalPerbaikan',
                'totalKadaluarsa'
            ));
        }

        return view('desa.dashboard', compact(
            'totalDomain',
            'totalAktif',
            'totalDitinjau',
            'totalDiproses',
            'totalPerbaikan',
            'totalKadaluarsa'
        ));
    }
}