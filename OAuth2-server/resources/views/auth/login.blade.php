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
    <div class="form-input">
        <label for="email">Email</label>
        <br>
        <input type="email" name="email" placeholder="Enter email" value="{{old('email')}}">
        <div class="error">
            @error('email')
                {{$message}}
            @enderror
        </div>
    </div>

    <div class="form-input">
        <label for="password">Password</label>
        <br>
        <input type="password" name="password" placeholder="Enter password">
        <div class="error">
            @error('password')
                {{$message}}
            @enderror
        </div>
    </div>
    <br>
    <button type="submit">Login</button>
    <br>
    Don't have an account? <a href="{{route('register')}}">Register here</a>
</form>
@endsection
