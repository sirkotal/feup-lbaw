<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Wishlist;
use Illuminate\Http\Request;


use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;

class WishlistController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show()
    {
        if(Auth::check()){
            $user = Auth::user();
            $products = $user->wishlist;
            return view('pages.wishlist', compact('products'));
        }
        return view('pages.wishlist');
    }
    public function removeFromWishlist($id)
    {
        $cartItem = Wishlist::where([
            'user_id' => auth()->id(),
            'product_id' => $id,
        ])->first();
        $itemId = $id;
        
        if($cartItem){
            Wishlist::where([
                'user_id' => auth()->id(),
                'product_id' => $id,
            ])->delete();

            return response()->json([
                'success' => true,
                'product_id'=> $itemId,
            ]);
        } 
        return response()->json([
            'success' => false,
        ]);
    }

    public function addToWishlist($id)
    {   
        Wishlist::insert(['user_id' => auth()->id(), 'product_id' => $id]);
        return response()->json([
            'success' => true,
            'product_id'=> $id,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Wishlist $wishlist)
    {
        //
    }
    /**
     * Update the specified resource in storage.
     */

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Wishlist $wishlist)
    {
        //
    }
}
