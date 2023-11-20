@extends('layouts.app')

@include('headers.simple-header')
@section('header')
    @yield('header')
@endsection

@section('content')
<div id='Profile'>
    <div id="Photo">
        <img id="pfp" src="{{ asset('storage/images/' . $user->user_path . '.png') }}" alt="User image">
        <form id='editPhoto' method="POST" enctype="multipart/form-data" action="{{ route('edit_photo') }}">
                {{ csrf_field() }}
                <input id="photo" type="file" name="photo" accept="image/png, image/jpg, image/gif, image/jpeg" required>
                @if ($errors->has('photo'))
                    <span class="error">
                        {{ $errors->first('photo') }}
                    </span>
                @endif
                <button type="submit">
                    Save
                </button>
        </form>
    </div>
    <div class='Data'>
        <div id='Username'>
            <h2>Username</h2>
            <p>{{$user->username}}</p>
        </div>
        <div id='Email'>
            <h2>Email</h2>
            <p>{{$user->email}}</p>
        </div>
        <div id='Birthday'>
            <h2>Birthday</h1>
            <p>{{$user->date_of_birth}}</p>
        </div>
        <div id='Number'>
            <h2>Phone Number</h1>
            <p>{{$user->phone_number}}</p>
        </div>
    </div>
    <table id='history'>
        <tr>
            <th>Date</th>
            <th>Status</th>
            <th>Total</th>
            <th>Address</th>
            <th>Payment Method</th>
            <th>Products</th>
        </tr>
        @foreach($orders as $order)
        <tr>
            <td>{{$order[0]->order_date}}</td>
            <td>{{$order[0]->order_status}}</td>
            <td>{{$order[0]->total}}€</td>
            <td>{{$order[0]->address}}</td>
            <td>{{$order[0]->paymentTransactions->sortDesc()->first()->method}}</td>
            <td><div class="products">
                    <button class='showProducts'>details</button>
                    <div class="list-products">
                        @foreach($order[1] as $product)
                            <a href="{{ route('showProductDetails', ['id' => $product->id]) }}">{{$product->product_name}} - {{$product->pivot->quantity}}</a>
                        @endforeach
                        <button>X</button>
                    </div>
                </div>
            </td>
        </tr>
        @endforeach
    </table>
    <div>
    <a class="edit_profile" href="{{ route('show/edit_profile') }}">Edit Profile</a>
    @if(Auth::user()->id == 1)
    <a class="edit_profile" href="{{ route('admin_page') }}">Admin</a>
    @endif
    </div>
</div>
@endsection