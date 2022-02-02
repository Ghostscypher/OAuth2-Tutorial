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
    <div class="form-input">
        <label for="email">Email</label><br>
        <input type="email" name="email" placeholder="Enter email" value="{{old('email')}}">
        <div class="error">
            @error('email')
                {{$message}}
            @enderror
        </div>
    </div>
    <div class="form-input">
        <label for="name">Username</label><br>
        <input type="text" name="name" placeholder="Enter Username" value="{{old('name')}}">
        <div class="error">
            @error('name')
                {{$message}}
            @enderror
        </div>
    </div>
    <div class="form-input">
        <label for="password">Password</label><br>
        <input type="password" name="password" placeholder="Enter password" value="{{old('password')}}">
        <div class="error">
            @error('password')
                {{$message}}
            @enderror
        </div>
    </div>

    <div class="form-input">
        <label for="password_confirmation">Confirm Password</label><br>
        <input type="password" name="password_confirmation" placeholder="Confirm password">
    </div>

    <br>
    <button type="submit">Register</button>
    <br>
    Already have an account? <a href="{{route('login')}}">Login here</a>
</form>
@endsection
