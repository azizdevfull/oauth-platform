<?php

use App\Http\Controllers\SSO\SSOController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;


Route::get('/', function () {
    return view('welcome');
});
Route::prefix('sso')->group(function () {
    Route::get('/login', [SSOController::class, 'getLogin'])->name('sso.login');


    Route::get("/callback", [SSOController::class, 'getCallback'])->name('sso.callback');


    Route::get('/authuser', [SSOController::class, 'getAuthUser'])->name('sso.authuser');
});

Auth::routes(['register' => false, 'reset' => false]);

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('index');
