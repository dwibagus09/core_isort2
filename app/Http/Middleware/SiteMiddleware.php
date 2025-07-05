<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use App\Models\Sites;

class SiteMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // Ambil site_id dari request
        $siteId = $request->input('site_id', session('site_id'));

        // Simpan site_id ke session
        if ($siteId) {
            session(['site_id' => $siteId]);
        }

        // Ambil semua sites untuk dropdown
        $sites = Sites::all();

        // Bagikan ke semua view
        View::share([
            'sites' => $sites,
            'currentSiteId' => session('site_id') ?? null
        ]);

        return $next($request);
    }
}
?>