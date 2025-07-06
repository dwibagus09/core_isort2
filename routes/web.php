<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

use App\Http\Controllers\DashboardsController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SecurityController;
use App\Http\Controllers\SafetyController;
use App\Http\Controllers\ParkingController;
use App\Http\Controllers\HousekeepingController;
use App\Http\Controllers\EngineeringController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\AreaController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\IncidentController;
use App\Http\Controllers\ModusController;
use App\Http\Controllers\SiteSelectController;
use App\Http\Controllers\SitesController;
use App\Http\Controllers\KaizenController;
use App\Http\Controllers\KaizenTypeController;
use App\Http\Controllers\WoKaizenController;
use App\Http\Controllers\DigitalChecklistController;

//Update Doni
use App\Http\Controllers\OtherSettingsController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });
//Update Doni

Route::get('/sharekaizen/{id}', [KaizenController::class, 'kaizenShare'])->name('kaizen.share');

//End Update Doni

Route::get('/', function () {
    return redirect('login');
});

Route::get('/check-session', function () {
    // Menampilkan semua data session
    return session()->all();
});

require __DIR__ . '/auth.php';
//Authentikasi

Route::middleware(['auth'])->group(function () {

//Update Doni
Route::get('/admin/othersetting/index', [OtherSettingsController::class, 'index'])->name('othersetting.index');
Route::get('/admin/othersetting/update', [OtherSettingsController::class, 'update']);


Route::get('/admin/kaizen/getdatatypeandarea/{id}', [KaizenController::class, 'getTypeandArea']);
Route::post('/admin/kaizen/submit', [KaizenController::class, 'submitKaizen']);

Route::get('/openkaizen', [KaizenController::class, 'openKaizen'])->name('kaizen.open');
Route::get('/openkaizen/data', [KaizenController::class, 'getKaizenOpen'])->name('kaizen.opendata');

Route::get('/closekaizen', [KaizenController::class, 'closeKaizen'])->name('kaizen.close');
Route::get('/closekaizen/data', [KaizenController::class, 'getKaizenClose'])->name('kaizen.closedata');

Route::post('/kaizen/addcomment', [KaizenController::class, 'addCommentKaizen'])->name('kaizen.comments');
Route::post('/kaizen/addprogress', [KaizenController::class, 'addProgressKaizen'])->name('kaizen.progress');
Route::post('/kaizen/addsolved', [KaizenController::class, 'addSolvedKaizen'])->name('kaizen.solved');

Route::get('/kaizen/testnotifwa', [KaizenController::class, 'tesnotifwa'])->name('kaizen.tesnotifwa');

Route::get('/admin/sites/index', [SitesController::class, 'index'])->name('sites');
Route::post('/admin/sites/save', [SitesController::class, 'save'])->name('sites.save');
//End Update Doni

Route::get('index', [DashboardsController::class, 'index']);
Route::post('/select-sites', [SiteSelectController::class, 'SiteSelect'])->name('save-selected-branch');
Route::post('/clear-selected-site', function() {
    session()->forget(['selected_branch', 'selected_site_id']);
    return response()->json(['success' => true]);
})->name('clear-selected-site');

//ROUTES MENU USER
Route::get('/user/view', [UserController::class, 'index']);
Route::get('/users/data', [UserController::class, 'getUsers'])->name('users.data');
Route::get('/user/{id}/edit', [UserController::class, 'edit'])->name('users.edit'); // Ambil data User Pengguna
Route::put('/user/{id}', [UserController::class, 'update'])->name('users.update'); // Ambil data User Pengguna
Route::post('/user/delete', [UserController::class, 'deleteUser'])->name('users.delete');
Route::post('/users/post', [UserController::class, 'store'])->name('users.store');
Route::get('/users/profile/{id}', [UserController::class, 'profile'])->name('users.profile');
Route::post('/profile/update', [UserController::class, 'updateProfile'])->middleware('auth')->name('profile.update');

// ROUTES MENU USER ADMIN
Route::get('/user/viewadmin', [UserController::class, 'indexadmin']);
Route::get('/users/dataadmin', [UserController::class, 'getUsersadmin'])->name('usersadmin.data');
Route::post('/users/postadmin', [UserController::class, 'storeadmin'])->name('usersadmin.store');
Route::get('/users/{id}/editadmin', [UserController::class, 'editadmin'])->name('usersadmin.edit'); // Ambil data User Pengguna
Route::put('/users/{id}', [UserController::class, 'updateadmin'])->name('usersadmin.update'); // Ambil data User Pengguna
Route::post('/users/delete', [UserController::class, 'deleteUseradmin'])->name('usersadmin.delete');

//ROUTES MENU DEPARTMENT
Route::get('/department/view', [DepartmentController::class, 'index']);
Route::get('/department/data', [DepartmentController::class, 'getDepartment'])->name('department.data');
Route::post('/department/post', [DepartmentController::class, 'store'])->name('department.store');
// Rute untuk Edit Department
Route::get('/department/{id}/edit', [DepartmentController::class, 'edit'])->name('department.edit'); // Ambil data department
Route::put('/department/{id}', [DepartmentController::class, 'update'])->name('department.update'); // Update data department
Route::post('/department/delete', [DepartmentController::class, 'deleteDepartments'])->name('department.delete');
Route::get('/departments/{siteId}', [DepartmentController::class, 'getDepartmentsBySite']);


//ROUTES AREA & LOCATION & INCIDENT & MODUS
Route::get('/area/{department_id}', [AreaController::class, 'list'])->name('area.list');
Route::get('/area/data/{department_id}', [AreaController::class, 'getArea'])->name('area.data');
Route::post('/area-store', [AreaController::class, 'store'])->name('area.store');
Route::get('/area/{id}/edit', [AreaController::class, 'edit'])->name('area.edit');
Route::put('/area/{id}', [AreaController::class, 'update'])->name('area.update');
Route::post('/area/delete', [AreaController::class, 'deleteArea'])->name('area.delete');

Route::get('/typekaizen/{department_id}', [KaizenTypeController::class, 'list'])->name('typekaizen.list');
Route::get('/typekaizen/data/{department_id}', [KaizenTypeController::class, 'getTypekaizen'])->name('typekaizen.data');
Route::post('typekaizen/area-store', [KaizenTypeController::class, 'store'])->name('typekaizen.store');
Route::get('/typekaizen/{id}/edit', [KaizenTypeController::class, 'edit'])->name('typekaizen.edit');
Route::put('/typekaizen/{id}', [KaizenTypeController::class, 'update'])->name('typekaizen.update');
Route::post('/typekaizen/delete', [KaizenTypeController::class, 'deleteTypekaizen'])->name('typekaizen.delete');

Route::get('/location/{department_id}', [LocationController::class, 'list'])->name('location.index');
Route::get('/location/data/{department_id}', [LocationController::class, 'getLocation'])->name('location.data');
Route::post('/location-store', [LocationController::class, 'store'])->name('location.store');
Route::get('/location/{id}/edit', [LocationController::class, 'edit'])->name('location.edit');
Route::put('/location/{id}', [LocationController::class, 'update'])->name('location.update');
Route::post('/location/delete', [LocationController::class, 'deleteLocation'])->name('location.delete');


Route::get('/incident/{department_id}', [IncidentController::class, 'list'])->name('incident.index');
Route::get('/incident/data/{department_id}', [IncidentController::class, 'getIncident'])->name('incident.data');
Route::post('/incident-store', [IncidentController::class, 'store'])->name('incident.store');
Route::get('/incident/{id}/edit', [IncidentController::class, 'edit'])->name('incident.edit');
Route::put('/incident/{id}', [IncidentController::class, 'update'])->name('incident.update');
Route::post('/incident/delete', [IncidentController::class, 'deleteIncident'])->name('incident.delete');

Route::get('/modus/{department_id}', [ModusController::class, 'list'])->name('modus.index');
Route::get('/modus/data/{department_id}', [ModusController::class, 'getIncident'])->name('modus.data');
Route::post('/modus-store', [ModusController::class, 'store'])->name('modus.store');
Route::get('/modus/{id}/edit', [ModusController::class, 'edit'])->name('modus.edit');
Route::put('/modus/{id}', [ModusController::class, 'update'])->name('modus.update');
Route::post('/modus/delete', [ModusController::class, 'deleteIncident'])->name('modus.delete');


//ROUTES MENU SECURITY
Route::get('/security/incident', [SecurityController::class, 'incident']);
Route::get('/security/modus', [SecurityController::class, 'modus']);
Route::get('/security/floor', [SecurityController::class, 'floor']);
Route::get('/security/action_plan_module', [SecurityController::class, 'action_plan_module']);
Route::get('/security/action_plan_target', [SecurityController::class, 'action_plan_target']);
Route::get('/security/action_plan_activity', [SecurityController::class, 'action_plan_activity']);
Route::get('/security/action_reminder_email', [SecurityController::class, 'action_reminder_email']);


//ROUTES MENU SAFETY
Route::get('/safety/viewbuildingprotectionequipmenttype', [SafetyController::class, 'bpe_type']);
Route::get('/safety/viewbuildingprotectionequipment', [SafetyController::class, 'bpe']);
Route::get('/safety/viewfireaccidentequipment', [SafetyController::class, 'baff_equipment']);
Route::get('/safety/incident', [SafetyController::class, 'incident']);
Route::get('/safety/modus', [SafetyController::class, 'modus']);
Route::get('/safety/floor', [SafetyController::class, 'floor']);
Route::get('/safety/action_plan_module', [SafetyController::class, 'action_plan_module']);
Route::get('/safety/action_plan_target', [SafetyController::class, 'action_plan_target']);
Route::get('/safety/action_plan_activity', [SafetyController::class, 'action_plan_activity']);
Route::get('/safety/action_reminder_email', [SafetyController::class, 'action_reminder_email']);

//ROUTES MENU Parking
Route::get('/parking/incident', [ParkingController::class, 'incident']);
Route::get('/parking/modus', [ParkingController::class, 'modus']);
Route::get('/parking/floor', [ParkingController::class, 'floor']);
Route::get('/parking/action_plan_module', [ParkingController::class, 'action_plan_module']);
Route::get('/parking/action_plan_target', [ParkingController::class, 'action_plan_target']);
Route::get('/parking/action_plan_activity', [ParkingController::class, 'action_plan_activity']);
Route::get('/parking/action_reminder_email', [ParkingController::class, 'action_reminder_email']);

//ROUTES MENU Parking
Route::get('/housekeeping/incident', [HousekeepingController::class, 'incident']);
Route::get('/housekeeping/modus', [HousekeepingController::class, 'modus']);
Route::get('/housekeeping/floor', [HousekeepingController::class, 'floor']);
Route::get('/housekeeping/action_plan_module', [HousekeepingController::class, 'action_plan_module']);
Route::get('/housekeeping/action_plan_target', [HousekeepingController::class, 'action_plan_target']);
Route::get('/housekeeping/action_plan_activity', [HousekeepingController::class, 'action_plan_activity']);
Route::get('/housekeeping/action_reminder_email', [HousekeepingController::class, 'action_reminder_email']);

//ROUTES MENU Parking
Route::get('/engineering/incident', [EngineeringController::class, 'incident']);
Route::get('/engineering/modus', [EngineeringController::class, 'modus']);
Route::get('/engineering/floor', [EngineeringController::class, 'floor']);
Route::get('/engineering/maintenance_module', [EngineeringController::class, 'action_plan_module']);
Route::get('/engineering/maintenance_target', [EngineeringController::class, 'action_plan_target']);
Route::get('/engineering/maintenance_activity', [EngineeringController::class, 'action_plan_activity']);
Route::get('/engineering/maintenance_email', [EngineeringController::class, 'action_reminder_email']);


//ROUTES MENU Role
Route::get('/role/index', [RoleController::class, 'index'])->name('role.index');
Route::get('/role/data', [RoleController::class, 'getRoles'])->name('role.data');
Route::post('/role/store', [RoleController::class, 'store'])->name('role.store');
Route::get('role/{id}/edit', [RoleController::class, 'edit'])->name('role.edit');
Route::put('role/{id}', [RoleController::class, 'update'])->name('role.update');
Route::delete('/role/delete/{id}', [RoleController::class, 'destroy'])->name('role.delete');
Route::get('/role/{id}/data', [RoleController::class, 'getRoleData'])->name('role.fetchData');
Route::put('/role/{id}', [RoleController::class, 'updateRole'])->name('role.update');

//ROUTES MENU Permission
Route::get('/permission/index', [PermissionController::class, 'index'])->name('permission.index');
Route::get('/permission/data', [PermissionController::class, 'getPermission'])->name('permission.data');
Route::post('/permission/store', [PermissionController::class, 'store'])->name('permission.store');
Route::get('/permission/{id}/edit', [PermissionController::class, 'edit'])->name('permission.edit');
Route::put('/permission/{id}', [PermissionController::class, 'update'])->name('permission.update');
Route::delete('/permission/delete/{id}', [PermissionController::class, 'destroy'])->name('permission.delete');
Route::get('/permission/{id}/data', [PermissionController::class, 'getPermissionData'])->name('permission.fetchData');
Route::put('/permission/{id}', [PermissionController::class, 'updatePermission'])->name('permission.update');

//ROUTES MENU Kaizen
Route::get('/admin/kaizen/index', [KaizenController::class, 'index'])->name('kaizen.index');
Route::get('/admin/kaizen/data', [KaizenController::class, 'getKaizen'])->name('kaizen.data');
Route::post('/admin/kaizen/store', [KaizenController::class, 'store'])->name('kaizen.store');
Route::get('/admin/kaizen/{id}/edit', [KaizenController::class, 'edit'])->name('kaizen.edit');
Route::put('/admin/kaizen/{id}', [KaizenController::class, 'update'])->name('kaizen.update');
Route::post('/admin/kaizen/delete', [KaizenController::class, 'deleteKaizen'])->name('kaizen.delete');
// Get Areas by Department
Route::get('/admin/get-areas/{department_id}', [KaizenController::class, 'getAreas']);

Route::get('/admin/get-moduses-by-incident/{incidentId}', [KaizenController::class, 'getModusesByIncident']);

// Get Locations by Area
Route::get('/admin/get-locations/{area_id}', [KaizenController::class, 'getLocations']);

//ROUTES MENU WO Kaizen
Route::get('/admin/kaizen/wo/index', [WoKaizenController::class, 'index'])->name('wo.index');
Route::get('/admin/kaizen/wo/data', [WoKaizenController::class, 'getKaizen'])->name('wo.data');
Route::post('/admin/kaizen/wo/store', [WoKaizenController::class, 'store'])->name('wo.store');
Route::get('/admin/kaizen/wo/{id}/edit', [WoKaizenController::class, 'edit'])->name('wo.edit');
Route::put('/admin/kaizen/wo/{id}', [WoKaizenController::class, 'update'])->name('wo.update');
Route::post('/admin/kaizen/wo/delete', [WoKaizenController::class, 'deleteWoKaizen'])->name('wo.delete');
Route::get('/admin/kaizen/get-workers', [WoKaizenController::class, 'getWorkers'])->name('get.workers');
Route::get('/admin/kaizen/get-worker/{id}', [WoKaizenController::class, 'getWorker']);


//ROUTES MENU Digital Checklist
//Template
Route::get('/admin/digitalchecklist/template/index', [DigitalChecklistController::class, 'indextemplate'])->name('templatedc.index');
Route::get('/admin/digitalchecklist/template/data', [DigitalChecklistController::class, 'gettemplate'])->name('templatedc.data');
Route::post('/admin/digitalchecklist/template/store', [DigitalChecklistController::class, 'storetemplate'])->name('templatedc.store');
Route::get('/admin/digitalchecklist/template/{id}/edit', [DigitalChecklistController::class, 'edittemplate'])->name('templatedc.edit');
Route::put('/admin/digitalchecklist/template/{id}', [DigitalChecklistController::class, 'updatetemplate'])->name('templatedc.update');
Route::post('/admin/digitalchecklist/template/delete/', [DigitalChecklistController::class, 'destroytemplate'])->name('templatedc.delete');

Route::post('/admin/digitalchecklist/template/list/index', [DigitalChecklistController::class, 'indextemplatelist'])->name('templatedc.indexlist');
Route::get('/admin/digitalchecklist/template/list/data', [DigitalChecklistController::class, 'gettemplatelist'])->name('templatedc.datalist');
Route::post('/admin/digitalchecklist/template/list/store', [DigitalChecklistController::class, 'storetemplatelist'])->name('templatedc.storelist');
Route::get('/admin/digitalchecklist/template/list/{id}/edit', [DigitalChecklistController::class, 'edittemplatelist'])->name('templatedc.editlist');
Route::put('/admin/digitalchecklist/template/list/{id}', [DigitalChecklistController::class, 'updatetemplatelist'])->name('templatedc.updatelist');
Route::post('/admin/digitalchecklist/template/list/delete/', [DigitalChecklistController::class, 'destroytemplatelist'])->name('templatedc.deletelist');

//Category
Route::get('/admin/digitalchecklist/category/index', [DigitalChecklistController::class, 'indexcategory'])->name('categorydc.index');
Route::get('/admin/digitalchecklist/category/data', [DigitalChecklistController::class, 'getcategory'])->name('categorydc.data');
Route::post('/admin/digitalchecklist/category/store', [DigitalChecklistController::class, 'storecategory'])->name('categorydc.store');
Route::get('/admin/digitalchecklist/category/{id}/edit', [DigitalChecklistController::class, 'editcategory'])->name('categorydc.edit');
Route::put('/admin/digitalchecklist/category/{id}', [DigitalChecklistController::class, 'updatecategory'])->name('categorydc.update');
Route::post('/admin/digitalchecklist/category/delete/', [DigitalChecklistController::class, 'destroycategory'])->name('categorydc.delete');
Route::get('/admin/digitalchecklist/category/{siteId}', [DigitalChecklistController::class, 'getCategorybysite']);
Route::get('/admin/digitalchecklist/listcategory/{categoryId}', [DigitalChecklistController::class, 'getSubCategorybysite']);

//Sub Category
Route::get('/admin/digitalchecklist/subcategory/index', [DigitalChecklistController::class, 'indexsubcategory'])->name('subcategorydc.index');
Route::get('/admin/digitalchecklist/subcategory/data', [DigitalChecklistController::class, 'getsubcategory'])->name('subcategorydc.data');
Route::post('/admin/digitalchecklist/subcategory/store', [DigitalChecklistController::class, 'storesubcategory'])->name('subcategorydc.store');
Route::get('/admin/digitalchecklist/subcategory/{id}/edit', [DigitalChecklistController::class, 'editsubcategory'])->name('subcategorydc.edit');
Route::put('/admin/digitalchecklist/subcategory/{id}', [DigitalChecklistController::class, 'updatesubcategory'])->name('subcategorydc.update');
Route::post('/admin/digitalchecklist/subcategory/delete/', [DigitalChecklistController::class, 'destroysubcategory'])->name('subcategorydc.delete');


//enduser DigitalChecklistController
// Digital Checklist Routes
Route::prefix('digital-checklist')->group(function () {
    Route::get('/', [DigitalChecklistController::class, 'indexenduser'])->name('digital-checklist.index');
    Route::get('/create', [DigitalChecklistController::class, 'createdcenduser'])->name('digital-checklist.create');
    Route::post('/', [DigitalChecklistController::class, 'store'])->name('digital-checklist.store');
    Route::get('/{id}', [DigitalChecklistController::class, 'show'])->name('digital-checklist.show');
    Route::get('/{id}/edit', [DigitalChecklistController::class, 'edit'])->name('digital-checklist.edit');
    Route::put('/{id}', [DigitalChecklistController::class, 'update'])->name('digital-checklist.update');
    Route::delete('/{id}', [DigitalChecklistController::class, 'destroy'])->name('digital-checklist.destroy');
});

// Room Status Routes (you might want to create a separate controller for this)
Route::prefix('room-status')->group(function () {
    Route::get('/', [RoomStatusController::class, 'index'])->name('room-status.index');
    // Add other room status routes as needed
});


//Fitur Logout
Route::post('/logout', function (Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect('/login');
})->name('logout');

});
