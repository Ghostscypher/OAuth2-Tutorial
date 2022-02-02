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
@endsection
