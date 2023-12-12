@extends('layouts.app')

@section('styles')
    <link href="{{ url('css/admin.css') }}" rel="stylesheet">
@endsection

@section('scripts')
    <script type="text/javascript" src="{{ URL::asset('js/admin_reviews.js') }}" defer></script>
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

    <h2 class="title">Review Management</h2>
    <table id="admin-reviews" class="table table-striped w-75">
        <thead>
            <tr class='header'>
                <th scope="col" class="text-center align-middle">Date</th>
                <th scope="col" class="text-center align-middle">Reason</th>
                <th scope="col" class="text-center align-middle">User</th>
                <th scope="col" class="text-center align-middle">Review</th>
            </tr>
        </thead>
        <tbody>
            @foreach($reports as $report)
            <tr class='reportInfo'>
                <td class="text-center align-middle">{{$report->report_date}}</td>
                <td class="text-center align-middle">{{$report->reason}}</td>
                @if(!$report->user->is_deleted)
                    <td class="text-center align-middle">{{$report->user->username}}</td>
                @else
                    <td class="text-center align-middle">[deleted-user]</td>
                @endif
                <td class="text-center align-middle">review</td>
                <td id='review_id' style="display: none">{{$report->review_id}}</td>
                <td id='user_id' style="display: none">{{$report->user_id}}</td>
                <td class="text-center align-middle p-0">
                    <div class="d-flex justify-content-center">
                        <button class="erase"><i class="bi bi-x-lg"></i></button>
                        <button class="delete"><i class="bi bi-trash3-fill"></i></button>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
@endsection