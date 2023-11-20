@extends('layouts.app')

@include('headers.header')
@section('header')
    @yield('header')
@endsection

@section('styles')
    <link href="{{ url('css/product.css') }}" rel="stylesheet">
@endsection

@section('content')
    <div class="product-details">
        <div class="product-image">
            <img src="{{ asset($product->product_path) }}" alt="{{ $product->product_name }}">
        </div>
        <div class="product-details-right">
            <div class="product-name">
                <h1>{{ $product->product_name }}</h1>
            </div>
            <div class="product-price">
                <p>{{ $product->price }}€ /un</p>
            </div>
            <div class="average-rating">
                <p>User Score:</p>
                <p> @for ($i = 1; $i <= 5; $i++)
                    @if ($i <= $product->reviews->avg('rating'))
                        <i class="fa fa-star checked"></i>
                    @else
                        <i class="fa fa-star"></i>
                    @endif
                @endfor</p>
            </div>
            <div class="product-status">
                <p>{{ $product->stock > 0 ? 'Your item is available!' : 'The item is currenly out of stock' }}</p>
            </div>
            @php
                $isUserLoggedIn = Auth::check();
                $quantityInCart = $isUserLoggedIn ? ($shoppingCartEntry ? $shoppingCartEntry->pivot->quantity : 0) : 0;
            @endphp

            <button id="add_to_cart_button" class="add-to-cart" product_id="{{ $product->id }}" style="{{ $quantityInCart > 0 ? 'display: none;' : '' }}">
                <i class="fa fa-shopping-cart"></i> Add to Cart
            </button>
            <div id="quantity_buttons" product_id="{{ $product->id }}" style="{{ $quantityInCart > 0 ? '' : 'display: none;' }}">
                <button id="decrease_quantity" product_id="{{ $product->id }}">-</button>
                <span id="current_quantity">{{ $quantityInCart }}</span>
                <button id="increase_quantity" product_id="{{ $product->id }}">+</button>
            </div>
            <button class="add-to-wishlist"> <i class="fa fa-heart"></i> Add to Wishlist</button>
        </div>
        <div class="product-description">
            <p>Product description</p>
            <p>{{ $product->description }}</p>
        </div>
        <div class="extra-information">
            <p>Additional information:</p>
            <p>
                {{ $product->extra_information }}</p>
        </div>
        <div class="user-reviews">
            <h2>User Reviews</h2>

            @if (Auth::check())
            <!-- missing action -->
                <form class="review-form" method="POST">
                    {{ csrf_field() }}
                    <label for="reviewTitle">Title:</label>
                    <input type="text" name="reviewTitle" required>

                    <label for="reviewRating">Rating (1-5):</label>
                    <input type="number" name="reviewRating" min="1" max="5" required>

                    <label for="reviewText">Review:</label>
                    <textarea name="reviewText" rows="4" required></textarea>

                    <button type="submit">Submit Review</button>
                </form>
            @endif

            @forelse ($product->reviews as $review)
                <div class="review">
                    <p>{{ $review->title }}</p>
                    <p> @for ($i = 1; $i <= 5; $i++)
                    @if ($i <= $review->rating)
                        <i class="fa fa-star checked"></i>
                    @else
                        <i class="fa fa-star"></i>
                    @endif
                @endfor</p>
                    <p>{{ $review->review_text }}</p>
                    <p>{{ $review->user->username }}</p>
                </div>
            @empty
                <p>No reviews available for this product.</p>
            @endforelse
        </div>
    </div>
@endsection