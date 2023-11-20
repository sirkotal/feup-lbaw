@extends('layouts.app')

@include('headers.simple-header')

@section('header')
    @yield('header')
@endsection

@section('styles')
    <link href="{{ url('css/shopping-cart.css') }}" rel="stylesheet">
@endsection

@section('content')
@if (count($items) == 0)
    <div class="no-items"> There are no items in your shopping cart. </div>
@else
<h1 class="cart">Shopping Cart</h1>
<div class="cart-content">
    <div class="cart-items">
        @foreach($items as $item)
        <div class="item-ticket">
            <img class="cart-img" src="{{ $item->product_path }}">
            <div class="item-details">
                <div class="item-name">{{ $item->product_name }}</div>
                <div class="item-quantity">{{ $item->price }}€/un</div>
            </div>
            <div id="quantity_buttons" class="buttons">
                <button id="decrease_button_{{ $item->id}}" class="decrease_quantity cart-button" product_id="{{ $item->id }}" style="{{ $item->pivot->quantity > 1 ? '' : 'display: none;' }}">-</button>
                <div id="unable_{{ $item->id}}" class="unable" style="{{ $item->pivot->quantity > 1 ? 'display: none;' : '' }}">-</div>
                <div id="quantity_{{ $item->id }}" class="current_quantity item-price">{{ $item->pivot->quantity }}</div>
                <button class="increase_quantity cart-button" product_id="{{ $item->id }}">+</button>
            </div>
            <div class="item-price-total"><span id="total_{{ $item->id }}">{{ number_format($item->pivot->quantity * $item->price, 2) }}</span>€</div>
            <form method="POST" action="{{ route('deleteFromCart', $item->id) }}">
                @csrf
                <button class="delete_product" type="submit"><i class="fa fa-trash"></i></button>
            </form>
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
