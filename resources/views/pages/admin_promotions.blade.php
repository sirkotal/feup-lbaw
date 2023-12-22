@extends('layouts.app')

@section('styles')
    <link href="{{ url('css/admin.css') }}" rel="stylesheet">
@endsection

@section('title', 'Admin - Promotions')

@section('scripts')
    <script type="text/javascript" src="{{ URL::asset('js/admin_promotions.js') }}" defer></script>
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

<h2 class="title">Promotion Management</h2>
    <table id="admin-promotions" class="table w-75">
        <thead>
            <tr class='header'>
                <th scope="col" class="text-center align-middle" onclick="handleSorting('name')">
                    Name
                    {!! Request::query('sort_column') === 'name' && Request::query('sort_direction') === 'asc' ? '<i class="fa fa-arrow-up"></i>' : '' !!}
                    {!! Request::query('sort_column') === 'name' && Request::query('sort_direction') === 'desc' ? '<i class="fa fa-arrow-down"></i>' : '' !!}
                </th>
                <th scope="col" class="text-center align-middle" onclick="handleSorting('start_date')">
                    Begin
                    {!! Request::query('sort_column') === 'start_date' && Request::query('sort_direction') === 'asc' ? '<i class="fa fa-arrow-up"></i>' : '' !!}
                    {!! Request::query('sort_column') === 'start_date' && Request::query('sort_direction') === 'desc' ? '<i class="fa fa-arrow-down"></i>' : '' !!}
                </th>
                <th scope="col" class="text-center align-middle" onclick="handleSorting('end_date')">
                    End
                    {!! Request::query('sort_column') === 'end_date' && Request::query('sort_direction') === 'asc' ? '<i class="fa fa-arrow-up"></i>' : '' !!}
                    {!! Request::query('sort_column') === 'end_date' && Request::query('sort_direction') === 'desc' ? '<i class="fa fa-arrow-down"></i>' : '' !!}
                </th>
                <th scope="col" class="text-center align-middle" onclick="handleSorting('percentage')">
                    Percentage
                    {!! Request::query('sort_column') === 'percentage' && Request::query('sort_direction') === 'asc' ? '<i class="fa fa-arrow-up"></i>' : '' !!}
                    {!! Request::query('sort_column') === 'percentage' && Request::query('sort_direction') === 'desc' ? '<i class="fa fa-arrow-down"></i>' : '' !!}
                </th>
                <th scope="col" class="text-center align-middle">Products</th>
            </tr>
        </thead>
        <tbody>
            <tr class='promotionInfo' id='addPromotionInfo'>        
                    <td class="text-center align-middle"><input type="text" name="name" required></td>
                    <td class="text-center align-middle"><input type="date" name="start_date" required></td>
                    <td class="text-center align-middle"><input type="date" name="end_date" required></td>
                    <td class="text-center align-middle"><input type="number" min="0" max="100" name="percentage" required></td>
                    <td class="products text-center align-middle">products <i class="bi bi-arrow-down-circle-fill"></i></td>
                    <td id='id' style="display: none">{{$max_id}}</td>
                    <td class="text-center align-middle p-0">
                        <div class="d-flex justify-content-center">
                            <button class="add_promotion"><i class="bi bi-plus-circle"></i></button>
                        </div>
                    </td>
                </tr>
            @foreach($promotions as $promotion)
                <tr class='promotionInfo showPromotionInfo'>
                    <td class="text-center align-middle">{{ $promotion->name }}</td>
                    <td class="text-center align-middle">{{ $promotion->start_date }}</td>
                    <td class="text-center align-middle">{{ $promotion->end_date }}</td>
                    <td class="text-center align-middle">{{ $promotion->percentage }} %</td>
                    <td class="products text-center align-middle">products <i class="bi bi-arrow-down-circle-fill"></i></td>
                    <td id='id' style="display: none">{{ $promotion->id }}</td>
                    <td class="text-center align-middle p-0">
                        <div class="d-flex justify-content-center">
                            <button class="edit_promotion"><i class="bi bi-pencil-fill"></i></button>
                        </div>
                    </td>
                </tr>
                <tr class='promotionInfo hidden editPromotionInfo'>
                    <td class="text-center align-middle"><input type="text" name="name" value='{{ $promotion->name }}' required></td>
                    <td class="text-center align-middle"><input type="text" name="start_date" value='{{ $promotion->start_date }}' required></td>
                    <td class="text-center align-middle"><input type="text" name="end_date" value='{{ $promotion->end_date }}' required></td>
                    <td class="text-center align-middle"><input type="text" name="percentage" value='{{ $promotion->percentage }}' required></td>
                    <td class="products text-center align-middle">products <i class="bi bi-arrow-down-circle-fill"></i></td>
                    <td id='id' style="display: none">{{ $promotion->id }}</td>
                    <td class="text-center align-middle p-0">
                        <div class="d-flex justify-content-center">
                            <button class="save_promotion"><i class="bi bi-floppy-fill"></i></button>
                            <button class="delete_promotion"><i class="bi bi-trash3-fill"></i></button>
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div class="links">
        {{ $promotions->links('pagination::bootstrap-5') }}
    </div>
    @foreach($promotions as $promotion)
        <div id="promotion_{{$promotion->id}}" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-body">
                <h4>Products</h4>
                <div class='products_list'>
                @if($promotion->products->isEmpty())
                    <div id="alert" class="d-flex justify-content-between">
                        <i class="bi bi-exclamation-triangle-fill mr-3"></i>
                        <p class="mb-0">THIS PROMOTION HAS NO PRODUCTS YET</p>
                    </div>
                @else
                    @foreach($promotion->products as $product)
                        <div class="product_bundle d-flex justify-content-between">
                            <p class="products_names">{{$product->product_name}}</p>
                            <button class='hidden align-middle'><i class="bi bi-trash3-fill"></i></button>
                        </div>
                    @endforeach
                @endif
                </div>
                <div class="add_product d-flex justify-content-between">
                    <select class='hidden' name="product">
                    @foreach($products as $product)
                        @if(!$promotion->products->contains($product))
                            <option>{{ $product->product_name }}</option>
                        @endif
                    @endforeach
                    </select>
                    <button class='hidden'><i class="bi bi-plus-circle"></i></button>
                </div>
            </div>
        </div>
    @endforeach
    <div id="promotion_{{$max_id}}" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-body">
            <h4>Products</h4>
            <div class='products_list'>
            </div>
            <div class="add_product d-flex justify-content-between">
                <select name="product">
                    @foreach($products as $product)
                        <option>{{ $product->product_name }}</option>
                    @endforeach
                </select>
                <button><i class="bi bi-plus-circle"></i></button>
            </div>
        </div>
    </div>
@endsection