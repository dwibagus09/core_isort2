<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sites;

class DashboardsController extends Controller
{
    
    public function index()
    {
        // Mengambil semua data dari tabel sites
        $sites = Sites::all();
        
        return view('pages.index', compact('sites'));
    }
    
    
}
