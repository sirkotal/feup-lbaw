<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ config('app.name', 'Laravel') }}</title>
        <link rel="icon" href="{{ asset('images/icon.png') }}" type="image/x-icon">
        <!-- Styles -->
        @yield('styles')
        <link href="{{ asset('css/edit_profile.css') }}" rel="stylesheet">
        <link href="{{ asset('css/profile.css') }}" rel="stylesheet">
        <link href="{{ url('css/login.css') }}" rel="stylesheet">
        <link href="{{ url('css/register.css') }}" rel="stylesheet">
        <link href="{{ url('css/constants.css') }}" rel="stylesheet">
        <link href="{{ url('css/mainpage.css') }}" rel="stylesheet">
        <link href="{{ url('css/header.css') }}" rel="stylesheet">
        <link href="{{ url('css/search-products.css') }}" rel="stylesheet">
        <link href="{{ url('css/faq.css') }}" rel="stylesheet">
        <link href="{{ url('css/about.css') }}" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        @yield('scripts')
        <script type="text/javascript">
            // Fix for Firefox autofocus CSS bug
            // See: http://stackoverflow.com/questions/18943276/html-5-autofocus-messes-up-css-loading/18945951#18945951
        </script>
        <script type="text/javascript" src="{{ URL::asset('js/admin.js') }}" defer></script>
        <script type="text/javascript" src="{{ URL::asset('js/profile.js') }}" defer></script>
        <script type="text/javascript" src="{{ url('js/app.js') }}" defer></script>
        <script type="text/javascript" src="{{ url('js/cart.js') }}" defer></script>
        <script type="text/javascript" src="{{ url('js/dropdown.js') }}" defer></script>
    </head>
    <body>
        <main>
            @section('header')
            @show
            <section id="content">
                @yield('content')
            </section>
        </main>
        <footer>
            <div class="static-pages">
                <a href="{{ url('/faq') }}">Frequently Asked Questions </a>
                <a href="{{ url('/features') }}">Features</a>
                <a href="{{ url('/about') }}">About Us</a>
            </div>
            <div class="payment">
                <p> Available Payment Methods</p>
                <div class="payment-methods">
                    <img src="{{ asset('images/mastercard-logo.png') }}">
                    <img src="{{ asset('images/visa-logo.png') }}">
                    <img src="{{ asset('images/mbway-logo.png') }}">
                </div>
            </div>
            <p>© 2023 Cappucino Hypermarket </p>
        </footer>
    </body>
</html>