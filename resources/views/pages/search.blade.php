@extends('layouts.app')

@section('title', 'Search: ' . htmlspecialchars($searchQuery))

@include('headers.header')

@section('header')
    @yield('header')
@endsection

@section('content')
@if (count($products) == 0)
    <div class="no-items"> There are no items for your search: "{{ htmlspecialchars($searchQuery) }} ". </div>
@else
<div class="sort-bar">
    <h1>{{ htmlspecialchars($searchQuery) }}</h1>
    <form id="sort-form" action="{{ route('sort.products') }}" method="POST">
        @csrf
        <input type="hidden" name="search_query" value="{{ $searchQuery }}">
        <select name="sort-button" id="sort-button">
            <option value="" disable selected>Select</option>
            <option value="rating">Rating</option>
            <option value="price">Price</option>
        </select>
    </form>
</div>
<div class="test-container">
    <div class="filters">
        <form id="filter-form">
            <label for="brand">Select Brand:</label>
            @foreach($brands as $brand)
                <div class="brand-checkbox">
                    <input type="checkbox" name="brands" value="{{ $brand->brand_name }}">
                    <span>{{ $brand->brand_name }}</span>
                </div>
            @endforeach

            <label for="price">Price Range:</label>
            <input type="text" id="min_price" placeholder="Min Price">
            <input type="text" id="max_price" placeholder="Max Price">
        </form>
    </div>

    <div class="featured-products">
    @foreach($products as $product)
        @php
            $isUserLoggedIn = Auth::check();
            $quantityInCart = $isUserLoggedIn ? ($product->shoppers()->where('user_id', auth()->id())->first() ? $product->shoppers()->where('user_id', auth()->id())->first()->pivot->quantity : 0) : 0;
        @endphp
        <div class="product-card">
        <a href="{{route('showProductDetails',$product->id )}}"><img title="{{ $product->product_name }}" class="product-image-mainpage" src="{{ asset( 'images/products/' . $product->id . '.png' ) }}" alt="{{ $product->product_path }}"></a>
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
                    @php 
                        $wishlisted = $product->wishlistedBy()->where('user_id', auth()->id())->first();
                    @endphp
                    <button id="wishlist_remove_{{ $product->id }}" data-loggedin="true" onClick="remove_from_wishlist(this.dataset.productid)" data-productid="{{ $product->id}}" style="{{ $wishlisted ? '' : 'display: none;' }}" class="product-wishlist-mainpage"><i class="fa fa-heart"></i></button>
                    <button id="wishlist_add_{{ $product->id }}" data-loggedin="true" onClick="add_to_wishlist(this.dataset.productid)" data-productid="{{ $product->id}}" style="{{ $wishlisted ? 'display: none;' : '' }}" class="product-removewishlist-mainpage auth-wishlist-checker"><i class="fa fa-heart-o"></i></button>
                @endif 
            </div>
        </div>
        <script>
            if(!isLoggedIn){
                function getCartFromLocalStorage() {
                    const cart = localStorage.getItem('cart');
                    return cart ? JSON.parse(cart) : {}; 
                }
    
                // Function to update the UI based on the cart data
                function updateUIFromCart(cart) {
                    Object.keys(cart).forEach(productId => {
                        const quantity = cart[productId];
                        const addButton = document.getElementById(`add_to_cart_button_${productId}`);
                        const quantityButtons = document.getElementById(`quantity_buttons_${productId}`);
                        const quantitySpan = document.getElementById(`quantity_${productId}`);
    
                        if (addButton && quantityButtons && quantitySpan) {
                            addButton.style.display = 'none'; 
                            quantityButtons.style.display = ''; 
                            quantitySpan.textContent = quantity; 
                        }
                    });
                }
    
                document.addEventListener('DOMContentLoaded', () => {
                    const cart = getCartFromLocalStorage();
                    updateUIFromCart(cart);
                });
            }
        </script>
    @endforeach
        </div>
    </div>
    <div class="links">
        {{ $products->links('pagination::bootstrap-5') }}
    </div>
    @endif
@endsection