@extends('layouts.app')

@include('headers.simple-header')
@section('header')
    @yield('header')
@endsection

@section('styles')
    <link href="{{ asset('css/checkout.css') }}" rel="stylesheet">
@endsection

@section('scripts')
  <script type="text/javascript" src={{ url('js/checkout.js') }} defer>
@endsection

@section('content')
<div class="row">
  <div class="col-75">
    <div class="container">
      <form action="{{ route('createOrder', auth()->id()) }}" method="post">
        {{ csrf_field() }}
        <div class="row">
          <div class="col-50">
            <h3>Billing Address</h3>
            <label for="fname"><i class="fa fa-user"></i> Full Name</label>
            <input type="text" id="fname" name="firstname" placeholder="John M. Doe" required>
            <label for="email_checkout"><i class="fa fa-envelope"></i> Email</label>
            <input type="text" id="email_checkout" name="email_checkout" placeholder="john@example.com" value="{{ $user->email }}" required>
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

          <div class="col-50">
            <h3>Payment</h3>
            <label for="payment_method">Payment Method</label>
            <select id="payment_method" name="payment_method" onchange="togglePaymentFields()" required>
              <option value="Credit Card">Credit Card</option>
              <option value="MBWAY">MBWAY</option>
            </select>

            <div id="credit_card_fields">
              <!-- Fields for Credit Card -->
              <label for="cname">Name on Card</label>
              <input type="text" id="cname" name="cardname" placeholder="John More Doe" required>
              <label for="ccnum">Credit card number</label>
              <input type="text" id="ccnum" name="cardnumber" placeholder="1111-2222-3333-4444" required>
              <label for="expmonth">Exp Month</label>
              <input type="text" id="expmonth" name="expmonth" placeholder="September" required>
              <div class="row">
                <div class="col-50">
                  <label for="expyear">Exp Year</label>
                  <input type="text" id="expyear" name="expyear" placeholder="2018" required>
                </div>
                <div class="col-50">
                  <label for="cvv">CVV</label>
                  <input type="text" id="cvv" name="cvv" placeholder="352" required>
                </div>
              </div>
            </div>

            <div id="mbway_fields" style="display: none;">
              <label for="mbway_phone">Phone Number</label>
              <input type="text" id="mbway_phone" name="mbway_phone" placeholder="Enter your phone number" value="{{ $user->phone_number }}">
            </div>
          </div>

        </div>
        <input type="submit" value="Place Order" class="btn">
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
            <span class="price">{{ $cartItem->price * $cartItem->pivot->quantity }}€ ({{ $cartItem->pivot->quantity }})</span></p>
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
