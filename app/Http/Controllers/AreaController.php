<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Area;
use App\Models\Sites;
use App\Models\Department;

class AreaController extends Controller
{
     protected $currentUser; // Properti untuk menyimpan user yang sedang login

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->currentUser = Auth::user(); // Mengambil user yang sedang login
            return $next($request);
        });
    }
    
    // public function list($department_id)
    // {
    //     // Ambil data area berdasarkan department_id
    //     $areas = Area::where('department_id', $department_id)
    //                  ->orderBy('sort_order', 'asc')
    //                  ->get();
    //     $sites = Sites::all();
    //     // Return ke view area.index dengan data
    //     return view('pages.area.index', compact('department_id', 'areas', 'sites'));
    // }
    
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
    
        // Filter selected_branch jika tidak 'all'
        if ($selectedBranchId !== 'all') {
            $departmentQuery->whereRaw("FIND_IN_SET(?, site_id)", [$selectedBranchId]);
            $areaQuery->whereRaw("FIND_IN_SET(?, site_id)", [$selectedBranchId]);
        }
    
        // Eksekusi query
        $department = $departmentQuery->first(); // Mengambil satu department berdasarkan ID
        $areas = $areaQuery->get();
        $filteredsites = $siteQuery->first();
        
       
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
    
        // if ($areas->isEmpty()) {
        //     dd("Error: Data area kosong!", [
        //         "Department ID" => $department_id,
        //         "Query" => $areaQuery->toSql(),
        //         "Bindings" => $areaQuery->getBindings()
        //     ]);
        // }
    
        return view('pages.area.index', compact('department_id', 'department', 'areas', 'filteredsites'));
    }

    
    
    public function store(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'area_name' => 'required|string|max:255',
            'site_id' => 'required|exists:sites,site_id',
            'department_id' => 'required|string|max:255',
            'sort_order' => 'required|string|max:255',
        ]);
    
        // Simpan data ke database
        $dept = new Area();
        $dept->area_name = $validated['area_name'];
        $dept->site_id = $validated['site_id'];
        $dept->department_id = $validated['department_id'];
        $dept->sort_order = $validated['sort_order'];
        //dd($dept);
        $dept->save();
    
        return response()->json(['success' => 'Area created successfully!']);
    }
    
    // public function getArea($department_id)
    // {
    //     // Ambil data area berdasarkan department_id
    //     $areas = Area::where('department_id', $department_id)->get();  // Misalnya kamu menggunakan 'department_id' sebagai field yang terkait
    
    //     if ($areas->isEmpty()) {
    //         return response()->json(['error' => 'No data found for this department'], 404);
    //     }
    
    //     return response()->json($areas);  // Mengembalikan data dalam format JSON
    //     }

    public function getArea($id)
    {
        // Ambil user yang sedang login
        $currentUser = Auth::user();
    
        // Pastikan user ditemukan
        if (!$currentUser) {
            return response()->json(["error" => "User tidak ditemukan"], 400);
        }
    
        // Ubah site_id dari string "2,3" menjadi array [2,3]
        $siteIds = explode(',', $currentUser->site_id);
    
        // Ambil selected_branch dari session (default: 'all' jika kosong)
        $selectedBranchId = session('selected_branch', 'all');
    
        // Query untuk mendapatkan area beserta nama department dan nama site
        $areaQuery = Area::join('departments', 'areas.department_id', '=', 'departments.id')
                         ->join('sites', 'departments.site_id', '=', 'sites.site_id')
                         ->where('areas.department_id', $id)
                         ->select(
                             'areas.*', 
                             'departments.department_name', // Ambil nama department
                             'sites.site_name' // Ambil nama site
                         )
                         ->orderBy('areas.sort_order', 'asc');
    
        // Jika user adalah admin, filter berdasarkan site_id yang dimiliki
        if ($currentUser->role->role === 'Admin') {
            $areaQuery->whereIn('areas.site_id', $siteIds);
        }
    
        // Filter berdasarkan selected_branch jika tidak 'all'
        if ($selectedBranchId !== 'all') {
            $areaQuery->whereRaw("FIND_IN_SET(?, areas.site_id)", [$selectedBranchId]);
        }
    
        // Eksekusi query
        $areas = $areaQuery->get();
        
        // Ambil nama department
         $departmentName = Department::find($id)->department_name;

    
        // Debugging jika data kosong
        // if ($areas->isEmpty()) {
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
    
       // return response()->json($areas);
       
       // Kembalikan data dalam format JSON, termasuk nama department
        return response()->json([
            'department_name' => $departmentName, // Menambahkan nama department
            'areas' => $areas
        ]);
    }
    
    public function edit($id)
    {
        $area = Area::with('site','department')->findOrFail($id);
        return response()->json($area);
    }
    
       public function update(Request $request, $id)
    {
     
        $areas = Area::findOrFail($id);
        
        // Validasi input agar tidak error
    $request->validate([
        'area_name_edit' => 'required|string|max:255',
        'sort_order_edit' => 'nullable|integer',
    ]);
    
        // Update data
        $areas->area_name = $request->area_name_edit;
        $areas->sort_order = $request->sort_order_edit;
    
        $areas->save();
    
        return response()->json(['success' => 'Areas updated successfully!']);
    }
    
    public function deleteArea(Request $request)
    {
        $ids = $request->input('ids'); // Array ID dari permintaan
        if (!empty($ids)) {
            Area::whereIn('id', $ids)->delete();
            return response()->json(['success' => true, 'message' => 'Departments deleted successfully.']);
        }
        return response()->json(['success' => false, 'message' => 'No departments selected.'], 400);
    }

    
    
}
    
    
