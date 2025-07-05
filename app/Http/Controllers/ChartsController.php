<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ChartsController extends Controller
{
    public function chart_chartist()
    {
        return view('pages.chart-chartist');
    }
    public function chart_flot()
    {
        return view('pages.chart-flot');
    }
    public function chart_echart()
    {
        return view('pages.chart-echart');
    }
    public function chart_morris()
    {
        return view('pages.chart-morris');
    }
    public function charts()
    {
        return view('pages.charts');
    }
    public function chart_line()
    {
        return view('pages.chart-line');
    }
    public function chart_donut()
    {
        return view('pages.chart-donut');
    }
    public function chart_pie()
    {
        return view('pages.chart-pie');
    }
}

