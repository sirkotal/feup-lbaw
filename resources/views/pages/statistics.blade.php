@extends('layouts.app')

@include('headers.admin-header')
@section('header')
    @yield('header')
@endsection

@section('styles')
    <link href="{{ url('css/statistics.css') }}" rel="stylesheet">
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
    <h2 class="title">Top Users</h2>
    <table id="admin-users" class="table table-striped w-75">
        <thead>
            <tr class='header'>
                <th scope="col" class="text-center align-middle">#</th>
                <th scope="col" class="text-center align-middle" >
                    Username
                </th>
                <th scope="col" class="text-center align-middle" >
                    Email
                </th>
                <th scope="col" class="text-center align-middle" >
                    Date of Birth
                </th>
                <th scope="col" class="text-center align-middle">Phone Number</th>
            </tr>
        </thead>
        <tbody>
            @foreach($topCustomers as $user)
            <tr class='userInfo'>
                @if($user->id != Auth::user()->id && !$user->is_deleted)
                <td class="text-center align-middle p-0"><a href="{{ route('AdminUsersDetails', ['id' => $user->id]) }}"><img src="{{ $user->user_path == 'def' ?   asset('images/' . $user->user_path . '.png') :asset('storage/images/' . $user->user_path . '.png') }}" alt="User image"></a></td>
                <td id="username" class="text-center align-middle">{{$user->username}}</td>
                <td class="text-center align-middle">{{$user->email}}</td>
                <td class="text-center align-middle">{{$user->date_of_birth}}</td>
                <td class="text-center align-middle">{{$user->phone_number}}</td>
                @endif
            </tr>
            @endforeach
        </tbody>
    </table>
    <h2 class="title">Top Sellers</h2>
    <table id="admin-products" class="table w-75">
        <thead>
            <tr class='header'>
                <th scope="col" class="text-center align-middle">
                    Name
                </th>
                <th scope="col" class="text-center align-middle">
                    Description
                </th>
                <th scope="col" class="text-center align-middle">
                    Additional Info
                </th>
                <th scope="col" class="text-center align-middle">
                    Price
                </th>
                <th scope="col" class="text-center align-middle">
                    Stock
                </th>
            </tr>
        </thead>
        <tbody>
            @foreach($favourites as $product)
            @if($product->id != null)
            <tr class='productInfo showProductInfo'>
                <td class="text-center align-middle p-0"><a href="{{ route('showProductDetails', ['id' => $product->id]) }}"><img src="{{ file_exists(public_path("storage/products/" . $product->id . "_1.png")) ? asset( 'storage/products/' . $product->id . '_1.png' ) : asset('images/products/default.png') }}" alt="User image"></a></td>
                <td class="text-center align-middle">{{ $product->product_name }}</td>
                <td class="text-center align-middle">{{ $product->description }}</td>
                <td class="text-center align-middle">{{ $product->extra_information }}</td>
                <td class="text-center align-middle">{{ $product->price }}</td>
                <td class="text-center align-middle">{{ $product->stock }}</td>
                <td id='id' data-id='{{$product->id}}' style="display: none">{{ $product->id }}</td>
            </tr>
            @endif
            @endforeach
        </tbody>
    </table>
@endsection