@extends('layouts.app')

@include('headers.simple-header')

@section('title', 'Cart')

@section('header')
    @yield('header')
@endsection

@section('styles')
    <link href="{{ url('css/shopping-cart.css') }}" rel="stylesheet">
@endsection

@section('scripts')
    <script type="text/javascript" src="{{ url('js/cartPage.js') }}" defer></script>
@endsection

@section('content')
@if(!Auth::check())
    <div id="no-items" class="no-items" style="display:none;"><span>There are no items in your shopping cart.</span> <a href="{{ route('mainPage')}}" class="go-search"> Search Products </a></div>
    <div id="cart-content" class="cart-content">
        <div id="cart-items" class="cart-items"></div>
        <div class="cart-checkout" style="display: none;">
            <p>Resume</p>
            <p>Subtotal: <span id="subtotal"></span>€</p>
            <p>Shippment Cost: Free</p>
            <p>Total: <span id="total"></span>€</p>
            <button onClick="redirectToLogin()" id="visitor-checkout">Checkout</button>
        </div>
    </div>
@elseif (count($items) == 0)
    <div class="no-items"> <span>There are no items in your shopping cart.</span><a href="{{ route('mainPage')}}" class="go-search"> Search Products </a></div>
@else
<h1 class="cart">Shopping Cart</h1>
<div class="cart-content">
    <div class="cart-items">
        @foreach($items as $item)
        <div id="product_content_{{ $item->id}}" class="item-ticket">
            <img class="cart-img" src="{{ 'images/products/' . $item->id . '.png' }}">
            <div class="item-details">
                <div class="item-name">{{ $item->product_name }}</div>
                @if($item->discount)
                    <div class="item-quantity">{{ number_format($item->price - ($item->price*$item->discount->percentage)/100,2) }}€/un <span class="notification-product-price"> {{  $item->price }}€/un </span></div>
                @else 
                    <div class="item-quantity"> {{ $item->price }} €/un</div> 
                @endif
            </div>
            <div id="quantity_buttons" class="buttons">
                <button onClick="removeDecreaseButton(this.dataset.productid)" data-productid="{{ $item->id}}" id="decrease_button_{{ $item->id}}" class="decrease_quantity cart-button" style="{{ $item->pivot->quantity > 1 ? '' : 'display: none;' }}">-</button>
                <div id="unable_{{ $item->id}}" class="unable" style="{{ $item->pivot->quantity > 1 ? 'display: none;' : '' }}">-</div>
                <div id="quantity_{{ $item->id }}" class="current_quantity item-price">{{ $item->pivot->quantity }}</div>
                <button onClick="addIncreaseButton(this.dataset.productid)" class="increase_quantity cart-button" data-productid="{{ $item->id}}">+</button>
            </div>
            @if($item->discount)
                <div class="item-price-total"><span id="total_{{ $item->id }}">{{ number_format($item->pivot->quantity * ($item->price - ($item->price*$item->discount->percentage)/100),2) }}</span>€ </div>
            @else
                <div class="item-price-total"><span id="total_{{ $item->id }}">{{ number_format($item->pivot->quantity * $item->price, 2) }}</span>€</div>
            @endif
            <button onClick="removeProductFromCartPage(this.dataset.productid)" data-productid="{{ $item->id}}" class="delete_product" type="submit"><i class="fa fa-trash"></i></button>
        </div>
        @endforeach
    </div>
    <div class="cart-checkout">
        <p>Resume</p>
        <p>Subtotal: <span id="subtotal">{{ number_format($totalPrice,2) }}</span>€</p>
        <p>Shippment Cost: Free</p>
        <p>Total: <span id="total">{{ number_format($totalPrice,2) }}</span>€</p>
        <a href="{{ route('checkoutPage') }}"><button>Checkout</button></a>
    </div>
</div>
@endif
@endsection
