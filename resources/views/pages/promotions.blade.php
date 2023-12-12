@extends('layouts.app')

@section('title', 'Promotions')

@include('headers.header')

@section('header')
    @yield('header')
@endsection

@section('content')
@if (count($products) == 0)
    <div class="no-items"> There are no items here. </div>
@else
    <h1 class="title">Promotions</h1>
    <div class="featured-products">
    @include('partials.product', ['products' => $products])
    </div>  
    <div class="links">
        {{ $products->links('pagination::bootstrap-5') }}
    </div>
@endif
@endsection