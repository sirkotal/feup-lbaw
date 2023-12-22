@extends('layouts.app')

@section('styles')
    <link href="{{ url('css/admin.css') }}" rel="stylesheet">
@endsection

@section('title', 'Admin - Reviews')

@section('scripts')
    <script type="text/javascript" src="{{ URL::asset('js/admin_reviews.js') }}" defer></script>
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

    <h2 class="title">Review Management</h2>
    <table id="admin-reviews" class="table table-striped w-75">
        <thead>
            <tr class='header'>
                <th scope="col" class="text-center align-middle" onclick="handleSorting('report_date')">
                    Date
                    {!! Request::query('sort_column') === 'report_date' && Request::query('sort_direction') === 'asc' ? '<i class="fa fa-arrow-up"></i>' : '' !!}
                    {!! Request::query('sort_column') === 'report_date' && Request::query('sort_direction') === 'desc' ? '<i class="fa fa-arrow-down"></i>' : '' !!}
                </th>
                <th scope="col" class="text-center align-middle" onclick="handleSorting('reason')">
                    Reason
                    {!! Request::query('sort_column') === 'reason' && Request::query('sort_direction') === 'asc' ? '<i class="fa fa-arrow-up"></i>' : '' !!}
                    {!! Request::query('sort_column') === 'reason' && Request::query('sort_direction') === 'desc' ? '<i class="fa fa-arrow-down"></i>' : '' !!}
                </th>
                <th scope="col" class="text-center align-middle" onclick="handleSorting('username')">
                    User
                    {!! Request::query('sort_column') === 'username' && Request::query('sort_direction') === 'asc' ? '<i class="fa fa-arrow-up"></i>' : '' !!}
                    {!! Request::query('sort_column') === 'username' && Request::query('sort_direction') === 'desc' ? '<i class="fa fa-arrow-down"></i>' : '' !!}
                </th>
                <th scope="col" class="text-center align-middle" onclick="handleSorting('product_name')">
                    Product
                    {!! Request::query('sort_column') === 'product_name' && Request::query('sort_direction') === 'asc' ? '<i class="fa fa-arrow-up"></i>' : '' !!}
                    {!! Request::query('sort_column') === 'product_name' && Request::query('sort_direction') === 'desc' ? '<i class="fa fa-arrow-down"></i>' : '' !!}
                </th>
                <th scope="col" class="text-center align-middle">Review</th>
            </tr>
        </thead>
        <tbody>
            @foreach($reports as $report)
            <tr class='reportInfo'>
                <td class="text-center align-middle">{{$report->report_date}}</td>
                <td class="text-center align-middle">{{$report->reason}}</td>
                @if(!$report->user->is_deleted)
                    <td class="text-center align-middle"><a href="{{ route('AdminUsersDetails', ['id' => $report->review->user->id]) }}"><img class="mr-2" src="{{ $report->review->user->user_path == 'def' ?   asset('images/' . $report->review->user->user_path . '.png') :asset('storage/images/' . $report->review->user->user_path . '.png') }}">{{$report->review->user->username}}</a></td>
                @else
                    <td class="text-center align-middle">[deleted-user]</td>
                @endif
                <td class="text-center align-middle">
                    @if(file_exists(public_path("storage/products/" . $report->review->product->id . "_1.png")))
                        <img class="mr-2" src="{{ asset('storage/products/' . $report->review->product->id . '_1.png') }}" alt="{{ $report->review->product->product_name }}">
                    @else
                        <img class="mr-2" src="{{ asset('storage/products/def.png') }}" alt="{{ $report->review->product->product_name }}">
                    @endif    
                    <a href="{{ route('showProductDetails', ['id' => $report->review->id]) }}">{{$report->review->product->product_name}}</a>
                </td>
                <td class="review text-center align-middle">review <i class="bi bi-arrow-down-circle-fill"></i></td>
                <td id='review_id' style="display: none">{{$report->review_id}}</td>
                <td id='user_id' style="display: none">{{$report->user_id}}</td>
                <td class="text-center align-middle p-0">
                    <div class="d-flex justify-content-center position-relative">
                        <button class="erase"><i class="bi bi-x-lg"></i></button>
                        <button class="delete"><i class="bi bi-trash3-fill"></i></button>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="links">
        {{ $reports->links('pagination::bootstrap-5') }}
    </div>
    @foreach($reports as $report)
        <div id="review_{{$report->review_id}}_{{$report->user_id}}" class="review_details" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="review_header">
                <div class="d-flex justify-content-between">
                    <div class=" test d-flex justify-content-center mr-2">
                        <h5>Title:</h5>
                        <p class="">{{$report->review->title}}</p>
                    </div>
                    <div class="d-flex justify-content-center">
                        <h5>Date:</h5>
                        <p>{{$report->review->review_date}}</p>
                    </div>
                </div>
                <div class="d-flex justify-content-between">
                    <div class="d-flex justify-content-center">
                        <h5>Rating:</h5>
                        <p>{{$report->review->rating}}</p>
                    </div>
                    <div class="d-flex justify-content-center">
                        <h5>Upvotes:</h5>
                        <p>{{$report->review->upvoters->count()}}</p>
                    </div>
                </div>
            </div>
            <div class="review_body">
                <p class="review_text">{{$report->review->review_text}}</p>
            </div>
        </div>
    @endforeach
@endsection