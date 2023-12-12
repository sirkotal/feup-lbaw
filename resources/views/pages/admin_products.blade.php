@extends('layouts.app')

@section('styles')
    <link href="{{ url('css/admin.css') }}" rel="stylesheet">
@endsection

@section('scripts')
    <script type="text/javascript" src="{{ URL::asset('js/admin_products.js') }}" defer></script>
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
                <th scope="col" class="text-center align-middle">Name</th>
                <th scope="col" class="text-center align-middle">Description</th>
                <th scope="col" class="text-center align-middle">Additional Info</th>
                <th scope="col" class="text-center align-middle">Brand</th>
                <th scope="col" class="text-center align-middle">Category</th>
                <th scope="col" class="text-center align-middle">Price</th>
                <th scope="col" class="text-center align-middle">Stock</th>
            </tr>
        </thead>
        <tbody>
            <tr class='productInfo' id='addProductInfo'>        
                <td class="text-center align-middle"><input type="text" name="product_name" required></td>
                <td class="text-center align-middle"><input type="text" name="description" required></td>
                <td class="text-center align-middle"><input type="text" name="extra_information" required></td>
                <td class="text-center align-middle"><input type="text" name="brand_name" required></td>
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
                    <td class="text-center align-middle">{{ $product->product_name }}</td>
                    <td class="text-center align-middle">{{ $product->description }}</td>
                    <td class="text-center align-middle">{{ $product->extra_information }}</td>
                    <td class="text-center align-middle">{{ $product->brand->brand_name }}</td>
                    <td class="category text-center align-middle">categories <i class="bi bi-arrow-down-circle-fill"></i></td>
                    <td class="text-center align-middle">{{ $product->price }}</td>
                    <td class="text-center align-middle">{{ $product->stock }}</td>
                    <td id='id' style="display: none">{{ $product->id }}</td>
                    <td class="text-center align-middle p-0">
                        <div class="d-flex justify-content-center">
                            <button class="edit_product"><i class="bi bi-pencil-fill"></i></button>
                        </div>
                    </td>
                </tr>
                <tr class='productInfo hidden editProductInfo'>
                    
                    <td class="text-center align-middle"><input type="text" name="product_name" value='{{ $product->product_name }}' required></td>
                    <!--<td><input type="text" name="product_name" value='{{ $product->product_name }}' @if ($errors->has('product_name')) placeholder='A name is necessary' @endif required></td> @if ($errors->has('product_name'))
                    <span class="error">
                        {{ $errors->first('product_name') }}
                    </span>
                @endif-->
                    <td class="text-center align-middle"><input type="text" name="description" value='{{ $product->description }}' required></td>
                    <td class="text-center align-middle"><input type="text" name="extra_information" value='{{ $product->extra_information }}' required></td>
                    <td class="text-center align-middle"><input type="text" name="brand_name" value='{{ $product->brand->brand_name }}' required></td>
                    <td class="category text-center align-middle">categories <i class="bi bi-arrow-down-circle-fill"></i></td>
                    <td class="text-center align-middle"><input type="number" name="price" value='{{ $product->price }}' required></td>
                    <td class="text-center align-middle"><input type="number" name="stock" value='{{ $product->stock }}' required></td>
                    <td id='id' style="display: none">{{ $product->id }}</td>
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
                <div class='categories_list'>
                @foreach($product->categories as $category)
                    <div class="category_bundle d-flex justify-content-between">
                        <p>{{$category->category_name}}</p>
                        <button class='hidden align-middle'><i class="bi bi-trash3-fill"></i></button>
                    </div>
                @endforeach
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
    @endforeach
    <div id="product_{{$max_id}}" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-body">
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