<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

use App\Models\User;

class LoginController extends Controller
{
    // Menampilkan form login
    public function showLoginForm()
    {
        return view('pages.login'); // Ganti dengan path view login Anda
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
        
        
        // Ambil user berdasarkan email
        $user = User::where('username', $request->username)->first();
       // dd($user);
        if ($user) {
           // dd($request->pass, $user->password);
           
            if (Hash::check($request->pass, $user->password)) {
                
                Auth::login($user);
              $sessionData = session()->all();
                $request->session()->regenerate();
               // dd('Session Data:', $sessionData);

                //dd('Session regenerated');
                return redirect()->intended('index');
            } else {
                // Jika password salah
                return back()->withErrors([
                    'loginError' => 'Username atau password salah.',
                ])->withInput();
            }
        } else {
            // Jika user tidak ditemukan
            return back()->withErrors([
                'loginError' => 'Username atau password salah A.',
            ])->withInput();
        }
    }
    

// public function login(Request $request)
// {
//     // Validasi input
//     $request->validate([
//         'username' => 'required',
//         'pass' => 'required',
//     ]);

//     // Ambil kredensial login
//     $credentials = [
//         'username' => $request->username,
//         'password' => $request->pass,
//     ];

//     // Coba login
//     if (Auth::attempt($credentials)) {
//         // Regenerasi session setelah login
//         $request->session()->regenerate();
        
//         // Cek semua session data
//         //dd(session()->all());  // Periksa data session

//         return redirect()->intended('index');
//     } else {
//         return back()->withErrors([
//             'loginError' => 'Username atau password salah.',
//         ])->withInput();
//     }
// }




    // Logout pengguna
    public function logout(Request $request)
    {
        Auth::logout();
        return redirect()->route('login'); // Ganti dengan route ke halaman login
    }
}
