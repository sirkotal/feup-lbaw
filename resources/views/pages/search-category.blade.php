@extends('layouts.app')

@include('headers.header')

@section('header')
    @yield('header')
@endsection

@section('content')
@if (count($products) == 0)
    <div class="no-items"> There are no items from this category yet. </div>
@else
    <div class="featured-products">
    @foreach($products as $product)
        @php
            $isUserLoggedIn = Auth::check();
            $quantityInCart = $isUserLoggedIn ? ($product->shoppers()->where('user_id', auth()->id())->first() ? $product->shoppers()->where('user_id', auth()->id())->first()->pivot->quantity : 0) : 0;
        @endphp
    <div class="product-card">
        <a href="{{route('showProductDetails',$product->id )}}"><img title="{{ $product->product_name }}" class="product-image-mainpage" src="{{ asset( $product->product_path ) }}" alt="{{ $product->product_path }}"></a>
        <a class="anchor" href="{{route('showProductDetails',$product->id )}}"><div class="product-name-mainpage">{{ $product->product_name }}</div></a>
            <div class="product-description-mainpage">{{ $product->brand->brand_name }}</div>
            <div class="product-score-searchpage"><p> @for ($i = 1; $i <= 5; $i++)
                    @if ($i <= $product->reviews->avg('rating'))
                        <i class="fa fa-star checked"></i>
                    @else
                        <i class="fa fa-star"></i>
                    @endif
                @endfor</p></div>
            <div class="product-price-mainpage"> €{{ $product->price }}/un</div>
            <div class="product-buttons-mainpage">
                @if ($product->stock === 0)
                    <button class="product-out-mainpage" disabled>Out of Stock</button>
                @else 
                    <button id="add_to_cart_button_{{ $product->id }}" class="add_to_cart_button product-cart-mainpage" product_id="{{ $product->id }}" style="{{ $quantityInCart > 0 ? 'display: none;' : '' }}">Add to Cart</button>
                @endif
                <div class="quantity_buttons" id="quantity_buttons_{{ $product->id }}" product_id="{{ $product->id }}" style="{{ $quantityInCart > 0 ? '' : 'display: none;' }}">
                    <button class="decrease_quantity" product_id="{{ $product->id }}">-</button>
                    <span id="quantity_{{ $product->id }}">{{ $quantityInCart }}</span>
                    <button class="increase_quantity" product_id="{{ $product->id }}">+</button>
                </div>
                <button class="product-wishlist-mainpage">Add to Wishlist</button>
            </div>
        </div>
    @endforeach
    </div>
@endif
@endsection