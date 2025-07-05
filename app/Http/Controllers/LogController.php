<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Log;
use Illuminate\Support\Facades\Auth;

class LogController extends Controller
{
    /**
     * Menyimpan aktivitas user ke dalam tabel logs_1.
     *
     * @param string $action
     * @param mixed $data
     * @return void
     */
    public static function createLog($action, $data = null)
    {
        Log::create([
            'site_id'     => config('app.site_id'), // Sesuaikan jika ada konfigurasi site_id
            'user_id'     => Auth::id() ?? null, // Jika user belum login, simpan null
            'action'      => $action,
            'data'        => json_encode($data),
            'log_date'    => now(),
            'browser'     => request()->header('User-Agent'),
            'ip_address'  => request()->ip(),
            'url'         => request()->fullUrl(),
        ]);
    }
}
