<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Log;

class LogControllerActions
{
    // public function handle(Request $request, Closure $next)
    // {
    //     $selectedBranch = session('selected_branch', 'all_branch');
    //     // Simpan log akses setiap function dalam controller
    //     Log::create([
    //         'site_id'     => $selectedBranch, 
    //         'user_id'     => Auth::id() ?? null,
    //         'action'      => class_basename($request->route()->getActionName()), // Nama function
    //         //'data'        => json_encode(['method' => $request->method()]),
    //         // 'data'       => json_encode(['method' => $request->method(), 'input' => $request->all()]), // Simpan input request
    //         'data'       => json_encode($logData['data']),
    //         'log_date'    => now(),
    //         'browser'     => $request->header('User-Agent'),
    //         'ip_address'  => $request->ip(),
    //         'url'         => $request->fullUrl() ?? null,
    //     ]);

    //     return $next($request);
    // }
    
    public function handle(Request $request, Closure $next)
    {
        // Lanjutkan request dulu
        $response = $next($request);

        // Cek apakah ada data yang disimpan dalam session untuk logging
        if (session()->has('log_data')) {
            $logData = session('log_data');

            // Simpan log dengan tambahan informasi dari request
            Log::create([
                'site_id'    => session('selected_branch', 'all_branch'),
                'user_id'    => Auth::id(),
                'action'     => $logData['action'],
                'data'       => json_encode($logData['data']),
                'log_date'   => now(),
                'browser'    => $request->header('User-Agent'),
                'ip_address' => $request->ip(),
                'url'        => $request->fullUrl(),
            ]);

            // Hapus session setelah log disimpan
            session()->forget('log_data');
        }

        return $response;
    }
   
}
