<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdvanceduiController extends Controller
{
    public function mediaobject()
    {
        return view('pages.mediaobject');
    }
    public function accordion()
    {
        return view('pages.accordion');
    }
    public function tabs()
    {
        return view('pages.tabs');
    }
    public function chart()
    {
        return view('pages.chart');
    }
    public function modal()
    {
        return view('pages.modal');
    }
    public function tooltipandpopover()
    {
        return view('pages.tooltipandpopover');
    }
    public function progress()
    {
        return view('pages.progress');
    }
    public function carousel()
    {
        return view('pages.carousel');
    }
    public function footers()
    {
        return view('pages.footers');
    }
    public function users_list()
    {
        return view('pages.users-list');
    }
    public function search()
    {
        return view('pages.search');
    }
    public function crypto_currencies()
    {
        return view('pages.crypto-currencies');
    }
}
