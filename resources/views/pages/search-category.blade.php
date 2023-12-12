@extends('layouts.app')

@section('title', 'Category: ' . $category)

@include('headers.header')

@section('header')
    @yield('header')
@endsection

@section('scripts')
    <script type="text/javascript" src="{{ url('js/sort.js') }}" defer></script>
@endsection

@section('content')
@if (count($products) == 0)
    <div class="no-items"> There are no items from this category yet. </div>
@else
    <div class="featured-products">
    @include('partials.product', ['products' => $products])
    </div>
    <div class="links">
        {{ $products->links('pagination::bootstrap-5') }}
    </div>
@endif
@endsection