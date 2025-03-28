<?php

namespace App\Http\Controllers;

use App\Imports\YourExcelImport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class AutomationController extends Controller
{
    public function automationLogin(Request $request){
        $validator = Validator::make($request->all(), [
            'pangkalan_pin' => 'required|numeric',
            'input_transaction' => 'required'
        ], [
            'input_transaction.required' => 'Jumlah inputan wajib diisi',
            'pangkalan_pin.required' => 'PIN akun merchant wajib diisi',
            'pangkalan_pin.numeric' => 'PIN akun merchant wajib angka'
        ]);

        if($validator->fails()){
            return response()->json([
                'message' => $validator->errors()
            ], 400);
        }

        $email = $request->pangkalan_email;
        $pin = $request->pangkalan_pin;

        $cmd = '"C:\Program Files\nodejs\node.exe" C:\laragon\www\map\automation\rt.cjs ' . $email . ' ' . $pin;
        $output = shell_exec($cmd . " 2>&1");
        dd($output);

    }

    public function run(){
        $email  = 'pangkalangashajihalimah@gmail.com';
        $pin    = '232323';

        // jika sudah di update dari fun upload, masukan fun ini ke fun atuomationLogin
        $nikList= [
            // '32011315023', // err || invalid
            '3201131501660023', // err || Belum ini
            '3201131501650023', // err ||
            // '32012215031', // err || invalid
            '3175035411820010', // pop || Sudah
            '3201306402890003', // los ||
            '3201131612950006', // err || ini
            '3201132010890001', // err || ini
            '3201132010700005', // los ||
        ];

        $jsonNikList = escapeshellarg(json_encode($nikList));
        $scriptPath = base_path('resources/js/pup-parent.cjs'); // Lokasi script Puppeteer

        $output = shell_exec("node $scriptPath $email $pin $jsonNikList 2>&1");
        // dd($output);
        return response()->json([
            'message'   => 'success',
            'output'    => $output,
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
        $filteredData = array_slice($data[0], 1);
        // Transpose array agar membaca data secara vertikal
        $transposedData = array_map(null, ...$filteredData);
        $mergedData     = array_merge(...$transposedData);
        // Hilangkan nilai null & hanya ambil yang panjangnya 16 karakter
        $cleanedData = array_filter($mergedData, function ($value) {
            return !is_null($value) && strlen($value) === 16;
        });
        $selectedData = array_slice($cleanedData, 0, $inputLoop);

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
