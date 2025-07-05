<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use App\Models\Role; // Pastikan model Role diimpor
use App\Models\User;
use App\Models\Sites;
use App\Models\Area;
use App\Models\Locations;
use App\Models\Department;
use App\Models\Kaizen;
use App\Models\Kaizen_Type;
use App\Models\Incidents;
use App\Models\Modus;
use Illuminate\Support\Facades\DB; // Add this line

class KaizenController extends Controller
{
  protected $currentUser; // Properti untuk menyimpan user yang sedang login

  public function __construct()
  {
    $this->middleware(function ($request, $next) {
      $this->currentUser = Auth::user(); // Mengambil user yang sedang login
      return $next($request);
    });
  }

  //Update Doni
  public function getTypeandArea($dept_id){
    $areas = DB::table('areas')
    ->where('department_id', $dept_id)
    ->select('id', 'area_name as name')
    ->get();

    $type = DB::table('kaizen_type')
    ->where('department_id', $dept_id)
    ->select('kaizen_type_id as id', 'kaizen_type as name')
    ->get();

    $incident = DB::table('incidents')
    ->where('department_id', $dept_id)
    ->select('id', 'incident_name as name')
    ->get();

    $data = ['area' => $areas, 'type' => $type, 'incident' => $incident];
    return response()->json($data);
  }

  public function submitKaizen(Request $request){
    $date = now();
    $years = $date->format('Y');

    $file = $request->file('photo');
    $cleanName = preg_replace('/[^A-Za-z0-9.\-_]/', '_', $file->getClientOriginalName());
    $fileName = $date->format('Ymd_His') . '_' . $cleanName;
    $file->move(public_path('uploads/kaizen/'.$years.'/open'), $fileName);

    $data = [
      'picture' => '/uploads/kaizen/'.$years.'/open/'.$fileName,
      'location' => $request->input('location'),
      'description' => $request->input('detail_location'),
      'issue_date' => $date,
      'user_id' => Auth::user()->user_id,
      'department_id' => $request->input('department'),
      'solved_picture' => NULL,
      'solved_date' => NULL,
      'kaizen_type_id' => $request->input('type'),
      'status' => 'Opened',
      'keterangan' => $request->input('detail_modus'),
      'mod_report_id' => NULL,
      'site_id' => session('selected_branch'),
      'kejadian_id' => $request->input('incident'),
      'modus_id' => $request->input('modus'),
      'floor_id' => NULL,
      'manpower_id' => NULL,
      'area_id' => $request->input('area'),
      'kaizen_type2_id' => NULL,
    ];

    $insertsubmit = DB::table('kaizen')->insertGetId($data);

    if($insertsubmit){
      $kaizenId = $insertsubmit;
      $kaizen = Kaizen::with([
        'department',
        'user',
      ])->where('kaizen_id', $insertsubmit)->select('kaizen.*')->first();

      $message = "📢 NEW KAIZEN 📢
👤  {$kaizen->user->name}

🌐  {$kaizen->site->site_name} - {$kaizen->department->department_name}
📍  {$kaizen->locations->location_name} - {$kaizen->area->area_name} - {$kaizen->description}
⚠️  {$kaizen->kaizentype->kaizen_type} - {$kaizen->incident->incident_name} - {$kaizen->modus->modus_name}
💬  {$kaizen->keterangan}

🔗  https://cmms.hanzel.id/sharekaizen/{$kaizenId}";

      $target = "120363401566386780@g.us";
      $curlWA = curl_init();

      curl_setopt_array($curlWA, array(
        CURLOPT_URL => 'https://api.fonnte.com/send',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => array(
          "target" => $target,
          "message" => $message
        ),
        CURLOPT_HTTPHEADER => array(
          'Authorization: DgQQ31k3SBbPE45N3Jmn'
        ),
      ));

      $response = curl_exec($curlWA);
      $error_msg = '';
      if (curl_errno($curlWA)) {
        $error_msg = curl_error($curlWA);
      }
      curl_close($curlWA);

      DB::table('logs_'.date('n'))->insert([
        'site_id'     => session('selected_branch'),
        'user_id'     => Auth::user()->user_id ?? null,
        'action'      => 'submitKaizen',
        'data'        => json_encode($data),
        'log_date'    => now(),
        'browser'     => request()->header('User-Agent'),
        'ip_address'  => request()->ip(),
        'url'         => request()->fullUrl(),
      ]);

      if ($error_msg != "") {
        return response()->json([
         'status' => 500,
         'message' => 'Failed to send WhatsApp message: ' . $error_msg
        ]);
      }else{
        return response()->json([
          'status' => 200,
          'message' => 'Kaizen submitted successfully',
        ]);
      }
    }else{
      return response()->json([
       'status' => 500,
       'message' => 'Failed to submit Kaizen'
      ]);
    }
  }

  public function kaizenShare($id)
  {
    $kaizenId = $id;
    $kaizen = Kaizen::findOrFail($id);
    return view('pages.kaizen.sharenotif', compact('kaizen','kaizenId'));
  }

