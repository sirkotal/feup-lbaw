@extends('layouts.app')

@section('title', config('app.name', 'Laravel') )

@include('headers.header')
@section('header')
    @yield('header')
@endsection

@section('content')
@php
    use Illuminate\Support\Facades\Auth;
    use App\Models\Order;
    use App\Models\Product;

    if (Auth::check()) {
        $user = Auth::user();
        $required = 5; 

        $userFavorite = Order::where('user_id', $user->id)
        ->with('products')
        ->get()
        ->flatMap(function ($order) {         // combinar as coisas num único array
            return $order->products;
        })
        ->groupBy('id')
        ->map(function ($product) {
            return [
                'id' => $product->first()->id,
                'total_quantity' => $product->sum('pivot.quantity')
            ];
        })
        ->sortByDesc('total_quantity') 
        ->first();

        $topSeller = Order::with('products')
        ->get()
        ->flatMap(function ($order) {
            return $order->products;
        })
        ->groupBy('id')
        ->map(function ($product) {
            return [
                'id' => $product->first()->id,
                'total_quantity' => $product->sum('pivot.quantity')
            ];
        })
        ->sortByDesc('total_quantity')
        ->first();

        if ($userFavorite) {
            $userFavorite = Product::where('id', $userFavorite['id'])->get();
        }

        if ($topSeller) {
            $topSeller = Product::where('id', $topSeller['id'])->get();
        }

        $lastOrder = Order::where('user_id', $user->id)->with('products')->orderBy('id', 'desc')->first();

        if ($lastOrder) {
            $lastOrderProducts = $lastOrder->products;

            $number = 3;
            $randomizedProducts = $lastOrderProducts->shuffle()->take($number);

            $products = $userFavorite->merge($topSeller)->merge($randomizedProducts);

            $productNum = $products->unique('id')->count();

            if ($productNum < $required) {
                $additionalProducts = Product::whereNotIn('id', $products->pluck('id')->toArray())
                    ->inRandomOrder()
                    ->take($required - $productNum)
                    ->get();

                $products = $products->merge($additionalProducts);
            }
        } 
    }
    else {
        $products = Product::take(5)->get();
    }
@endphp
    <div class="cappucino">
        <p class="simple-description">Welcome!</p>
        <p class="description">The best products at the best prices!</p>
        <a href="{{ route('promotions')}}"><button class="publicity-button">Promotions</button></a>
    </div>
    <div class="featured-products">
    @include('partials.product', ['products' => $products])
    </div>
@endsection
