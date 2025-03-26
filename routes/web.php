<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SuperAdminController;
use App\Http\Controllers\PangkalanController;
use App\Http\Controllers\OperasionalController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\AutomationController;
use App\Livewire\Index;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/run', [AutomationController::class, 'run'])->name('run');
Route::get('/excel', [AutomationController::class, 'index'])->name('pup-index');
Route::post('/excel', [AutomationController::class, 'upload'])->name('pup-upload');

Route::get('/', [AuthController::class, 'loginForm'])->name('loginForm');
Route::post('/', [AuthController::class, 'login'])->name('login');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/subs/expired', [UserController::class, 'subsExpired'])->name('subs.expired');

Route::get('/subs/expired', [UserController::class, 'subsExpired'])->name('subs.expired');

Route::middleware(['role:ap,ao,sa'])->group(function() {
    Route::get('/transaksi-master', [TransaksiController::class, 'transaksiMaster'])->name('transaksi.master');
});

Route::middleware(['auth', 'role:ap', 'check.subs'])->group(function() {
    Route::get('/pangkalan', [PangkalanController::class, 'index'])->name('ap.index');
    Route::get('/pangkalan/check', [PangkalanController::class, 'pangkalanCheck'])->name('pangkalan.check');

    Route::post('/automation/login', [AutomationController::class, 'automationLogin'])->name('automation.login');

    Route::post('/transaksi/add', [TransaksiController::class, 'transaksiAdd'])->name('transaksi.add');


});

Route::middleware(['role:ao'])->group(function() {
    Route::get('/operasional', [OperasionalController::class, 'index'])->name('ao.index');
});

Route::middleware(['role:sa'])->group(function() {
    Route::get('/admin', [SuperAdminController::class, 'index'])->name('sa.index');
    Route::get('/subs-master', [SuperAdminController::class, 'subsMaster'])->name('subs.master');

    Route::get('/pangkalan-master', [PangkalanController::class, 'pangkalanMaster'])->name('pangkalan.master');
    Route::get('/pangkalan/getall', [PangkalanController::class, 'getAll'])->name('pangkalan.getall');
    Route::post('/pangkalan/add', [PangkalanController::class, 'pangkalanAdd'])->name('pangkalan.add');
    Route::get('/pangkalan/fetch', [PangkalanController::class, 'pangkalanFetch'])->name('pangkalan.fetch');
    Route::put('/pangkalan/update', [PangkalanController::class, 'pangkalanUpdate'])->name('pangkalan.update');
    Route::delete('/pangkalan/delete', [PangkalanController::class, 'pangkalanDelete'])->name('pangkalan.delete');
    Route::get('/pangkalan/getnull', [PangkalanController::class, 'getPangkalanNull'])->name('pangkalan.null');
    Route::put('/pangkalan/assignadmin', [PangkalanController::class, 'assignAdmin'])->name('pangkalan.assignadmin');

    Route::get('/user-master', [UserController::class, 'userMaster'])->name('user.master');
    Route::get('/user/getusersubs', [UserController::class, 'getUserSubs'])->name('user.getusersubs');
    Route::get('/subs/history/{id}', [UserController::class, 'getSubsHistory'])->name('subs.history');
    Route::post('/subs/renew', [UserController::class, 'subsRenewal'])->name('subs.renewal');
    Route::get('/subs/check', [UserController::class, 'subsCheck'])->name('subs.check');
    Route::put('/subs/pangkalanchange', [UserController::class, 'pangkalanChange'])->name('pangkalan.change');
    Route::get('/user/getall', [UserController::class, 'getAll'])->name('user.getall');
    Route::post('/user/add', [UserController::class, 'userAdd'])->name('user.add');
    Route::delete('/user/delete', [UserController::class, 'userDelete'])->name('user.delete');
    Route::put('/user/update', [UserController::class, 'userUpdate'])->name('user.update');
    Route::get('/user/fetch', [UserController::class, 'userFetch'])->name('user.fetch');
    Route::put('/user/changepass', [UserController::class, 'userChangePass'])->name('user.changepass');
    Route::get('/user/getadminpangkalan', [UserController::class, 'getAdminPangkalan'])->name('user.getadminpangkalan');
});




