<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use App\Models\Role;
use App\Models\User;
use App\Models\Sites;
use App\Models\Department;

use App\Models\SettingCameras;

class OtherSettingsController extends Controller
{
  protected $currentUser;

  public function __construct()
  {
    $this->middleware(function ($request, $next) {
      $this->currentUser = Auth::user();
      return $next($request);
    });
  }


  public function index()
  {
    $statussetting = SettingCameras::get()->first();
    return view('pages.othersetting.index', compact('statussetting'));
  }

  public function update(Request $request)
  {
    $status = $request->input('status');

    $update = SettingCameras::find(1);
    $update->status = $status;
    $update->save();
    return redirect('/admin/othersetting/index');
  }

  public static function getCameraSetting()
  {
    $statussetting = SettingCameras::get()->first();
    $status = $statussetting->status;

    return $status;
  }

}
