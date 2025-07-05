<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Locations;
use App\Models\Area;
use App\Models\Sites;
use App\Models\Department;
use App\Models\Incidents;
use App\Models\Modus;

class ModusController extends Controller
{
     public function list($department_id)
    {
        // Ambil user yang sedang login
        $currentUser = Auth::user();
    
        // Debugging: Pastikan user ditemukan
        if (!$currentUser) {
            dd("Error: User tidak ditemukan.");
        }
    
        // Ubah site_id dari string "2,3" menjadi array [2,3]
        $siteIds = explode(',', $currentUser->site_id);
    
        // Ambil selected_branch dari session (default: 'all' jika kosong)
        $selectedBranchId = session('selected_branch', 'all');
    
        // Debugging: Pastikan siteIds tidak kosong
        if (empty($siteIds)) {
            dd("Error: siteIds kosong.", ["siteIds" => $siteIds, "User" => $currentUser]);
        }
    
        // Query untuk Sites (hanya untuk Admin)
        if ($currentUser->role->role === 'Admin') {
            $siteQuery = Sites::whereIn('site_id', $siteIds);
        } else {
            $siteQuery = Sites::query(); // Non-admin melihat semua site
        }
    
        // Filter selected_branch jika tidak 'all'
        if ($selectedBranchId !== 'all') {
            $siteQuery->whereRaw("FIND_IN_SET(?, site_id)", [$selectedBranchId]);
        }
    
        // Query untuk Department berdasarkan department_id
        if ($currentUser->role->role === 'Admin') {
            $departmentQuery = Department::where('id', $department_id)
                                         ->whereIn('site_id', $siteIds);
        } else {
            $departmentQuery = Department::where('id', $department_id);
        }
    
        // Query untuk Area berdasarkan department_id
        $areaQuery = Area::where('department_id', $department_id)
                         ->orderBy('sort_order', 'asc');
        
        $locationQuery = Incidents::where('department_id', $department_id)
                         ->orderBy('sort_order', 'asc');
        
        // Filter selected_branch jika tidak 'all'
        if ($selectedBranchId !== 'all') {
            $departmentQuery->whereRaw("FIND_IN_SET(?, site_id)", [$selectedBranchId]);
            $areaQuery->whereRaw("FIND_IN_SET(?, site_id)", [$selectedBranchId]);
            $locationQuery->whereRaw("FIND_IN_SET(?, site_id)", [$selectedBranchId]);
        }
    
        // Eksekusi query
        $department = $departmentQuery->first(); // Mengambil satu department berdasarkan ID
        $area = $areaQuery->get();
        $filteredsites = $siteQuery->first();
        $incident = $locationQuery->get();
       // dd($department_id);
       
        // // Debugging: Jika data tidak ditemukan
        // if (!$department) {
        //     dd("Error: Department tidak ditemukan!", [
        //         "User Role" => $currentUser->role->role,
        //         "Department ID" => $department_id,
        //         "Site IDs" => $siteIds,
        //         "Selected Branch" => $selectedBranchId,
        //         "Query" => $departmentQuery->toSql(),
        //         "Bindings" => $departmentQuery->getBindings()
        //     ]);
        // }
    
        // if ($locations->isEmpty()) {
        //     dd("Error: Data area kosong!", [
        //         "Department ID" => $department_id,
        //         "Query" => $areaQuery->toSql(),
        //         "Bindings" => $areaQuery->getBindings()
        //     ]);
        // }
    
        return view('pages.modus.index', compact('department_id', 'department','incident', 'area', 'filteredsites'));
    }
    
