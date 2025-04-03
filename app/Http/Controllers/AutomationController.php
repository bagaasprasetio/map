<?php

namespace App\Http\Controllers;

use App\Imports\YourExcelImport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Transaksi;
use App\Models\Pangkalan;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class AutomationController extends Controller
{

    public function automationAttrCheck(Request $request){
        $validator = Validator::make($request->all(), [
            'pangkalan_pin' => 'required|numeric',
            'input_transaction' => 'required',
            'excel_file' => 'required|file|mimes:xlsx,xls|max:2048'
        ], [
            'input_transaction.required' => 'Jumlah inputan wajib diisi',
            'pangkalan_pin.required' => 'PIN akun merchant wajib diisi',
            'pangkalan_pin.numeric' => 'PIN akun merchant wajib angka',
            'excel_file.required' => 'File excel belum dimasukkan',
            'excel_file.file' => 'File excel tidak valid',
            'excel_file.mimes' => 'File excel harus berformat .xlsx atau .xls',
            'excel_file.max' => 'File excel melebihi batas maksimal 2MB'
        ]);

        if($validator->fails()){
            return response()->json([
                'message' => $validator->errors()
            ], 400);
        }

        return response()->json([
            'status' => 'success'
        ], 200);
    }

    public function automationLogin(Request $request){

        $email = $request->pangkalan_email;
        $pin = $request->pangkalan_pin;

        $cmd = '"C:\Program Files\nodejs\node.exe" C:\laragon\www\map\automation\rt.cjs ' . $email . ' ' . $pin;
        $output = shell_exec($cmd . " 2>&1");
        dd($output);

        return response()->json([
            'status' => 'success'
        ], 200);

    }

    public function run(Request $request){
        $email  = $request->pangkalan_email;
        $pin    = $request->pangkalan_pin;
        $inputTrx = $request->input_transaction;
        $nikType = $request->nik_type;

        $message = [
            'Pelanggan Usaha Mikro yang sudah terdaftar perlu memperbarui informasi jenis usaha. Pelanggan diwajibkan untuk melengkapi Nomor Induk Berusaha (NIB) paling lambat 30 April 2025. Silahkan melanjutkan ke tahapan berikutnya untuk dapat melakukan transaksi.'
        ];

        $data           = Excel::toArray(new YourExcelImport, $request->file('excel_file'));
        // Hilangkan baris pertama (biasanya header)
        $filteredData = array_slice($data[0], 0);
        // Transpose array agar membaca data secara vertikal
        $transposedData = array_map(null, ...$filteredData);
        $mergedData     = array_merge(...$transposedData);
        // Hilangkan nilai null & hanya ambil yang panjangnya 16 karakter
        $cleanedData = array_filter($mergedData, function ($value) {
            return !is_null($value) && strlen($value) === 16;
        });
        //$selectedData = array_slice($cleanedData, 0, $inputTrx);

        $jsonNikList = escapeshellarg(json_encode(array_values($cleanedData)));
        $scriptPath = base_path('resources/js/pup-parent.cjs'); // Lokasi script Puppeteer

        $output = shell_exec("node $scriptPath $email $pin $jsonNikList $inputTrx 2>&1");
        //$outputArray = json_decode(trim($output), true);

        preg_match('/\{.*\}/s', $output, $matches);
        $jsonPart = $matches[0] ?? null;
        $outputArray = json_decode($jsonPart, true);

        //return response()->json(['raw' => $output]);

        if (!is_array($outputArray) || empty($outputArray['valid_nik'])) {
            return response()->json(['message' => 'No valid NIK found'], 400);
        }

        //$validNik = array_slice($outputArray['valid_nik'], 0, $inputTrx);
        //dd($output);

        $transactions = [];
        $validNikList = $outputArray['valid_nik'] ?? [];

        $pangkalan = Pangkalan::with('user')
                    ->where('user_id', Auth::user()->id)
                    ->first();
        
        foreach ($validNikList as $nik) {
            $transactions[] = [
                'transaction_date' => Carbon::now()->toDateString(),
                'nik' => $nik,
                'nik_type' => 'UM',
                'user_id' => Auth::user()->id,
                'pangkalan_id' => $pangkalan->id,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ];
        }

        Transaksi::insert($transactions);

        return response()->json([
            'transactions' => $transactions,
            'mentah' => $output,
            'decoded' => $outputArray,
            'json_extracted' => $jsonPart,
            'error' => json_last_error_msg()
        ]);
    }

    public function index()
    {
        return view('pup-excel');
    }

    public function upload(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:2048'
        ]);

        //  ganti line 75 - 86 ke functun run di line 42 - 52
        $inputLoop = 4;
        $data           = Excel::toArray(new YourExcelImport, $request->file('file'));
        // Hilangkan baris pertama (biasanya header)
        $filteredData = array_slice($data[0], 0);
        // Transpose array agar membaca data secara vertikal
        $transposedData = array_map(null, ...$filteredData);
        $mergedData     = array_merge(...$transposedData);
        // Hilangkan nilai null & hanya ambil yang panjangnya 16 karakter
        $cleanedData = array_filter($mergedData, function ($value) {
            return !is_null($value) && strlen($value) === 16;
        });
        $selectedData = array_slice($cleanedData, 0, $inputLoop);

        $scriptPath = base_path('resources/js/pup-parent.cjs'); // Lokasi script Puppeteer


        // $listNikUM = [
        //     '3201135502690001', // los
        //     '3276016101940002', // pop Perbarui Data Pelanggan + $message -> Kembali
        //     '3271012402770004', // pop nik terdafatat -> UM -> Lanjut -> Perbarui Data Pelanggan + $message -> Lewati, Lanjut Transaksi -> proses qty
        //     '3174015502670005', // pop nik terdafatat -> UM -> Lanjut -> proses qty
        //     '3201135709690008', // pop Perbarui Data Pelanggan + $message -> Lewati, Lanjut Transaksi -> proses qty
        // ];

        // $listNikRT = [
        //     '3201045806050004', // los
        //     '3271016407850006', // pop nik terdafatat -> UM -> Lanjut -> proses qty
        // ];


        $email          = 'rikalikal97@gmail.com';
        $pin            = '232323';
        $type           = 'UM'; // UM atau RT
        $URL            = config('app.url_verification_nik');
        $jsonNikList    = escapeshellarg(json_encode(array_values($cleanedData)));

        $output = shell_exec("node $scriptPath $email $pin $jsonNikList $type $URL 2>&1");
        // return $URL;

        return response()->json([
            'message'       => 'success',
            'output'        => $output,
        ]);

        return response()->json([
            'filteredData'  => $filteredData,
            'transposedData'=> $transposedData,
            'mergedData'    => $mergedData,
            'cleanedData'   => $cleanedData,
            'selectedData'  => $selectedData,
        ]);

        return redirect()->back()->with('success', 'File berhasil diunggah dan diproses.');
    }
}
