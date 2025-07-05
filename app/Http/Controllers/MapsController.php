<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MapsController extends Controller
{
    public function maps()
    {
        return view('pages.maps');
    }
    public function maps1()
    {
        return view('pages.maps1');
    }
    public function maps2()
    {
        return view('pages.maps2');
    }
}
