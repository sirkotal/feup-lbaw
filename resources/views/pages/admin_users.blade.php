@extends('layouts.app')

@section('styles')
    <link href="{{ url('css/admin.css') }}" rel="stylesheet">
@endsection

@section('scripts')
    <script type="text/javascript" src="{{ URL::asset('js/admin_users.js') }}" defer></script>
@endsection

@include('headers.admin-header')
@section('header')
    @yield('header')
@endsection

@section('content')

@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif

    <h2 class="title">User Management</h2>
    <table id="admin-users" class="table table-striped w-75">
        <thead>
            <tr class='header'>
                <th scope="col" class="text-center align-middle">Username</th>
                <th scope="col" class="text-center align-middle">Email</th>
                <th scope="col" class="text-center align-middle">Date of Birth</th>
                <th scope="col" class="text-center align-middle">Phone Number</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
            <tr class='userInfo'>
                @if($user->id != Auth::user()->id && !$user->is_deleted)
                <td class="text-center align-middle">{{$user->username}}</td>
                <td class="text-center align-middle">{{$user->email}}</td>
                <td class="text-center align-middle">{{$user->date_of_birth}}</td>
                <td class="text-center align-middle">{{$user->phone_number}}</td>
                <td class="text-center align-middle p-0"><div class="d-flex justify-content-center"> @if(in_array($user->id,$blocked))<button class="unblock"><i class="bi bi-lock-fill"></i></button>@else<button class="block"><i class="bi bi-unlock-fill"></i></button>@endif<button class="delete"><i class="bi bi-trash3-fill"></i></button></div></td>
                @endif
            </tr>
            @endforeach
        </tbody>
    </table>
@endsection