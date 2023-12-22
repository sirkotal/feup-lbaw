@extends('layouts.app')

@section('title', 'Wishlist')

@include('headers.simple-header')

@section('header')
    @yield('header')
@endsection

@section('content')
@if (count($products) == 0)
    <div class="no-items"><span> You don't have any items in your wishlist.</span><a href="{{ route('mainPage')}}" class="go-search"> Search Products </a></div>
@else
    <h1 class="title">Wishlist</h1>
    <div class="featured-products">
        @foreach($products as $product)
        @php
            $isUserLoggedIn = Auth::check();
            $quantityInCart = $isUserLoggedIn ? ($product->shoppers()->where('user_id', auth()->id())->first() ? $product->shoppers()->where('user_id', auth()->id())->first()->pivot->quantity : 0) : 0;
        @endphp
        <div id="wishlist-item-{{ $product->id }}" class="product-card">
        <a href="{{route('showProductDetails',$product->id )}}"><img title="{{ $product->product_name }}" class="product-image-mainpage" src="{{ file_exists(public_path("storage/products/" . $product->id . "_1.png")) ? asset( 'storage/products/' . $product->id . '_1.png' ) : asset('images/products/default.png') }}" alt="{{ $product->product_path }}"></a>
        <a class="anchor" href="{{route('showProductDetails',$product->id )}}"><div class="product-name-mainpage">{{ $product->product_name }}</div></a>
            <div class="product-description-mainpage">{{ $product->brand->brand_name }}</div>
            <div class="product-score-searchpage"><p> @for ($i = 1; $i <= 5; $i++)
                    @if ($i <= $product->reviews->avg('rating'))
                        <i class="fa fa-star checked"></i>
                    @else
                        <i class="fa fa-star"></i>
                    @endif
                @endfor</p></div>
                @if($product->discount)
                    <div class="product-price-mainpage"> €{{ number_format($product->price - ($product->price * $product->discount->percentage)/100,2) }}/un <span class="notification-product-price"> {{  $product->price }}€/un </span></div>
                @else 
                    <div class="product-price-mainpage"> €{{ $product->price }}/un</div>
                @endif
            @if(Auth::check() && !Auth()->user()->is_admin)
            <div class="product-buttons-mainpage">
                @if ($product->stock === 0)
                    <button class="product-out-mainpage" disabled>Out of Stock</button>
                @else 
                    <button onClick="addToCart(this.dataset.productid)" data-productid="{{ $product->id}}" id="add_to_cart_button_{{ $product->id }}" class="add_to_cart_button product-cart-mainpage" product_id="{{ $product->id }}" style="{{ $quantityInCart > 0 ? 'display: none;' : '' }}">Add to Cart</button>
                @endif
                <div class="quantity_buttons" id="quantity_buttons_{{ $product->id }}" product_id="{{ $product->id }}" style="{{ $quantityInCart > 0 ? '' : 'display: none;' }}">
                    <button onClick="removeDecreaseButton(this.dataset.productid)" data-productid="{{ $product->id}}" class="decrease_quantity">-</button>
                    <span id="quantity_{{ $product->id }}">{{ $quantityInCart }}</span>
                    <button onClick="addIncreaseButton(this.dataset.productid)" data-productid="{{ $product->id}}" class="increase_quantity">+</button>
                </div>
                @if(Auth::check())
                 <button onClick="remove_from_wishlist(this.dataset.productid)" data-productid="{{ $product->id}}" class="product-removewishlist-mainpage"><i class="fa fa-heart"></i></button>
                @endif 
            </div>
            @endif
        </div>
        @endforeach
    </div>
@endif
@endsection