  public function addCommentKaizen(Request $request){
    $date = now();
    $fileName = null;

    if ($request->hasFile('file') && $request->file('file')->isValid()) {
      $file = $request->file('file');
      $cleanName = preg_replace('/[^A-Za-z0-9.\-_]/', '_', $file->getClientOriginalName());
      $fileName = $date->format('Ymd_His') . '_' . $cleanName;
      $file->move(public_path('uploads/kaizen/'.$date->format('Y').'/comments'), $fileName);
      $fileName = '/uploads/kaizen/'.$date->format('Y').'/comments/'.$fileName;
    }

    $data = [
      'kaizen_id' =>  $request->input('kaizen_id'),
      'comment' =>  $request->input('comment'),
      'comment_date' =>  $date,
      'user_id' =>  Auth::user()->user_id,
      'site_id' =>  session('selected_branch'),
      'filename' => $fileName,
      'isclosed' => 0,
    ];

    $addcomments = DB::table('comments')->insert($data);

    if ($addcomments) {
      DB::table('logs_'.date('n'))->insert([
        'site_id'     => session('selected_branch'),
        'user_id'     => Auth::user()->user_id ?? null,
        'action'      => 'addcomments',
        'data'        => json_encode($data),
        'log_date'    => now(),
        'browser'     => request()->header('User-Agent'),
        'ip_address'  => request()->ip(),
        'url'         => request()->fullUrl(),
      ]);
      return response()->json(['success' => 'Data Comment berhasil ditambah']);
    }

    return response()->json(['error' => 'Gagal memperbarui data'], 500);
  }

  public function addProgressKaizen(Request $request){
    if (!$request->hasFile('images')) {
      return response()->json(['error' => 'No files sent'], 422);
    }

    $date = now();
    $uploadPath = public_path('uploads/kaizen/' . $date->format('Y') . '/progress');
    $savedFiles = [];
    $countFiles = count($request->file('images'));
    $id = $request->input('kaizen_id');
    foreach ($request->file('images') as $file) {
      if ($file->isValid()) {

        $cleanName = preg_replace('/[^A-Za-z0-9.\-_]/', '_', $file->getClientOriginalName());
        $fileName = $date->format('Ymd_His') . '_' . uniqid() . '_' . $cleanName;
        $file->move($uploadPath, $fileName);
        $relativePath = '/uploads/kaizen/' . $date->format('Y') . '/progress/' . $fileName;

        $addprogresskaizen = DB::table('kaizen_progress_images')->insert([
          'kaizen_id' => $id,
          'filename' => $relativePath,
          'user_id' => Auth::user()->user_id,
          'upload_date' => $date,
        ]);

        if($addprogresskaizen){
          $savedFiles[] = $relativePath;
        }
      }
    }

    if ($countFiles == count($savedFiles)) {
      DB::table('logs_'.date('n'))->insert([
        'site_id'     => session('selected_branch'),
        'user_id'     => Auth::user()->user_id ?? null,
        'action'      => 'addprogresskaizen',
        'data'        => json_encode($savedFiles),
        'log_date'    => now(),
        'browser'     => request()->header('User-Agent'),
        'ip_address'  => request()->ip(),
        'url'         => request()->fullUrl(),
      ]);
      return response()->json(['success' => 'Data Progress Kaizen berhasil ditambah']);
    }

    return response()->json(['error' => 'Gagal memperbarui data'], 500);
  }

  public function addSolvedKaizen(Request $request){
    if ($request->hasFile('file') && $request->file('file')->isValid()) {
      $date = now();
      $year = $date->format('Y');

      $file = $request->file('file');
      $originalName = preg_replace('/[^A-Za-z0-9.\-_]/', '_', $file->getClientOriginalName());
      $justfileName = $date->format('Ymd_His') . '_' . $originalName;
      $file->move(public_path('uploads/kaizen/'.$year.'/closed'), $justfileName);
      $fileName = '/uploads/kaizen/'.$year.'/closed/'.$justfileName;

      $datasolved  = [
        'solved_picture' => $fileName,
        'solved_date' =>  $date,
        'status' =>  'Closed',
      ];
      $addsolved = DB::table('kaizen')->where('kaizen_id', $request->input('kaizen_id'))->update($datasolved);

      if ($addsolved) {

        $datacomment = [
          'kaizen_id' =>  $request->input('kaizen_id'),
          'comment' =>  $request->input('comment'),
          'comment_date' =>  $date,
          'user_id' =>  Auth::user()->user_id,
          'site_id' =>  session('selected_branch'),
          'filename' => $fileName,
          'isclosed' => 1,
        ];
        $addcomments = DB::table('comments')->insert($datacomment);

        if($addcomments){
          $data = ['data_solved' => $datasolved, 'data_comment_solved' => $datacomment];
          DB::table('logs_'.date('n'))->insert([
            'site_id'     => session('selected_branch'),
            'user_id'     => Auth::user()->user_id ?? null,
            'action'      => 'addsolved',
            'data'        => json_encode($data),
            'log_date'    => now(),
            'browser'     => request()->header('User-Agent'),
            'ip_address'  => request()->ip(),
            'url'         => request()->fullUrl(),
          ]);
          return response()->json(['success' => 'Data Solved Kaizen berhasil ditambah']);
        }else{
          return response()->json(['error' => 'Gagal memperbarui data'], 500);
        }

      }
      return response()->json(['error' => 'Gagal memperbarui data'], 500);
    }else{
      return response()->json(['error' => 'No files sent'], 422);
    }
  }

