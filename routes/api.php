<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:api', 'scope:view-user');
Route::get('/logmeout', function (Request $request) {
    $user = $request->user();
    $accessToken = $user->token();
    DB::table('oauth_refresh_tokens')->wheres('access_token_id', $accessToken->id)->delete();
    $accessToken->delete();
    return response()->json(['message' => 'Revoked'], 200);
})->middleware('auth:api');