    public function store(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'incident_name' => 'required|string|max:255',
            'site_id' => 'required',
            'department_id' => 'required|string|max:255',
            'sort_order' => 'required|string|max:255',
            'modus_name' => 'required|string|max:255',
            // 'location_name' => 'required|string|max:255',
        ]);
    
        // Simpan data ke database
        $dept = new Modus();
        // $dept->location_id = $validated['location_name'];
        $dept->site_id = $validated['site_id'];
        $dept->department_id = $validated['department_id'];
        // $dept->area_id = $validated['area_name'];
        $dept->incident_id = $validated['incident_name'];
        $dept->modus_name = $validated['modus_name'];
        $dept->sort_order = $validated['sort_order'];
       // dd($dept);
        $dept->save();
    
        return response()->json(['success' => 'Modus created successfully!']);
    }
    
    public function getIncident($id)
    {
        // Ambil user yang sedang login
        $currentUser = Auth::user();
    
        // Pastikan user ditemukan
        if (!$currentUser) {
            return response()->json(["error" => "Icidents tidak ditemukan"], 400);
        }
    
        // Ubah site_id dari string "2,3" menjadi array [2,3]
        $siteIds = explode(',', $currentUser->site_id);
    
        // Ambil selected_branch dari session (default: 'all' jika kosong)
        $selectedBranchId = session('selected_branch', 'all');
    
        // Query untuk mendapatkan area beserta nama department dan nama site
        $locationQuery = Modus::join('departments', 'modus.department_id', '=', 'departments.id')
                         ->join('sites', 'modus.site_id', '=', 'sites.site_id')
                         ->join('incidents', 'modus.incident_id', '=', 'incidents.id')
                         ->where('modus.department_id', $id)
                         ->select(
                             'modus.*', 
                             'incidents.incident_name',
                             'sites.site_name' // Ambil nama site
                         )
                         ->orderBy('incidents.sort_order', 'asc');
    
        // Jika user adalah admin, filter berdasarkan site_id yang dimiliki
        if ($currentUser->role->role === 'Admin') {
            $locationQuery->whereIn('modus.site_id', $siteIds);
        }
    
        // Filter berdasarkan selected_branch jika tidak 'all'
        if ($selectedBranchId !== 'all') {
            $locationQuery->whereRaw("FIND_IN_SET(?, modus.site_id)", [$selectedBranchId]);
        }
    
        // Eksekusi query
        $modus = $locationQuery->get();
        
        // Ambil nama department
         $departmentName = Department::find($id)->department_name;

    
        // Debugging jika data kosong
        // if ($locations->isEmpty()) {
        //     return response()->json([
        //         "error" => "Data area kosong",
        //         "User Role" => $currentUser->role->role,
        //         "Department ID" => $id,
        //         "Site IDs" => $siteIds,
        //         "Selected Branch" => $selectedBranchId,
        //         "Query" => $areaQuery->toSql(),
        //         "Bindings" => $areaQuery->getBindings()
        //     ], 404);
        // }
    
       // return response()->json($locations);
       
       // Kembalikan data dalam format JSON, termasuk nama department
        return response()->json([
            'department_name' => $departmentName, // Menambahkan nama department
            'moduss' => $modus
        ]);
    }
    
    public function edit($id)
    {
        $areas = Modus::with('site','department', 'incident')->findOrFail($id);
        return response()->json($areas);
    }
    
    public function update(Request $request, $id)
    {
     
        $location = Modus::findOrFail($id);
        
        // Validasi input agar tidak error
    $request->validate([
        'modus_name_edit' => 'required|string|max:255',
        'sort_order_edit' => 'nullable|integer',
    ]);
    
        // Update data
        $location->modus_name = $request->modus_name_edit;
        //  $location->incident_id = $request->incident_name_edit;
        $location->sort_order = $request->sort_order_edit;
    
        $location->save();
    
        return response()->json(['success' => 'Modus updated successfully!']);
    }
    
    public function deleteIncident(Request $request)
    {
        $ids = $request->input('ids'); // Array ID dari permintaan
        if (!empty($ids)) {
            Modus::whereIn('id', $ids)->delete();
            return response()->json(['success' => true, 'message' => 'Modus deleted successfully.']);
        }
        return response()->json(['success' => false, 'message' => 'No Modus selected.'], 400);
    }
}
