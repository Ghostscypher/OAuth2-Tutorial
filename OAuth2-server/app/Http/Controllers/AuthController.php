<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Passport\Http\Controllers\AccessTokenController;
use Laravel\Passport\Passport;
use Psr\Http\Message\ServerRequestInterface;

class AuthController extends Controller
{
    public function login(Request $request){
        $validated = $request->validate([
            'email' => ['bail', 'required', 'exists:users,email'],
            'password' => ['bail', 'required', 'string'],
        ]);

        if(auth()->attempt($validated)){
            return redirect()->intended();
        }

        return back()->withErrors(['error' => 'Invalid username or password']);
    }

    public function register(Request $request){
        $validated = $request->validate([
            'email' => ['bail', 'required', 'email', 'unique:users,email'],
            'name' => ['bail', 'required', 'unique:users,name'],
            'password' => ['bail', 'required', 'string', 'confirmed'],
        ]);

        User::create([
            'email' => $validated['email'],
            'name' => $validated['name'],
            'email_validated_at' => now(),
            'password' => Hash::make($validated['password']),
        ]);

        return redirect('login')->withErrors(['success' => 'Successfully registered.']);
    }

    // Device grant flow
    public function deviceGrantGenerateCode(Request $request){
        // This is just an example validation, in reality we need
        // to confirm that the client_id is registered in our app
        // for now we will just assume any client id is valid
        $request->validate([
            'client_id' => ['bail', 'required', 'string'],
            'scope' => ['bail', 'nullable', 'string'],
        ]);

        $user_code = strtoupper(Str::random(8));

        $response = [
            'device_code' => Str::uuid(),
            'user_code' => $user_code,
            'verification_uri' => 'http://oauth2-server.test/oauth/device/activate',
            'verification_uri_complete' => 'http://oauth2-server.test/oauth/device/activate?user_code=' . $user_code,
            'expires_in' => 600,
            'interval' => 3,
        ];

        // I'm storing the device code in Cache but in reality this will be
        // stored in a place where this data can't be easily destroyed
        Cache::put($response['device_code'], $response + ['scope' => $request->scope ?? ''], 600);
        Cache::put($response['user_code'], false, 600);

        return response()->json($response);
    }

    public function deviceGrantActivate(Request $request){
        if(!$request->user_code){
            return view('auth.activate');
        }

        // If code does not exist in cache or has already been used
        if(!Cache::has($request->user_code) || Cache::get($request->user_code)){
            return back()->withErrors([
                'error' => 'Invalid code entered',
            ]);
        }

        // Code is valid
        Cache::forget($request->user_code);
        Cache::put($request->user_code, $request->user()->id, 600);

        // Instead of this we should ideally show a consent screen to allow the user to authorize
        // the client similar to authorization flow
        return back()->withErrors([
            'success' => 'Successfully activated the device.',
        ]);
    }

    public function deviceGrantGetToken(Request $request){
        $request->validate([
            'device_code' => ['required', 'string'],
            'grant_type' => ['required', 'string', 'in:urn:ietf:params:oauth:grant-type:device_code'],
            'client_id' => ['required', 'string']
        ]);

        // Check if the device code is in DB
        if(!Cache::has($request->device_code)){
            return response()->json(['message' => 'No session found'], 404);
        }

        // Get the session data
        $data = Cache::get($request->device_code);

        // Check if activation has been done
        if(!Cache::has($data['user_code'])){
            return response()->json(['message' => 'Session expired'], 401);
        }

        if(!$user_id = Cache::get($data['user_code'])){
            return response()->json(['message' => 'Activation pending'], 401);
        }

        // Issue the token
        Cache::forget($request->device_code);
        Cache::forget($data['user_code']);
        $user = User::find($user_id);

        $token_model = $user->createToken($request->device_code, explode(" ", $data['scope']));

        $response = [
            'token_type' => 'Bearer',
            'expires_in' => $token_model->token->expires_at->diffInSeconds($token_model->token->created_at),
            'access_token' => $token_model->accessToken,
        ];

        return response()->json($response);
    }

}
