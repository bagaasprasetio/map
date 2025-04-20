<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pangkalan;
use App\Models\Transaksi;
use Carbon\Carbon;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PangkalanController extends Controller
{
    public function index(){
        $data           = Pangkalan::where('user_id', Auth::user()->id)->get();
        $pangkalan      = Transaksi::where('user_id', Auth::user()->id)->first();
        $transactions = 0;

        if ($pangkalan){
            $transactions = Transaksi::whereDate('created_at', Carbon::now())
                            ->where('pangkalan_id', $pangkalan->pangkalan_id)
                            ->count();
        }

        return view('index', compact('data', 'transactions'));
    }

    public function checkJobStatus()
    {
        $jobs = DB::table('jobs')->count();
        $isJobQueueFull = $jobs >= 3;

        return response()->json([
            'status'    => $isJobQueueFull,
            'count'     => $jobs
        ]);
    }

    function pangkalanMaster(){
        return view('pangkal');
    }

    public function getAll(){
        $all = Pangkalan::with('user')->orderBy('created_at', 'desc')->get();
        //return response()->json($all);

        return DataTables::of($all)
            ->addIndexColumn()
            ->addColumn('action', function($row) {
                return '<a class="btn btn-outline" id="editBtn" data-id="'.$row->id.'"><i class="fas fa-pen-to-square"></i></a>
                <a class="btn btn-outline-secondary" id="adminBtn" data-id="'.$row->id.'"><i class="fas fa-user"></i></a>
                <a class="btn btn-outline-delete" id="deleteBtn" data-id="'.$row->id.'"><i class="fas fa-trash"></i></a>
                ';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function pangkalanAdd(Request $request){

        $validator = Validator::make($request->all(), [
            'pangkalan_name' => 'required',
            'pangkalan_address' => 'required',
            'pangkalan_quota' => 'required'
        ], [
            'pangkalan_name.required' => 'Nama pangkalan wajib diisi',
            'pangkalan_address.required' => 'Alamat pangkalan wajib diisi',
            'pangkalan_quota.required' => 'Kuota pangkalan wajib diisi',
            'pangkalan_quota.integer' => 'Isian wajib angka',
            'pangkalan_quota.min' => 'Kuota transaksi haru lebih dari 0'
        ]);

        if($validator->fails()){
            return response()->json([
                'message' => $validator->errors()
            ], 400);
        }

        try {
            Pangkalan::create([
                'pangkalan_name' => $request->pangkalan_name,
                'pangkalan_address' => $request->pangkalan_address,
                'transaction_quota' => $request->pangkalan_quota
            ]);

            return response()->json(['message' => 200]);

        } catch (\Exception $e){
            return response()->json([
                'message' => 'Error',
                'error' => $e->getMessage()
            ], 500);
        }

    }

    public function pangkalanFetch(Request $request){
        $id = $request->id;

        try {
            $pangkalan = Pangkalan::find($id);

            if ($pangkalan){
                return response()->json($pangkalan);
            } else {
                return response()->json([
                    'message' => 'Pangkalan tidak ditemukan'
                ], 404);
            }
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function pangkalanUpdate(Request $request){
        $id = $request->id;

        $validator = Validator::make($request->all(), [
            'pangkalan_name' => 'required',
            'pangkalan_address' => 'required',
            'pangkalan_quota' => 'required|integer|min:1'
        ], [
            'pangkalan_name.required' => 'Nama pangkalan wajib diisi',
            'pangkalan_address.required' => 'Alamat pangkalan wajib diisi',
            'pangkalan_quota.required' => 'Kuota pangkalan wajib diisi',
            'pangkalan_quota.integer' => 'Isian wajib angka',
            'pangkalan_quota.min' => 'Kuota transaksi haru lebih dari 0'
        ]);

        if($validator->fails()){
            return response()->json([
                'message' => $validator->errors()
            ], 400);
        }

        try {
            $pangkalan = Pangkalan::find($id);

            $pangkalan->pangkalan_name = $request->pangkalan_name;
            $pangkalan->pangkalan_address = $request->pangkalan_address;
            $pangkalan->transaction_quota = $request->pangkalan_quota;

            $pangkalan->save();

            return response()->json([
                'message' => 'berhasil dihapus'
            ], 200);

        } catch (\Exception $e){
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }

    }

    public function pangkalanDelete(Request $request){
        $id = $request->id;

        try {
            $pangkalan = Pangkalan::find($id);

            if ($pangkalan){
                $pangkalan->delete();

                return response()->json([
                    'message' => 'berhasil dihapus'
                ], 200);
            } else {
                return response()->json([
                    'message' => 'User tidak ditemukan!',
                ], 404);
            }

        } catch (\Exception $e){
            return response()->json([
                'message' => 'Error',
                'error' => $e->getMessage()
            ], 500);
        }

    }

    public function getPangkalanNull(){
        try {
            $pangakalanNull = Pangkalan::where('user_id', null)->get();
            return response()->json($pangakalanNull);
        } catch (\Exception $e){
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function assignAdmin(Request $request){
        $id = $request->id;

        try {
            $pangkalan = Pangkalan::find($id);

            $user_id = $request->user_id === "null" || $request->user_id === "" ? null : $request->user_id;

            $pangkalan->user_id = $user_id;
            $pangkalan->save();

            return response()->json([
                'message' => 'berhasil dihapus'
            ], 200);

        } catch (\Exception $e){
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function pangkalanCheck(){
        $pangkalan = Pangkalan::where('user_id', Auth::user()->id)->first();

        if($pangkalan){
            return response()->json(['pangkalan' => 'exist', 'email' => Auth::user()->email]);
        } else {
            return response()->json(['pangkalan' => null ]);
        }

    }

}