  public function openKaizen(Request $request)
  {
    $searchId = $request->query('search') ? $request->query('search') : '';
    $currentUser = Auth::user();
    $newkaizen = null;
    $pictureKaizen = 'https://cmms.hanzel.id/build/assets/images/brand/isort_new_logo.png';
    $keteranganKaizen = 'Open Kaizen';

    if (!$currentUser) {
      dd("Error: User tidak ditemukan.");
    }

    if($searchId){
       $newkaizen = Kaizen::findOrFail($searchId);
       $selectedBranchId = $newkaizen->site_id;
       $pictureKaizen = 'https://cmms.hanzel.id'.$newkaizen->picture;
       $keteranganKaizen = 'New Kaizen : '.$newkaizen->keterangan;
       session()->put('selected_branch', $selectedBranchId);
    }

    $siteIds = explode(',', $currentUser->site_id);
    $selectedBranchId = session('selected_branch', 'all');

    $totalallkaizens = DB::table('kaizen')
    ->where('site_id', $selectedBranchId)
    ->where('status','Opened')
    ->count();
    $departments = Department::where('site_id', $selectedBranchId)
    ->withCount(['kaizens' => function ($query) {
      $query->select(DB::raw("count(*)"));
      $query->where('status','Opened');
    }])
    ->get();
    $sitename = Sites::where('site_id',$selectedBranchId)->first();

    return view('pages.kaizen.open.index', compact('departments','sitename','totalallkaizens','searchId','keteranganKaizen','pictureKaizen'));
  }

  public function getKaizenOpen(Request $request)
  {
    $department_id = $request->get('department_id') ?? 'all';

    $currentUser = Auth::user();
    if (!$currentUser) {
      return response()->json(["error" => "User tidak ditemukan"], 400);
    }

    $siteIds = explode(',', $currentUser->site_id);
    $selectedBranchId = session('selected_branch', 'all');

    $query = Kaizen::with([
      'department',
      'user',
      'progressImages' => function($query) {
        $query->select('kaizen_progress_image_id', 'kaizen_id', 'filename');
        $query->orderBy('upload_date','desc');
      },
      'commentKaizens' => function($query) {
        $query->select('kaizen_id','comment', 'comment_date', 'filename','user_id');
        $query->orderBy('comment_date','desc');
      }
    ])
    ->whereNot('status', 'Closed');

    if($department_id != 'all'){
      $query->where('department_id', $department_id);
    }

    $query->select('kaizen.*');

    if ($currentUser->role->role === 'Admin') {
      $query->whereHas('department', function($q) use ($siteIds) {
        $q->whereIn('site_id', $siteIds);
      });
    }

    if ($selectedBranchId !== 'all') {
      $query->whereHas('department', function($q) use ($selectedBranchId) {
        $q->whereRaw("FIND_IN_SET(?, site_id)", [$selectedBranchId]);
      });
    }
    $query->orderBy('kaizen_id','desc');

    $kaizens = $query->get();
    // echo json_encode($kaizens);die;
    $formattedKaizens = $kaizens->map(function($kaizen) use ($selectedBranchId) {
      return [
        'kaizen_id' => $kaizen->kaizen_id,
        'picture' => $kaizen->picture,
        'solved_picture' => $kaizen->solved_picture,
        'area' => $kaizen->area->area_name,
        'location' => $kaizen->locations->location_name,
        'description' => $kaizen->description,
        'type' => $kaizen->kaizentype->kaizen_type,
        'incident' => $kaizen->incident->incident_name ?? '',
        'modus' => $kaizen->modus->modus_name ?? '',
        'keterangan' => $kaizen->keterangan,
        'issue_date' => $kaizen->issue_date,
        'status' => $kaizen->status,
        'department_name' => $kaizen->department->department_name ?? '',
        'user_name' => $kaizen->user->name ?? '',
        'user_ids' => $kaizen->user_id ?? '',
        'siteName' => $kaizen->site->site_name,
        'sessionSite' => $selectedBranchId,
        'filename' => $kaizen->progressImages->map(function($image) {
          return [
            'filename' => $image->filename
          ];
        })->toArray(),
        'comments' => $kaizen->commentKaizens->map(function($result) {
          return [
            'comment' => $result->comment,
            'comment_date' => $result->comment_date,
            'user' => $result->user->name ?? '-',
            'filename' => $result->filename ?? ''
          ];
        })->toArray(),
      ];
    });

    if ($formattedKaizens->isEmpty()) {
      return response()->json([
        "error" => "Data kaizen kosong",
        "details" => [
          "User Role" => $currentUser->role->role,
          "Site IDs" => $siteIds,
          "Selected Branch" => $selectedBranchId
        ]
      ], 404);
    }

    return response()->json($formattedKaizens);
  }

  public function closeKaizen()
  {
    $currentUser = Auth::user();
    if (!$currentUser) {
      dd("Error: User tidak ditemukan.");
    }
    $siteIds = explode(',', $currentUser->site_id);
    $selectedBranchId = session('selected_branch', 'all');

    $totalallkaizens = DB::table('kaizen')
    ->where('site_id', $selectedBranchId)
    ->where('status','Closed')
    ->count();
    $departments = Department::where('site_id', $selectedBranchId)
    ->withCount(['kaizens' => function ($query) {
      $query->select(DB::raw("count(*)"));
      $query->where('status','Closed');
    }])
    ->get();
    $sitename = Sites::where('site_id',$selectedBranchId)->first();
    // echo json_encode($departments);die;
    return view('pages.kaizen.close.index', compact('departments','sitename','totalallkaizens'));
  }

