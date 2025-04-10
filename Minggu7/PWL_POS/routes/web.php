<?php

use App\Http\Controllers\BarangController;
use App\Http\Controllers\LevelController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SupplierController;


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

Route::pattern('id', '[0-9]+'); // artinya ketika ada parameter {id}, maka harus berupa angka

Route::get('login', [AuthController::class, 'login'])->name('login');
Route::post('login', [AuthController::class, 'postLogin']);
Route::post('logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

Route::middleware(['auth'])->group(function () {
    // Masukkan semua route yang perlu autentikasi di sini

    Route::get('/', [WelcomeController::class, 'index']);
    Route::group(['prefix' => 'user'], function () {
    Route::get('/', [UserController::class, 'index']); // menampilkan halaman awal user
    Route::post('/list', [UserController::class, 'list']); // menampilkan data user dalam bentuk JSON untuk DataTables
    Route::get('/create', [UserController::class, 'create']); // menampilkan halaman form tambah user
    Route::post('/', [UserController::class, 'store']); // menyimpan data user baru
    Route::get('/create_ajax', [UserController::class, 'create_ajax']);
    Route::post('/ajax', [UserController::class, 'store_ajax']);
    Route::get('/{id}', [UserController::class, 'show']); // menampilkan detail user
    Route::get('/{id}/edit', [UserController::class, 'edit']); // menampilkan halaman form edit user
    Route::put('/{id}', [UserController::class, 'update']); // menyimpan perubahan data user
    Route::get('/{id}/edit_ajax', [UserController::class, 'edit_ajax']); // menampilkan halaman form edit user Ajax
    Route::put('/{id}/update_ajax', [UserController::class, 'update_ajax']); // menyimpan perubahan data user Ajax
    Route::get('/{id}/delete_ajax', [UserController::class, 'confirm_ajax']); // menampilkan halaman form delete user Ajax
    Route::delete('/{id}/delete_ajax', [UserController::class, 'delete_ajax']); // Menghapus data user Ajax
    Route::delete('/{id}', [UserController::class, 'destroy']); // menghapus data user
    });

    Route::group(['prefix' => 'barang'], function () {
    Route::get('/', [BarangController::class, 'index']); // menampilkan halaman awal Barang
    Route::post('/list', [BarangController::class, 'list']); // menampilkan data Barang dalam bentuk json untuk datatables
    Route::get('/create', [BarangController::class, 'create']); // menampilkan halaman form tambah Barang
    Route::post('/', [BarangController::class, 'store']);  // menyimpan data Barang baru
    Route::get('/create_ajax', [BarangController::class, 'create_ajax']);
    Route::post('/ajax', [BarangController::class, 'store_ajax']);
    Route::get('/{id}', [BarangController::class, 'show']); // menampilkan detail Barang
    Route::get('/{id}/edit', [BarangController::class, 'edit']); // menampilkan halaman form edit Barang
    Route::put('/{id}', [BarangController::class, 'update']);  // menyimpan perubahan data Barang
    Route::get('/{id}/edit_ajax', [BarangController::class, 'edit_ajax']); // menampilkan halaman form edit barang Ajax
    Route::put('/{id}/update_ajax', [BarangController::class, 'update_ajax']); // menyimpan perubahan data barang Ajax
    Route::get('/{id}/delete_ajax', [BarangController::class, 'confirm_ajax']); // menampilkan halaman form delete barang Ajax
    Route::delete('/{id}/delete_ajax', [BarangController::class, 'delete_ajax']); // Menghapus data barang Ajax
    Route::delete('/{id}', [BarangController::class, 'destroy']); // menghapus data barang
    });

    Route::group(['prefix' => 'kategori'], function () {
    Route::get('/', [KategoriController::class, 'index']); // menampilkan halaman awal Kategori
    Route::post('/list', [KategoriController::class, 'list']); // menampilkan data Kategori dalam bentuk json untuk datatables
    Route::get('/create', [KategoriController::class, 'create']); // menampilkan halaman form tambah Kategori
    Route::post('/', [KategoriController::class, 'store']);  // menyimpan data Kategori baru
    Route::get('/create_ajax', [KategoriController::class, 'create_ajax']);
    Route::post('/ajax', [KategoriController::class, 'store_ajax']);
    Route::get('/{id}', [KategoriController::class, 'show']); // menampilkan detail Kategori
    Route::get('/{id}/edit', [KategoriController::class, 'edit']); // menampilkan halaman form edit Kategori
    Route::put('/{id}', [KategoriController::class, 'update']);  // menyimpan perubahan data Kategori
    Route::get('/{id}/edit_ajax', [KategoriController::class, 'edit_ajax']); // menampilkan halaman form edit kategori Ajax
    Route::put('/{id}/update_ajax', [KategoriController::class, 'update_ajax']); // menyimpan perubahan data kategori Ajax
    Route::get('/{id}/delete_ajax', [KategoriController::class, 'confirm_ajax']); // menampilkan halaman form delete kategori Ajax
    Route::delete('/{id}/delete_ajax', [KategoriController::class, 'delete_ajax']); // Menghapus data kategori Ajax
    Route::delete('/{id}', [KategoriController::class, 'destroy']); // menghapus data kategori
    });

    Route::group(['prefix' => 'level'], function () {
    Route::get('/', [LevelController::class, 'index']); // menampilkan halaman awal Level
    Route::post('/list', [LevelController::class, 'list']); // menampilkan data Level dalam bentuk json untuk datatables
    Route::get('/create', [LevelController::class, 'create']); // menampilkan halaman form tambah Level
    Route::post('/', [LevelController::class, 'store']);  // menyimpan data Level baru
    Route::get('/create_ajax', [LevelController::class, 'create_ajax']);
    Route::post('/ajax', [LevelController::class, 'store_ajax']);
    Route::get('/{id}', [LevelController::class, 'show']); // menampilkan detail Level
    Route::get('/{id}/edit', [LevelController::class, 'edit']); // menampilkan halaman form edit Level
    Route::put('/{id}', [LevelController::class, 'update']);  // menyimpan perubahan data Level
    Route::get('/{id}/edit_ajax', [LevelController::class, 'edit_ajax']); // menampilkan halaman form edit level Ajax
    Route::put('/{id}/update_ajax', [LevelController::class, 'update_ajax']); // menyimpan perubahan data level Ajax
    Route::get('/{id}/delete_ajax', [LevelController::class, 'confirm_ajax']); // menampilkan halaman form delete level Ajax
    Route::delete('/{id}/delete_ajax', [LevelController::class, 'delete_ajax']); // Menghapus data level Ajax
    Route::delete('/{id}', [LevelController::class, 'destroy']); // menghapus data level
    });

    Route::group(['prefix' => 'supplier'], function () {
    Route::get('/',[SupplierController::class, 'index']);
    Route::post('/list',[SupplierController::class, 'list']);
    Route::get('/create',[SupplierController::class, 'create']);
    Route::post('/',[SupplierController::class, 'store']);
    Route::get('/create_ajax',[SupplierController::class, 'create_ajax']);
    Route::post('/ajax',[SupplierController::class, 'store_ajax']);
    Route::get('/{id}',[SupplierController::class, 'show']);
    Route::get('/{id}/edit',[SupplierController::class, 'edit']);
    Route::put('/{id}',[SupplierController::class, 'update']);
    Route::get('/{id}/edit_ajax',[SupplierController::class, 'edit_ajax']);
    Route::put('/{id}/update_ajax',[SupplierController::class, 'update_ajax']);
    Route::delete('/{id}',[SupplierController::class, 'destroy']);
    Route::get('/{id}/delete_ajax',[SupplierController::class, 'confirm_ajax']);
    Route::delete('/{id}/delete_ajax',[SupplierController::class, 'delete_ajax']);
    });

});


Route::get('/users/data', [UserController::class, 'getData'])->name('users.data');
Route::delete('/{id}', [UserController::class, 'destroy']);