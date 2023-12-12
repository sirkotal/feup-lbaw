<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>@yield('title')</title>
        <link rel="icon" href="{{ asset('images/icon.png') }}" type="image/x-icon">
        <!-- bootstrap -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
        <!-- Styles -->
        @yield('styles')
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css">
        <link href="{{ asset('css/profile.css') }}" rel="stylesheet">
        <link href="{{ url('css/login.css') }}" rel="stylesheet">
        <link href="{{ url('css/register.css') }}" rel="stylesheet">
        <link href="{{ url('css/constants.css') }}" rel="stylesheet">
        <link href="{{ url('css/mainpage.css') }}" rel="stylesheet">
        <link href="{{ url('css/header.css') }}" rel="stylesheet">
        <link href="{{ url('css/search-products.css') }}" rel="stylesheet">
        <link href="{{ url('css/faq.css') }}" rel="stylesheet">
        <link href="{{ url('css/about.css') }}" rel="stylesheet">
        <link href="{{ url('css/features.css') }}" rel="stylesheet">
        <link href="{{ url('css/notifications.css') }}" rel="stylesheet">
        <link href="{{ url('css/filter.css') }}" rel="stylesheet">

        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        @yield('scripts')
        <script type="text/javascript">
            // Fix for Firefox autofocus CSS bug
            // See: http://stackoverflow.com/questions/18943276/html-5-autofocus-messes-up-css-loading/18945951#18945951
        </script>
        <script type="text/javascript" src="{{ URL::asset('js/header.js') }}" defer></script>
        <script type="text/javascript" src="{{ url('js/cart.js') }}" defer></script>
        <script type="text/javascript" src="{{ url('js/dropdown.js') }}" defer></script>
        <script type="text/javascript" src="{{ url('js/notifications.js') }}" defer></script>
        <script type="text/javascript" src="{{ url('js/wishlist.js') }}" defer></script>
        <script type="text/javascript" src="{{ url('js/filter.js') }}" defer></script>
        <script type="text/javascript" src="{{ url('js/sort.js') }}" defer></script>
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
            <div class="row mx-0 justify-content-center footer-row">
                <div class="col-md-3 text-left">
                    <h5>Quick Links</h5>
                    <ul class="list-unstyled">
                        <li><a href="{{ url('/faq') }}">Frequently Asked Questions</a></li>
                        <li><a href="{{ url('/features') }}">Features</a></li>
                        <li><a href="{{ url('/about') }}">About Us</a></li>
                    </ul>
                </div>
                <div class="col-md-3 text-center">
                    <h5>Payment Methods</h5>
                    <div class="payment-methods">
                        <img src="{{ asset('images/mastercard-logo.png') }}">
                        <img src="{{ asset('images/visa-logo.png') }}">
                        <img src="{{ asset('images/ideal-logo.png') }}">
                        <img src="{{ asset('images/klarna-logo.png') }}">
                        <img src="{{ asset('images/bancontact-logo.png') }}">
                    </div>
                </div>
                <div class="col-md-3 text-right">
                    <h5>Contact Us</h5>
                    <ul class="list-unstyled">
                        <p><i class="fa fa-envelope" aria-hidden="true"></i> geral@cappuccino.com</p>
                        <p><i class="fa fa-phone" aria-hidden="true"></i> +351 912 345 678</p>
                    </ul>
                </div>
            </div>
            <div class="row mx-0 text-center">
                <div class="col">
                    <p class="text-muted">© 2023 Cappuccino Hypermarket</p>
                </div>
            </div>
        </footer>
        <!-- scripts loaded no final do body, como a recomendação no site do bootstrap -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
        <!-- sweet alert 2 -->
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    </body>
</html>