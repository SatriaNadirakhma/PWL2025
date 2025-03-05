<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PageController extends Controller
{
    public function index() {
        return 'Selamat Datang';
    }

    public function about() {
        return 'Satria Rakhmadani 2341760106';
    }

    public function articles($articlesid) {
        return 'Halaman Artikel Dengan ID ' .$articlesid;
    }
}
