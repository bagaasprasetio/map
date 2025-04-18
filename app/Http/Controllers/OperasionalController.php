<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaksi;
use App\Models\User;
use App\Models\Pangkalan;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;


class OperasionalController extends Controller
{
    public function index(){
        $transactions   = Transaksi::whereDate('created_at', Carbon::now())->count();
        $pangkalan      = Pangkalan::all()->count();
        $user           = User::all()->count();
        return view('index', compact('transactions', 'pangkalan', 'user'));
    }
}
