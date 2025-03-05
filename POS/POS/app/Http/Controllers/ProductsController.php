<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProductsController extends Controller
{
    public function foodBeverage()
    {
        return view('food-beverage');
    }

    public function beautyhealth()
    {
        return view('beauty-health');
    }

    public function homeCare()
    {
        return view('home-care');
    }

    public function babyKid()
    {
        return view('baby-kid');
    }
}
