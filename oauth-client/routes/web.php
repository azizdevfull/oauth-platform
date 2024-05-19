<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;


Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', function (Request $request) {
    $request->session()->put('state', $state = Str::random(40));

    $query = http_build_query([
        'client_id' => "9c14a349-486f-4c4c-a265-1d1ceb18c87e",
        'redirect_uri' => "http://127.0.0.1:8080/callback",
        'response_type' => 'code',
        'scope' => '',
        'state' => $state,
    ]);
    return redirect("http://127.0.0.1:8000/oauth/authorize?" . $query);
});


Route::get("/callback", function (Request $request) {
    $state = $request->session()->pull('state',);
    dd($state);
});
