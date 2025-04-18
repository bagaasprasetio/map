<?php

namespace App\Http\Controllers;

use App\Imports\MultipleExcelImport;
use App\Imports\YourExcelImport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Transaksi;
use App\Models\Pangkalan;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class AutomationController extends Controller
{

    public function automationAttrCheck(Request $request){
        $validator = Validator::make($request->all(), [
            'pangkalan_pin' => 'required|numeric|digits:6',
            'input_transaction' => 'required',
            'excel_file' => 'required|file|mimes:xlsx,xls|max:2048'
        ], [
            'input_transaction.required' => 'Jumlah inputan wajib diisi',
            'pangkalan_pin.required' => 'PIN akun merchant wajib diisi',
            'pangkalan_pin.numeric' => 'PIN akun merchant wajib angka',
            'pangkalan_pin.digits' => 'PIN akun merchant terdiri dari 6 karakter',
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

        $inputTrx = $request->input_transaction;
        $eta = $inputTrx * 36;

        // Generate unique token buat identifikasi proses
        $token = uniqid('bot_', true);
        Cache::put("bot_done_$token", false, now()->addMinutes(30)); // TTL optional

        // Simulasi proses async
        dispatch(function () use ($token, $eta) {
            sleep($eta);
            Cache::put("bot_done_$token", true, now()->addMinutes(30));
        });

        // $userId = Auth::id();
        // $batchId = now()->timestamp;

        // $statusKey = "bot_status_{$userId}";
        // $batchKey = "bot_batch_{$userId}";

        // // Simpan batch ID baru
        // Cache::put($batchKey, $batchId, now()->addMinutes(30));
        // Cache::put($statusKey, 'processing', now()->addMinutes(30));
        
        // //$cacheKey = 'bot_done_'.Auth::user()->id;
        // //Cache::forget($cacheKey);
        // //Cache::put($cacheKey, false);

        // try {
        //     dispatch(function () use ($inputTrx, $statusKey, $batchKey, $batchId) {
        //         sleep($inputTrx * 36); // simulasi proses
                 //Cache::put($cacheKey, true);

        //         if (Cache::get($batchKey) == $batchId) {
        //             Cache::put($statusKey, 'done', now()->addMinutes(30));
        //         }
        //     });
        // } catch (\Exception $e) {
        //     Cache::put($cacheKey, 'error'); // atau false
        // }

        // Bikin token unik buat proses ini
        // $token = now()->timestamp . '_' . Str::random(5);
        // $cacheKey = "bot_status_$token";

        // Cache::put($cacheKey, 'processing', now()->addMinutes(30));

        // dispatch(function () use ($inputTrx, $cacheKey) {
        //     sleep($inputTrx * 36);
        //     Cache::put($cacheKey, 'done', now()->addMinutes(30));
        // });

        return response()->json([
            'status'    => 'success',
            'eta'       => $eta,
            'token'     => $token
        ], 200);
    }

    public function automationLogin(Request $request){

        $email  = $request->pangkalan_email;
        $pin    = $request->pangkalan_pin;

        $cmd = '"C:\Program Files\nodejs\node.exe" C:\laragon\www\map\automation\rt.cjs ' . $email . ' ' . $pin;
        $output = shell_exec($cmd . " 2>&1");
        dd($output);

        return response()->json([
            'status' => 'success'
        ], 200);

    }


    public function run(Request $request){
        ini_set('max_execution_time', 3600); // dalam detik
        $data           = Excel::toArray(new MultipleExcelImport, $request->file('excel_file'));
        // Hilangkan baris pertama (biasanya header)

        $nikType = $request->nik_type;// NIKTYPE
        $usedNik = Transaksi::where('nik_type', $nikType)
                            ->whereRaw('DATE_ADD(transaction_date, INTERVAL 6 DAY) >= ?', [Carbon::now()])
                            ->pluck('nik')
                            ->toArray();
        $arraySlice = '';
        $processedNik = '';

        // Hilangkan baris pertama (biasanya header)
        if ($nikType == 'UM') {
            $arraySlice = array_slice($data[0], 1);
        } else {
            $arraySlice = array_slice($data[1], 1);
        }

        $arraySlice = array_map(function($item) {
            return is_array($item) ? reset($item) : $item;
        }, $arraySlice);

        // Filter NIK dalam 7 hari terakhir
        $processedNik = array_diff($arraySlice, $usedNik);

        // Reset index biar clean
        $filteredData = array_values($processedNik);

        // Transpose array agar membaca data secara vertikal
        //$transposedData = array_map(null, ...$filteredData);
        //$mergedData     = array_merge(...$transposedData);
        // Hilangkan nilai null & hanya ambil yang panjangnya 16 karakter
        $cleanedData = array_filter($filteredData, function ($value) {
            return !is_null($value) && strlen($value) === 16;
        });

        $email          = $request->pangkalan_email;
        $pin            = $request->pangkalan_pin;
        $inputTrx       = $request->input_transaction;
        $URL            = config('app.url_verification_nik');
        $jsonNikList    = escapeshellarg(json_encode(array_values($cleanedData)));

        if (count($cleanedData) < $inputTrx){
            return response()->json([
                'message' => 'Jumlah NIK pada excel kurang dari jumlah input transaksi yang diminta!',
                'total_valid_nik' => count($cleanedData)
            ], 422);
        }

        $scriptPath     = base_path('resources/js/pup-parent.cjs'); // Lokasi script Puppeteer
        $output = shell_exec("node $scriptPath $email $pin $jsonNikList $nikType $URL $inputTrx 2>&1");

        preg_match('/\{.*\}/s', $output, $matches);
        $jsonPart = $matches[0] ?? null;
        $outputArray = json_decode($jsonPart, true);

        //return response()->json(['raw' => $output]);

        // if (!is_array($outputArray) || empty($outputArray['valid_nik'])) {
        //     return response()->json([
        //         'message' => 'No valid NIK found'
        //     ], 400);
        // }

        // Kasus login gagal (dari Puppeteer)
        if (isset($outputArray['success']) && $outputArray['success'] === false) {
            return response()->json([
                'success' => false,
                'message' => $outputArray['error'] ?? 'Terjadi kesalahan saat login bot'
            ], 400);
        }

        // Kasus: login berhasil, tapi nggak ada NIK valid
        if (empty($outputArray['valid_nik'])) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak ada NIK valid yang bisa diproses.'
            ], 400);
        }

        $transactions = [];
        $validNikList = $outputArray['valid_nik'] ?? [];
        $jmlValidNik  = count($outputArray['valid_nik']);

        $pangkalan = Pangkalan::with('user')
                    ->where('user_id', Auth::user()->id)
                    ->first();

        DB::beginTransaction();
        try {

            // $pangkalan->update([
            //     'transaction_quota' => $pangkalan->transaction_quota - $jmlValidNik
            // ]);

            foreach ($validNikList as $nik) {
                $transactions[] = [
                    'transaction_date'  => Carbon::now()->toDateString(),
                    'nik'               => $nik,
                    'nik_type'          => $nikType,
                    'user_id'           => Auth::user()->id,
                    'pangkalan_id'      => $pangkalan->id,
                    'created_at'        => Carbon::now(),
                    'updated_at'        => Carbon::now()
                ];
            }

            Transaksi::insert($transactions);

        } catch (\Exception $e) {
            DB::rollback(); // Batalkan semua perubahan jika terjadi error
            return response()->json(['error' => $e->getMessage()], 500);
        }
        DB::commit(); // Simpan perubahan ke database jika tidak ada error

        return response()->json([
            'transactions'   => $transactions,
            'jmlValidNik'    => $jmlValidNik,
            'pangkalan'      => $pangkalan->transaction_quota,
            'mentah'         => $output,
            'decoded'        => $outputArray,
            'json_extracted' => $jsonPart,
            'error'          => json_last_error_msg(),
            'used_nik'       => $usedNik,
            'diff_nik'       => $filteredData,
            'input_trx'      => $inputTrx
        ]);
    }

    public function getProgress(Request $request){

        $token = $request->query('token'); // dari frontend
        //$done = Cache::get("bot_done_$token", false);

        if (!$token) {
            return response()->json(['error' => 'Invalid token'], 400);
        }
        
        // Gunakan remember untuk menghindari pembacaan berulang dalam interval waktu singkat
        $done = Cache::remember("bot_status_$token", 3, function() use ($token) {
            return Cache::get("bot_done_$token", false);
        });

        return response()->json([
            'done' => $done
        ]);
    }

    public function getUsedNik(){
        $usedNik = Transaksi::where('nik_type', 'RT')
                            ->whereRaw('DATE_ADD(transaction_date, INTERVAL 6 DAY) >= ?', [Carbon::now()])
                            ->pluck('nik')
                            ->toArray();

        return response()->json([
            'used_nik' => $usedNik
        ]);
    }

    public function index()
    {
        return view('pup-excel');
    }

    public function upload(Request $request)
    {
        // ini_set('max_execution_time', 3600);

        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:2048'
        ]);

        //  ganti line 75 - 86 ke functun run di line 42 - 52
        $inputLoop = 4;
        // $data           = Excel::toArray(new YourExcelImport, $request->file('file'));
        $data           = Excel::toArray(new MultipleExcelImport, $request->file('file'));
        // Hilangkan baris pertama (biasanya header)

        $type = 'UM';
        $arraySlice = '';
        if ($type == 'UM') {
            $arraySlice = array_slice($data[0], 1);
        } else {
            $arraySlice = array_slice($data[1], 1);
        }
        $filteredData = $arraySlice;


        // Transpose array agar membaca data secara vertikal
        $transposedData = array_map(null, ...$filteredData);
        $mergedData     = array_merge(...$transposedData);
        // Hilangkan nilai null & hanya ambil yang panjangnya 16 karakter
        $cleanedData = array_filter($mergedData, function ($value) {
            return !is_null($value) && strlen($value) === 16;
        });
        $selectedData = array_slice($cleanedData, 0, $inputLoop);

        // return response()->json([
        //     'filteredData'  => $filteredData,
        //     'transposedData'=> $transposedData,
        //     'mergedData'    => $mergedData,
        //     'cleanedData'   => $cleanedData,
        //     'selectedData'  => $selectedData,
        // ]);

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
        $inputTrx       = 100;
        $nikType        = $type; // UM atau RT
        $URL            = config('app.url_verification_nik');
        $jsonNikList    = escapeshellarg(json_encode(array_values($cleanedData)));

        // $output = shell_exec("node $scriptPath $email $pin $jsonNikList $type $URL 2>&1");
        $output = shell_exec("node $scriptPath $email $pin $jsonNikList $nikType $URL $inputTrx 2>&1");
        // return $URL;

        return response()->json([
            'message'       => 'susccess',
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

    public function tx(Request $request)
    {
        return view('thankyou'); // langsung render halaman
    }
}
