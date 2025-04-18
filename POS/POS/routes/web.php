<?php

use App\Http\Controllers\BarangController;
use App\Http\Controllers\LevelController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\PenjualanController;
use App\Http\Controllers\PenjualanDetailController;
use App\Http\Controllers\StokController;
use App\Models\StokModel;

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
Route::get('register', [AuthController::class, 'register'])->name('register');
Route::post('register', [AuthController::class, 'postregister']);

Route::middleware(['auth'])->group(function () {
    // Masukkan semua route yang perlu autentikasi di sini

Route::get('/', [WelcomeController::class, 'index']);

    Route::middleware(['authorize:ADM'])->prefix('user')->group(function() {
        Route::get('/', [UserController::class, 'index']); // menampilkan halaman awal user
        Route::post('/list', [UserController::class, 'list']); // menampilkan data user dalam bentuk JSON untuk DataTables
        Route::get('/create_ajax', [UserController::class, 'create_ajax']);
        Route::post('/ajax', [UserController::class, 'store_ajax']);
        Route::get('/{id}/show_ajax', [UserController::class, 'show_ajax']);
        Route::get('/{id}/edit_ajax', [UserController::class, 'edit_ajax']); // menampilkan halaman form edit user Ajax
        Route::put('/{id}/update_ajax', [UserController::class, 'update_ajax']); // menyimpan perubahan data user Ajax
        Route::get('/{id}/delete_ajax', [UserController::class, 'confirm_ajax']); // menampilkan halaman form delete user Ajax
        Route::delete('/{id}/delete_ajax', [UserController::class, 'delete_ajax']); // Menghapus data user Ajax
        Route::get('/import', [UserController::class, 'import']);
        Route::post('/import_ajax', [UserController::class, 'import_ajax']);
        Route::get('/export_excel', [UserController::class, 'export_excel']);
        Route::get('/export_pdf', [UserController::class, 'export_pdf']);
    });

    Route::middleware(['authorize:ADM,MNG,STF'])->prefix('barang')->group(function() {
        Route::get('/', [BarangController::class, 'index']); // menampilkan halaman awal Barang
        Route::post('/list', [BarangController::class, 'list']); // menampilkan data Barang dalam bentuk json untuk datatables
        Route::get('/create_ajax', [BarangController::class, 'create_ajax']);
        Route::post('/ajax', [BarangController::class, 'store_ajax']);
        Route::get('/{id}/show_ajax', [BarangController::class, 'show_ajax']);
        Route::get('/{id}/edit_ajax', [BarangController::class, 'edit_ajax']); // menampilkan halaman form edit barang Ajax
        Route::put('/{id}/update_ajax', [BarangController::class, 'update_ajax']); // menyimpan perubahan data barang Ajax
        Route::get('/{id}/delete_ajax', [BarangController::class, 'confirm_ajax']); // menampilkan halaman form delete barang Ajax
        Route::delete('/{id}/delete_ajax', [BarangController::class, 'delete_ajax']); // Menghapus data barang Ajax
        Route::get('/import', [BarangController::class, 'import']);
        Route::post('/import_ajax', [BarangController::class, 'import_ajax']);
        Route::get('/export_excel', [BarangController::class, 'export_excel']);
        Route::get('/export_pdf', [BarangController::class, 'export_pdf']);
    });

    Route::middleware(['authorize:ADM,MNG'])->prefix('kategori')->group(function() {
        Route::get('/', [KategoriController::class, 'index']); // menampilkan halaman awal Kategori
        Route::post('/list', [KategoriController::class, 'list']); // menampilkan data Kategori dalam bentuk json untuk datatables
        Route::get('/create_ajax', [KategoriController::class, 'create_ajax']);
        Route::post('/ajax', [KategoriController::class, 'store_ajax']);
        Route::get('/{id}/show_ajax', [KategoriController::class, 'show_ajax']);
        Route::get('/{id}/edit_ajax', [KategoriController::class, 'edit_ajax']); // menampilkan halaman form edit kategori Ajax
        Route::put('/{id}/update_ajax', [KategoriController::class, 'update_ajax']); // menyimpan perubahan data kategori Ajax
        Route::get('/{id}/delete_ajax', [KategoriController::class, 'confirm_ajax']); // menampilkan halaman form delete kategori Ajax
        Route::delete('/{id}/delete_ajax', [KategoriController::class, 'delete_ajax']); // Menghapus data kategori Ajax
        Route::get('/import', [KategoriController::class, 'import']);
        Route::post('/import_ajax', [KategoriController::class, 'import_ajax']);
        Route::get('/export_excel', [KategoriController::class, 'export_excel']);
        Route::get('/export_pdf', [KategoriController::class, 'export_pdf']);
    });

    Route::middleware(['authorize:ADM'])->prefix('level')->group(function() {
        Route::get('/', [LevelController::class, 'index']); // menampilkan halaman awal Level
        Route::post('/list', [LevelController::class, 'list']); // menampilkan data Level dalam bentuk json untuk datatables
        Route::get('/create_ajax', [LevelController::class, 'create_ajax']);
        Route::post('/ajax', [LevelController::class, 'store_ajax']);
        Route::get('/{id}/show_ajax', [LevelController::class, 'show_ajax']);
        Route::get('/{id}/edit_ajax', [LevelController::class, 'edit_ajax']); // menampilkan halaman form edit level Ajax
        Route::put('/{id}/update_ajax', [LevelController::class, 'update_ajax']); // menyimpan perubahan data level Ajax
        Route::get('/{id}/delete_ajax', [LevelController::class, 'confirm_ajax']); // menampilkan halaman form delete level Ajax
        Route::delete('/{id}/delete_ajax', [LevelController::class, 'delete_ajax']); // Menghapus data level Ajax
        Route::get('/import', [LevelController::class, 'import']);
        Route::post('/import_ajax', [LevelController::class, 'import_ajax']);
        Route::get('/export_excel', [LevelController::class, 'export_excel']);
        Route::get('/export_pdf', [LevelController::class, 'export_pdf']);
    });

    Route::middleware(['authorize:ADM,MNG'])->prefix('supplier')->group(function() {
        Route::get('/',[SupplierController::class, 'index']);
        Route::post('/list',[SupplierController::class, 'list']);
        Route::get('/create_ajax',[SupplierController::class, 'create_ajax']);
        Route::post('/ajax',[SupplierController::class, 'store_ajax']);
        Route::get('/{id}/show_ajax', [SupplierController::class, 'show_ajax']);
        Route::get('/{id}/edit_ajax',[SupplierController::class, 'edit_ajax']);
        Route::put('/{id}/update_ajax',[SupplierController::class, 'update_ajax']);
        Route::get('/{id}/delete_ajax',[SupplierController::class, 'confirm_ajax']);
        Route::delete('/{id}/delete_ajax',[SupplierController::class, 'delete_ajax']);
        Route::get('/import', [SupplierController::class, 'import']);
        Route::post('/import_ajax', [SupplierController::class, 'import_ajax']);
        Route::get('/export_excel', [SupplierController::class, 'export_excel']);
        Route::get('/export_pdf', [SupplierController::class, 'export_pdf']);
    });

    Route::middleware(['authorize:ADM,MNG,STF'])->prefix('penjualan')->group(function() {
        Route::get('/',[PenjualanController::class, 'index']);
        Route::post('/list',[PenjualanController::class, 'list']);
        Route::get('/create_ajax',[PenjualanController::class, 'create_ajax']);
        Route::post('/ajax',[PenjualanController::class, 'store_ajax']);
        Route::get('/{id}/show_ajax', [PenjualanController::class, 'show_ajax']);
        Route::get('/{id}/edit_ajax',[PenjualanController::class, 'edit_ajax']);
        Route::put('/{id}/update_ajax',[PenjualanController::class, 'update_ajax']);
        Route::get('/{id}/delete_ajax',[PenjualanController::class, 'confirm_ajax']);
        Route::delete('/{id}/delete_ajax',[PenjualanController::class, 'delete_ajax']);
        Route::get('/import', [PenjualanController::class, 'import']);
        Route::post('/import_ajax', [PenjualanController::class, 'import_ajax']);
        Route::get('/export_excel', [PenjualanController::class, 'export_excel']);
        Route::get('/export_pdf', [PenjualanController::class, 'export_pdf']);
    });

    Route::middleware(['authorize:ADM,MNG,STF'])->prefix('penjualandetail')->group(function() {
        Route::get('/',[PenjualanDetailController::class, 'index']);
        Route::post('/list',[PenjualanDetailController::class, 'list']);
        Route::get('/create_ajax',[PenjualanDetailController::class, 'create_ajax']);
        Route::post('/ajax',[PenjualanDetailController::class, 'store_ajax']);
        Route::get('/{id}/edit_ajax',[PenjualanDetailController::class, 'edit_ajax']);
        Route::put('/{id}/update_ajax',[PenjualanDetailController::class, 'update_ajax']);
        Route::get('/{id}/delete_ajax',[PenjualanDetailController::class, 'confirm_ajax']);
        Route::delete('/{id}/delete_ajax',[PenjualanDetailController::class, 'delete_ajax']);
        Route::get('/import', [PenjualanDetailController::class, 'import']);
        Route::post('/import_ajax', [PenjualanDetailController::class, 'import_ajax']);
        Route::get('/export_excel', [PenjualanDetailController::class, 'export_excel']);
        Route::get('/export_pdf', [PenjualanDetailController::class, 'export_pdf']);
    });

    Route::middleware(['authorize:ADM,MNG,STF'])->prefix('stok')->group(function() {
        Route::get('/',[StokController::class, 'index']);
        Route::post('/list',[StokController::class, 'list']);
        Route::get('/create_ajax',[StokController::class, 'create_ajax']);
        Route::post('/ajax',[StokController::class, 'store_ajax']);
        Route::get('/{id}/edit_ajax',[StokController::class, 'edit_ajax']);
        Route::put('/{id}/update_ajax',[StokController::class, 'update_ajax']);
        Route::get('/{id}/delete_ajax',[StokController::class, 'confirm_ajax']);
        Route::delete('/{id}/delete_ajax',[StokController::class, 'delete_ajax']);
        Route::get('/import', [StokController::class, 'import']);
        Route::post('/import_ajax', [StokController::class, 'import_ajax']);
        Route::get('/export_excel', [StokController::class, 'export_excel']);
        Route::get('/export_pdf', [StokController::class, 'export_pdf']);
    });

    Route::group(['prefix' => 'user'], function () {
        Route::get('/profile', [UserController::class, 'profile_page']);
        Route::post('/update_picture', [UserController::class, 'update_picture']);
    });
});