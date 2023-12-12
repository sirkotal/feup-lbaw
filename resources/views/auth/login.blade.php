@extends('layouts.auth')

@section('title', 'Login')

@section('content')
<div class="login-page">
    <div class="left-login">
        <a href="{{ route('mainPage') }}"><img src="{{ asset('cap.png') }}" alt="yummy"></a>
        <h1>Cappuccino</h1>
    </div>

    <div class="right-login">
        <h1>Log In</h1>
        <form method="POST" action="{{ route('login') }}">
            {{ csrf_field() }}

            <input id="email" type="email" name="email" value="{{ old('email') }}" placeholder="Email" required autofocus>
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

            <div class="button-container">
                <button type="submit" id="login-button">
                    Login
                </button> 
            </div>
            <div class="button-container"><a class="button button-outline" href="{{ route('register') }}">Sign Up</a></div>
            <div class="button-container reset-password">
                <a class="button button-outline" href="{{ route('forgotPassword') }}">Forgot password</a>
            </div>
            @if (session('success'))
                <p class="success">
                    {{ session('success') }}
                </p>
            @endif
        </form>
    </div>
</div>
@endsection