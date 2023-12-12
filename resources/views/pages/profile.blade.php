@extends('layouts.app')

@section('scripts')
    <script type="text/javascript" src="{{ URL::asset('js/profile.js') }}" defer></script>
@endsection

@section('title', 'Profile')

@include('headers.simple-header')
@section('header')
    @yield('header')
@endsection

@section('content')
<div class="container mt-5">
    <div class="row">
    <div class="col-md-8">
            <table class="table" id='history'>
                <thead>
                    <tr>
                        <th scope="col">Date</th>
                        <th scope="col">Status</th>
                        <th scope="col">Total</th>
                        <th scope="col">Address</th>
                        <th scope="col">Payment Method</th>
                        <th scope="col">Products</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orders as $order)
                        <tr>
                            <td>{{ $order[0]->order_date }}</td>
                            <td>{{ $order[0]->order_status }}</td>
                            <td>{{ $order[0]->total }}€</td>
                            <td>{{ $order[0]->address }}</td>
                            <td>{{ $order[0]->paymentTransactions->sortDesc()->first()->method }}</td>
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
                </tbody>
            </table>
        </div>
        <div class="col-md-4">
            <div class="card">
                <img class="card-img-top" src="{{ asset('storage/images/' . $user->user_path . '.png') }}" alt="User image">
                <div class="card-body">
                    <h5 id="Username" class="card-title">{{ $user->username }}</h5>
                    <p class="card-text"><i class="fa fa-envelope" aria-hidden="true"></i>  {{ $user->email }}</p>
                    <p class="card-text"><i class="fa fa-calendar" aria-hidden="true"></i>  {{ $user->date_of_birth }}</p>
                    <p class="card-text"><i class="fa fa-phone" aria-hidden="true"></i>  {{ $user->phone_number }}</p>
                    <a href="{{ route('show/edit_profile') }}" class="btn btn-success">Edit Profile</a>
                    @if(Auth::user()->id == 1)
                        <a href="{{ route('admin_users') }}" class="btn btn-primary">Admin</a>
                    @else
                        <button class="delete btn btn-danger">Delete Profile</button>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