  public function getKaizenClose(Request $request)
  {
    $department_id = $request->get('department_id') ?? 'all';

    $currentUser = Auth::user();
    if (!$currentUser) {
      return response()->json(["error" => "User tidak ditemukan"], 400);
    }

    $siteIds = explode(',', $currentUser->site_id);
    $selectedBranchId = session('selected_branch', 'all');

    $query = Kaizen::with([
      'department',
      'user',
      'progressImages' => function($query) {
        $query->select('kaizen_progress_image_id', 'kaizen_id', 'filename');
        $query->orderBy('upload_date','desc');
      },
      'commentKaizens' => function($query) {
        $query->select('kaizen_id','comment', 'comment_date', 'filename','user_id','isclosed');
        $query->orderBy('comment_date','desc');
      }
    ])
    ->whereNot('status', 'Opened');

    if($department_id != 'all'){
      $query->where('department_id', $department_id);
    }

    $query->select('kaizen.*');

    if ($currentUser->role->role === 'Admin') {
      $query->whereHas('department', function($q) use ($siteIds) {
        $q->whereIn('site_id', $siteIds);
      });
    }

    if ($selectedBranchId !== 'all') {
      $query->whereHas('department', function($q) use ($selectedBranchId) {
        $q->whereRaw("FIND_IN_SET(?, site_id)", [$selectedBranchId]);
      });
    }
    $query->orderBy('kaizen_id','desc');

    $kaizens = $query->get();
    // echo json_encode($kaizens);die;
    $formattedKaizens = $kaizens->map(function($kaizen) use ($selectedBranchId) {
      return [
        'kaizen_id' => $kaizen->kaizen_id,
        'picture' => $kaizen->picture,
        'solved_picture' => $kaizen->solved_picture,
        'area' => $kaizen->area->area_name,
        'location' => $kaizen->locations->location_name,
        'description' => $kaizen->description,
        'type' => $kaizen->kaizentype->kaizen_type,
        'incident' => $kaizen->incident->incident_name ?? '',
        'modus' => $kaizen->modus->modus_name ?? '',
        'keterangan' => $kaizen->keterangan,
        'issue_date' => $kaizen->issue_date,
        'status' => $kaizen->status,
        'department_name' => $kaizen->department->department_name ?? '',
        'user_name' => $kaizen->user->name ?? '',
        'user_ids' => $kaizen->user_id ?? '',
        'siteName' => $kaizen->site->site_name,
        'sessionSite' => $selectedBranchId,
        'filename' => $kaizen->progressImages->map(function($image) {
          return [
            'filename' => $image->filename
          ];
        })->toArray(),
        'comments' => $kaizen->commentKaizens->map(function($result) {
          return [
            'comment' => $result->comment,
            'comment_date' => $result->comment_date,
            'user' => $result->user->name ?? '-',
            'filename' => $result->filename ?? '',
            'isclosed' => $result->isclosed
          ];
        })->toArray(),
      ];
    });

    if ($formattedKaizens->isEmpty()) {
      return response()->json([
        "error" => "Data kaizen kosong",
        "details" => [
          "User Role" => $currentUser->role->role,
          "Site IDs" => $siteIds,
          "Selected Branch" => $selectedBranchId
        ]
      ], 404);
    }

    return response()->json($formattedKaizens);
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

    $totalallkaizens = DB::table('kaizen')
    ->where('site_id', $selectedBranchId)
    ->count();
    $departments = Department::where('site_id', $selectedBranchId)
    ->withCount(['kaizens' => function ($query) {
      $query->select(DB::raw("count(*)"));
    }])
    ->get();
    $sitename = Sites::where('site_id',$selectedBranchId)->first();

    return view('pages.kaizen.index', compact('departments','sitename','totalallkaizens'));
  }

  public function getKaizen(Request $request)
  {
  $department_id = $request->get('department_id') ?? 'all';

  $currentUser = Auth::user();
  if (!$currentUser) {
    return response()->json(["error" => "User tidak ditemukan"], 400);
  }

  $siteIds = explode(',', $currentUser->site_id);
  $selectedBranchId = session('selected_branch', 'all');

  $query = Kaizen::with([
    'department',
    'user',
    'progressImages' => function($query) {
      $query->select('kaizen_progress_image_id', 'kaizen_id', 'filename');
      $query->orderBy('upload_date','desc');
    },
    'commentKaizens' => function($query) {
      $query->select('kaizen_id','comment', 'comment_date', 'filename','user_id','isclosed');
      $query->orderBy('comment_date','desc');
    }
  ]);

  if($department_id != 'all'){
    $query->where('department_id', $department_id);
  }

  $query->select('kaizen.*');

  if ($currentUser->role->role === 'Admin') {
    $query->whereHas('department', function($q) use ($siteIds) {
      $q->whereIn('site_id', $siteIds);
    });
  }

  if ($selectedBranchId !== 'all') {
    $query->whereHas('department', function($q) use ($selectedBranchId) {
      $q->whereRaw("FIND_IN_SET(?, site_id)", [$selectedBranchId]);
    });
  }
  $query->orderBy('kaizen_id','desc');

  $kaizens = $query->get();
  // echo json_encode($kaizens);die;
  $formattedKaizens = $kaizens->map(function($kaizen) use ($selectedBranchId) {
    return [
      'kaizen_id' => $kaizen->kaizen_id,
      'picture' => $kaizen->picture,
      'solved_picture' => $kaizen->solved_picture,
      'area' => $kaizen->area->area_name,
      'location' => $kaizen->locations->location_name,
      'description' => $kaizen->description,
      'type' => $kaizen->kaizentype->kaizen_type,
      'incident' => $kaizen->incident->incident_name ?? '',
      'modus' => $kaizen->modus->modus_name ?? '',
      'keterangan' => $kaizen->keterangan,
      'issue_date' => $kaizen->issue_date,
      'status' => $kaizen->status,
      'department_name' => $kaizen->department->department_name ?? '',
      'user_name' => $kaizen->user->name ?? '',
      'user_ids' => $kaizen->user_id ?? '',
      'siteName' => $kaizen->site->site_name,
      'sessionSite' => $selectedBranchId,
      'filename' => $kaizen->progressImages->map(function($image) {
        return [
          'filename' => $image->filename
        ];
      })->toArray(),
      'comments' => $kaizen->commentKaizens->map(function($result) {
        return [
          'comment' => $result->comment,
          'comment_date' => $result->comment_date,
          'user' => $result->user->name ?? '-',
          'filename' => $result->filename ?? '',
          'isclosed' => $result->isclosed
        ];
      })->toArray(),
    ];
  });

  if ($formattedKaizens->isEmpty()) {
    return response()->json([
      "error" => "Data kaizen kosong",
      "details" => [
        "User Role" => $currentUser->role->role,
        "Site IDs" => $siteIds,
        "Selected Branch" => $selectedBranchId
      ]
    ], 404);
  }

  return response()->json($formattedKaizens);
  }

