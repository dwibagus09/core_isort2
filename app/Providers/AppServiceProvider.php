<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Models\Sites;

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
    public function boot(): void
    {
        //
    //     View::composer('*', function ($view) {
    //     $view->with('sites', Sites::all());
    // });

    View::composer('*', function ($view) {
    // Ambil user yang sedang login
    $currentUser = Auth::user();
    // Jika tidak ada user yang login, kirimkan sites kosong
    if (!$currentUser) {
        $sites = collect(); // Collection kosong
    } elseif ($currentUser->role->role === 'Admin') {
        // Jika user adalah Admin, ambil sites sesuai site_id mereka
        $siteIds = explode(',', $currentUser->site_id);
        $sites = Sites::whereIn('site_id', $siteIds)->get();
    } else {
        // Jika bukan Admin, ambil semua site
        $sites = Sites::all();
    }

    // Kirimkan data ke semua view
    $view->with('sites', $sites);
    });

    }


}
