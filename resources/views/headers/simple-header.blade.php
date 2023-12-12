@section('header')
    <script>
        var isLoggedIn = {{ Auth::check() ? 'true' : 'false' }};
    </script>
    @php
        if(Auth::check()) {
        $notifications = auth()->user()->notifications()->where('is_read', false)->get();
        }
    @endphp
    <header>
        <div class="title-logo">
            <h1>
                <a title="Cappuccino Home" href="{{ url('/mainpage') }}">Cappuccino</a>
            </h1>
            <a href="{{ url('/mainpage') }}"><img src="{{ asset('images/image.png') }}" alt="Cappuccino" > </a>
        </div>
        @if (Auth::check())
        <div class="user-logout">
                <a onClick="notifications()" id="notifications_icon" title="Notifications" href="#"> @if(count($notifications) > 0)<span class="dot"></span> @endif<i class="fa fa-bell"></i></a>
                @include('partials.notifications', ['notifications' => $notifications])
                <a title="Wishlist" href="{{ route('wishlist') }}"><i class="fa fa-bookmark"></i></a>
                <a title="Shopping Cart" href="{{ url('/shopping-cart') }}"><i class="fa fa-shopping-cart"></i></a>
                <a class="logout-button" href="{{ url('/logout') }}"> Logout </a>
                <a href="{{ url('/profile') }}"><img src="{{ asset('storage/images/' . auth()->user()->user_path . '.png') }}" alt="User image"></a>
            </div>
        @else
        <div class="user-logout">
            <a title="Shopping Cart" href="{{ url('/shopping-cart') }}"><i class="fa fa-shopping-cart"></i></a>
            <a class="login-register" href="{{ url('/login') }}"> Login/Register<i class="fa fa-user"></i></a>
        </div>
        @endif
    </header>
@endsection