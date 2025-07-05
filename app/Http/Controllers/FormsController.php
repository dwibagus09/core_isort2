<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FormsController extends Controller
{
    public function form_elements()
    {
        return view('pages.form-elements');
    }
    public function form_editor()
    {
        return view('pages.form-editor');
    }
    public function form_wizard()
    {
        return view('pages.form-wizard');
    }
    public function form_validation()
    {
        return view('pages.form-validation');
    }
    public function form_layouts()
    {
        return view('pages.form-layouts');
    }
}
