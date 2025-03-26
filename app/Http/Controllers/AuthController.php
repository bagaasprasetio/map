<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;


class AuthController extends Controller
{
    public function loginForm()
    {
        return view('auth');
    }

    function login(Request $request){

        try {
            $validator = Validator::make($request->all(), [
                'email' => 'required|email',
                'password' => 'required'
            ], [
                'email.required' => 'Email tidak boleh kosong',
                'email.email' => 'Format isian salah, cth: name@mail.com',
                'password.required' => 'Password tidak boleh kosong'
            ]);
    
            if($validator->fails()){
                return response()->json([
                    'message' => $validator->errors()
                ], 400);
            }

            $infoLogin = [
                'email' => $request->email,
                'password' => $request->password
            ];
    
            if (Auth::attempt($infoLogin)) {
                $user = Auth::user();
    
                //dd($infoLogin);
                return response()->json([
                    'message' => 'Login berhasil',
                    'role' => $user->role
                ], 200);
    
            } else {
                return response()->json([
                    'message' => 'Username atau Password Salah!'
                ], 404);
            }
        } catch (\Exception $e){
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }

    function logout(){
        Auth::logout();
        return redirect()->route('login');
    }
}
