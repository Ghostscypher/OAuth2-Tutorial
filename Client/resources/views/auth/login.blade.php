@extends('layout.layout')

@section('content')
<form action="{{route('login.post')}}" method="POST">
    @csrf
    @error('success')
        <div class="success">
            {{$message}}
        </div>
        <br><br>
    @enderror
    @error('error')
        <div class="error">
            {{$message}}
        </div>
        <br><br>
    @enderror
    <br>
    <button type="submit">Login with Oauth2-Server</button>
    <br>
    Don't have an account? <a href="{{route('register')}}">Register here</a>
</form>

<form action="{{route('login.post.without-socialite')}}" method="POST">
    @csrf
    <br>
    <button type="submit">Login with Oauth2-Server without using Laravel Socialite</button>
</form>

<form action="{{route('login.post.without-socialite-pkce')}}" method="POST">
    @csrf
    <br>
    <button type="submit">Login with Oauth2-Server without using Laravel Socialite with PKCE</button>
</form>

@endsection
