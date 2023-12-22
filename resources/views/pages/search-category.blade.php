@extends('layouts.app')

@section('title', 'Category: ' . $category)

@include('headers.header')

@section('header')
    @yield('header')
@endsection

@php
    $sortOption = request()->input('sort_option', 'product_name_asc');
    $selectedBrands = request()->input('selected_brands');
    $discount = request()->input('discount');
@endphp

@section('content')
<div class="sort-bar">
    <form id="sort-form" action="{{ route('showProducts', ['id' => $category_id]) }}" method="GET">
        {{ csrf_field() }}
        <input type="hidden" name="selected_brands" value="">
        <input type="hidden" name="selected_categories" value="">
        <input type="hidden" name="min-price" value="">
        <input type="hidden" name="max-price" value="">
        <input type="hidden" name="selected_discount" value="">
        <select name="sort_option" id="sort-option" onchange="this.form.submit()">
            <option value="" disabled>Select</option>
            <option value="price_asc" {{ $sortOption === 'price_asc' ? 'selected' : '' }}>By Price (Low to High)</option>
            <option value="price_desc" {{ $sortOption === 'price_desc' ? 'selected' : '' }}>By Price (High to Low)</option>
            <option value="rating_asc" {{ $sortOption === 'rating_asc' ? 'selected' : '' }}>By Rating (Low to High)</option>
            <option value="rating_desc" {{ $sortOption === 'rating_desc' ? 'selected' : '' }}>By Rating (High to Low)</option>
            <option value="product_name_asc" {{ $sortOption === 'product_name_asc' ? 'selected' : '' }}>By Product Name (A to Z)</option>
            <option value="product_name_desc" {{ $sortOption === 'product_name_desc' ? 'selected' : '' }}>By Product Name (Z to A)</option>
        </select>
    </form>
</div>
    <div class="test-container">
        <div class="filters">
            <form id="filter-form" action="{{ route('showProducts', ['id' => $category_id]) }}" method="GET">
                <input type="hidden" name="selected_brands" id="selected-brands" value="">
                <input type="hidden" name="selected_categories" value="">
                <input type="hidden" name="min-price" value="">
                <input type="hidden" name="max-price" value="">
                <input type="hidden" name="selected_discount" value="">
                <label for="brand">Brands:</label>
                @foreach($AllBrands as $brand)
                <div class="brand-checkbox" style="{{ $loop->index >= 5 ? 'display: none;' : '' }}">
                    <input type="checkbox" value="{{ $brand->id }}">
                    <span class="checkmark">{{ $brand->brand_name }}</span>
                </div>
                @endforeach

                <div class="view-more" id="view-more-brands">
                    <span>Show more...</span>
                </div>
                

                <label for="price">Price Range:</label>
                <input type="text" id="min-price" name="min-price" placeholder="Min Price">
                <input type="text" id="max-price" name="max-price" placeholder="Max Price">

                <label for="discount">Product:</label>
                <div class="discount-filter">
                    <div class="discount-option">
                        <input type="checkbox" value="discount">
                        <span>Discounted</span>
                    </div>
                </div>
                <div class="filter_buttons_container">
                    <button type="submit" class="btn btn-primary" onclick="setPrices()">Apply Filters</button>
                    <button type="button" class="btn btn-secondary" id="reset-filters-button">Reset Filters</button>
                </div>
            </form>
        </div>
<div class="featured-products">
    @if (count($products) == 0)
    <div class="no-items"> There are no items that match your search. </div>
    @endif
    @include('partials.product', ['products' => $products])
</div>
</div>
<div class="links">
    {{ $products->links('pagination::bootstrap-5') }}
</div>
@endsection