<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


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
}
