<?php

namespace App\Http\Controllers;

use App\Models\Log;
use App\Models\Panduan;
use Illuminate\Http\Request;
use \Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response;

class PanduanController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $data = Panduan::all();
            
        $log = new Log;
        $log->id_user = Auth::user()->id;
        $log->aktivitas = 'Mengakses halaman Panduan';
        $log->save();

        return view('panduan.index', compact('data'));
    }

    public function tambah(Request $request)
    {
        if (Auth::user()->id_priv == 1) {
            $panduan = new Panduan;
            $panduan->tipe = $request->tipe;
            $panduan->pertanyaan = $request->pertanyaan;
            $panduan->jawaban = $request->jawaban;
            $panduan->save();

            $log = new Log;
            $log->id_user = Auth::user()->id;
            $log->aktivitas = 'Menambahkan panduan';
            $log->save();

            return redirect()->back()->with('success', 'Panduan berhasil ditambahkan.');
        } else {
            return redirect()->back()->with('error', 'Maaf, anda tidak memiliki akses.');
        }
    }

    public function update(Request $request)
    {
        if (Auth::user()->id_priv == 1) {
            $panduan = Panduan::find($request->id_panduan);
            $panduan->tipe = $request->tipe;
            $panduan->pertanyaan = $request->pertanyaan;
            $panduan->jawaban = $request->jawaban;
            $panduan->save();

            $log = new Log;
            $log->id_user = Auth::user()->id;
            $log->aktivitas = 'Memperbarui panduan: '.$request->pertanyaan;
            $log->save();

            return redirect()->back()->with('success', 'Panduan berhasil diperbarui.');
        } else {
            return redirect()->back()->with('error', 'Maaf, anda tidak memiliki akses.');
        }
    }

    public function hapus($id)
    {
        if (Auth::user()->id_priv == 1) {
            $panduan = Panduan::where('id_panduan', $id)->first();

            $log = new Log;
            $log->id_user = Auth::user()->id;
            $log->aktivitas = 'Menghapus panduan: '.$panduan->pertanyaan;
            $log->save();
                
            $panduan->delete();

            return redirect()->back()->with('success', 'Panduan berhasil dihapus.');
        } else {
            return redirect()->back()->with('error', 'Maaf, anda tidak memiliki akses.');
        }
    } 
}
