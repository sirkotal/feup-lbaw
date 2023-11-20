<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class StaticPageController extends Controller
{
    public function showMainPage()
    {
        $products = Product::take(5)->get();
        $mainCategories = Category::whereNull('parent_category_id')->get();

        return view('pages.mainpage',['products' => $products]);
    }

    public function showFAQPage()
    {
        return view('pages.faq');
    }

    public function showAboutPage()
    {
        return view('pages.about');
    }

    public function showCheckoutPage()
    {
        $this->authorize('showCheckout', Order::class);
        $user = auth()->user();
        $cartItems = $user->shoppingCart;

        $totalCost = $cartItems->sum(function ($item) {
            return $item->pivot->quantity * $item->price;
        });

        $numberOfItems = $cartItems->sum('pivot.quantity');

        $lastOrder = $user->orders()->latest('order_date')->first();

        return view('pages.checkout',compact('cartItems', 'totalCost', 'numberOfItems', 'user', 'lastOrder'));
    }

    public function showSearchedProductsPage()
    {
        /* temporary */
        $products = Product::all();
        $mainCategories = Category::whereNull('parent_category_id')->get();

        return view('pages.search-products',['products' => $products]);
    }

}