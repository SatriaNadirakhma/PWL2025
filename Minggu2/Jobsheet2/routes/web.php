<?php

use App\Http\Controllers\WelcomeController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PageController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AboutController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\PhotoController;

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

// Route::get('/hello', function () {
//     return 'Hello World';
// });

// Route::get('/world', function () {
//     return 'World';
// });

// Route::get('/welcome', function () {
//     return 'Selamat Datang';
// });

// Route::get('/about', function () {
//     return '2341760106 Satria Rakhmadani';
// });

// Route::get('/user/{Satria}', function ($name) {
//     return 'Nama saya '. $name;
// });

// Route::get('/posts/{post}/comments/{comment}', function ($postId, $commentId) {
//     return 'Nama Pos ke-'. $postId. " Komentar ke-: ".$commentId;
// });

// Route::get('/articles/{id}', function ($articlesId) {
//     return 'Halaman artikel dengan ID '. $articlesId;
// });

// Route::get('/user/{name?}', function ($name=null) {
//     return 'Nama Saya '. $name;
// });

// Route::get('/user/{name?}', function ($name='John') {
//     return 'Nama Saya '. $name;
// });

// Route::get('/hello', [WelcomeController::class, 'hello']);

// Route::get('/', [PageController::class, 'index']);

// Route::get('/about', [PageController::class, 'about']);

// Route::get('articles/{id}', [PageController::class, 'articles']);

// Route::get('/', [HomeController::class, 'index']);

// Route::get('/about', [AboutController::class, 'about']);

// Route::get('articles/{id}', [ArticleController::class, 'articles']);

// Route::resource('photos', PhotoController::class);

// Route::get('/greeting', function () {
//     return view('hello', ['name' => 'Satria R.']);
//     });

// Route::get('/greeting', function () {
//     return view('blog.hello', ['name' => 'Satria R.']);
//     });

Route::get('/greeting', [WelcomeController::class, 'greeting']);
        