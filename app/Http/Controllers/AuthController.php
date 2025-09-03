<?php

namespace App\Http\Controllers;

use App\Models\Log;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class AuthController extends Controller
{
    /**
     * Instantiate a new AuthController instance.
     */
    public function __construct()
    {
        $this->middleware('guest')->except([
            'logout', 'dashboard'
        ]);
    }

    /**
     * Display a registration form.
     *
     * @return \Illuminate\Http\Response
     */
    public function register()
    {
        return view('auth.register');
    }

    /**
     * Store a new user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validasi data yang diterima dari request
        $validatedData = $request->validate([
            'name' => 'required|string|min:5|max:250|unique:users',
            'nama_lengkap' => 'required|string|max:250',
            'email' => 'required|email:dns|max:250|unique:users',
            'no_telp' => 'required|string|min:10|max:13',
            'alamat' => 'required|string|max:250',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'password' => 'required|min:8|max:250|confirmed',
        ]);
    
        // Upload foto jika ada
        $filefoto = null;
        if ($request->hasFile('foto')) {
            $file1 = $request->file('foto');
            $filename = $file1->getClientOriginalName();
            $extension = $file1->getClientOriginalExtension();
            $mimetype = $file1->getClientMimeType();
            $filefoto = $filename . '.' . $extension;
            Storage::disk('public')->put('/images/user/' . $filefoto, File::get($file1));
        }
    
            // Buat instance model User
            $user = new User;
            
            // Set atribut-atribut user dari data yang diterima dari request
            $user->name = $validatedData['name'];
            $user->nama_lengkap = $validatedData['nama_lengkap'];
            $user->email = $validatedData['email'];
            $user->no_telp = $validatedData['no_telp'];
            $user->alamat = $validatedData['alamat'];
            $user->password = Hash::make($validatedData['password']); // Hash password sebelum disimpan
            $user->foto = $filefoto;
        
            // Simpan user ke database
            $user->save();

        $log = new Log;
        $log->id_user = $user->id;;
        $log->aktivitas = 'Membuat akun';
        $log->save();
        
        // Redirect ke halaman login dengan pesan sukses
        return redirect()->route('login')
            ->withSuccess('Anda telah berhasil mendaftar! Silahkan Login!');
    }

    /**
     * Display a login form.
     *
     * @return \Illuminate\Http\Response
     */
    public function login()
    {
        if(Auth::check()){
            return redirect()->back()->with('error', 'Maaf, anda sudah login.');
        }
        return view('auth.login');
    }

    /**
     * Authenticate the user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);
        
        if(Auth::attempt($credentials))
        {
            $request->session()->regenerate();
            return redirect()->route('dashboard')
            ->withSuccess('Anda berhasil login!');
        }
        
        return back()->withErrors([
            'email' => 'Maaf, kredensial yang Anda berikan tidak cocok dengan data yang tersimpan.',
            ])->onlyInput('email');
            
        } 
    
    /**
     * Display a dashboard to authenticated users.
     *
     * @return \Illuminate\Http\Response
     */
    public function dashboard()
    {
        if(Auth::check())
        {
            return view('dashboard');
        }
        
        return redirect()->route('login')
            ->withErrors([
            'email' => 'Tolong login terlebih dahulu untuk mengakses dashboard.',
        ])->onlyInput('email');
    } 
    
    /**
     * Log out the user from application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login')
            ->withSuccess('Anda telah berhasil logout!');;
    }

    public function cek()
    {
        if(Auth::check()){
            return redirect('/dashboard')->with('error', 'Maaf, anda sudah login.');
        } else {
            return redirect('/login')->with('error', 'Maaf, anda belum login.');
        }
    }

}