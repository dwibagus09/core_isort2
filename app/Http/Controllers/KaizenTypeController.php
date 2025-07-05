<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Sites;
use App\Models\Department;
use App\Models\Kaizen_Type;

class KaizenTypeController extends Controller
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
            $departmentQuery = Department::where('id', $department_id);
        } else {
            $departmentQuery = Department::where('id', $department_id);
        }
    
        // Query untuk Area berdasarkan department_id
        $areaQuery = Kaizen_Type::where('department_id', $department_id)
                         ->orderBy('sort_order', 'asc');
    
        // Filter selected_branch jika tidak 'all'
        if ($selectedBranchId !== 'all') {
            $departmentQuery->whereRaw("FIND_IN_SET(?, site_id)", [$selectedBranchId]);
            // $areaQuery->whereRaw("FIND_IN_SET(?, site_id)", [$selectedBranchId]);
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
    
        return view('pages.kaizen.type.index', compact('department_id', 'department', 'areas', 'filteredsites'));
    }

    
    
    public function store(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'type_name' => 'required|string|max:255',
            'site_id' => 'required|exists:sites,site_id',
            'department_id' => 'required|string|max:255',
            'sort_order' => 'required|string|max:255',
        ]);
    
        // Simpan data ke database
        $dept = new Kaizen_Type();
        $dept->kaizen_type = $validated['type_name'];
        $dept->department_id = $validated['department_id'];
        $dept->sort_order = $validated['sort_order'];
        //dd($dept);
        $dept->save();
    
        // return response()->json(['success' => 'Kaizen Type created successfully!']);
        return response()->json([
            'success' => true,
            'message' => 'Kaizen Type created successfully'
        ]);
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

    public function getTypekaizen($id)
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
        $areaQuery = Kaizen_Type::join('departments', 'kaizen_type.department_id', '=', 'departments.id')
                         ->join('sites', 'departments.site_id', '=', 'sites.site_id')
                         ->where('kaizen_type.department_id', $id)
                         ->select(
                             'kaizen_type.*', 
                             'departments.department_name', // Ambil nama department
                             'sites.site_name' // Ambil nama site
                         )
                         ->orderBy('kaizen_type.sort_order', 'asc');
    
        // Jika user adalah admin, filter berdasarkan site_id melalui department
        if ($currentUser->role->role === 'Admin') {
            $areaQuery->whereHas('department', function($query) use ($siteIds) {
                $query->whereIn('departments.site_id', $siteIds);
            });
        }
        
        // Filter berdasarkan selected_branch jika tidak 'all'
        if ($selectedBranchId !== 'all') {
            $areaQuery->whereHas('department', function($query) use ($selectedBranchId) {
                $query->where('departments.site_id', $selectedBranchId);
                // Jika site_id disimpan sebagai CSV di department:
                // $query->whereRaw("FIND_IN_SET(?, departments.site_id)", [$selectedBranchId]);
            });
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
        $area = Kaizen_Type::with(['department' => function($query) {
        $query->with('site');
    }])->findOrFail($id);
        return response()->json($area);
    }
    
       public function update(Request $request, $id)
    {
     
        $areas = Kaizen_Type::findOrFail($id);
        
        // Validasi input agar tidak error
    $request->validate([
        'type_name_edit' => 'required|string|max:255',
        'sort_order_edit' => 'nullable|integer',
    ]);
    
        // Update data
        $areas->kaizen_type = $request->type_name_edit;
        $areas->sort_order = $request->sort_order_edit;
    
        $areas->save();
    
        
        return response()->json([
            'success' => true,
            'message' => 'Kaizen Type Updated successfully'
        ]);
    }
    
    public function deleteTypekaizen(Request $request)
    {
        $ids = $request->input('ids'); // Array ID dari permintaan
        if (!empty($ids)) {
            Kaizen_Type::whereIn('kaizen_type_id', $ids)->delete();
            return response()->json([
            'success' => true,
            'message' => 'Kaizen Type deleted successfully'
        ]);
        }
     
        return response()->json([
            'success' => false,
            'message' => 'No departments selected.'
        ]);
    }

    
    
}
    
    
