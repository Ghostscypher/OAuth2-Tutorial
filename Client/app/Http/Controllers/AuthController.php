<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{
    // With socialite
    public function loginWithOauth2(Request $request){
        return Socialite::driver('oauth2_server')
            ->with([
                'type' => 'login'
            ])
            ->redirect();
    }

    public function oauthCallback(){
        $user = Socialite::driver('oauth2_server')->user();
        dd(app()['request']->all(), $user);
    }

    // Without socialite
    public function loginWithOauth2WithoutSocialite(){
        session()->put('state', $state = Str::random(40));

        $query = http_build_query([
            'client_id' => config('services.oauth2_server.client_id'),
            'redirect_uri' => 'http://client.test/oauth/callback/without-socialite',
            'response_type' => 'code',
            'scope' => '',
            'state' => $state,
        ]);

        return redirect('http://oauth2-server.test/oauth/authorize?' . $query);
    }

    public function oauthCallbackWithoutSocialite(Request $request){
        $state = session()->pull('state');

        throw_unless(
            strlen($state) > 0 && $state === $request->state,
            InvalidArgumentException::class
        );

        $response = Http::asForm()->post('http://oauth2-server.test/oauth/token', [
            'grant_type' => 'authorization_code',
            'client_id' => config('services.oauth2_server.client_id'),
            'client_secret' => config('services.oauth2_server.client_secret'),
            'redirect_uri' => 'http://client.test/oauth/callback/without-socialite',
            'code' => $request->code,
        ]);

        $user = $this->getUser($response->json());
        dd($user->json(), $response->json());
    }

    // With PKCE
    public function loginWithOauth2WithoutSocialiteWithPKCE(){
        session()->put('state', $state = Str::random(40));

        session()->put(
            'code_verifier', $code_verifier = Str::random(128)
        );

        $codeChallenge = strtr(rtrim(
            base64_encode(hash('sha256', $code_verifier, true))
        , '='), '+/', '-_');

        $query = http_build_query([
            'client_id' => config('services.oauth2_server.client_id'),
            'redirect_uri' => 'http://client.test/oauth/callback/without-socialite-pkce',
            'response_type' => 'code',
            'scope' => '',
            'state' => $state,
            'code_challenge' => $codeChallenge,
            'code_challenge_method' => 'S256',
        ]);

        return redirect('http://oauth2-server.test/oauth/authorize?' . $query);
    }

    public function oauthCallbackWithoutSocialiteWithPKCE(Request $request){
        $state = session()->pull('state');
        $codeVerifier = session()->pull('code_verifier');

        throw_unless(
            strlen($state) > 0 && $state === $request->state,
            InvalidArgumentException::class
        );

        $response = Http::asForm()->post('http://oauth2-server.test/oauth/token', [
            'grant_type' => 'authorization_code',
            'client_id' => config('services.oauth2_server.client_id'),
            'client_secret' => config('services.oauth2_server.client_secret'),
            'redirect_uri' => 'http://client.test/oauth/callback/without-socialite-pkce',
            'code' => $request->code,
            'code_verifier' => $codeVerifier,
        ]);

        $user = $this->getUser($response->json());
        dd($user->json(), $response->json());
    }

    public function loginWithOauth2Implicit(){
        session()->put('state', $state = Str::random(40));

        $query = http_build_query([
            'client_id' => '95814ae5-6381-4529-9c33-5572050e8ebe',
            'redirect_uri' => 'http://client.test/oauth/callback/implicit',
            'response_type' => 'token',
            'scope' => '*',
            'state' => $state,
        ]);

        return redirect('http://oauth2-server.test/oauth/authorize?' . $query);
    }

    public function oauthCallbackImplicit(Request $request){
        dd('success');
    }

    private function getUser($json_response){
        $token = $json_response['access_token'];

        return Http::withHeaders([
                'Accept' => 'application/json',
                'Authorization' => "Bearer $token",
        ])->get('http://oauth2-server.test/api/user');
    }

}
