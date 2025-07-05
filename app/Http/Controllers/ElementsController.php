<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ElementsController extends Controller
{
    public function alerts()
    {
        return view('pages.alerts');
    }
    public function buttons()
    {
        return view('pages.buttons');
    }
    public function colors()
    {
        return view('pages.colors');
    }
    public function avatarsquare()
    {
        return view('pages.avatarsquare');
    }
    public function avatar_round()
    {
        return view('pages.avatar-round');
    }
    public function avatar_radius()
    {
        return view('pages.avatar-radius');
    }
    public function dropdown()
    {
        return view('pages.dropdown');
    }
    public function list()
    {
        return view('pages.list');
    }
    public function tags()
    {
        return view('pages.tags');
    }
    public function toast()
    {
        return view('pages.toast');
    }
    public function pagination()
    {
        return view('pages.pagination');
    }
    public function navigation()
    {
        return view('pages.navigation');
    }
    public function typography()
    {
        return view('pages.typography');
    }
    public function breadcrumbs()
    {
        return view('pages.breadcrumbs');
    }
    public function badge()
    {
        return view('pages.badge');
    }
    public function panels()
    {
        return view('pages.panels');
    }
    public function thumbnails()
    {
        return view('pages.thumbnails');
    }
}
