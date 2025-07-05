<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ComponentsController extends Controller
{
    public function cards()
    {
        return view('pages.cards');
    }
    public function calendar()
    {
        return view('pages.calendar');
    }
    public function calendar2()
    {
        return view('pages.calendar2');
    }
    public function chat()
    {
        return view('pages.chat');
    }
    public function notify()
    {
        return view('pages.notify');
    }
    public function sweetalert()
    {
        return view('pages.sweetalert');
    }
    public function rangeslider()
    {
        return view('pages.rangeslider');
    }
    public function scroll()
    {
        return view('pages.scroll');
    }
    public function loaders()
    {
        return view('pages.loaders');
    }
    public function counters()
    {
        return view('pages.counters');
    }
    public function rating()
    {
        return view('pages.rating');
    }
    public function timeline()
    {
        return view('pages.timeline');
    }
    public function treeview()
    {
        return view('pages.treeview');
    }
}
