@extends('layouts.app')

@include('headers.header')
@section('header')
    @yield('header')
@endsection

@section('content')
<div class="about-header">
    <h1>About Us</h1>
    <p>Welcome to <span>Cappuccino</span> - your ultimate online grocery shopping destination!</p>
</div>
<div id="about-us">
    <div class="about-section">
        <h2>Our Story</h2>
        <p>At Cappuccino, we aim to revolutionize the way people shop for groceries. Our journey began with a simple idea: to make grocery shopping more convenient, efficient, and enjoyable for everyone. We understand the importance of quality products and exceptional service, and we strive to deliver both.</p>
    </div>

    <div class="about-section">
        <h2>Our Mission</h2>
        <p>Our mission is to provide our customers with an accessible and engaging shopping experience. We source the finest products, offer a wide variety of options, and ensure timely delivery to your doorstep. We are committed to exceeding your expectations at every step.</p>
    </div>

    <div class="about-list">
    <h2>Why Choose Cappuccino?</h2>
    <ul>
        <li>Wide range of high-quality products (always)</li>
        <li>Convenient and user-friendly online shopping platform (definitely)</li>
        <li>Reliable and timely delivery (mostly)</li>
        <li>Exceptional customer service (not Carlos)</li>
    </ul>
    </div>
        
    <div class="about-section">
        <h2>Contact Us</h2>
        <p>If you have any questions or suggestions, feel free (not) to contact us:</p>
        <p>Email: betterticketcarlos@cappuccino.com</p>
        <p>Phone: 1-800-PESCATOR</p>
    </div>
</div>
@endsection