  //End Update Doni

  // public function getKaizen()
  // {
  //   $currentUser = Auth::user();
  //
  //   if (!$currentUser) {
  //     return response()->json(["error" => "User tidak ditemukan"], 400);
  //   }
  //
  //   $siteIds = explode(',', $currentUser->site_id);
  //   $selectedBranchId = session('selected_branch', 'all');
  //
  //   $query = Kaizen::with([
  //     'department',
  //     'user',
  //     'progressImages' => function($query) {
  //       $query->select('kaizen_progress_image_id', 'kaizen_id', 'filename');
  //     }
  //   ])
  //   ->whereNot('status', 'Closed')
  //   ->select('kaizen.*');
  //
  //   // For Admin users, filter by site
  //   if ($currentUser->role->role === 'Admin') {
  //     $query->whereHas('department', function($q) use ($siteIds) {
  //       $q->whereIn('site_id', $siteIds);
  //     });
  //   }
  //
  //   // Filter by selected branch if not 'all'
  //   if ($selectedBranchId !== 'all') {
  //     $query->whereHas('department', function($q) use ($selectedBranchId) {
  //       $q->whereRaw("FIND_IN_SET(?, site_id)", [$selectedBranchId]);
  //     });
  //   }
  //
  //   // Get the results
  //   $kaizens = $query->get();
  //   //dd($kaizens->first()->progressImages); // Cek apakah relasi terload
  //   // Transform the data to match frontend expectations
  //   $formattedKaizens = $kaizens->map(function($kaizen) {
  //     return [
  //       'kaizen_id' => $kaizen->kaizen_id,
  //       'picture' => $kaizen->picture,
  //       'solved_picture' => $kaizen->solved_picture,
  //       'location' => $kaizen->locations->location_name,
  //       'area' => $kaizen->area->area_name,
  //       'type' => $kaizen->kaizentype->kaizen_type,
  //       'description' => $kaizen->description,
  //       'issue_date' => $kaizen->issue_date,
  //       'status' => $kaizen->status,
  //       'department_name' => $kaizen->department->department_name ?? '',
  //       'user_name' => $kaizen->user->name ?? '',
  //       'filename' => $kaizen->progressImages->map(function($image) {
  //         return [
  //           'filename' => $image->filename
  //         ];
  //       })->toArray(),
  //       // Add other required fields
  //     ];
  //   });
  //
  //   //dd($formattedKaizens);
  //
  //   if ($formattedKaizens->isEmpty()) {
  //     return response()->json([
  //       "error" => "Data kaizen kosong",
  //       "details" => [
  //         "User Role" => $currentUser->role->role,
  //         "Site IDs" => $siteIds,
  //         "Selected Branch" => $selectedBranchId
  //       ]
  //     ], 404);
  //   }
  //
  //   return response()->json($formattedKaizens);
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

