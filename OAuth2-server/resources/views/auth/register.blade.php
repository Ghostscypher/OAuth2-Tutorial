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
    <input type="email" name="email" placeholder="Enter email" value="{{old('email')}}">
    <div class="error">
        @error('email')
            {{$message}}
        @enderror
    </div>
    <input type="text" name="name" placeholder="Enter Username" value="{{old('name')}}">
    <div class="error">
        @error('name')
            {{$message}}
        @enderror
    </div>
    <input type="password" name="password" placeholder="Enter password" value="{{old('password')}}">
    <div class="error">
        @error('password')
            {{$message}}
        @enderror
    </div>
    <input type="password" name="password_confirmation" placeholder="Confirm password">
    <br>
    <button type="submit">Login</button>
</form>
@endsection
