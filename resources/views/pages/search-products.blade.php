@extends('layouts.app')

@include('headers.header')
@section('header')
    @yield('header')
@endsection

@section('content')
<div class="sort-bar">
    <h1>Search Results</h1>
    <form id="sort-form" action="{{ route('sort.products') }}" method="POST">
        @csrf
        <select name="sort-button" id="sort-button">
            <option value="rating">Rating</option>
            <option value="price">Price</option>
        </select>
    </form>
</div>

<div class="filter-section"> </div>

<div class="searched-products"> <!-- change $products to $searched_products -->
    @foreach($products as $product) 
        <div class="searched-product"> <!-- image is static for now -> testing purposes -->
            <img title="{{ $product->product_name }}" class="product-image-searchpage" src="{{ asset( '/images/products/apples.png' ) }}" alt="{{ '/images/products/apples.png' }}">
            <a href="{{ asset( $product->product_path ) }}"><div class="product-name-searchpage">{{ $product->product_name }}</div></a>
            <div class="product-score-searchpage"><p> @for ($i = 1; $i <= 5; $i++)
                    @if ($i <= $product->reviews->avg('rating'))
                        <i class="fa fa-star checked"></i>
                    @else
                        <i class="fa fa-star"></i>
                    @endif
                @endfor</p></div>
            <div class="product-price-searchpage"> €{{ $product->price }}/un</div>
            <div class="product-button-searchpage">
                @if ($product->stock === 0)
                    <button action="" class="product-out-searchpage" disabled>Out of Stock</button>
                @else
                    <button action="" class="product-cart-searchpage">Add to Cart</button>
                @endif
            </div>
        </div>
    @endforeach
</div>
@endsection