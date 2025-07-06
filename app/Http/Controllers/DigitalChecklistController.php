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
use App\Models\Checklist_template;
use App\Models\Category_Checklist;
use App\Models\SubCategory_Checklist;
use App\Models\Checklist_templateitem;


class DigitalChecklistController extends Controller
{
    protected $currentUser; // Properti untuk menyimpan user yang sedang login

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->currentUser = Auth::user(); // Mengambil user yang sedang login
            return $next($request);
        });
    }
    
    public function getCategorybysite($siteId)
    {
        $departments = Category_Checklist::where('site_id', $siteId)->get();
        return response()->json($departments);
    }
    
    public function getSubCategorybysite($categoryId)
    {
        $subcategory = SubCategory_Checklist::where('category_id', $categoryId)->get();
        return response()->json($subcategory);
    }
    
    // Start Digital Checklist Template
    
    public function indextemplate(){
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
    
   // dd($selectedBranchId);
    
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
    
     return view('pages.digital_checklist.template', compact('departments', 'filteredsites', 'selectedBranchId'));
}

public function gettemplate(){
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
        $templateQuery = Checklist_template::join('sites', 'checklist_templates.site_id', '=', 'sites.site_id')
            ->join('departments', 'checklist_templates.department_id', '=', 'departments.id')
            ->whereIn('checklist_templates.site_id', $siteIds);
    } else {
        // Non-admin melihat semua department tanpa filter site_id
        $templateQuery = Checklist_template::join('sites', 'checklist_templates.site_id', '=', 'sites.site_id')
        ->join('departments', 'checklist_templates.department_id', '=', 'departments.id')
        ;
    }

    // Filter berdasarkan selected_branch jika tidak 'all'
    if ($selectedBranchId !== 'all') {
        $templateQuery->whereRaw("FIND_IN_SET(?, checklist_templates.site_id)", [$selectedBranchId]);
    }

    // Eksekusi query
    $templates = $templateQuery->get();
   // dd($templates);
    // Debugging jika data kosong
    if ($templates->isEmpty()) {
        return response()->json([
            "error" => "Data department kosong",
            "User Role" => $currentUser->role->role,
            "Site IDs" => $siteIds,
            "Selected Branch" => $selectedBranchId,
            "Query" => $departmentQuery->toSql(),
            "Bindings" => $departmentQuery->getBindings()
        ], 404);
    }

    return response()->json($templates);
    
    
}

public function storetemplate(Request $request){
    // Validasi input
        $validated = $request->validate([
            'sites_template' => 'required|exists:sites,site_id', // Sesuaikan dengan tabel dan kolom yang ada
            'name_dept_tempdc' => 'required|exists:departments,id', // Sesuaikan dengan tabel dan kolom yang ada
            'nama_tempdc' => 'required|string|max:255',
        ]);

        
        // Simpan data ke database
        try {
            $template = new Checklist_template();
            $template->site_id = $validated['sites_template'];
            $template->department_id = $validated['name_dept_tempdc'];
            $template->template_name = $validated['nama_tempdc'];
            $template->save();

            return response()->json(['success' => 'Template Inserted successfully!']);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to create template: ' . $e->getMessage(),
            ], 500);
        }
    }
    


public function edittemplate($id){
     $template = Checklist_template::findOrFail($id);
    
    // Ambil site_id dari template
    $site_id = $template->site_id;
    
    // Ambil department berdasarkan site_id
    $departments = Department::where('site_id', $site_id)->get();
    
    // Sertakan departments dalam response
    $response = [
        'template' => $template,
        'departments' => $departments
    ];
    
    return response()->json($response);
     
}

public function updatetemplate(Request $request, $id){
     // Validasi input
    $request->validate([
        'nama_tempdc_edit' => 'required|string|max:255',
        'name_dept_edit' => 'required|exists:departments,id', // Pastikan department_id valid
    ]);

    // Temukan template berdasarkan ID
    $template = Checklist_template::findOrFail($id);
    //dd($template);
    // Update data template
    $template->update([
        'template_name' => $request->input('nama_tempdc_edit'),
        'department_id' => $request->input('name_dept_edit'),
    ]);

    // Berikan response sukses
    return response()->json(['success' => 'Template updated successfully!']);
}

public function destroytemplate(Request $request){
    $ids = $request->input('ids'); // Array ID dari permintaan
        if (!empty($ids)) {
            Checklist_template::whereIn('template_id', $ids)->delete();
            return response()->json(['success' => true, 'message' => 'Departments deleted successfully.']);
        }
        return response()->json(['success' => false, 'message' => 'No departments selected.'], 400);
    
}

