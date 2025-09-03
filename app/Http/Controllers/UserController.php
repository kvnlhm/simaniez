<?php

namespace App\Http\Controllers;

use App\Models\Log;
use App\Models\Priv;
use App\Models\User;
use Illuminate\Http\Request;
use \Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function index()
    {
        if (Auth::user()->id_priv == 1) {

            $data = User::all();
            $priv = Priv::all();
            return view('user.index', compact('data' ,'priv'));
        } else {
            return redirect()->back()->with('error', 'Maaf, anda tidak memiliki akses.');
        }
    }

    public function tambah(Request $request)
    {
        if (Auth::user()->id_priv == 1) {

            $file = $request->foto;
            if ($file != null) {
                $extension = $file->getClientOriginalExtension();
            }else{
                $extension = null;
            }
            if ($extension != null) {
                $mimetype = $file->getClientMimeType();
            }else{
                $mimetype = null;
            }
            if ($mimetype != null) {
                $filegambar = $file->getFilename().'.'.$extension;
                Storage::disk('public')->put('/images/user/'.$file->getFilename().'.'.$extension,File::get($file));
            }else{
                $filegambar = null;
            }

            $user = new User;
            $user->name = $request->name;
            $user->nama_lengkap = $request->nama_lengkap;
            $user->email = $request->email;
            $user->no_telp = $request->no_telp;
            $user->alamat = $request->alamat;
            $user->password = bcrypt($request->password);
            $user->id_priv = $request->id_priv;
            if ($filegambar != null) {
                $user->foto = $filegambar;
            }
            $user->save();
            
            $log = new Log;
            $log->id_user = Auth::user()->id;
            $log->aktivitas = 'Menambahkan user';
            $log->save();

            return redirect('/user')->with('success', 'User berhasil ditambahkan.');
        } else {
            return redirect()->back()->with('error', 'Maaf, anda tidak memiliki akses.');
        }
    }

    public function update(Request $request)
    {
        if (Auth::user()->id_priv == 1) {

            $file = $request->foto;
            if ($file != null) {
                $extension = $file->getClientOriginalExtension();
            }else{
                $extension = null;
            }
            if ($extension != null) {
                $mimetype = $file->getClientMimeType();
            }else{
                $mimetype = null;
            }
            if ($mimetype != null) {
                $filegambar = $file->getFilename().'.'.$extension;
                Storage::disk('public')->put('/images/user/'.$file->getFilename().'.'.$extension,File::get($file));
            }else{
                $filegambar = null;
            }
            if ($request->foto) {
                Storage::delete('public/images/user/'.$request->foto_lama);
            }

            // dd($request->id);
            $user = User::find($request->id);
            $user->name = $request->name;
            $user->nama_lengkap = $request->nama_lengkap;
            $user->email = $request->email;
            $user->no_telp = $request->no_telp;
            $user->alamat = $request->alamat;
            $user->id_priv = $request->id_priv;
            if ($filegambar != null) {
                $user->foto = $filegambar;
            }
            // $user->password = bcrypt($request->password);
            // $user->is_aktiv = $request->isaktif;
            $user->save();

            $log = new Log;
            $log->id_user = Auth::user()->id;
            $log->aktivitas = 'Memperbarui user '.$request->name;
            $log->save();

            return redirect('/user')->with('success', 'User berhasil diperbarui.');
        } else {
            return redirect()->back()->with('error', 'Maaf, anda tidak memiliki akses.');
        }
    }

    function updatePass(Request $request)
    {
        if (Auth::user()->id_priv == 1) {
            $user = User::find($request->id);
            $user->password = bcrypt($request->password);
            $user->save();

            $log = new Log;
            $log->id_user = Auth::user()->id;
            $log->aktivitas = 'Memperbarui password user '.$request->name;
            $log->save();

            return redirect('/user')->with('success', 'Password user berhasil diperbarui.');
        } else {
            return redirect()->back()->with('error', 'Maaf, anda tidak memiliki akses.');
        }
    }

    public function hapus($id)
    {
        if (Auth::user()->id_priv == 1) {
        
            $user = User::where('id', $id)->first();
            Storage::delete('public/images/user/'.$user->foto);
            
            $log = new Log;
            $log->id_user = Auth::user()->id;
            $log->aktivitas = 'Menghapus user '.$user->name;
            $log->save();

            $user->delete();
            
            return redirect('/user')->with('success', 'User berhasil dihapus.');
        } else {
            return redirect()->back()->with('error', 'Maaf, anda tidak memiliki akses.');
        }
    }

    public function profil()
    {
        $user = Auth::user();
        return view('user.profil', compact('user'));
    }

    public function updateProfil(Request $request)
    {
        $file = $request->foto;
        if ($file != null) {
            $extension = $file->getClientOriginalExtension();
        }else{
            $extension = null;
        }
        if ($extension != null) {
            $mimetype = $file->getClientMimeType();
        }else{
            $mimetype = null;
        }
        if ($mimetype != null) {
            $filegambar = $file->getFilename().'.'.$extension;
            Storage::disk('public')->put('/images/user/'.$file->getFilename().'.'.$extension,File::get($file));
        }else{
            $filegambar = null;
        }
        if ($request->foto) {
            Storage::delete('public/images/user/'.$request->foto_lama);
        }

        $user = Auth::user();
        $user->name = $request->name;
        $user->nama_lengkap = $request->nama_lengkap;
        $user->email = $request->email;
        $user->no_telp = $request->no_telp;
        $user->alamat = $request->alamat;
        $user->id_priv = $request->id_priv;
        if ($filegambar != null) {
            $user->foto = $filegambar;
        }
        if (!empty($request->password)) {
            $user->password = bcrypt($request->password);
        }
        $user->save();

        $log = new Log;
        $log->id_user = $user->id;
        $log->aktivitas = 'Memperbarui profil';
        $log->save();

        return redirect('/user/profil')->with('success', 'Profil berhasil diperbarui.');
    }

    public function updateProfilPass(Request $request)
    {
        $user = Auth::user();
        $user->password = bcrypt($request->password);
        $user->save();

        $log = new Log;
        $log->id_user = $user->id;
        $log->aktivitas = 'Mengganti password';
        $log->save();

        return redirect('/user/profil')->with('success', 'Password berhasil diganti.');
    }
}
