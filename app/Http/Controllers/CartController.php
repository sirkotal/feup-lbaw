<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Models\shoppingCart;
use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
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
        $user = Auth::user();
        $items = $user->shoppingCart;
    
        $totalPrice = 0;

        foreach ($items as $item) {
            $totalPrice += $item->price * $item->pivot->quantity;
        }

        return view('pages.shopping-cart',compact('items', 'totalPrice'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(shoppingCart $shoppingCart)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, shoppingCart $shoppingCart)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(shoppingCart $shoppingCart)
    {
        //
    }

    public function addToShoppingCart($id)
    {

        $this->authorize('create', shoppingCart::Class);
        
        $cartItem = ShoppingCart::where([
            'user_id' => auth()->id(),
            'product_id' => $id,
        ])->first();

        if ($cartItem) {
            $newQuantity = $cartItem->quantity + 1;
        }
        else {
            $newQuantity = 1;
        }

        ShoppingCart::updateOrInsert(
            ['user_id' => auth()->id(), 'product_id' => $id],
            ['quantity' => $newQuantity]
        );

        $user = Auth::user();
        $items = $user->shoppingCart;

        $totalPrice = 0;

        foreach ($items as $item) {
            $totalPrice += $item->price * $item->pivot->quantity;
        }

        return response()->json([
            'success' => true,
            'quantity' => $newQuantity,
            'product_id' => $id,
            'price' => number_format(Product::where('id', $id)->first()->price*$newQuantity,2),
            'total' => number_format($totalPrice,2)
        ]);
    }

    public function removeFromShoppingCart($id)
    {
        $cartItem = ShoppingCart::where([
            'user_id' => auth()->id(),
            'product_id' => $id,
        ])->first();
    
        if ($cartItem) {
            $newQuantity = $cartItem->quantity - 1;
    
            if ($newQuantity <= 0) {
                ShoppingCart::where([
                    'user_id' => auth()->id(),
                    'product_id' => $id,
                ])->delete();
            } else {
                ShoppingCart::where([
                    'user_id' => auth()->id(),
                    'product_id' => $id,
                ])->update(['quantity' => $newQuantity]);
            }
            
            return response()->json([
                'success' => true,
                'quantity' => $newQuantity,
                'product_id' => $id
            ]);
        }
    
        return response()->json([
            'success' => false,
            'error' => 'Item not found in the shopping cart.',
        ], 404);
    }

    public function removeFromCartPage($id)
    {
        $cartItem = ShoppingCart::where([
            'user_id' => auth()->id(),
            'product_id' => $id,
        ])->first();
    
        if ($cartItem && $cartItem->quantity > 1) {

            $newQuantity = $cartItem->quantity - 1;
                ShoppingCart::where([
                    'user_id' => auth()->id(),
                    'product_id' => $id,
                ])->update(['quantity' => $newQuantity]);
            
            $user = Auth::user();
            $items = $user->shoppingCart;

            $totalPrice = 0;

            foreach ($items as $item) {
                $totalPrice += $item->price * $item->pivot->quantity;
            }
            return response()->json([
                'success' => true,
                'quantity' => $newQuantity,
                'product_id' => $id,
                'price' => number_format(Product::where('id', $id)->first()->price*$newQuantity,2),
                'total' => number_format($totalPrice,2)
            ]);
        }
    
        return response()->json([
            'success' => false,
            'error' => 'Cannot remove more items from the cart.',
        ], 404);
    }

    public function deleteFromCart($id) {
        ShoppingCart::where([
            'user_id' => auth()->id(),
            'product_id' => $id,
        ])->delete();
        $user = Auth::user();
        $items = $user->shoppingCart;

        $totalPrice = 0;

        foreach ($items as $item) {
            $totalPrice += $item->price * $item->pivot->quantity;
        }
        
        return back();
    }

    public function getShoppingCartStatus($id)
    {
        $inCart = ShoppingCart::where([
            'user_id' => auth()->id(),
            'product_id' => $id,
        ])->exists();

        $quantity = 0;

        if ($inCart) {
            $quantity = ShoppingCart::where([
                'user_id' => auth()->id(),
                'product_id' => $id,
            ])->value('quantity');
        }

        return response()->json([
            'inCart' => $inCart,
            'quantity' => $quantity,
        ]);
    }
}