public function getDatatemplate(){
    
}
//============= End Digital Checklist Template ================================


// Start Digital Checklist Template List
    
    public function indextemplatelist(Request $request){

      // Validasi input
    $validated = $request->validate([
        'template_id' => 'required',
        'site_id' => 'required'
    ]);
    
    $template_id = $validated['template_id'];
    $site_id = $validated['site_id'];

    // 1. Ambil nama template berdasarkan template_id saja
    $template = Checklist_template::findOrFail($template_id);
    $template_name = $template->template_name;
    
    // 2. Ambil semua category yang terkait dengan site_id
    $categories = Category_Checklist::where('site_id', $site_id)
    ->orderBy('category_name', 'asc')
    ->get();
    
    
     return view('pages.digital_checklist.index', compact('template_name','categories', 'template_id', 'site_id'));
}

public function gettemplatelist(Request $request){
    
    $template_id = $request->input('template_id');
    
    // Ambil user yang sedang login
    $currentUser = Auth::user();

    // Pastikan user ditemukan
    if (!$currentUser) {
        return response()->json(["error" => "User tidak ditemukan"], 400);
    }

    $siteIds = explode(',', $currentUser->site_id);

    $selectedBranchId = session('selected_branch', 'all');
    
    if ($currentUser->role->role === 'Admin') {
        $templateQuery = Checklist_templateitem::join('sites', 'checklist_template_items.site_id', '=', 'sites.site_id')
            ->join('checklist_categories', 'checklist_template_items.category_id', '=', 'checklist_categories.category_id')
            ->join('checklist_subcategories', 'checklist_template_items.subcategory_id', '=', 'checklist_subcategories.subcategory_id')
            ->where('checklist_template_items.template_id',$template_id )
            ->whereIn('checklist_template_items.site_id', $siteIds);
    } else {
        $templateQuery = Checklist_templateitem::join('sites', 'checklist_template_items.site_id', '=', 'sites.site_id')
        ->join('checklist_categories', 'checklist_template_items.category_id', '=', 'checklist_categories.category_id')
        ->join('checklist_subcategories', 'checklist_template_items.subcategory_id', '=', 'checklist_subcategories.subcategory_id')
        ->where('checklist_template_items.template_id',$template_id )
        ;
    }

    if ($selectedBranchId !== 'all') {
        $templateQuery->whereRaw("FIND_IN_SET(?, checklist_template_items.site_id)", [$selectedBranchId]);
    }

    // Eksekusi query
    $templates = $templateQuery->get();
   
    return response()->json($templates);
    
    
}

public function storetemplatelist(Request $request){
    // Validasi input
        $validated = $request->validate([
            'name_category_tempdc' => 'required', // Sesuaikan dengan tabel dan kolom yang ada
            'name_subcategory_tempdc' => 'required', // Sesuaikan dengan tabel dan kolom yang ada
            'itemname_dc' => 'required|string|max:255',
            'sort_orderdc' => 'required',
            'template_id' => 'required',
            'site_id' => 'required'
        ]);

        
        // Simpan data ke database
        try {
            $template = new Checklist_templateitem();
            $template->site_id = $validated['site_id'];
            $template->template_id = $validated['template_id'];
            $template->category_id = $validated['name_category_tempdc'];
            $template->subcategory_id = $validated['name_subcategory_tempdc'];
            $template->item_name = $validated['itemname_dc'];
            $template->sort_order = $validated['sort_orderdc'];
            $template->save();

             return response()->json(['success' => 'Checklist Item Insert successfully!']);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to create template: ' . $e->getMessage(),
            ], 500);
        }
    }
    


public function edittemplatelist($id){
     $template = Checklist_templateitem::findOrFail($id);
     
     //dd($template );
    
    // Sertakan departments dalam response
    $response = [
        'template' => $template,
    ];
    
    return response()->json($response);
     
}

public function updatetemplatelist(Request $request, $id){
     // Validasi input
    $request->validate([
        'itemname_dc_edit' => 'string|max:255',
        'sort_orderdc_edit' => '', // Pastikan department_id valid
    ]);

    // Temukan template berdasarkan ID
    $template = Checklist_templateitem::findOrFail($id);
    //dd($template);
    // Update data template
    $template->update([
        'item_name' => $request->input('itemname_dc_edit'),
        'sort_order' => $request->input('sort_orderdc_edit'),
    ]);

    // Berikan response sukses
    return response()->json(['success' => 'Checklist Item updated successfully!']);
}

