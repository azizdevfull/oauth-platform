<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;


Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', function (Request $request) {
    $request->session()->put('state', $state = Str::random(40));
    $query = http_build_query([
        'client_id' => "9c14aee0-708b-4407-8a0a-b00fd3eec75d",
        'redirect_uri' => "http://127.0.0.1:8080/callback",
        'response_type' => 'code',
        'scope' => '',
        'state' => $state,
    ]);

    return redirect("http://127.0.0.1:8000/oauth/authorize?" . $query);
});


Route::get("/callback", function (Request $request) {
    $state = $request->session()->pull('state');

    throw_unless(strlen($state) > 0 && $state == $request->state, InvalidArgumentException::class);

    $response = Http::asForm()->post('http://127.0.0.1:8000/oauth/token', [
        'grant_type' => 'authorization_code',
        'client_id' => '9c14aee0-708b-4407-8a0a-b00fd3eec75d',
        'client_secret' => 'Md6cXPneQ9yUqM7mvE6B2NFiFPAn1LuaK0eVTol6',
        'redirect_uri' => 'http://127.0.0.1:8080/callback',
        'code' => $request->code
    ]);
    return $response->json();
});
