@section('header')
    <header>
        <div class="title-logo">
            <h1>
                <a class="cappuccino" title="Cappuccino Home" href="{{ url('/mainpage') }}">Cappuccino</a>
            </h1>
            <a href="{{ url('/mainpage') }}"><img src="{{ asset('images/image.png') }}" alt="Cappuccino" > </a>
        </div>
    </header>
@endsection