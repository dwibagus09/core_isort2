<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

use App\Models\UserAdmin;

class AuthController extends Controller
{
    // Menampilkan form login
    public function showLoginForm()
    {
        return view('pages.login');
    }

    // Proses login
    public function login(Request $request)
    {
        // Validasi input
        $request->validate([
            'username' => 'required',
            //'pass' => 'required|min:6',
        ], [
            'username.required' => 'Username wajib diisi.',
            'pass.required' => 'Password wajib diisi.',
        ]);

        // Cek kredensial
        $credentials = [
            'username' => $request->username,
            'password' => $request->pass,
        ];
        
        dd($request->username);
        
        // Ambil user berdasarkan email
        $user = UserAdmin::where('username', $request->username)->first();
       
      
        // Jika user ditemukan
        if ($user) {
            // Cek password (dengan MD5 atau algoritma lain)
            if (md5($request->pass) == $user->password) {
                // Jika password cocok, login user
                Auth::login($user);
                // Log status login
            Log::info('User logged in: ', ['user' => $user->username]);
            
                return redirect()->intended('index');
            } else {
                // Jika password salah
                return back()->withErrors([
                    'loginError' => 'Username atau password salah. B',
                ])->withInput();
            }
        } else {
            // Jika user tidak ditemukan
            return back()->withErrors([
                'loginError' => 'Username atau password salah A.',
            ])->withInput();
        }
    }

    // Fungsi untuk logout
    public function logout()
    {
        Auth::logout();
        return redirect()->route('login');
    }
}
