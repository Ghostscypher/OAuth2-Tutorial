<?php

use App\Http\Controllers\AuthController;
use GuzzleHttp\Psr7\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('login', function(){
    return view('auth.login');
})->name('login');

Route::get('register', function(){
    return view('auth.register');
})->name('register');

Route::get('dashboard', function(){
    return view('welcome');
})->name('dashboard')->middleware('auth');

Route::post('login', [AuthController::class, 'loginWithOauth2'])->name('login.post');
Route::post('register', [AuthController::class, 'loginWithOauth2'])->name('register.post');

// Oauth callback
Route::get('oauth/callback', [AuthController::class, 'oauthCallback'])->name('oauth.callback');


// Without socialite
Route::post('/login/without-socialite', [AuthController::class, 'loginWithOauth2WithoutSocialite'])->name('login.post.without-socialite');

// Login implementing PKCE
Route::post('/login/without-socialite-pkce', [AuthController::class, 'loginWithOauth2WithoutSocialiteWithPKCE'])->name('login.post.without-socialite-pkce');

// Oauth callbacks
Route::get('oauth/callback/without-socialite', [AuthController::class, 'oauthCallbackWithoutSocialite'])->name('oauth.callback.without-socialite');
Route::get('oauth/callback/without-socialite-pkce', [AuthController::class, 'oauthCallbackWithoutSocialiteWithPKCE'])->name('oauth.callback.without-socialite-pkce');
