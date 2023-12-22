@extends('layouts.app')

@section('styles')
    <link href="{{ url('css/admin.css') }}" rel="stylesheet">
@endsection

@section('title', 'Admin - Users')

@section('scripts')
    <script type="text/javascript" src="{{ URL::asset('js/admin_users.js') }}" defer></script>
    <script type="text/javascript" src="{{ URL::asset('js/admin.js') }}" defer></script>
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
                <th scope="col" class="text-center align-middle">#</th>
                <th scope="col" class="text-center align-middle" onclick="handleSorting('username')" data-sort-column="username">
                    Username
                    {!! Request::query('sort_column') === 'username' && Request::query('sort_direction') === 'asc' ? '<i class="fa fa-arrow-up"></i>' : '' !!}
                    {!! Request::query('sort_column') === 'username' && Request::query('sort_direction') === 'desc' ? '<i class="fa fa-arrow-down"></i>' : '' !!}
                </th>
                <th scope="col" class="text-center align-middle" onclick="handleSorting('email')" data-sort-column="email">
                    Email
                    {!! Request::query('sort_column') === 'email' && Request::query('sort_direction') === 'asc' ? '<i class="fa fa-arrow-up"></i>' : '' !!}
                    {!! Request::query('sort_column') === 'email' && Request::query('sort_direction') === 'desc' ? '<i class="fa fa-arrow-down"></i>' : '' !!}
                </th>
                <th scope="col" class="text-center align-middle" onclick="handleSorting('date_of_birth')" data-sort-column="date_of_birth">
                    Date of Birth
                    {!! Request::query('sort_column') === 'date_of_birth' && Request::query('sort_direction') === 'asc' ? '<i class="fa fa-arrow-up"></i>' : '' !!}
                    {!! Request::query('sort_column') === 'date_of_birth' && Request::query('sort_direction') === 'desc' ? '<i class="fa fa-arrow-down"></i>' : '' !!}
                </th>
                <th scope="col" class="text-center align-middle">Phone Number</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
            <tr class='userInfo'>
                @if($user->id != Auth::user()->id && !$user->is_deleted)
                <td class="text-center align-middle p-0"><a href="{{ route('AdminUsersDetails', ['id' => $user->id]) }}"><img src="{{ $user->user_path == 'def' ?   asset('images/' . $user->user_path . '.png') :asset('storage/images/' . $user->user_path . '.png') }}" alt="User image"></a></td>
                <td id="username" class="text-center align-middle">{{$user->username}}</td>
                <td class="text-center align-middle">{{$user->email}}</td>
                <td class="text-center align-middle">{{$user->date_of_birth}}</td>
                <td class="text-center align-middle">{{$user->phone_number}}</td>
                <td class="text-center align-middle p-0"><div class="d-flex justify-content-center"> @if(in_array($user->id,$blocked))<button class="unblock"><i class="bi bi-lock-fill"></i></button>@else<button class="block"><i class="bi bi-unlock-fill"></i></button>@endif<button class="delete"><i class="bi bi-trash3-fill"></i></button></div></td>
                @endif
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="links">
        {{ $users->links('pagination::bootstrap-5') }}
    </div>
@endsection