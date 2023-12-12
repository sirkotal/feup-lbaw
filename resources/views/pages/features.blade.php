@extends('layouts.app')

@section('title', 'Features' )

@include('headers.header')
@section('header')
    @yield('header')
@endsection

@section('content')
    <div class="features-page">
        <div class="feature">
            <div class="feature-bar feature-left"></div>
            <div class="feature-content">
                <h2>{{ $productCount }} products available!</h2>
                <p>From fresh produce to pantry staples, household essentials to delectable treats, our shelves boast a diverse selection catering to every need. Experience the ease of finding everything you desire under one roof.</p>
            </div>
        </div>
        
        <div class="feature">
            <div class="feature-bar feature-right"></div>
            <div class="feature-content">
                <h2>A total of {{ $userCount }} users rely on our services!</h2>
                <p>That's why we focus on providing our customers with an efficient and personalized shopping experience - giving you the ability to get what you need without having to commute to a physical supermarket.</p>
            </div>
        </div>
        
        <div class="feature">
            <div class="feature-bar feature-left"></div>
            <div class="feature-content">
                <h2>We are partnered with {{ $brandCount }} brands!</h2>
                <p>Our partnerships reflect our commitment to delivering premium experiences, offering you a wide array of choices across diverse industries. </p>
            </div>
        </div>
        
        <div class="feature">
            <div class="feature-bar feature-right"></div>
            <div class="feature-content">
                <h2>{{ $orderCount }} orders have been placed using Cappuccino!</h2>
                <p>Join the multitude of satisfied customers who've chosen Cappuccino as their trusted online supermarket, where quality meets convenience with every click.</p>
            </div>
        </div>
</div>
@endsection