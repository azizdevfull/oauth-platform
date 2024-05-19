<?php

namespace App\Http\Controllers\SSO;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use InvalidArgumentException;


class SSOController extends Controller
{
    public function getLogin(Request $request)
    {
        $request->session()->put('state', $state = Str::random(40));
        $query = http_build_query([
            'client_id' => "9c14aee0-708b-4407-8a0a-b00fd3eec75d",
            'redirect_uri' => "http://127.0.0.1:8080/sso/callback",
            'response_type' => 'code',
            'scope' => 'view-user',
            'state' => $state,
        ]);

        return redirect("http://127.0.0.1:8000/oauth/authorize?" . $query);
    }

    public function getCallback(Request $request)
    {
        $state = $request->session()->pull('state');

        throw_unless(strlen($state) > 0 && $state == $request->state, InvalidArgumentException::class);

        $response = Http::asForm()->post('http://127.0.0.1:8000/oauth/token', [
            'grant_type' => 'authorization_code',
            'client_id' => '9c14aee0-708b-4407-8a0a-b00fd3eec75d',
            'client_secret' => 'Md6cXPneQ9yUqM7mvE6B2NFiFPAn1LuaK0eVTol6',
            'redirect_uri' => 'http://127.0.0.1:8080/sso/callback',
            'code' => $request->code
        ]);
        $request->session()->put($response->json());
        return redirect()->route('sso.authuser');
    }

    public function getAuthUser(Request $request)
    {
        $access_token = $request->session()->get('access_token');
        $response = Http::withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $access_token
        ])->get("http://127.0.0.1:8000/api/user");

        return $response->json();
    }
}