    return response()->json(['success' => 'Kaizen created successfully!']);
  }


  //   public function edit($id)
  //     {
  //         $kaizen = Kaizen::with([
  //             'department',
  //             'area',
  //             'locations',
  //             'kaizenType',
  //             'modus.incident'
  //         ])->findOrFail($id);

  //         return response()->json([
  //             // 'areas' => Area::where('department_id', $kaizen->department_id)->get(),
  //             'areas' => Area::where('department_id', $kaizen->department_id)
  //             ->get()
  //             ->map(fn ($area) => [
  //                 'id' => $area->id,
  //                 'name' => $area->area_name
  //             ]),
  //             'locations' => Locations::where('area_id', $kaizen->area_id)
  //             ->get()
  //             ->map(fn ($locations) => [
  //                 'id' => $locations->id,
  //                 'name' => $locations->location_name
  //             ]),
  //             'kaizen_types' => Kaizen_Type::where('department_id', $kaizen->department_id)
  //             ->get()
  //             ->map(fn ($type) => [
  //                 'id' => $type->kaizen_type_id,
  //                 'name' => $type->kaizen_type
  //             ]),
  //             'current_department' => [
  //                 'id' => $kaizen->department_id,
  //                 'name' => $kaizen->department->department_name ?? 'N/A'
  //             ],
  //             'current_selections' => [
  //                 'area' => $kaizen->area_id,
  //                 'location' => $kaizen->location,
  //                 'kaizen_type' => $kaizen->kaizen_type_id,
  //                 'description' => $kaizen->description,
  //                 'modus' => [
  //                     'id' => $kaizen->modus_id,
  //                     'name' => $kaizen->modus->name ?? 'N/A'
  //                 ],
  //                 'incident' => [
  //                     'id' => $kaizen->modus->incident->id ?? null,
  //                     'name' => $kaizen->modus->incident->name ?? 'N/A'
  //                 ]
  //             ],
  //             'initial_incidents' => Incidents::where('department_id', $kaizen->department_id)->get(),
  //             'initial_moduses' => Modus::whereHas('incident', function($q) use ($kaizen) {
  //                 $q->where('department_id', $kaizen->department_id);
  //             })->get()
  //         ]);
  //     }

  //     public function edit($id)
  // {
  //     $kaizen = Kaizen::with([
  //         'department',
  //         'area',
  //         'locations',
  //         'kaizenType',
  //         'modus.incident'
  //     ])->findOrFail($id);

  //     // Format lama untuk kompatibilitas PHP < 7.4
  //     $areas = Area::where('department_id', $kaizen->department_id)->get();
  //     $formattedAreas = [];
  //     foreach ($areas as $area) {
  //         $formattedAreas[] = [
  //             'id' => $area->id,
  //             'name' => $area->area_name
  //         ];
  //     }

  //     $locations = Locations::where('area_id', $kaizen->area_id)->get();
  //     $formattedLocations = [];
  //     foreach ($locations as $location) {
  //         $formattedLocations[] = [
  //             'id' => $location->id,
  //             'name' => $location->location_name
  //         ];
  //     }

  //     $kaizenTypes = Kaizen_Type::where('department_id', $kaizen->department_id)->get();
  //     $formattedKaizenTypes = [];
  //     foreach ($kaizenTypes as $type) {
  //         $formattedKaizenTypes[] = [
  //             'id' => $type->kaizen_type_id,
  //             'name' => $type->kaizen_type
  //         ];
  //     }

  //     $incidents = Incidents::where('department_id', $kaizen->department_id)->get();
  //     $formattedIncidents = [];
  //     foreach ($incidents as $incident) {
  //         $formattedIncidents[] = [
  //             'id' => $incident->id,
  //             'name' => $incident->incident_name
  //         ];
  //     }

  //     $moduses = Modus::whereHas('incident', function($q) use ($kaizen) {
  //         $q->where('department_id', $kaizen->department_id);
  //     })->get();
  //     $formattedModuses = [];
  //     foreach ($moduses as $modus) {
  //         $formattedModuses[] = [
  //             'id' => $modus->id,
  //             'name' => $modus->incident_nname
  //         ];
  //     }

  //     return response()->json([
  //         'areas' => $formattedAreas,
  //         'locations' => $formattedLocations,
  //         'kaizen_types' => $formattedKaizenTypes,
  //         'current_department' => [
  //             'id' => $kaizen->department_id,
  //             'name' => $kaizen->department->department_name ?? 'N/A'
  //         ],
  //         'current_selections' => [
  //             'area' => $kaizen->area_id,
  //             'location' => $kaizen->location,
  //             'kaizen_type' => $kaizen->kaizen_type_id,
  //             'description' => $kaizen->description,
  //             'modus' => [
  //                 'id' => $kaizen->modus_id,
  //                 'name' => $kaizen->modus->modus_name ?? 'N/A'
  //             ],
  //             'incident' => [
  //                 'id' => $kaizen->modus->incident->id ?? null,
  //                 'name' => $kaizen->modus->incident->incident_name ?? 'N/A'
  //             ]
  //         ],
  //         'initial_incidents' => $formattedIncidents,
  //         'initial_moduses' => $formattedModuses
  //     ]);
  // }

  // public function edit($id)
  // {
  //     // Get the kaizen data with relationships
  //     $kaizen = DB::table('kaizen')
  //         ->where('kaizen_id', $id)
  //         ->first();

  //     if (!$kaizen) {
  //         return response()->json(['error' => 'Kaizen not found'], 404);
  //     }

  //     // Get department name
  //     $department = DB::table('departments')
  //         ->where('id', $kaizen->department_id)
  //         ->first();

  //     // Get related data using simple queries
  //     $areas = DB::table('areas')
  //         ->where('department_id', $kaizen->department_id)
  //         ->select('id', 'area_name as name')
  //         ->get();

  //     $locations = DB::table('locations')
  //         ->where('area_id', $kaizen->area_id)
  //         ->select('id', 'location_name as name')
  //         ->get();

  //     $kaizenTypes = DB::table('kaizen_type')
  //         ->where('department_id', $kaizen->department_id)
  //         ->select('kaizen_type_id as id', 'kaizen_type as name')
  //         ->get();

  //     $incidents = DB::table('incidents')
  //         ->where('department_id', $kaizen->department_id)
  //         ->select('id', 'incident_name as name')
  //         ->get();

  //   // dd($incidents);

  //     $moduses = DB::table('modus')
  //         ->where('incident_id', $kaizen->incident_id ?? null)
  //         ->select('id', 'modus_name as name')
  //         ->get();

  //     // Get current modus and incident if exists
  //     $currentModus = $kaizen->modus_id ? DB::table('modus')
  //         ->where('id', $kaizen->modus_id)
  //         ->first() : null;

  //     $currentIncident = $currentModus ? DB::table('incidents')
  //         ->where('id', $currentModus->incident_id)
  //         ->first() : null;

  //     return response()->json([
  //         'areas' => $areas,
  //         'locations' => $locations,
  //         'kaizen_types' => $kaizenTypes,
  //         'incidents' => $incidents,
  //         'moduses' => $moduses,
  //         'current_data' => [
  //             'department' => [
  //                 'id' => $kaizen->department_id,
  //                 'name' => $department->department_name ?? 'N/A'
  //             ],
  //             'area_id' => $kaizen->area_id,
  //             'location_id' => $kaizen->location,
  //             'kaizen_type_id' => $kaizen->kaizen_type_id,
  //             'incident_id' => $currentIncident->id ?? null,
  //             'modus_id' => $currentModus->id,
  //             'description' => $kaizen->description
  //         ]
  //     ]);
  // }

  public function edit($id)
  {
    // Get the kaizen data with relationships
    $kaizen = DB::table('kaizen')
    ->where('kaizen_id', $id)
    ->first();

    if (!$kaizen) {
      return response()->json(['error' => 'Kaizen not found'], 404);
    }

    // Get department name
    $department = DB::table('departments')
    ->where('id', $kaizen->department_id)
    ->first();

    // Get related data using simple queries
    $areas = DB::table('areas')
    ->where('department_id', $kaizen->department_id)
    ->select('id', 'area_name as name')
    ->get();

    $locations = DB::table('locations')
    ->where('area_id', $kaizen->area_id)
    ->select('id', 'location_name as name')
    ->get();

    $kaizenTypes = DB::table('kaizen_type')
    ->where('department_id', $kaizen->department_id)
    ->select('kaizen_type_id as id', 'kaizen_type as name')
    ->get();

    $incidents = DB::table('incidents')
    ->where('department_id', $kaizen->department_id)
    ->select('id', 'incident_name as name')
    ->get();

    // Get current modus if exists
    $currentModus = $kaizen->modus_id ? DB::table('modus')
    ->where('id', $kaizen->modus_id)
    ->first() : null;

    // Get moduses based on current incident (if modus exists)
    $moduses = [];
    if ($currentModus) {
      $moduses = DB::table('modus')
      ->where('incident_id', $currentModus->incident_id)
      ->select('id', 'modus_name as name')
      ->get();
    }

    // Get current incident based on current modus
    $currentIncident = $currentModus ? DB::table('incidents')
    ->where('id', $currentModus->incident_id)
    ->first() : null;

    return response()->json([
      'areas' => $areas,
      'locations' => $locations,
      'kaizen_types' => $kaizenTypes,
      'incidents' => $incidents,
      'moduses' => $moduses,
      'current_data' => [
        'department' => [
          'id' => $kaizen->department_id,
          'name' => $department->department_name ?? 'N/A'
        ],
        'area_id' => $kaizen->area_id,
        'location_id' => $kaizen->location,
        'kaizen_type_id' => $kaizen->kaizen_type_id,
        'incident_id' => $currentIncident->id ?? null,
        'modus_id' => $kaizen->modus_id,
        'description' => $kaizen->description
      ]
    ]);
  }

  // Update data
  // public function update(Request $request, $id)
  // {
  //     $validated = $request->validate([
  //         'department_id' => 'required|exists:departments,id',
  //         'area_id' => 'required|exists:areas,id',
  //         'location' => 'required|exists:locations,id', // Sesuai name di form
  //         'kaizen_type_id' => 'required|exists:kaizen_type,kaizen_type_id'
  //     ]);

  //     $kaizen = Kaizen::findOrFail($id);

  //     // Ubah data sebelum update
  //     $dataToUpdate = [
  //         'department_id' => $validated['department_id'],
  //         'area_id' => $validated['area_id'],
  //         'location' => $validated['location'], // Ubah 'location' menjadi 'location_id'
  //         'kaizen_type_id' => $validated['kaizen_type_id']
  //     ];

  //     $kaizen->update($dataToUpdate);

  //     return response()->json(['success' => 'Kaizen Updated successfully!']);
  // }

  public function update(Request $request, $kaizenId)
  {
    $validated = $request->validate([
      'department_id' => 'required',
      'area_id' => 'required',
      'location' => 'required',
      'kaizen_type_id' => 'required',
      'incident_id' => 'nullable',
      'modus_id' => 'nullable',
      'description' => 'string',
      // tambahkan validasi lainnya sesuai kebutuhan
    ]);

    // dd($kaizenId);

    $kaizen = DB::table('kaizen')
    ->where('kaizen_id', $kaizenId)
    ->update([
      'department_id' => $validated['department_id'],
      'area_id' => $validated['area_id'],
      'location' => $validated['location'],
      'kaizen_type_id' => $validated['kaizen_type_id'],
      //'incident_id' => $validated['incident_id'] ?? null,
      'modus_id' => $validated['modus_id'] ?? null,
      'description' => $validated['description'],
      //'updated_at' => now(),
    ]);
    //dd($validated);
    if ($kaizen) {
      return response()->json(['success' => 'Data kaizen berhasil diperbarui']);
    }

    return response()->json(['error' => 'Gagal memperbarui data'], 500);
  }

  // Get Areas by Department
  public function getAreas($department_id)
  {
    return response()->json(
      Area::where('department_id', $department_id)->get()
    );
  }

  // Get Locations by Area
  public function getLocations($area_id)
  {
    return response()->json(
      Locations::where('area_id', $area_id)->get()
    );
  }

  // KaizenController.php
  // public function getModusesByIncident($incidentId)
  // {
  //     $moduses = Modus::where('incident_id', $incidentId)->get();
  //     return response()->json($moduses);
  // }
  public function getModusesByIncident($incidentId)
  {
    $moduses = DB::table('modus')
    ->where('incident_id', $incidentId)
    ->select('id', 'modus_name as name')
    ->get();

    return response()->json($moduses);
  }

  // public function deleteKaizen(Request $request)
  // {
  //     $ids = $request->input('ids'); // Array ID dari permintaan
  //     if (!empty($ids)) {
  //         Kaizen::whereIn('kaizen_id', $ids)->delete();
  //         return response()->json(['success' => true, 'message' => 'Kaizen deleted successfully.']);
  //     }
  //     return response()->json(['success' => false, 'message' => 'No departments selected.'], 400);
  // }

  // public function deleteKaizen(Request $request)
  // {
  //     $ids = $request->input('ids');

  //     if (!empty($ids)) {
  //         // Ambil data kaizen beserta gambar terkait
  //         $kaizens = Kaizen::with('progressImages')->whereIn('kaizen_id', $ids)->get();

  //         foreach ($kaizens as $kaizen) {
  //             // Hapus gambar utama kaizen jika ada
  //             if (!empty($kaizen->picture)) {
  //                 $picturePath = public_path($kaizen->picture);
  //                 if (file_exists($picturePath)) {
  //                     unlink($picturePath);
  //                 }
  //             }

  //             if (!empty($kaizen->solved_picture)) {
  //                 $picturePath = public_path($kaizen->solved_picture);
  //                 if (file_exists($picturePath)) {
  //                     unlink($picturePath);
  //                 }
  //             }

  //             // Hapus gambar progress jika ada
  //             foreach ($kaizen->progressImages as $image) {
  //                 if (!empty($image->image_path)) {
  //                     $imagePath = public_path($image->image_path);
  //                     if (file_exists($imagePath)) {
  //                         unlink($imagePath);
  //                     }
  //                 }
  //             }

  //             // Hapus record dari database
  //             $kaizen->progressImages()->delete();
  //             $kaizen->delete();
  //         }

  //         return response()->json(['success' => true, 'message' => 'Kaizen deleted successfully.']);
  //     }

  //     return response()->json(['success' => false, 'message' => 'No kaizen selected.'], 400);
  // }

  public function deleteKaizen(Request $request)
  {
    $ids = $request->input('ids');

    if (empty($ids)) {
      return response()->json(['success' => false, 'message' => 'No kaizen selected.'], 400);
    }

    try {
      DB::transaction(function () use ($ids) {
        // 1. Ambil semua kaizen yang akan dihapus
        $kaizens = Kaizen::with('progressImages')->whereIn('kaizen_id', $ids)->get();

        foreach ($kaizens as $kaizen) {
          // 2. Hapus file fisik terkait kaizen
          $this->deleteFileIfExists($kaizen->picture);
          $this->deleteFileIfExists($kaizen->solved_picture);

          // Hapus gambar progress
          foreach ($kaizen->progressImages as $image) {
            $this->deleteFileIfExists($image->image_path);
          }

          // 3. Cari semua work order terkait kaizen ini (manual tanpa relasi)
          $workOrders = DB::table('work_order')
          ->where('kaizen_id', $kaizen->kaizen_id)
          ->get();

          foreach ($workOrders as $workOrder) {
            // Hapus attachment files work order
            $attachments = DB::table('work_order_progress_attachment')
            ->where('wo_id', $workOrder->wo_id)
            ->get();

            foreach ($attachments as $attachment) {
              $this->deleteFileIfExists($attachment->filename);
            }

            // Hapus record database terkait work order
            DB::table('work_order_comments')
            ->where('wo_id', $workOrder->wo_id)
            ->delete();

            DB::table('work_order_progress_attachment')
            ->where('wo_id', $workOrder->wo_id)
            ->delete();

            // Hapus work order itu sendiri
            DB::table('work_order')
            ->where('wo_id', $workOrder->wo_id)
            ->delete();
          }

          // 4. Hapus data kaizen dan relasinya
          DB::table('kaizen_progress_images')
          ->where('kaizen_id', $kaizen->kaizen_id)
          ->delete();

          $kaizen->delete();
        }
      });

      return response()->json(['success' => true, 'message' => 'Kaizen and all related data deleted successfully.']);

    } catch (\Exception $e) {
      return response()->json([
        'success' => false,
        'message' => 'Failed to delete kaizen: ' . $e->getMessage()
      ], 500);
    }
  }

  private function deleteFileIfExists($filePath)
  {
    if (!empty($filePath) && file_exists(public_path($filePath))) {
      unlink(public_path($filePath));
    }
  }





}
