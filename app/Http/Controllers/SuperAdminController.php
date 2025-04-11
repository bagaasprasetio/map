<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaksi;
use App\Models\Pangkalan;
use App\Models\User;
use Carbon\Carbon;


class SuperAdminController extends Controller
{
    public function index(){
        $transactions   = Transaksi::whereDate('created_at', Carbon::now())->count();
        $pangkalan      = Pangkalan::all()->count();
        $user           = User::all()->count();
        return view('index', compact('transactions', 'pangkalan', 'user'));
    }

    function subsMaster(){
        return view('subs');
    }
}
