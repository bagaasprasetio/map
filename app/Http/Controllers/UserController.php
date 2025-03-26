<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Subscription;
use App\Models\Pangkalan;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;



class UserController extends Controller
{
    public function userMaster(){
        return view('user');
    }

    public function getUserSubs(){
        $ap = User::with('subscriptions', 'pangkalan')
                ->where('role', 'ap')
                ->whereHas('pangkalan', function ($query) {
                    $query->whereNotNull('user_id'); // Filter yang user_id-nya gak null di tb_pangkalan
                });
        return DataTables::of($ap)
            ->addIndexColumn()
            ->addColumn('subs_history', function($row){
                return '<a class="" id="subsHistoryBtn" data-toggle="modal" data-target="#subsHistoryModal" data-url="'.route('subs.history', ['id' => $row->id]).'">Lihat Detail</a>';
            })
            ->addColumn('action', function($row){
                return '<a class="btn btn-outline" id="subsBtn" data-id="'.$row->id.'"><i class="fas fa-rotate"></i></a>';
            })
            ->rawColumns(['subs_history', 'action'])
            ->make(true);
    }

    public function getSubsHistory($id){
        $subHis = Subscription::with('registeredBy')->where('user_id', $id)->orderBy('subs_end', 'DESC');
        return DataTables::of($subHis)
            ->addIndexColumn()
            ->make(true);
    }

    public function subsRenewal(Request $request){
        
        $validator = Validator::make($request->all(), [
            'subs_end' => 'required|after:today'
        ], [
            'subs_end.required' => 'Tanggal akhir langganan wajib diisi',
            'subs_end.after' => 'Tanggal berakhir harus lebih dari hari ini'
        ]);

        if ($validator->fails()){
            return response()->json([
                'message' => $validator->errors()
            ], 400);
        }

        Subscription::create([
            'subs_start' => now()->format('Y-m-d'),
            'subs_end' => $request->subs_end,
            'registered_by' => Auth()->user()->id,
            'user_id' => $request->id_ap
        ]);

        return response()->json(['message' => 'Langganan berhasil diperbarui']);

    }

    public function subsCheck(Request $request){
        $subsActive = Subscription::where('user_id', $request->id_ap)
                ->whereDate('subs_end', '>=', now())
                ->orderBy('created_at', 'desc')
                ->get();
        return response()->json(['subs_active' => $subsActive]);
    }

    public function subsExpired(){
        return view('subsExpired');
    }


    public function getAll(){
        $all = User::all();
        return DataTables::of($all)
            ->addIndexColumn()
            ->addColumn('action', function($row) {
                return '<a class="btn btn-outline" id="editBtn" data-id="'.$row->id.'"><i class="fas fa-pen-to-square"></i></a>
                <a class="btn btn-outline-secondary" id="changePassBtn" data-id="'.$row->id.'"><i class="fas fa-key"></i></a>
                <a class="btn btn-outline-delete" id="deleteBtn" data-id="'.$row->id.'"><i class="fas fa-trash"></i></a>
                ';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function userAdd(Request $request){
        $validator = Validator::make($request->all(), [
            'user_name' => 'required',
            'user_email' => 'required|email',
            'user_password' => 'required|min:8',
            'user_role' => 'in:sa,ao,ap'
        ], [
            'user_name.required' => 'Username wajib diisi',
            'user_email.required' => 'Email wajib diisi',
            'user_password.required' => 'Password wajib diisi',
            'user_email.email' => 'Format isian salah, cth: name@mail.com',
            'user_password.min' => 'Password kurang dari 8 karakter',
            'user_role.in' => 'User role wajib dipilih'
        ]);

        if($validator->fails()){
            return response()->json([
                'message' => $validator->errors()
            ], 400);
        }

        User::create([
            'user_name' => $request->user_name,
            'email' => $request->user_email,
            'password' => Hash::make($request->user_password),
            'role' => $request->user_role
        ]);

        return response()->json(['message' => 200]);

    }

    public function userDelete(Request $request){
        $id = $request->id;

        try {
            $user = User::find($id);

            if ($user){
                $user->delete();

                return response()->json([
                    'message' => 'berhasil dihapus'
                ], 200);
            } else {
                return response()->json([
                    'message' => 'User tidak ditemukan!',
                ], 404);
            }

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function userFetch(Request $request){
        $id = $request->id;

        try {
            $user = User::find($id);

            if ($user){
                return response()->json($user);
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

    public function userUpdate(Request $request){
        $id = $request->id;

        $validator = Validator::make($request->all(), [
            'user_name' => 'required',
            'user_email' => 'required|email',
            'user_role' => 'in:sa,ao,ap'
        ], [
            'user_name.required' => 'Username wajib diisi',
            'user_email.required' => 'Email wajib diisi',
            'user_email.email' => 'Format isian salah, cth: name@mail.com',
            'user_role.in' => 'User role wajib dipilih'
        ]);

        if($validator->fails()){
            return response()->json([
                'message' => $validator->errors()
            ], 400);
        }

        try{
            $user = User::find($id);

            $user->user_name = $request->user_name;
            $user->email = $request->user_email;
            $user->role = $request->user_role;

            $user->save();

            return response()->json([
                'message' => 'berhasil dihapus'
            ], 200);

        } catch (\Exception $e){
            return response()->json([
                'message' => 'Error',
                'error' => $e->getMessage()
            ], 500);
        }

    }

    public function userChangePass(Request $request){
        $id = $request->id;

        $validator = Validator::make($request->all(), [
            'user_change_password' => 'required|min:8'
        ], [
            'user_change_password.required' => 'Password wajib diisi',
            'user_change_password.min' => 'Password kurang dari 8 karakter'
        ]);

        if ($validator->fails()){
            return response()->json([
                'message' => $validator->errors()
            ], 400);
        }

        try {
            
            $user = User::find($id);
            $user->password = Hash::make($request->user_change_password);
            $user->save();

        } catch (\Exception $e){
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getAdminPangkalan(){
        $data = User::with('pangkalan')
            ->where('role', 'ap')
            ->doesntHave('pangkalan')
            ->get();
        return response()->json($data);
    }

    
}
