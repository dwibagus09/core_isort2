<?php 
namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Department;


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
}
?>
