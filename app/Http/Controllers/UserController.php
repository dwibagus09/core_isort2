<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use App\Models\Role; // Pastikan model Role diimpor\
use App\Models\Sites; 
use App\Models\User;
use App\Models\UserAdmin;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    protected $currentUser;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->currentUser = Auth::user();
            return $next($request);
        });
    }
    
    public function index(){
    
        $roles = Role::all(); // Ambil semua role
        return view('pages.user.index', compact('roles'));
    }
    
    public function getUsers()
    {

        //dd($currentUser);
        $users = User::select('users.*', 'role.role as role_name')
            //->whereNot('role.role', ['Super User', 'Admin'])
            ->join('role', 'users.role_id', '=', 'role.role_id') // Join ke tabel roles
            ->get();
        //dd ($users);
        return response()->json($users);
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'username' => 'required|unique:users,username',
            'password' => 'required|min:6|same:confirm_password',
            'nama' => 'required|string|max:255',
            'role' => 'required|exists:role,role_id',
        ]);
    
        $user = new User();
        $user->username = $request->username;
        $user->password = Hash::make($request->password);
        $user->email = $request->email;
        $user->name = $request->nama;
        $user->phone_no = $request->nope;
        $user->role_id = $request->role;
        
      //dd($user);
        $user->save();
    
        return response()->json(['success' => 'User created successfully!']);
    }
    
    public function edit($id)
    {
        $users = User::findOrFail($id);
        return response()->json($users);
    }
    
     public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
    
        // Update data
        $user->username = $request->username;
        $user->password = Hash::make($request->password);
        $user->name = $request->nama;
        $user->email = $request->email;
        $user->phone_no = $request->nope;
        $user->role_id = $request->role;
        //dd($user);
        
        $user->save();
    
        return response()->json(['success' => 'User updated successfully!']);
    }
    
    public function deleteUser(Request $request)
    {
        $ids = $request->input('ids'); // Array ID dari permintaan
        if (!empty($ids)) {
            User::whereIn('user_id', $ids)->delete();
            return response()->json(['success' => true, 'message' => 'Users deleted successfully.']);
        }
        return response()->json(['success' => false, 'message' => 'No Users selected.'], 400);
    }
    
    
   public function indexadmin()
{
    
     $currentUser = Auth::user();
    // Pastikan user memiliki site_id yang valid
    $siteIds = !empty($currentUser->site_id) ? explode(',', $currentUser->site_id) : [];
   
    
    if ($this->currentUser->role->role === 'Admin') {
        //filtersites berdasarkan site_id users
       $filteredSites = Sites::whereIn('site_id', $siteIds)->get();
       //dd($sites->toArray());
       //dd($sites);
        // Mengambil hanya role "Admin"
        $roles = Role::where('role', 'Admin')->get();
    } else {
        // Jika Super Admin, tampilkan semua site dan role
        $filteredSites = Sites::all();
        $roles = Role::whereIn('role', ['Super Admin', 'Admin'])->get();
    }

    return view('pages.user.indexadmin', compact('roles', 'filteredSites'));
}

   
   public function getUsersadmin() // Tambahkan Request $request
    {
        // Ambil user yang sedang login
        $currentUser = Auth::user(); 
    
        $siteIds = explode(',', $currentUser->site_id); // Ubah string "2,3" menjadi array [2, 3]
    
        // Cek apakah yang login adalah Admin
        if ($currentUser->role->role === 'Admin') {
    
            // Query untuk mendapatkan user dengan role "Admin" dan site yang sama
            $users = UserAdmin::select('user_admins.*', 'role.role as role_name')
                ->join('role', 'user_admins.role_id', '=', 'role.role_id') // Join ke tabel roles
                ->where(function($query) use ($siteIds) {
                    foreach ($siteIds as $siteId) {
                        $query->orWhereRaw("FIND_IN_SET(?, user_admins.site_id)", [$siteId]);
                    }
                })
                ->where('role.role', 'Admin') // Filter hanya Admin
                ->get();
        
        } else {
        
                // Ambil selected_branch langsung dari session (default: 'all' kalau kosong)
            $selectedBranchId = session('selected_branch', 'all');
            //dd($selectedBranchId);
        
            // Query user berdasarkan branch_id yang ada dalam site_id
            $query = UserAdmin::select('user_admins.*', 'role.role as role_name')
                ->join('role', 'user_admins.role_id', '=', 'role.role_id');
            // Jika selected_branch adalah 'all', kembalikan array kosong
            // if ($selectedBranchId === 'all') {
            //     return []; // Mengembalikan array kosong
            // }
        
            if ($selectedBranchId !== 'all') {
                // Menggunakan FIND_IN_SET untuk menangani multi-site "2,3" atau "1"
                $query->whereRaw("FIND_IN_SET(?, user_admins.site_id)", [$selectedBranchId]);
            }
        
            $users = $query->get();
           
        }
    
        return response()->json($users);
    }


        
    public function storeadmin(Request $request)
    {
        // Validasi input
        $request->validate([
            'username' => 'required|unique:users,username', // Cek unik pada kolom username
            'password' => 'required|min:6|same:confirm_password',
            'nama' => 'required|string|max:255',
            'role' => 'required|exists:role,role_id',
            'site' => 'required|array', // Pastikan site adalah array
            'site.*' => 'exists:sites,site_id', // Validasi bahwa setiap site valid
        ]);
    
        // Cek apakah user dengan username yang sama sudah ada
        $existingUser = UserAdmin::where('username', $request->username)->first();
        if ($existingUser) {
            return response()->json(['error' => 'User already exists!'], 400);
        }
        
        // Gabungkan site menjadi string dengan koma
         $site_ids = implode(',', $request->site);
    
        // Jika user tidak ada, buat user baru
        $user = new UserAdmin();
        $user->username = $request->username ?? null;
        $user->password = Hash::make($request->password) ?? null; // Hindari MD5, gunakan bcrypt
        $user->name = $request->nama ?? null;
        $user->phone_no = $request->nope ?? null; // Pastikan null jika no telp tidak diisi
        $user->role_id = $request->role ?? null;
        $user->site_id = $site_ids ?? null; // Simpan site sebagai string
        
        $user->save();
    
        return response()->json(['success' => 'User created successfully!']);
    }
    
    public function editadmin($id)
    {
        $users = UserAdmin::findOrFail($id);
        return response()->json($users);
    }
    
     public function updateadmin(Request $request, $id)
    {
        $user = UserAdmin::findOrFail($id);
    
        // Update data
        $user->username = $request->username;
        $user->password = Hash::make($request->password);
        $user->name = $request->nama;
        $user->phone_no = $request->nope;
        $user->role_id = $request->role;
        //dd($user);
        
        $user->save();
    
        return response()->json(['success' => 'User updated successfully!']);
    }
    
    public function deleteUseradmin(Request $request)
    {
        $ids = $request->input('ids'); // Array ID dari permintaan
        if (!empty($ids)) {
            UserAdmin::whereIn('user_id', $ids)->delete();
            return response()->json(['success' => true, 'message' => 'USers deleted successfully.']);
        }
        return response()->json(['success' => false, 'message' => 'No Users selected.'], 400);
    }
    
    public function profile($id){
        $currentUser = Auth::user();
         $user = User::findOrFail($id);   
        // Ambil data user berdasarkan ID
        // if ($currentUser->role->role === 'Admin' || $currentUser->role->role === 'Super Admin' ) {
        // $user = UserAdmin::findOrFail($id);
        // //dd($user);
        // }else{
        
        // }
        // Kirim data ke view
        return view('pages.user.profile', compact('user'));
    }
    
    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        
        // Gunakan user_id sebagai primary key
        $request->validate([
            'email' => '',
            'phone' => 'nullable|string|max:15',
            'password' => 'nullable||confirmed',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Hanya update email & phone jika user bukan Admin atau Super Admin
        if (!in_array($user->role->role ?? '', ['Admin', 'Super Admin'])) {
            $user->email = $request->email;
            $user->phone_no = $request->phone;
        }

        // Update password jika diisi
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            $filename = time() . '_' . $file->getClientOriginalName();
            
            // Pastikan folder upload ada
            if (!file_exists(public_path('upload_photo'))) {
                mkdir(public_path('upload_photo'), 0755, true);
            }
        
            // Simpan file di public/upload
            $file->move(public_path('upload_photo'), $filename);
            $user->photo = 'upload_photo/' . $filename;
        }

        $user->save();

        return response()->json(['message' => 'Profile updated successfully', 'user' => $user]);
    }


    
  
}