@extends('layout.layout')

@section('content')
<form action="{{route('oauth.device.activate')}}" method="GET">
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
        <label for="user_code">Enter Code</label>
        <br>
        <input type="text" name="user_code" placeholder="Enter code" value="{{old('user_code')}}">
        <div class="error">
            @error('user_code')
                {{$message}}
            @enderror
        </div>
    </div>

    <br>
    <button type="submit">Next</button>
</form>
@endsection
