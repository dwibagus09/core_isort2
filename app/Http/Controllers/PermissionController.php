<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use App\Models\Role;
use App\Models\Access_Control_Module; // Pastikan model Role diimpor
use App\Models\Access_Control;



class PermissionController extends Controller
{
    
    public function index(){
        $modules = Access_Control_Module::all();
    //dd($modules);
        return view('pages.permission.index', compact('modules'));
    }
    
    public function getPermission()
    {
        $permission = Access_Control_Module::get();
        // dd($users);
        return response()->json($permission);
    }
    
    // Ambil data role berdasarkan ID
public function getPermissionData($id)
{
    
    $permissions = Access_Control_Module::where('module_id', $id)->first();


    return response()->json([
        'permissions' => $permissions
    ]);
}

// Perbarui data role
public function updatePermission(Request $request, $id)
{
   $validated = $request->validate([
        'menu_name' => 'required|string|max:255',
        'submenu_name' => 'nullable|string|max:255', // Nullable karena bisa kosong
        'url_edit' => 'required|string|max:255',
    ]);
     dd($id);
    // Cari role
    $permission = Access_Control_Module::find($id);
    if (!$permission) {
        return redirect()->route('permission.index')->with('error', 'Permission not found');
    }

    // Update nama role
    $permission->menu_name = $validated['menu_name'];
    $permission->submenu_name = $validated['submenu_name'];
    $permission->url = $validated['url_edit'];
   
    $permission->save();

    return redirect()->route('permission.index')->with('success', 'Role updated successfully');
}


    
    
    // Simpan data role baru
    public function store(Request $request)
{
    $validated = $request->validate([
        'menu_name' => 'required|string|max:255',
        'submenu_name' => 'nullable|string|max:255', // Nullable karena bisa kosong
        'url_menu' => 'required|string|max:255',
    ]);
    //dd($validated);
    // Simpan data ke tabel Access_Control_Module
    Access_Control_Module::create([
        'menu_name' => $validated['menu_name'], // Gunakan data dari validasi
        'submenu_name' => $validated['submenu_name'], // Bisa null
        'url' => $validated['url_menu'], // Gunakan data dari validasi
    ]);

    return redirect()->route('permission.index')->with('success', 'Module berhasil ditambahkan.');
}

    
    
    // Hapus role
    public function destroy($id)
    {
        $permission = Access_Control_Module::findOrFail($id);
        //dd($access);
        $permission->delete();

         return response()->json(['success' => true, 'message' => 'Role berhasil dihapus.']);
    }

    

}