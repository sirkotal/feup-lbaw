@section('header')
    @php
        use App\Models\Category;
        $mainCategories = Category::whereNull('parent_category_id')->get();
    @endphp
    <header>
        <div class="title-logo">
            <h1>
                <a title="Cappuccino Home" href="{{ url('/mainpage') }}">Cappuccino</a>
            </h1>
            <a href="{{ url('/mainpage') }}"><img src="{{ asset('images/image.png') }}" alt="Cappucino" > </a>
            <form class="search-container" action="{{ route('showResult') }}" method="GET">
                <input type="text" class="search-input" placeholder="Search..." name="search_query">
                <button class="search-button"><i class="fa fa-search"></i></button>
            </form>
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
    <nav>
        <ul>
            @foreach ($mainCategories as $category)
                <li>
                    <a class="dropdown" href="{{ route('showProducts',$category->id) }}">{{ $category->category_name }}</a>
                    <div class="dropdown-content">
                    @foreach ($category->subcategories as $subcategory)
                            <a class="dropdown" href="{{ route('showProducts',$subcategory->id) }}">{{ $subcategory->category_name }}</a>
                                <div class="dropdown-content-second">
                                @foreach ($subcategory->subcategories as $subsubcategory)
                                    <a href="{{ route('showProducts',$subsubcategory->id) }}">{{ $subsubcategory->category_name }}</a>
                                @endforeach
                                </div>
                    @endforeach
                    </div>
                </li>
            @endforeach
        </ul>
    </nav>
    
@endsection