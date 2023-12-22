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
                <a class="cappuccino" title="Cappuccino Home" href="{{ url('/mainpage') }}">Cappuccino</a>
            </h1>
            <a href="{{ url('/mainpage') }}"><img src="{{ asset('images/image.png') }}" alt="Cappuccino" > </a>
        </div>
        @if (Auth::check())
        <div class="user-logout">
            @if(!Auth()->user()->is_admin)
                <a onClick="notifications()" data-userid="{{ auth()->user()->id }}" id="notifications_icon" title="Notifications" href="#"> <span id="dot" class="dot" style="{{ count($notifications) > 0 ? 'display:inline-block;' : 'display:none;' }}"  ></span> <i class="fa fa-bell"></i></a>
                @include('partials.notifications', ['notifications' => $notifications])
                <a title="Wishlist" href="{{ route('wishlist') }}"><i class="fa fa-bookmark"></i></a>
                <a title="Shopping Cart" href="{{ url('/shopping-cart') }}"><i class="fa fa-shopping-cart"></i></a>
            @endif
                <a onclick="logout()" class="logout-button" href="{{ url('/logout') }}"> Logout </a>
                <a href="{{ url('/profile') }}"><img src="{{ auth()->user()->user_path == 'def' ?   asset('images/' . auth()->user()->user_path . '.png') : asset('storage/images/' . auth()->user()->user_path . '.png') }}" alt="{{auth()->user()->user_path}}"></a>
            </div>
        @else
        <div class="user-logout">
            <a title="Shopping Cart" href="{{ url('/shopping-cart') }}"><i class="fa fa-shopping-cart"></i></a>
            <a class="login-register" href="{{ url('/login') }}"> Login/Register<i class="fa fa-user"></i></a>
        </div>
        @endif
    </header>
@endsection