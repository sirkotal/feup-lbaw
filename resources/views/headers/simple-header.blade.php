@section('header')
    <header>
        <div class="title-logo">
            <h1>
                <a title="Cappuccino Home" href="{{ url('/mainpage') }}">Cappuccino</a>
            </h1>
            <a href="{{ url('/mainpage') }}"><img src="{{ asset('images/image.png') }}" alt="Cappuccino" > </a>
        </div>
        @if (Auth::check())
        <div class="user-logout">
                <a title="Notifications" href="#"><i class="fa fa-bell"></i></a>
                <a title="Wishlist" href="#"><i class="fa fa-bookmark"></i></a>
                <a title="Shopping Cart" href="{{ url('/shopping-cart') }}"><i class="fa fa-shopping-cart"></i></a>
                <a class="logout-button" href="{{ url('/logout') }}"> Logout </a>
                <a href="{{ url('/user') }}"><img src="{{ asset('storage/images/' . auth()->user()->user_path . '.png') }}" alt="User image"></a>
            </div>
        @else
            <a class="login-register" href="{{ url('/login') }}"> Login/Register<i class="fa fa-user"></i></a>
        @endif
    </header>
@endsection