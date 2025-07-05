<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;

Route::middleware(['web'])->group(function () {
// Rute untuk menampilkan halaman login
Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');

// Rute untuk memproses login
Route::post('postlogin', [LoginController::class, 'login'])->name('login.process');

// Rute untuk logout
Route::post('logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');
});