


@extends('layouts.app')

@section('title', 'Edit Profile' )

@php
    $user = Auth::user(); 
@endphp

@section('styles')
    <link href="{{ url('css/edit_profile.css') }}" rel="stylesheet">
    <!-- Add Bootstrap CSS link here if not already included -->
@endsection

@include('headers.simple-header')
@section('header')
    @yield('header')
@endsection

@section('content')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="mb-4">
                <h2>Edit Profile</h2>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <form id="editProfile" method="POST" action="{{ route('edit_profile') }}">
                        {{ csrf_field() }}
                        <div class="form-group">
                            <label for="username">Username</label>
                            <input id="username" type="text" name="username" value="{{ $user->username }}" class="form-control" required autofocus>
                            @if ($errors->has('username'))
                                <span class="error">
                                    {{ $errors->first('username') }}
                                </span>
                            @endif
                        </div>
                        <div class="form-group">
                            <label for="email">E-mail</label>
                            <input id="email" type="email" name="email" value="{{ $user->email }}" class="form-control" required autofocus>
                            @if ($errors->has('email'))
                                <span class="error">
                                    {{ $errors->first('email') }}
                                </span>
                            @endif
                        </div>
                        <div class="form-group">
                            <label for="date_of_birth">Date of Birth</label>
                            <input id="dateofbirth" type="date" name="date_of_birth" value="{{ $user->date_of_birth }}" class="form-control" required autofocus>
                            @if ($errors->has('date_of_birth'))
                                <span class="error">
                                    {{ $errors->first('date_of_birth') }}
                                </span>
                            @endif
                        </div>
                        <div class="form-group">
                            <label for="phone_number">Phone Number</label>
                            <input id="phonenumber" type="text" name="phone_number" value="{{ $user->phone_number }}" class="form-control" required autofocus>
                            @if ($errors->has('phone_number'))
                                <span class="error">
                                    {{ $errors->first('phone_number') }}
                                </span>
                            @endif
                        </div>
                        <div class="row">
                            <div class="col-md-8">
                                <button type="submit" class="btn btn-primary btn-block">
                                    Save
                                </button>
                            </div>
                            <div class="col-md-4">
                                <a href="{{ route('user') }}" class="btn btn-secondary">Cancel</a>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="col-md-6">
                    <form id="editPassword" method="POST" action="{{ route('edit_password') }}">
                        {{ csrf_field() }}
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input id="password" type="password" name="password" class="form-control" required autofocus>
                            @if ($errors->has('password'))
                                <span class="error">
                                    {{ $errors->first('password') }}
                                </span>
                            @endif
                        </div>
                        <div class="form-group">
                            <label for="password_confirmation">Confirm Password</label>
                            <input id="password-confirm" type="password" name="password_confirmation" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-danger btn-block">
                            Change Password
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
