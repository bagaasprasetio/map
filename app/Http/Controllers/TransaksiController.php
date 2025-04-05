<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaksi;
use Yajra\DataTables\Facades\DataTables;

class TransaksiController extends Controller
{
    public function transaksiMaster(){
        return view('transaksi');
    }

    public function transaksiAdd(Request $request){
        try {
            $user = Auth::user()->id;
            $pangkalan = Pangkalan::where('user_id', $user)->get();

            Transaksi::create([
                'transaction_date' => now()->format('Y-m-d'),
                'transaction_total' => $request->transaction_total,
                'nik_type' => $request->nik_type,
                'transaction_status' => '1',
                'user_id' => $user,
                'pangkalan_id' => $user
            ]);

            return response()->json([
                'message' => 'berhasil dihapus'
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getAll(){
        $all = Transaksi::with('user', 'pangkalan')->orderBy('transaction_date', 'desc');

        return DataTables::of($all)
            ->addIndexColumn()
            ->make(true);
    }

    public function getUsedNik(Request $request){
        //$nikType = $request->nik_type;
        $nikType = 'UM';
        $usedNik = Transaksi::where('nik_type', $nikType)->pluck('nik');

        return response()->json([
            'used_nik' => $usedNik
        ]);
    }
}
