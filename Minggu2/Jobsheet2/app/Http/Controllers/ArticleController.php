<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ArticleController extends Controller
{
    public function articles($articlesid) {
        return 'Halaman Artikel Dengan ID ' .$articlesid;
    }
}
