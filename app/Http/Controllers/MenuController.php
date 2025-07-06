<?php 
namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Department;
use App\Models\Access_Control_Module;
use App\Models\Access_Control;


class MenuController extends Controller
{
    
public static function getMenu()
{
    // Ambil role user yang login
  //  $roleId = Auth::user()->role_id;
    //dd($roleId);
    //
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
    
    // Jika selected_branch adalah 'all', kembalikan array kosong
    if ($selectedBranchId === 'all') {
        return []; // Mengembalikan array kosong
    }

    // Debugging: Pastikan siteIds tidak kosong
    if (empty($siteIds)) {
        dd("Error: siteIds kosong.", ["siteIds" => $siteIds, "User" => $currentUser]);
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
    
    // Ambil menu berdasarkan role dan akses read_access
    $menus = $departmentQuery->get();
    //$menus = Department::get();

    // Formatkan hasil untuk menu
    $formattedMenu = $menus->map(function ($menu) {
        return [
            'id' => $menu->id,
            'menu_name' => $menu->department_name,
            'icon_name' => $menu->icon_menu,
            //'submenu_name' => $menu->module->submenu_name,
            //'url' => $menu->module->url,
        ];
    });

    return $formattedMenu;
}

public static function getMenuUsers()
{
    // Ambil user yang sedang login
    $currentUser = Auth::user();
    
    // Debugging: Pastikan user ditemukan
    if (!$currentUser) {
        return []; // Mengembalikan array kosong jika user tidak ditemukan
    }

    // Jika role adalah Super Admin, tampilkan semua menu
    if ($currentUser->role->role === 'Super Admin') {
        $modules = Access_Control_Module::all();
    } else {
        // Ambil modul yang memiliki read_access = 1 untuk role user yang login
        $modules = Access_Control::where('role_id', $currentUser->role_id)
            ->where('read_access', 1)
            ->with('module') // Pastikan relasi 'module' sudah didefinisikan di model AccessControl
            ->get()
            ->pluck('module')
            ->filter(); // Filter untuk menghapus nilai null jika ada
    }

    // Formatkan hasil untuk menu
    $formattedMenu = $modules->map(function ($module) {
        return [
            'id' => $module->module_id,
            'menu_name' => $module->menu_name,
            'submenu_name' => $module->submenu_name,
            'url' => $module->url,
            // Anda bisa menambahkan icon jika diperlukan
            'icon_name' => 'circle', // Default icon, bisa disesuaikan
        ];
    });

    return $formattedMenu;
}

}
?>
