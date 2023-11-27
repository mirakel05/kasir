<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\JenisBarangController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\DiskonController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\CartController;
use Illuminate\Routing\RouteGroup;

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

// Route::get('/', function () {
//     return view('welcome');
// });


Route::get('/', [LoginController::class, 'login'])->name('login')->middleware('guest');
Route::post('/', [LoginController::class, 'authenticate']);
Route::get('/logout', [LoginController::class, 'logout']);


Route::group(['middleware' => ['auth','ceklevel:admin']], function () {
    // Route::get('/admin', [LoginController::class, 'admin'])->name('admin');


    //CRUD DATA USER
    Route::get('/user', [UserController::class, 'index']);
    Route::post('/user/store', [UserController::class, 'store']);
    Route::post('/user/update/{id}', [UserController::class, 'update']);
    Route::get('/user/destroy/{id}', [UserController::class, 'destroy']);

    //CRUD DATA JENIS BARANG
    Route::get('/jenisbarang', [JenisBarangController::class, 'index']);
    Route::post('/jenisbarang/store', [JenisBarangController::class, 'store']);
    Route::post('/jenisbarang/update/{id}', [JenisBarangController::class, 'update']);
    Route::get('/jenisbarang/destroy/{id}', [JenisBarangController::class, 'destroy']);

    //CRUD DATA BARANG
    Route::get('/barang', [BarangController::class, 'index']);
    Route::post('/barang/store', [BarangController::class, 'store']);
    Route::post('/barang/update/{id}', [BarangController::class, 'update']);
    Route::get('/barang/destroy/{id}', [BarangController::class, 'destroy']);

    //SETTING DISKON
    Route::get('/setdiskon', [DiskonController::class, 'index']);
    Route::post('/setdiskon/update/{id}', [DiskonController::class, 'update']);
});

Route::group(['middleware' => ['auth','ceklevel:admin,kasir']], function () {
    Route::get('/home', [HomeController::class, 'index']);
    Route::get('/kasir', [LoginController::class, 'kasir'])->name('kasir');

    //PROFILE
    Route::get('/profile', [UserController::class, 'profile']);
    Route::post('/profile/updateprofile/{id}', [UserController::class, 'updateprofile']);

    //DATA TRANSAKSI
    Route::get('/transaksi', [TransaksiController::class, 'index']);
    Route::get('/transaksi/create', [TransaksiController::class, 'create']);
    Route::post('/transaksi/store', [TransaksiController::class, 'store']);
    Route::get('/transaksi/show/{id}',[TransaksiController::class, 'show']);
    Route::get('/cek_stok/{barang}', [TransaksiController::class, 'cek_stok']);

    //DATA CART
    Route::get('/cart/create', [CartController::class, 'index']);
    Route::post('/cart/store', [CartController::class, 'store']);
    Route::get('/cart/destroy/{cart}', [CartController::class, 'destroy']);

});
