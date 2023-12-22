@section('header')
    @php
        use App\Models\Category;
        $mainCategories = Category::whereNull('parent_category_id')->get();
        if(Auth::check()) {
        $notifications = auth()->user()->notifications()->where('is_read', false)->get();
        }
    @endphp
    <script>
        var isLoggedIn = {{ Auth::check() ? 'true' : 'false' }};
    </script>
    <header>
        <div class="title-logo">
            <h1>
                <a class="cappuccino" title="Cappuccino Home" href="{{ url('/mainpage') }}">Cappuccino</a>
            </h1>
            <a href="{{ url('/mainpage') }}"><img src="{{ asset('images/image.png') }}" alt="Cappucino" > </a>
            <form class="search-container" action="{{ route('showResult') }}" method="GET">
                <input type="text" class="search-input" placeholder="Search..." name="search_query">
                <button class="search-button" id="reset-checkboxes-button"><i class="fa fa-search"></i></button>
            </form>
        </div>
        @if (Auth::check())
            <div class="user-logout">
                @if(!auth()->user()->is_admin)
                <a onClick="notifications()" data-userid="{{ auth()->user()->id }}" id="notifications_icon" title="Notifications" href="#" style="position: relative;">
                    <span id="dot" class="dot" style="{{ count($notifications) > 0 ? 'display:inline-block;' : 'display:none;' }}"  ></span>
                    <i class="fa fa-bell"></i> 
                </a>
                @include('partials.notifications', ['notifications' => $notifications])
                <a title="Wishlist" href="{{ route('wishlist') }}"><i class="fa fa-bookmark"></i></a>
                <a title="Shopping Cart" href="{{ url('/shopping-cart') }}"><i class="fa fa-shopping-cart"></i></a>
                @endif
                <a onclick="logout()" class="logout-button" href="{{ url('/logout') }}"> Logout </a>
                <a href="{{ url('/profile') }}"><img src="{{ auth()->user()->user_path == 'def' ?   asset('images/' . auth()->user()->user_path . '.png') : asset('storage/images/' . auth()->user()->user_path . '.png') }}" alt="User image"></a>
            </div>
        @else
        <div class="user-logout">
            <a title="Shopping Cart" href="{{ url('/shopping-cart') }}"><i class="fa fa-shopping-cart"></i></a>
            <a class="login-register" href="{{ url('/login') }}"> Login/Register<i class="fa fa-user"></i></a>
        </div>
        @endif
    </header>
    <nav>
        <ul class="categories">
            @foreach ($mainCategories as $category)
                <li>
                    <a class="dropdown" href="{{ route('showProducts',$category->id) }}">{{ $category->category_name }}</a>
                    <div class="dropdown-content">
                    @foreach ($category->subcategories as $subcategory)
                            <a data-categoryid="{{ $subcategory->id }}" class="dropdown" href="{{ route('showProducts',$subcategory->id) }}">{{ $subcategory->category_name }}</a>
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