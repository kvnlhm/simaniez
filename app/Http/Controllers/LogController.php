<?php

namespace App\Http\Controllers;

use App\Models\Log;
use Illuminate\Http\Request;
use \Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response;

class LogController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        if (Auth::user()->id_priv == 1) {
            $data = Log::latest()->get();

            $log = new Log;
            $log->id_user = Auth::user()->id;
            $log->aktivitas = 'Mengakses halaman log aktivitas';
            $log->save();

            return view('log.index', compact('data'));
        } else {
            return redirect()->back()->with('error', 'Maaf, anda tidak memiliki akses.');
        }
    }

    public function hapus($id)
    {
        if (Auth::user()->id_priv == 1) {
            $tgl = Log::where('id_log', $id)->get();
            $tgl = $tgl[0]->created_at;
                
            $log = new Log;
            $log->id_user = Auth::user()->id;
            $log->aktivitas = 'Menghapus log aktivitas pada waktu '.$tgl;
            $log->save();

            Log::where('id_log', $id)->delete();

            return redirect()->back()->with('success', 'Log Aktifitas berhasil dihapus.');
        } else {
            return redirect()->back()->with('error', 'Maaf, anda tidak memiliki akses.');
        }
    } 
}
