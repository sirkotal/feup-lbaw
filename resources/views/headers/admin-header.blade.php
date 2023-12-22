@section('header')
    <header>
        <div class="title-logo">
            <h1>
                <a class="cappuccino" title="Cappuccino Home" href="{{ url('/mainpage') }}">Cappuccino</a>
            </h1>
            <a href="{{ url('/mainpage') }}"><img src="{{ asset('images/image.png') }}" alt="Cappuccino" > </a>
        </div>
        @if (Auth::check())
        <div class="user-logout">
                <a class="logout-button" href="{{ url('/logout') }}"> Logout </a>
                <a href="{{ url('/profile') }}"><img src="{{ auth()->user()->user_path == 'def' ?   asset('images/' . auth()->user()->user_path . '.png') : asset('storage/images/' . auth()->user()->user_path . '.png') }}" alt="User image"></a>
            </div>
        @else
            <a class="login-register" href="{{ url('/login') }}"> Login/Register<i class="fa fa-user"></i></a>
        @endif
    </header>
    <nav>
        <ul class="categories">
            <li><a href="{{ route('admin_users') }}">Users</a></li>
            <li><a href="{{ route('admin_products') }}">Products</a></li>
            <li><a href="{{ route('admin_promotions') }}">Promotions</a></li>
            <li><a href="{{ route('admin_orders') }}">Orders</a></li>
            <li><a href="{{ route('admin_reviews') }}">Reviews</a></li>
            <li><a href="{{ route('Statistics') }}">Statistics</a></li>
        </ul>
    </nav>
@endsection