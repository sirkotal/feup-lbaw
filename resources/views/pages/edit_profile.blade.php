@extends('layouts.app')

@section('title', 'Edit Profile' )

@php
    $user = Auth::user(); 
@endphp

@section('styles')
    <link href="{{ url('css/edit_profile.css') }}" rel="stylesheet">
@endsection

@include('headers.simple-header')
@section('header')
    @yield('header')
@endsection

@section('content')
<div id='forms'>
    <form id='editProfile' method="POST" action="{{ route('edit_profile') }}">
        {{ csrf_field() }}
        <div class='editComponent'>
            <label class="editProfile" for="username">Username</label>
            <input id="username" type="text" name="username" value="{{ $user->username }}" required autofocus>
            @if ($errors->has('username'))
                <span class="error">
                    {{ $errors->first('username') }}
                </span>
            @endif
        </div>
        <div class='editComponent'>
            <label class="editProfile" for="email">E-mail</label>
            <input id="email" type="email" name="email" value="{{ $user->email }}" required autofocus>
            @if ($errors->has('email'))
                <span class="error">
                    {{ $errors->first('email') }}
                </span>
            @endif
        </div>
        <div class='editComponent'>
            <label class="editProfile" for="date_of_birth">Date of Birth</label>
            <input id="dateofbirth" type="date" name="date_of_birth" value="{{ $user->date_of_birth }}" required autofocus>
            @if ($errors->has('date_of_birth'))
                <span class="error">
                    {{ $errors->first('date_of_birth') }}
                </span>
            @endif
        </div>
        <div class='editComponent'>
            <label class="editProfile" for="phone_number">Phone Number</label>
            <input id="phonenumber" type="text" name="phone_number" value="{{ $user->phone_number }}" required autofocus>
            @if ($errors->has('phone_number'))
                <span class="error">
                    {{ $errors->first('phone_number') }}
                </span>
            @endif
        </div>
        <button type="submit">
            Save
        </button>
    </form>
    <form id='editPassword' method="POST" action="{{ route('edit_password') }}">
        {{ csrf_field() }}
        <div class='editComponent' id='editPassword'>
            <label class="editPassword" for="password">Password</label>
            <input id="password" type="password" name="password" required autofocus>
            @if ($errors->has('password'))
                <span class="error">
                    {{ $errors->first('password') }}
                </span>
            @endif
        </div>
        <label class="editPassword" for="password_confirmation">Confirm Password</label>
        <input id="password-confirm" type="password" name="password_confirmation" required>
        <button>Change Password</button>
    </form>
</div>
@endsection