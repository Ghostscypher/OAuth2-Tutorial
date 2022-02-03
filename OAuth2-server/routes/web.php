<?php

use App\Http\Controllers\AuthController;
use App\Http\Middleware\VerifyCsrfToken;
use Illuminate\Support\Facades\Route;

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

Route::post('logout', function () {
    auth()->logout();
    return redirect('login');
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

Route::post('login', [AuthController::class, 'login'])->name('login.post');
Route::post('register', [AuthController::class, 'register'])->name('register.post');

// Device grant flow
Route::get('oauth/device/code', [AuthController::class, 'deviceGrantGenerateCode']);
Route::get('oauth/device/activate', [AuthController::class, 'deviceGrantActivate'])
    ->middleware('auth')
    ->name('oauth.device.activate');

Route::post('oauth/device/token', [AuthController::class, 'deviceGrantGetToken'])
    ->name('oauth.device.token')->withoutMiddleware(VerifyCsrfToken::class);
