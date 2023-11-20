@extends('layouts.app')

@section('styles')
    <link href="{{ url('css/admin.css') }}" rel="stylesheet">
@endsection

@include('headers.simple-header')
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

<h2>User Management</h2>

    <table id="admin">
        <tr class='header'>
            <th>Username</th>
            <th>Email</th>
            <th>Date of Birth</th>
            <th>Phone Number</th>
        </tr>
        @foreach($users as $user)
        <tr class='userInfo'>
            @if($user->id != Auth::user()->id && !$user->is_deleted)
            <td>{{$user->username}}</td>
            <td>{{$user->email}}</td>
            <td>{{$user->date_of_birth}}</td>
            <td>{{$user->phone_number}}</td>
            <td><div class="features"> @if(in_array($user->id,$blocked))<button class="unblock">unblock</button>@else<button class="block">block</button>@endif<button class="delete"> delete</button></div></td>
            @endif
        </tr>
        @endforeach
    </table>
    <h2>Product Management</h2>

    <form id="add_product_form" action="{{ route('addProduct') }}" method="post">
        @csrf
        <td><input type="text" name="product_name" placeholder="Product Name" required></td>
        <td><input type="text" name="description" placeholder="Description" required></td>
        <td><input type="text" name="extra_information" placeholder="Additional Info" required></td>
        <td><input type="text" name="brand_name" placeholder="Brand" required></td>
        <td><input type="text" name="price" placeholder="Price (€)" required></td>
        <td>
            <select name="category_id" required>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}">{{ $category->category_name }}</option>
                @endforeach
            </select>
        </td>
        <td><input type="number" name="stock" placeholder="Stock" required></td>
        <td>
            <div class="features">
                <button type="submit" class="add">Add</button>
            </div>
        </td>
    </form>

    <table id="admin-products">
        <tr class='header'>
            <th>Name</th>
            <th>Description</th>
            <th>Additional Info</th>
            <th>Brand</th>
            <th>Category</th>
            <th>Price</th>
            <th>Stock</th>
            <th>Actions</th>
        </tr>
        @foreach($products as $product)
            <tr class='productInfo'>
                <td>{{ $product->product_name }}</td>
                <td>{{ $product->description }}</td>
                <td>{{ $product->extra_information }}</td>
                <td>{{ $product->brand->brand_name }}</td>
                <td>{{ $product->categories[0]->category_name }}</td>
                <td>{{ $product->price }}</td>
                <td>{{ $product->stock }}</td>
                <td>
                    <div class="features">
                        <button class="edit_product">Edit</button>
                        <form action="{{ route('adminDeleteProduct', ['id' => $product->id]) }}" method="post">
                            @csrf
                            <button type="submit" class="delete_product">Delete</button>
                        </form>
                    </div>
                </td>
            </tr>
        @endforeach
    </table>
@endsection