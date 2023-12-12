@section('header')
    <header>
        <div class="title-logo">
            <h1>
                <a title="Cappuccino Home" href="{{ url('/mainpage') }}">Cappuccino</a>
            </h1>
            <a href="{{ url('/mainpage') }}"><img src="{{ asset('images/image.png') }}" alt="Cappuccino" > </a>
        </div>
    </header>
@endsection