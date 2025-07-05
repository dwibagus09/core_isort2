<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use App\Models\Role;
use App\Models\Access_Control_Module; // Pastikan model Role diimpor
use App\Models\Access_Control;



class RoleController extends Controller
{
    
    public function index(){
        $modules = Access_Control_Module::all();
    //dd($modules);
        return view('pages.role.index', compact('modules'));
    }
    
    public function getRoles()
    {
        $role = role::get();
        // dd($users);
        return response()->json($role);
    }
    
    // Ambil data role berdasarkan ID
public function getRoleData($id)
{
    $role = Role::find($id);
    if (!$role) {
        return response()->json(['error' => 'Role not found'], 404);
    }

    $permissions = Access_Control::where('role_id', $id)->get();

    $permissionData = [];
    foreach ($permissions as $permission) {
        if ($permission->read_access) {
            $permissionData[$permission->module_id][] = 'read';
        }
        if ($permission->write_access) {
            $permissionData[$permission->module_id][] = 'write';
        }
    }
   

    return response()->json([
        'role' => $role,
        'permissions' => $permissionData
    ]);
}

// Perbarui data role
public function updateRole(Request $request, $id)
{
    $validated = $request->validate([
        'role_name' => 'required|string|max:255',
        'permissions_edit' => 'nullable|array',
    ]);

    // Cari role
    $role = Role::find($id);
    if (!$role) {
        return redirect()->route('role.index')->with('error', 'Role not found');
    }

    // Update nama role
    $role->role = $validated['role_name'];
    $role->save();

    // Hapus semua akses lama untuk role ini
    Access_Control::where('role_id', $id)->delete();

    // Simpan akses baru
    if (!empty($validated['permissions_edit'])) {
        foreach ($validated['permissions_edit'] as $moduleId => $permissions) {
            $readAccess = in_array('read', $permissions) ? '1' : '0';
            $writeAccess = in_array('write', $permissions) ? '1' : '0';

            Access_Control::create([
                'role_id' => $role->role_id,
                'module_id' => $moduleId,
                'read_access' => $readAccess,
                'write_access' => $writeAccess,
            ]);
        }
    }

    return redirect()->route('role.index')->with('success', 'Role updated successfully');
}


    
    
    // Simpan data role baru
    public function store(Request $request)
{
    $validated = $request->validate([
        'role_name' => 'required|string|max:255',
        'permissions' => 'nullable|array',
    ]);

    // Simpan nama role ke dalam tabel roles
    $role = Role::create([
        'role' => $validated['role_name'],
    ]);

    // Simpan data ke dalam tabel access_control
    $modules = Access_Control_Module::all(); // Ambil semua module dari database
    foreach ($modules as $module) {
        $moduleId = $module->module_id;

        // Tentukan apakah 'read' atau 'write' dipilih untuk module ini
        $readAccess = isset($validated['permissions'][$moduleId]) && in_array('read', $validated['permissions'][$moduleId]) ? 1 : 0;
        $writeAccess = isset($validated['permissions'][$moduleId]) && in_array('write', $validated['permissions'][$moduleId]) ? 1 : 0;

        // Simpan data access control
        Access_Control::create([
            'role_id' => $role->role_id,
            'module_id' => $moduleId,
            'read_access' => $readAccess,
            'write_access' => $writeAccess,
        ]);
    }

    return redirect()->route('role.index')->with('success', 'Role berhasil ditambahkan.');
}

    
    
    // Hapus role
    public function destroy($id)
    {
        $role = Role::findOrFail($id);
        
        // Hapus permissions terkait
        $access = Access_Control::where('role_id', $role->role_id);
        //dd($access);
        // Hapus role
        $access->delete();
        $role->delete();

         return response()->json(['success' => true, 'message' => 'Role berhasil dihapus.']);
    }

    

}