public function destroytemplatelist(Request $request){
    $ids = $request->input('ids'); // Array ID dari permintaan
        if (!empty($ids)) {
            Checklist_templateitem::whereIn('item_id', $ids)->delete();
            return response()->json(['success' => true, 'message' => 'Item Checklist deleted successfully.']);
        }
        return response()->json(['success' => false, 'message' => 'No Item selected.'], 400);
    
}

public function getDatatemplatelist(){
    
}
//============= End Digital Checklist Template List ================================






//============= Sub Sub Category Digital Checklist Template ===========================

    public function indexsubcategory(){
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
            $departmentQuery = Category_Checklist::whereIn('site_id', $siteIds);
        } else {
            $departmentQuery = Category_Checklist::query(); // Non-admin melihat semua department
        }
    
        // Filter selected_branch jika tidak 'all'
        if ($selectedBranchId !== 'all') {
            $departmentQuery->whereRaw("FIND_IN_SET(?, site_id)", [$selectedBranchId]);
        }
    
        // Eksekusi query
        $departments = $departmentQuery->get();
        $filteredsites = $siteQuery->get();
        
         return view('pages.digital_checklist.sub_category.index', compact('departments', 'filteredsites'));
    }
    
    public function getsubcategory(){
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
    
        // Query untuk Department
        if ($currentUser->role->role === 'Admin') {
            $templateQuery = SubCategory_Checklist::join('sites', 'checklist_subcategories.site_id', '=', 'sites.site_id')
                ->join('checklist_categories', 'checklist_subcategories.category_id', '=', 'checklist_categories.category_id')
                ->whereIn('checklist_templates.site_id', $siteIds);
        } else {
            // Non-admin melihat semua department tanpa filter site_id
            $templateQuery = SubCategory_Checklist::join('sites', 'checklist_subcategories.site_id', '=', 'sites.site_id')
            ->join('checklist_categories', 'checklist_subcategories.category_id', '=', 'checklist_categories.category_id')
            ;
        }
    
        // Filter berdasarkan selected_branch jika tidak 'all'
        if ($selectedBranchId !== 'all') {
            $templateQuery->whereRaw("FIND_IN_SET(?, checklist_subcategories.site_id)", [$selectedBranchId]);
        }
    
        // Eksekusi query
        $templates = $templateQuery->get();
       // dd($templates);
        // Debugging jika data kosong
        if ($templates->isEmpty()) {
            return response()->json([
                "error" => "Data department kosong",
                "User Role" => $currentUser->role->role,
                "Site IDs" => $siteIds,
                "Selected Branch" => $selectedBranchId,
                "Query" => $departmentQuery->toSql(),
                "Bindings" => $departmentQuery->getBindings()
            ], 404);
        }
    
        return response()->json($templates);
        
        
    }
    
    public function storesubcategory(Request $request){
        // Validasi input
            $validated = $request->validate([
                'sites_template' => 'required|exists:sites,site_id', // Sesuaikan dengan tabel dan kolom yang ada
                'name_category_tempdc' => 'required',
                'name_subcategory_tempdc' => 'required', // Sesuaikan dengan tabel dan kolom yang ada
                'sort_categorydc' => 'required|string|max:255',
            ]);
    
            
            // Simpan data ke database
            try {
                $template = new SubCategory_Checklist();
                $template->site_id = $validated['sites_template'];
                $template->category_id = $validated['name_category_tempdc'];
                $template->subcategory_name = $validated['name_subcategory_tempdc'];
                $template->sort_order = $validated['sort_categorydc'];
                $template->save();
    
                return response()->json(['success' => 'Sub Category created successfully!']);
            } catch (\Exception $e) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Failed to create Sub Category: ' . $e->getMessage(),
                ], 500);
            }
        }
        
    
    
    public function editsubcategory($id){
         $template = SubCategory_Checklist::findOrFail($id);
        
        // Ambil site_id dari template
        $site_id = $template->site_id;
        
        // Ambil department berdasarkan site_id
        $departments = Department::where('site_id', $site_id)->get();
        
        // Sertakan departments dalam response
        $response = [
            'template' => $template,
            'departments' => $departments
        ];
        
        return response()->json($response);
         
    }
    
    public function updatesubcategory(Request $request, $id){
         // Validasi input
        $request->validate([
                'name_category_tempdc' => 'string',
                'name_subcategory_tempdc' => 'string', // Sesuaikan dengan tabel dan kolom yang ada
                'sort_categorydc' => 'string|max:255',
        ]);
    
        // Temukan template berdasarkan ID
        $template = SubCategory_Checklist::findOrFail($id);
        //dd($template);
        // Update data template
        $template->update([
            'subcategory_name' => $request->input('name_subcategory_tempdc_edit'),
            'sort_order' => $request->input('sort_subcategorydc_edit'),
        ]);
    
        // Berikan response sukses
        return response()->json(['success' => 'Sub Category updated successfully!']);
    }
    
    public function destroysubcategory(Request $request){
        $ids = $request->input('ids'); // Array ID dari permintaan
            if (!empty($ids)) {
                SubCategory_Checklist::whereIn('subcategory_id', $ids)->delete();
                return response()->json(['success' => true, 'message' => 'Sub Category deleted successfully.']);
            }
            return response()->json(['success' => false, 'message' => 'No departments selected.'], 400);
        
    }

