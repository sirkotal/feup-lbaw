@extends('layouts.app')

@section('styles')
    <link href="{{ url('css/admin.css') }}" rel="stylesheet">
@endsection

@section('title', 'Admin - Products')

@section('scripts')
    <script type="text/javascript" src="{{ URL::asset('js/admin_products.js') }}" defer></script>
    <script type="text/javascript" src="{{ URL::asset('js/admin.js') }}" defer></script>
@endsection

@include('headers.admin-header')
@section('header')
    @yield('header')
@endsection

@section('content')

@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif

    <h2 class="title">Product Management</h2>
    <table id="admin-products" class="table w-75">
        <thead>
            <tr class='header'>
                <th scope="col" class="text-center align-middle">#</th>
                <th scope="col" class="text-center align-middle" onclick="handleSorting('product_name')">
                    Name
                    {!! Request::query('sort_column') === 'product_name' && Request::query('sort_direction') === 'asc' ? '<i class="fa fa-arrow-up"></i>' : '' !!}
                    {!! Request::query('sort_column') === 'product_name' && Request::query('sort_direction') === 'desc' ? '<i class="fa fa-arrow-down"></i>' : '' !!}
                </th>
                <th scope="col" class="text-center align-middle" onclick="handleSorting('description')">
                    Description
                    {!! Request::query('sort_column') === 'description' && Request::query('sort_direction') === 'asc' ? '<i class="fa fa-arrow-up"></i>' : '' !!}
                    {!! Request::query('sort_column') === 'description' && Request::query('sort_direction') === 'desc' ? '<i class="fa fa-arrow-down"></i>' : '' !!}
                </th>
                <th scope="col" class="text-center align-middle" onclick="handleSorting('extra_information')">
                    Additional Info
                    {!! Request::query('sort_column') === 'extra_information' && Request::query('sort_direction') === 'asc' ? '<i class="fa fa-arrow-up"></i>' : '' !!}
                    {!! Request::query('sort_column') === 'extra_information' && Request::query('sort_direction') === 'desc' ? '<i class="fa fa-arrow-down"></i>' : '' !!}
                </th>
                <th scope="col" class="text-center align-middle" onclick="handleSorting('brand_name')">
                    Brand
                    {!! Request::query('sort_column') === 'brand_name' && Request::query('sort_direction') === 'asc' ? '<i class="fa fa-arrow-up"></i>' : '' !!}
                    {!! Request::query('sort_column') === 'brand_name' && Request::query('sort_direction') === 'desc' ? '<i class="fa fa-arrow-down"></i>' : '' !!}
                </th>
                <th scope="col" class="text-center align-middle">Category</th>
                <th scope="col" class="text-center align-middle" onclick="handleSorting('price')">
                    Price
                    {!! Request::query('sort_column') === 'price' && Request::query('sort_direction') === 'asc' ? '<i class="fa fa-arrow-up"></i>' : '' !!}
                    {!! Request::query('sort_column') === 'price' && Request::query('sort_direction') === 'desc' ? '<i class="fa fa-arrow-down"></i>' : '' !!}
                </th>
                <th scope="col" class="text-center align-middle" onclick="handleSorting('stock')">
                    Stock
                    {!! Request::query('sort_column') === 'stock' && Request::query('sort_direction') === 'asc' ? '<i class="fa fa-arrow-up"></i>' : '' !!}
                    {!! Request::query('sort_column') === 'stock' && Request::query('sort_direction') === 'desc' ? '<i class="fa fa-arrow-down"></i>' : '' !!}
                </th>
            </tr>
        </thead>
        <tbody>
            <tr class='productInfo' id='addProductInfo'>
                <td class="text-center align-middle"></td>
                <td class="text-center align-middle"><input type="text" name="product_name" required></td>
                <td class="text-center align-middle"><input type="text" name="description" required></td>
                <td class="text-center align-middle"><input type="text" name="extra_information" required></td>
                <td class="text-center align-middle">                
                    <select name="brand_name">
                    @foreach($brands as $brand)
                        <option>{{ $brand->brand_name }}</option>
                    @endforeach
                    </select>
                </td>
                <td class="category text-center align-middle">categories <i class="bi bi-arrow-down-circle-fill"></i></td>
                <td class="text-center align-middle"><input type="number" name="price" required></td>
                <td class="text-center align-middle"><input type="number" name="stock" required></td>
                <td id='id' style="display: none">{{ $max_id }}</td>
                <td class="text-center align-middle p-0">
                    <div class="d-flex justify-content-center">
                        <button class="add_product"><i class="bi bi-plus-circle"></i></button>
                    </div>
                </td>
            </tr>
            @foreach($products as $product)
                <tr class='productInfo showProductInfo'>
                    <td class="text-center align-middle">                        
                        @if(file_exists(public_path("storage/products/" . $product->id . "_1.png")))
                            <img src="{{ asset('storage/products/' . $product->id . '_1.png') }}" alt="{{ $product->product_name }}">
                        @else
                            <img src="{{ asset('images/products/default.png') }}" alt="{{ $product->product_name }}">
                        @endif</td>
                    <td class="text-center align-middle"><a href="{{ route('showProductDetails', ['id' => $product->id]) }}">{{ $product->product_name }}</a></td>
                    <td class="text-center align-middle">{{ $product->description }}</td>
                    <td class="text-center align-middle">{{ $product->extra_information }}</td>
                    <td class="text-center align-middle">{{ $product->brand->brand_name }}</td>
                    <td class="category text-center align-middle">categories <i class="bi bi-arrow-down-circle-fill"></i></td>
                    <td class="text-center align-middle">{{ $product->price }}</td>
                    <td class="text-center align-middle">{{ $product->stock }}</td>
                    <td id='id' data-id='{{$product->id}}' style="display: none">{{ $product->id }}</td>
                    <td class="text-center align-middle p-0">
                        <div class="d-flex justify-content-center">
                            <button class="edit_product"><i class="bi bi-pencil-fill"></i></button>
                        </div>
                    </td>
                </tr>
                <tr class='productInfo hidden editProductInfo'>
                    <td class="text-center align-middle">
                        @if(file_exists(public_path("storage/products/" . $product->id . "_1.png")))
                            <img src="{{ asset('storage/products/' . $product->id . '_1.png') }}" alt="{{ $product->product_name }}">
                        @else
                            <img src="{{ asset('images/products/default.png') }}" alt="{{ $product->product_name }}">
                        @endif
                    </td>    
                    <td class="text-center align-middle"><input type="text" name="product_name" value='{{ $product->product_name }}' required></td>
                    <!--<td><input type="text" name="product_name" value='{{ $product->product_name }}' @if ($errors->has('product_name')) placeholder='A name is necessary' @endif required></td> @if ($errors->has('product_name'))
                    <span class="error">
                        {{ $errors->first('product_name') }}
                    </span>
                @endif-->
                    <td class="text-center align-middle"><input type="text" name="description" value='{{ $product->description }}' required></td>
                    <td class="text-center align-middle"><input type="text" name="extra_information" value='{{ $product->extra_information }}' required></td>
                    <td class="text-center align-middle">              
                        <select name="brand_name">
                        @foreach($brands as $brand)
                            @if($product->brand->id == $brand->id)
                                <option selected="selected">{{ $brand->brand_name }}</option>
                            @else
                                <option>{{ $brand->brand_name }}</option>
                            @endif
                        @endforeach
                        </select>
                    </td>
                    <td class="category text-center align-middle">categories <i class="bi bi-arrow-down-circle-fill"></i></td>
                    <td class="text-center align-middle"><input type="number" name="price" value='{{ $product->price }}' required></td>
                    <td class="text-center align-middle"><input type="number" name="stock" value='{{ $product->stock }}' required></td>
                    <td id='id' data-id='{{$product->id}}' style="display: none">{{ $product->id }}</td>
                    <td class="text-center align-middle p-0">
                        <div class="d-flex justify-content-center">
                            <button class="save_edit"><i class="bi bi-floppy-fill"></i></button>
                            <button class="delete_product"><i class="bi bi-trash3-fill"></i></button>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="links">
            {{ $products->links('pagination::bootstrap-5') }}
        </div>
    @foreach($products as $product)
        <div id="product_{{$product->id}}" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-body">
                <h4>Categories</h4>
                <div class='categories_list'>
                @if($product->categories->isEmpty())
                    <div id="alert" class="d-flex justify-content-between">
                        <i class="bi bi-exclamation-triangle-fill mr-3"></i>
                        <p class="mb-0">THIS PRODUCT HAS NO CATEGORIES YET</p>
                    </div>
                @else
                    @foreach($product->categories as $category)
                        <div class="category_bundle d-flex justify-content-between">
                            <p class="categories_names">{{$category->category_name}}</p>
                            <button class='hidden align-middle'><i class="bi bi-trash3-fill"></i></button>
                        </div>
                    @endforeach
                @endif
                </div>
                <div class="add_category d-flex justify-content-between">
                    <select class='hidden' name="category">
                    @foreach($categories as $category)
                        @if(!$product->categories->contains($category))
                            <option>{{ $category->category_name }}</option>
                        @endif
                    @endforeach
                    </select>
                    <button class='hidden'><i class="bi bi-plus-circle"></i></button>
                </div>
            </div>
        </div>
        <div id="photo_{{$product->id}}" class='edit_photos' data-id="{{$product->id}}">
            <i class="bi bi-chevron-left"></i>
            @foreach($photos as $photo)
                @if(substr(basename($photo), 0, strpos(basename($photo), '_')) == $product->id)
                    <img class="hidden" src="{{ asset('storage/products/' .  basename($photo)) }}" alt="{{ basename($photo) }}">
                @endif
            @endforeach
            <p class="hidden">NO PHOTOS ADDED YET</p>
            <i class="bi bi-chevron-right"></i>
            <button class="delete_image"><i class="bi bi-trash3-fill"></i></button>
            <form id='addPhoto' class='d-flex flex-column' method="POST" enctype="multipart/form-data" action="{{ route('adminAddPhoto', ['id' => $product->id]) }}">
                    {{ csrf_field() }}
                    <input id="photo" type="file" name="photo" accept="image/png, image/jpg, image/gif, image/jpeg" required>
                    @if ($errors->has('photo'))
                        <span class="error">
                            {{ $errors->first('photo') }}
                        </span>
                    @endif
                    <button type="submit">
                        Add
                    </button>
            </form>
            <button class="btn close_edit">X</button>
        </div>
    @endforeach
    <div id="product_{{$max_id}}" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-body">
            <h4>Categories</h4>
            <div class='categories_list'>
            </div>
            <div class="add_category d-flex justify-content-between">
                <select name="category">
                @foreach($categories as $category)
                    <option>{{ $category->category_name }}</option>
                @endforeach
                </select>
                <button><i class="bi bi-plus-circle"></i></button>
            </div>
        </div>
    </div>
@endsection