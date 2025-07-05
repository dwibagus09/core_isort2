<?php

namespace App\Http\Controllers;

use App\Models\Sites;

use Illuminate\Http\Request;

class SiteSelectController extends Controller
{
    
    public function SiteSelect(Request $request)
    {
        $selectedSitesId = $request->input('selected_branch');

        session(['selected_branch' => $selectedSitesId]); // Menyimpan nilai cabang yang dipilih ke dalam session
        
        //return redirect()->back(); // Redirect kembali ke halaman sebelumnya atau halaman yang sesuai.
    }

}