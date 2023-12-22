<?php

namespace App\Http\Controllers;

use App\Models\Discount;
use App\Models\Product;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use App\Events\NotificationsEvent;


use Illuminate\Support\Facades\Auth;

class DiscountController extends Controller
{
    public function addPromotion(Request $request)
    {
        try {
            if (Auth::user()->is_admin){
                $request->validate([
                    'name' => 'required',
                    'start_date' => 'required|date',
                    'end_date' => 'required|date|after:start_date',
                    'percentage' => 'required|numeric|min:0|max:100',
                    'products' => 'required',
                ]);
    
                $promotion = Discount::create([
                    'name' => $request->input('name'),
                    'start_date' => $request->input('start_date'),
                    'end_date' => $request->input('end_date'),
                    'percentage' => $request->input('percentage'),
                ]);
                            
                $products = json_decode($request->input('products'));

                $usersToNotify = [];
                foreach ($products as $product_name) {
                    $product= Product::where('product_name', $product_name)->first();
                    $product->discount_id = $request->input('id');
                    $product->save();
                    $wishlisted= $product->wishlistedBy()->pluck('user_id')->toArray();
                    $usersToNotify = array_merge($usersToNotify, $wishlisted);
                }
                $usersToNotify = array_unique($usersToNotify);
                foreach ($usersToNotify as $userId) {
                    broadcast(new NotificationsEvent($userId));
                }

                return redirect()->route('admin_products')->with('success', 'Product added successfully!');
            } else {
                return redirect()->back();
            }
        } catch (\Exception $e) {
            return redirect()->route('admin_products')->with('error', 'Failed to add product: ' . $e->getMessage());
        }
    }

    /**
     * Updates a Discount.
     */
    public function edit(Request $request)
{   
    $this->authorize('editPromotion', Discount::class);

    $request->validate([
        'name' => 'required',
        'start_date' => 'required|date',
        'end_date' => 'required|date|after:start_date',
        'percentage' => 'required|numeric|min:0|max:100',
        'products' => 'required',
    ]);

    $promotion = Discount::findOrFail($request->input('id'));

    $promotion->update([
        'name' => $request->input('name'),
        'start_date' => $request->input('start_date'),
        'end_date' => $request->input('end_date'),
        'percentage' => $request->input('percentage'),
    ]);

    $allProducts = Product::all();
    $products = json_decode($request->input('products'));
    $usersToNotify = [];

    foreach ($allProducts as $product) {
        if (in_array($product->product_name, $products)) {
            if ($product->discount_id == $request->input('id')) {
                continue;
            }
            $product->discount_id = $request->input('id');
            $wishlisted = $product->wishlistedBy()->distinct()->pluck('user_id')->toArray();
            $usersToNotify = array_merge($usersToNotify, $wishlisted);
        } else if ($product->discount_id == $request->input('id')) {
            $product->discount_id = null;
        }        
        $product->save();
    }

    // Remove duplicate user IDs
    $usersToNotify = array_unique($usersToNotify);

    // Broadcast notifications to each user
    foreach ($usersToNotify as $userId) {
        broadcast(new NotificationsEvent($userId));
    }

    return response()->json(['message' => 'ok']);
}

    public function deletePromotion(Request $request)
    {
        if (Auth::user()->is_admin) {
            $promotion = Discount::findOrFail($request->input('id'));
            $promotion->delete();
            return redirect()->route('admin_products')->with('success', 'Product deleted successfully!');
        } else {
            return redirect()->back();
        }
    }
}
