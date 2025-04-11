<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaksi;
use Carbon\Carbon;

class OperasionalController extends Controller
{
    public function index(){
        $transactions   = Transaksi::whereDate('created_at', Carbon::now())->count();
        return view('index', compact('transactions'));
    }
}