//============= End Sub Category Digital Checklist Template ================================

//============= Category Digital Checklist Template ===========================

    public function indexcategory(){
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
        
         return view('pages.digital_checklist.category.index', compact('departments', 'filteredsites'));
    }
    
    public function getcategory(){
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
            $templateQuery = Category_Checklist::join('sites', 'checklist_categories.site_id', '=', 'sites.site_id')
                ->whereIn('checklist_categories.site_id', $siteIds);
        } else {
            // Non-admin melihat semua department tanpa filter site_id
            $templateQuery = Category_Checklist::join('sites', 'checklist_categories.site_id', '=', 'sites.site_id')
            ;
        }
    
        // Filter berdasarkan selected_branch jika tidak 'all'
        if ($selectedBranchId !== 'all') {
            $templateQuery->whereRaw("FIND_IN_SET(?, checklist_categories.site_id)", [$selectedBranchId]);
        }
    
        // Eksekusi query
        $templates = $templateQuery->get();
       // dd($templates);
        // Debugging jika data kosong
        if ($templates->isEmpty()) {
            return response()->json([
                "error" => "Data department kosong",
                "User Role" => $currentUser->role->role,
                "Site IDs" => $siteIds,
                "Selected Branch" => $selectedBranchId,
                "Query" => $departmentQuery->toSql(),
                "Bindings" => $departmentQuery->getBindings()
            ], 404);
        }
    
        return response()->json($templates);
        
        
    }
    
    public function storecategory(Request $request){
        // Validasi input
            $validated = $request->validate([
                'sites_template' => 'required|exists:sites,site_id', // Sesuaikan dengan tabel dan kolom yang ada
                'name_category_tempdc' => 'required', // Sesuaikan dengan tabel dan kolom yang ada
                'sort_categorydc' => 'required|string|max:255',
            ]);
    
            
            // Simpan data ke database
            try {
                $template = new Category_Checklist();
                $template->site_id = $validated['sites_template'];
                $template->category_name = $validated['name_category_tempdc'];
                $template->sort_order = $validated['sort_categorydc'];
                $template->save();
    
              return response()->json(['success' => 'Category created successfully!']);
            } catch (\Exception $e) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Failed to create category: ' . $e->getMessage(),
                ], 500);
            }
        }
        
    
    
    public function editcategory($id){
         $template = Category_Checklist::findOrFail($id);
        
        // Ambil site_id dari template
        $site_id = $template->site_id;
        
        // Ambil department berdasarkan site_id
        $departments = Department::where('site_id', $site_id)->get();
        
        // Sertakan departments dalam response
        $response = [
            'template' => $template,
            'departments' => $departments
        ];
        
        return response()->json($response);
         
    }
    
    public function updatecategory(Request $request, $id){
         // Validasi input
        $request->validate([
            'name_category_tempdc_edit' => 'required|string|max:255',
            'sort_categorydc_edit' => 'required', // Pastikan department_id valid
        ]);
    
        // Temukan template berdasarkan ID
        $template = Category_Checklist::findOrFail($id);
        //dd($template);
        // Update data template
        $template->update([
            'category_name' => $request->input('name_category_tempdc_edit'),
            'sort_order' => $request->input('sort_categorydc_edit'),
        ]);
    
        // Berikan response sukses
        return response()->json(['success' => 'Department updated successfully!']);
    }
    
    public function destroycategory(Request $request){
        $ids = $request->input('ids'); // Array ID dari permintaan
            if (!empty($ids)) {
                Category_Checklist::whereIn('category_id', $ids)->delete();
                return response()->json(['success' => true, 'message' => 'Categories deleted successfully.']);
            }
            return response()->json(['success' => false, 'message' => 'No Categories selected.'], 400);
        
    }

//============= End Category Digital Checklist Template ================================

//================ Start Digital Checklist End User =====================================
public function indexenduser()
    {
        return view('pages.digital_checklist.enduser.index');
    }
    
public function createdcenduser()
    {
        return view('pages.digital_checklist.enduser.add');
    }


//================ Enda Digital Checklist End User =====================================
}