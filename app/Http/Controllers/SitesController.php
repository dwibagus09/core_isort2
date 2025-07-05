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

class SitesController extends Controller
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
    $sites = Sites::get()->first();
    return view('pages.sites.index', compact('sites'));
  }

  public function save(Request $request)
  {
    if ($request->site_id) {
          Sites::where('site_id', $request->site_id)->update([
              'site_name' => $request->site_name,
              'site_fullname' => $request->site_fullname,
              'site_address' => $request->site_address,
              'initial' => $request->initial,
          ]);
      } else {
          Sites::create([
              'site_name' => $request->site_name,
              'site_fullname' => $request->site_fullname,
              'site_address' => $request->site_address,
              'initial' => $request->initial,
          ]);
      }
      return redirect()->back()->with('success', 'Data saved!');
  }
}
