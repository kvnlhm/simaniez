<?php

namespace App\Http\Controllers;

use App\Models\Log;
use App\Models\Data;
use Illuminate\Http\Request;
use \Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Carbon\Carbon;

class DataController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $search = $request->input('search');
        $query = Data::orderBy('tanggal');

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('item', 'like', "%{$search}%")
                  ->orWhere('tanggal', 'like', "%{$search}%")
                  ->orWhere('waktu', 'like', "%{$search}%");
            });
        }

        $data = $query->paginate(15);
        
        $log = new Log;
        $log->id_user = Auth::user()->id;
        $log->aktivitas = 'Mengakses halaman Data';
        $log->save();

        return view('data.index', compact('data'));
    }

    public function tambah(Request $request)
    {
        if (Auth::user()->id_priv == 1) {
            $data = new Data;
            $data->hari = $request->hari;
            $data->tanggal = $request->tanggal;
            $data->waktu = $request->waktu;
            $data->item = $request->item;
            $data->save();

            $log = new Log;
            $log->id_user = Auth::user()->id;
            $log->aktivitas = 'Menambahkan data';
            $log->save();

            return redirect()->back()->with('success', 'Data berhasil ditambahkan.');
        } else {
            return redirect()->back()->with('error', 'Maaf, anda tidak memiliki akses.');
        }
    }

    public function update(Request $request)
    {
        if (Auth::user()->id_priv == 1) {
            $data = Data::find($request->id_data);
            $data->hari = $request->hari;
            $data->tanggal = $request->tanggal;
            $data->waktu = $request->waktu;
            $data->item = $request->item;
            $data->save();

            $log = new Log;
            $log->id_user = Auth::user()->id;
            $log->aktivitas = 'Memperbarui data: '.$request->item;
            $log->save();

            return redirect()->back()->with('success', 'Data berhasil diperbarui.');
        } else {
            return redirect()->back()->with('error', 'Maaf, anda tidak memiliki akses.');
        }
    }

    public function hapus($id)
    {
        if (Auth::user()->id_priv == 1) {
            $data = Data::where('id_data', $id)->first();

            $log = new Log;
            $log->id_user = Auth::user()->id;
            $log->aktivitas = 'Menghapus data: '.$data->item;
            $log->save();
                
            $data->delete();

            return redirect()->back()->with('success', 'Data berhasil dihapus.');
        } else {
            return redirect()->back()->with('error', 'Maaf, anda tidak memiliki akses.');
        }
    }

    public function hapusSemuaData()
    {
        if (Auth::user()->id_priv == 1) {
            Data::truncate();

            $log = new Log;
            $log->id_user = Auth::user()->id;
            $log->aktivitas = 'Menghapus semua data';
            $log->save();

            return redirect()->back()->with('success', 'Semua data berhasil dihapus.');
        } else {
            return redirect()->back()->with('error', 'Maaf, anda tidak memiliki akses.');
        }
    }

    public function uploadExcel(Request $request)
    {
        if (Auth::user()->id_priv != 1) {
            return redirect()->back()->with('error', 'Maaf, anda tidak memiliki akses.');
        }

        $request->validate([
            'excel_file' => 'required|mimes:xlsx,xls'
        ]);

        try {
            $file = $request->file('excel_file');
            $path = $file->store('temp');
            $fullPath = Storage::path($path);

            $spreadsheet = IOFactory::load($fullPath);
            $worksheet = $spreadsheet->getActiveSheet();
            $rows = $worksheet->toArray();

            // Remove header row if exists
            array_shift($rows);

            $count = 0;
            $errors = [];
            foreach ($rows as $index => $row) {
                if (count($row) < 3) continue; // Skip if row doesn't have enough columns

                try {
                    // Convert date and time to the correct format
                    $tanggal = $this->convertExcelDate($row[0]);
                    $waktu = $this->convertExcelTime($row[1]);
                    $item = $row[2];
                    $inisial = $this->generateInisial($item);

                    Data::create([
                        'tanggal' => $tanggal,
                        'waktu' => $waktu,
                        'item' => $item,
                        'inisial' => $inisial,
                    ]);

                    $count++;
                } catch (\Exception $e) {
                    $errors[] = "Baris " . ($index + 2) . ": " . $e->getMessage();
                }
            }

            Storage::delete($path);

            if (!empty($errors)) {
                $errorMessage = "Beberapa data tidak dapat diimpor:\n" . implode("\n", $errors);
                return redirect()->back()->with('warning', "$count data berhasil diupload. $errorMessage");
            }

            $log = new Log;
            $log->id_user = Auth::user()->id;
            $log->aktivitas = "Mengupload $count data dari Excel";
            $log->save();

            return redirect()->back()->with('success', "$count data berhasil diupload dari Excel.");
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan saat mengupload file: ' . $e->getMessage());
        }
    }

    private function generateInisial($item)
    {
        $words = explode(' ', $item);
        $inisial = '';
        foreach ($words as $word) {
            $inisial .= strtoupper(substr($word, 0, 1));
        }
        return $inisial;
    }

    private function convertExcelDate($value)
    {
        if (is_numeric($value)) {
            return \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value)->format('Y-m-d');
        } else {
            // Coba parse format "d/m/Y"
            try {
                return \Carbon\Carbon::createFromFormat('d/m/Y', $value)->format('Y-m-d');
            } catch (\Exception $e) {
                // Jika gagal, coba parse sebagai string date biasa
                return \Carbon\Carbon::parse($value)->format('Y-m-d');
            }
        }
    }

    private function convertExcelTime($value)
    {
        if (is_numeric($value)) {
            return \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value)->format('H:i:s');
        } else {
            // Jika bukan numerik, coba parse sebagai string time
            return \Carbon\Carbon::parse($value)->format('H:i:s');
        }
    }
}
