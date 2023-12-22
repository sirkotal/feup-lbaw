@extends('layouts.app')

@section('title', 'Checkout' )

@include('headers.simple-header')
@section('header')
    @yield('header')
@endsection

@section('styles')
    <link href="{{ asset('css/checkout.css') }}" rel="stylesheet">
@endsection

@section('content')
<div class="row">
  <div class="col-75">
    <div class="container">
      <form action="{{ route('createOrder') }}" method="post">
        {{ csrf_field() }}
        <div class="row">
          <div class="col-50">
            <h3>User Details</h3>
            <label for="fname"><i class="fa fa-user"></i> Full Name</label>
            <input type="text" id="fname" name="firstname" placeholder="John M. Doe" required>
            <label for="email_checkout"><i class="fa fa-envelope"></i> Email</label>
            <input type="text" id="email_checkout" name="email_checkout" placeholder="john@example.com" value="{{ $user->email }}" required>
            <label for="phone_number">Phone Number</label>
            <input type="text" id="phone_number" name="mbway_phone" placeholder="Enter your phone number" value="{{ $user->phone_number }}">
          </div>

          <div class="col-50">
            <h3>Billing Address</h3>
            <label for="adr"><i class="fa fa-address-card-o"></i> Address</label>
            <input type="text" id="adr" name="address" placeholder="Rua Visconde de Monserrate 48" value="{{ optional($lastOrder)->address }}" required>
            <label for="country"><i class="fa fa-flag"></i> Country</label>
            <input type="text" id="country" name="country" placeholder="Portugal" value="{{ optional($lastOrder)->country }}" required>

            <div class="row">
              <div class="col-50">
                <label for="city">City</label>
                <input type="text" id="city" name="city" placeholder="Porto" value="{{ optional($lastOrder)->city }}" required>
              </div>
              <div class="col-50">
                <label for="zip">Zip</label>
                <input type="text" id="zip" name="zip" placeholder="2710-591" value="{{ optional($lastOrder)->zip_code }}" required>
              </div>
            </div>
          </div>

        </div>
        <input type="submit" value="Pay using Stripe" class="btn">
      </form>
    </div>
  </div>

  <div class="col-25">
    <div class="container">
      <h4>Cart
        <span class="price" style="color:black">
          <i class="fa fa-shopping-cart"></i>
          <b>{{ $numberOfItems }}</b>
        </span>
      </h4>
      @foreach($cartItems as $cartItem)
            <p><a href="{{ route('showProductDetails',$cartItem->id) }}">{{ $cartItem->product_name }}</a> 
            @if($cartItem->discount)
            <span class="price">{{ number_format(($cartItem->price - ($cartItem->price * $cartItem->discount->percentage)/100) * $cartItem->pivot->quantity, 2) }}€ ({{ $cartItem->pivot->quantity }})</span></p>

            @else
            <span class="price">{{ $cartItem->price * $cartItem->pivot->quantity }}€ ({{ $cartItem->pivot->quantity }})</span></p>
            @endif
      @endforeach
      <hr>
      <p>Total <span class="price" style="color:black"><b>{{ $totalCost }}€</b></span></p>
    </div>
      @if ($errors->has('transaction-error'))
          <span class="error">
            {{ $errors->first('transaction-error') }}
          </span>
      @endif
  </div>
</div>
@endsection
