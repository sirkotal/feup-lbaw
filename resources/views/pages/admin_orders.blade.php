@extends('layouts.app')

@section('styles')
    <link href="{{ url('css/admin.css') }}" rel="stylesheet">
@endsection

@section('scripts')
    <script type="text/javascript" src="{{ URL::asset('js/admin_orders.js') }}" defer></script>
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

    <h2 class="title">Order Management</h2>
    <table id="admin-orders" class="table table-striped w-75">
        <thead>
            <tr class='header'>
                <th scope="col" class="text-center align-middle">Date</th>
                <th scope="col" class="text-center align-middle">Address</th>
                <th scope="col" class="text-center align-middle">User</th>
                <th scope="col" class="text-center align-middle">Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($orders as $order)
            <tr class='orderInfo' id='showOrderInfo'>
                <td class="text-center align-middle">{{$order->order_date}}</td>
                <td class="text-center align-middle">{{$order->address}}</td>
                @if(!$order->user->is_deleted)
                    <td class="text-center align-middle">{{$order->user->username}}</td>
                @else
                    <td class="text-center align-middle">[deleted-user]</td>
                @endif
                <td class="text-center align-middle">{{$order->order_status}}</td>
                <td class="text-center align-middle p-0"><div class="d-flex justify-content-center"><button class="edit_order"><i class="bi bi-pencil-fill"></i></button><div></td>
            </tr>
            <tr class='orderInfo hidden' id='editOrderInfo'>
                <td class="text-center align-middle">{{$order->order_date}}</td>
                <td class="text-center align-middle">{{$order->address}}</td>
                @if(!$order->user->is_deleted)
                    <td class="text-center align-middle">{{$order->user->username}}</td>
                @else
                    <td class="text-center align-middle">[deleted-user]</td>
                @endif
                <td id='id' style="display: none">{{ $order->id }}</td>
                <td class="text-center align-middle">
                    <select name="orders">
                        <option>Waiting for payment</option>
                        <option>Payment Approved</option>
                        <option>Shipping</option>
                        <option>Received</option>
                        @if($order->order_status != 'Shipping' && $order->order_status != 'Received')
                            <option>Canceled</option>
                        @endif
                    </select>
                </td>
                <td class="text-center align-middle p-0"><div class="d-flex justify-content-center"><button class="save_order"><i class="bi bi-floppy-fill"></i></button><div></td>
            </tr>
            @endforeach
        </tbody>
    </table>
@endsection