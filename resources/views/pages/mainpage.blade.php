@extends('layouts.app')

@section('title', config('app.name', 'Laravel') )

@include('headers.header')
@section('header')
    @yield('header')
@endsection

@section('content')
    @php
        use App\Models\Product;
        $products = Product::take(5)->get();
    @endphp
    <div class="cappucino">
        <p class="simple-description">Welcome!</p>
        <p class="description">The best products at the best prices!</p>
        <a href="{{ route('promotions')}}"><button class="publicity-button">Promotions</button></a>
    </div>
    <div class="featured-products">
    @include('partials.product', ['products' => $products])
    </div>
@endsection
