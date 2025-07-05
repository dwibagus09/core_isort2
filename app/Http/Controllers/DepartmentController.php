<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use App\Models\Role; // Pastikan model Role diimpor
use App\Models\User;
use App\Models\Sites;
use App\Models\Department;

class DepartmentController extends Controller
{
    protected $currentUser; // Properti untuk menyimpan user yang sedang login

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->currentUser = Auth::user(); // Mengambil user yang sedang login
            return $next($request);
        });
    }

    
  public function index()
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

    // Query untuk Sites
    if ($currentUser->role->role === 'Admin') {
        $siteQuery = Sites::whereIn('site_id', $siteIds);
        //dd($siteQuery);
    } else {
        $siteQuery = Sites::query(); // Non-admin melihat semua site
    }

    // Filter selected_branch jika tidak 'all'
    if ($selectedBranchId !== 'all') {
        $siteQuery->whereRaw("FIND_IN_SET(?, site_id)", [$selectedBranchId]);
    }

    // Query untuk Department
    if ($currentUser->role->role === 'Admin') {
        $departmentQuery = Department::whereIn('site_id', $siteIds);
    } else {
        $departmentQuery = Department::query(); // Non-admin melihat semua department
    }

    // Filter selected_branch jika tidak 'all'
    if ($selectedBranchId !== 'all') {
        $departmentQuery->whereRaw("FIND_IN_SET(?, site_id)", [$selectedBranchId]);
    }

    // Eksekusi query
    $departments = $departmentQuery->get();
    $filteredsites = $siteQuery->get();

    // Debugging: Cek apakah hasilnya kosong
   /* if ($departments->isEmpty()) {
        dd("Error: Data department kosong!", [
            "User Role" => $currentUser->role->role,
            "Site IDs" => $siteIds,
            "Selected Branch" => $selectedBranchId,
            "Query" => $departmentQuery->toSql(),
            "Bindings" => $departmentQuery->getBindings()
        ]);
    }

    if ($sites->isEmpty()) {
        dd("Error: Data site kosong!", [
            "User Role" => $currentUser->role->role,
            "Site IDs" => $siteIds,
            "Selected Branch" => $selectedBranchId,
            "Query" => $siteQuery->toSql(),
            "Bindings" => $siteQuery->getBindings()
        ]);
    }*/

    return view('pages.department.index', compact('departments', 'filteredsites'));
}

    
    public function getDepartment()
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
    
    // Jika selected_branch adalah 'all', kembalikan array kosong
    // if ($selectedBranchId === 'all') {
    //     return []; // Mengembalikan array kosong
    // }

    // Query untuk Department
    if ($currentUser->role->role === 'Admin') {
        $departmentQuery = Department::join('sites', 'departments.site_id', '=', 'sites.site_id')
            ->whereIn('departments.site_id', $siteIds);
    } else {
        // Non-admin melihat semua department tanpa filter site_id
        $departmentQuery = Department::join('sites', 'departments.site_id', '=', 'sites.site_id');
    }

    // Filter berdasarkan selected_branch jika tidak 'all'
    if ($selectedBranchId !== 'all') {
        $departmentQuery->whereRaw("FIND_IN_SET(?, departments.site_id)", [$selectedBranchId]);
    }

    // Eksekusi query
    $departments = $departmentQuery->get();

    // Debugging jika data kosong
    if ($departments->isEmpty()) {
        return response()->json([
            "error" => "Data department kosong",
            "User Role" => $currentUser->role->role,
            "Site IDs" => $siteIds,
            "Selected Branch" => $selectedBranchId,
            "Query" => $departmentQuery->toSql(),
            "Bindings" => $departmentQuery->getBindings()
        ], 404);
    }

    return response()->json($departments);
}

    //jika menggunakan implode
    // public function store(Request $request)
    // {
    //     // Validasi input
    //     $validated = $request->validate([
    //         'name_dept' => 'required|string|max:255',
    //         'site' => 'required|exists:sites,site_id',
    //         'nama_chtel' => 'required|string|max:255',
    //         'nama_grwa' => 'required|string|max:255',
    //         'icon_menu' => 'required|string|max:255',
    //         'thumb' => 'required|file|mimes:jpg,jpeg,png|max:2048', // Maksimal 2MB
    //     ]);
        
    //     // Gabungkan site menjadi string dengan koma
    //      $site_ids = implode(',', $request->site);
        
    //     // Simpan file upload di folder public/uploads
    //     $file = $request->file('thumb');
    //     $fileName = time() . '_' . $file->getClientOriginalName(); // Buat nama unik
    //     $file->move(public_path('uploads'), $fileName); // Simpan file di public/uploads
    
    //     // Simpan data ke database
    //     $dept = new Department();
    //     $dept->department_name = $validated['name_dept'] ?? null;
    //     $dept->site_id = $site_ids ?? null;
    //     $dept->telegram_channel_id = $validated['nama_chtel'] ?? null;
    //     $dept->whatsapp_group_id = $validated['nama_grwa'] ?? null;
    //     $dept->icon_menu = $validated['icon_menu'] ?? null;
    //     $dept->icon_thumbnail = 'uploads/' . $fileName ?? null ; // Simpan path relatif ke database
    //     //dd($dept);
    //     $dept->save();
    
    //     return response()->json(['success' => 'Department created successfully!']);
    // }
    
    public function store(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'name_dept' => 'required|string|max:255',
            'site' => 'required|array|min:1', // Pastikan 'site' adalah array dan memiliki minimal satu nilai
            'site.*' => 'exists:sites,site_id', // Validasi setiap item dalam array
            'nama_chtel' => 'required|string|max:255',
            'nama_grwa' => 'required|string|max:255',
            'icon_menu' => 'required|string|max:255',
            'thumb' => 'required|file|mimes:jpg,jpeg,png|max:2048', // Maksimal 2MB
        ]);
    
        // Simpan file upload di folder public/uploads
        $file = $request->file('thumb');
        $fileName = time() . '_' . $file->getClientOriginalName(); // Buat nama unik
        $file->move(public_path('uploads'), $fileName); // Simpan file di public/uploads
    
        // Simpan data department untuk setiap site_id
        foreach ($request->site as $site_id) {
            $dept = new Department();
            $dept->department_name = $validated['name_dept'];
            $dept->site_id = $site_id; // Simpan satu site_id per baris
            $dept->telegram_channel_id = $validated['nama_chtel'];
            $dept->whatsapp_group_id = $validated['nama_grwa'];
            $dept->icon_menu = $validated['icon_menu'];
            $dept->icon_thumbnail = 'uploads/' . $fileName; // Simpan path relatif ke database
            $dept->save();
        }
    
        return response()->json(['success' => 'Department created successfully!']);
    }


    public function edit($id)
    {
        $department = Department::findOrFail($id);
        return response()->json($department);
    }
    
    public function update(Request $request, $id)
    {
        $request->validate([
            'name_dept' => 'required|string|max:255',
            'site' => 'required|exists:sites,site_id',
            'nama_chtel' => 'required|string|max:255',
            'nama_grwa' => 'required|string|max:255',
            'icon_menu' => 'required|string|max:255',
            'thumb' => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
        ]);
    
        $department = Department::findOrFail($id);
    
        // Update data
        $department->department_name = $request->name_dept;
        $department->site_id = $request->site;
        $department->telegram_channel_id = $request->nama_chtel;
        $department->whatsapp_group_id = $request->nama_grwa;
        $department->icon_menu = $request->icon_menu;
    
        // Update file jika ada
        if ($request->hasFile('thumb')) {
           // Simpan file upload di folder public/uploads
            $file = $request->file('thumb');
            $fileName = time() . '_' . $file->getClientOriginalName(); // Buat nama unik
            $file->move(public_path('uploads'), $fileName); // Simpan file di public/uploads
           $dept->icon_thumbnail = 'uploads/' . $fileName; // Simpan path relatif ke database
        }
    
        $department->save();
    
        return response()->json(['success' => 'Department updated successfully!']);
    }
    
    public function deleteDepartments(Request $request)
    {
        $ids = $request->input('ids'); // Array ID dari permintaan
        if (!empty($ids)) {
            Department::whereIn('id', $ids)->delete();
            return response()->json(['success' => true, 'message' => 'Departments deleted successfully.']);
        }
        return response()->json(['success' => false, 'message' => 'No departments selected.'], 400);
    }
    
    
    public function getDepartmentsBySite($siteId)
    {
        $departments = Department::where('site_id', $siteId)->get();
        return response()->json($departments);
    }



}