<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{
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
}
