@extends('layouts.app')

@section('title', $product->product_name)

@include('headers.header')
@section('header')
    @yield('header')
@endsection

@section('styles')
    <link href="{{ url('css/product.css') }}" rel="stylesheet">
@endsection
@section('scripts')
    <script type="text/javascript" src="{{ url('js/review.js') }}" defer></script>
@endsection

@section('content')
    @if(session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                Swal.fire({
                    title: "Success!",
                    text: "{{ session('success') }}",
                    icon: "success",
                    confirmButtonColor: '#00754D'
                    });
            });
        </script>
    @endif

    @if(session('error'))
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                Swal.fire({
                    title: "Oops!",
                    text: "{{ session('error') }}",
                    icon: "error",
                    confirmButtonColor: '#00754D'
                    });
            });
        </script>
    @endif
    <div class="product-details">
        <div class="product-image">
            <img src="{{ asset('images/products/' . $product->id . '.png') }}" alt="{{ $product->product_name }}">
        </div>
        <div class="product-details-right">
            <div class="product-name">
                <h1>{{ $product->product_name }}</h1>
            </div>
            <div class="product-price">
                @if($product->discount)
                <p>{{ number_format($product->price - ($product->price * $product->discount->percentage)/100,2) }}€ /un <span class="notification-product-price" style="color:#333333"> {{  $product->price }}€/un </span></p>
                @else
                <p>{{ $product->price }}€ /un</p>
                @endif
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

            <button product_id="{{ $product->id }}" id="add_to_cart_button" class="add-to-cart" style="{{ $quantityInCart > 0 ? 'display: none;' : '' }}">
                <i class="fa fa-shopping-cart"></i> Add to Cart
            </button>
            <div id="quantity_buttons" product_id="{{ $product->id }}" style="{{ $quantityInCart > 0 ? '' : 'display: none;' }}">
                <button id="decrease_quantity" product_id="{{ $product->id }}">-</button>
                <span id="current_quantity">{{ $quantityInCart }}</span>
                <button id="increase_quantity" product_id="{{ $product->id }}">+</button>
            </div>
            @php 
                $wishlisted = $product->wishlistedBy()->where('user_id', auth()->id())->first();
            @endphp
            <button id="wishlist_remove_{{ $product->id }}" onClick="remove_from_wishlist(this.dataset.productid)" data-productid="{{ $product->id }}" style="{{ $wishlisted ? '' : 'display: none;' }}" class="add-to-wishlist"><i class="fa fa-heart"></i></button>
            <button id="wishlist_add_{{ $product->id }}" onClick="add_to_wishlist(this.dataset.productid)" data-productid="{{ $product->id }}" style="{{ $wishlisted ? 'display: none;' : '' }}" class="add-to-wishlist"><i class="fa fa-heart-o"></i></button>
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

            @if (Auth::check() && !auth()->user()->isBlocked())
                <form class="review-form" action="{{ route('submitReview', ['id' => $product->id]) }}" method="post" onsubmit="return validateRating()">
                    {{ csrf_field() }}
                    <div class="form-group">
                    <label for="title">Review Title</label>
                    <input type="text" class="form-control" id="title" name="title" required>
                    </div>

                    <label for="rating">Rating</label>
                    <div class="star-rating">
                        <i class="fa fa-star" data-rating="1"></i>
                        <i class="fa fa-star" data-rating="2"></i>
                        <i class="fa fa-star" data-rating="3"></i>
                        <i class="fa fa-star" data-rating="4"></i>
                        <i class="fa fa-star" data-rating="5"></i>
                        <input type="hidden" name="rating" id="rating" value="0" required>
                        <div id="ratingError"></div>
                    </div>


                    <div class="form-group">
                        <label for="review">Review Text</label>
                        <textarea class="form-control" id="review" name="review" rows="4" required></textarea>
                    </div>

                    <button type="submit" class="btn btn-primary">Submit Review</button>
                </form>
            @else
                <p id="blocked_warning">You are blocked and cannot submit a review.</p>
            @endif

            @forelse ($product->reviews as $review)
            <div class="card mb-3">
                <div class="card-body">
                    <h5 class="card-title" id="editTitle" >{{ $review->title }}</h5>
                    <input type="text" class="form-control" id="editTitleInput" value="{{ $review->title }}" style="display: none;">
                    <div class="rating" id="editRating">
                        @for ($i = 1; $i <= 5; $i++)
                            @if ($i <= $review->rating)
                                <i class="fa fa-star checked"></i>
                            @else
                                <i class="fa fa-star"></i>
                            @endif
                        @endfor
                    </div>
                    <div class="star-rating-input" id="editRatingInput" style="display: none;">
                        <i class="fa fa-star" data-rating="1"></i>
                        <i class="fa fa-star" data-rating="2"></i>
                        <i class="fa fa-star" data-rating="3"></i>
                        <i class="fa fa-star" data-rating="4"></i>
                        <i class="fa fa-star" data-rating="5"></i>
                        <input type="hidden" name="ratingInput" id="ratingInput" value="0" required>
                    </div>
                    <div id="ratingErrorInput"></div>
                    <p class="card-text" id="editReviewText">{{ $review->review_text }}</p>
                    <textarea class="form-control" id="editReviewTextInput" style="display: none;">{{ $review->review_text }}</textarea>
                    <p class="card-text">
                        <small class="text-muted">{{ $review->user->username }}</small>
                    </p>
                    
                </div>
                <div class="card-footer d-flex justify-content-between align-items-center">
                    <span id="upvoteCount_{{ $review->id }}" class="text-muted">Upvotes: {{ $review->upvoters->count() }}</span>
                    <div class="btn-group">
                        @if(auth()->check() && $review->user_id == auth()->user()->id)
                            <button type="button" id="deleteReviewButton" class="btn btn-danger" onclick="deleteReview('{{ $review->id }}')"><i class="fa fa-trash"></i> Delete</button>
                            <button type="button" id="editButton" class="btn btn-primary" onclick="toggleEditMode()"><i class="fa fa-pencil"></i> Edit</button>
                            <button type="button" class="btn btn-success" id="saveButton" style="display: none;" onclick="saveChanges('{{ $review->id }}')">Save</button>
                            <button type="button" class="btn btn-secondary" id="cancelButton" style="display: none;" onclick="cancelEdit()">Cancel</button>
                        @else

                        <button type="button" class="btn btn-danger"  onclick="reportReview('{{ $review->id }}')"><i class="fa fa-exclamation-triangle"></i></button>
                        <button id="upvoteButton_{{ $review->id }}" type="button" review_id="{{ $review->id }}" class="btn btn-primary {{ auth()->check() && auth()->user()->upvotedReviews->contains($review->id) ? 'liked' : '' }} upvoteButton">
                            <i class="fa {{ auth()->check() && auth()->user()->upvotedReviews->contains($review->id) ? 'fa-thumbs-up' : 'fa-thumbs-o-up' }}"></i>
                        </button>
                        @endif
                    </div>
                </div>
            </div>
            @empty
                <p id="no_reviews">No reviews available for this product.</p>
            @endforelse
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
                    const quantity = cart[{{$product->id}}];
                    const addButton = document.getElementById('add_to_cart_button');
                    const quantityButtons = document.getElementById('quantity_buttons');
                    const quantitySpan = document.getElementById('current_quantity');
    
                    if (addButton && quantityButtons && quantitySpan) {
                        if(quantity > 0) {
                                quantityButtons.style.display = 'flex'; 
                                addButton.style.display = 'none'; 
                            } else {
                                quantityButtons.style.display = 'none'; 
                                addButton.style.display = 'block'; 
                            }
                        quantitySpan.textContent = quantity; 
                    }
            }
    
            document.addEventListener('DOMContentLoaded', () => {
                const cart = getCartFromLocalStorage();
                updateUIFromCart(cart);
            });
        }
    </script>
@endsection