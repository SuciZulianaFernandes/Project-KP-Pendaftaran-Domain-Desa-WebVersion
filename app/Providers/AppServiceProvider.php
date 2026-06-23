<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Pesan;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot()
{
    View::composer('*', function ($view) {
        $unreadPesan = 0;

        if (Auth::check()) {
            $user = Auth::user();

            if ($user->role === 'admin') {
                $unreadPesan = Pesan::where('role_tujuan', 'admin')
                    ->where('is_read', 0)
                    ->count();
                    
            } elseif ($user->role === 'desa') {
                // HANYA hitung pesan yang judulnya sesuai dengan filter di view
                $unreadPesan = Pesan::where('id_user', $user->id_user)
                    ->where('role_tujuan', 'desa')
                    ->where('is_read', 0)
                    ->where(function($query) {
                        $query->where('judul', 'like', '%Domain Aktif%')
                              ->orWhere('judul', 'like', '%Faktur Telah Dibuat%')
                              ->orWhere('judul', 'like', '%Faktur Perpanjangan Dibuat%')
                              ->orWhere('judul', 'like', '%Konfirmasi Pembayaran%')
                              ->orWhere('judul', 'like', '%Perlu Perbaikan%')
                              ->orWhere('judul', 'like', '%Ditolak%');
                    })
                    ->count();
            }
        }

        $view->with('unreadPesan', $unreadPesan);
    });
}
}
