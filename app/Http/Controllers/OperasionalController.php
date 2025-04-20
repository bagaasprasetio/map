<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaksi;
use App\Models\User;
use App\Models\Pangkalan;
use App\Models\Subscription;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class OperasionalController extends Controller
{
    public function index(){
        $transactions   = Transaksi::whereDate('created_at', Carbon::now())->count();
        $pangkalan      = Pangkalan::all()->count();
        $user           = User::all()->count();
        $subs           = Subscription::where('subs_end', '>=', Carbon::now())->count();
        return view('index', compact('transactions', 'pangkalan', 'user', 'subs'));
    }
}
