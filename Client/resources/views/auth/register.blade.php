@extends('layout.layout')

@section('content')

<form action="{{route('register.post')}}" method="POST">
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
    <button type="submit">Register with Oauth2-Server</button>
    <br>
    Already have an account? <a href="{{route('login')}}">Login here</a>
</form>
@endsection
