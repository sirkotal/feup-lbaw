@extends('layouts.auth')

@section('title', 'Register')

@section('content')
<div class="register-page">
    <div class="left-register">
        <a href="{{ route('mainPage') }}"><img src="{{ asset('cap.png') }}" alt="yummy"></a>
        <h1>Cappuccino</h1>
    </div>

    <div class="right-register">
        <h1>Register</h1>
        <form method="POST" action="{{ route('register') }}">
            {{ csrf_field() }}

            <input id="username" type="username" name="username" value="{{ old('username') }}" placeholder="Username" required autofocus>
            @if ($errors->has('username'))
            <span class="error">
                {{ $errors->first('username') }}
            </span>
            @endif

            <input id="email" type="email" name="email" value="{{ old('email') }}" placeholder="Email" required>
            @if ($errors->has('email'))
            <span class="error">
                {{ $errors->first('email') }}
            </span>
            @endif

            <input id="password" type="password" name="password" placeholder="Password" required>
            @if ($errors->has('password'))
            <span class="error">
                {{ $errors->first('password') }}
            </span>
            @endif

            <input id="password-confirm" type="password" name="password_confirmation" placeholder="Confirm Password" required>

            <div class="button-container"> <button type="submit" id="register-button">
            Register
            </button></div>
            <div class="button-container"><a class="button button-outline" href="{{ route('login') }}">Login</a></div>
        </form>
    </div>
</div>
@endsection