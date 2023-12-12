<?php

namespace App\Http\Controllers;

use App\Models\Discount;
use App\Models\Product;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;

class DiscountController extends Controller
{
    public function addPromotion(Request $request)
    {
        try {
            if (Auth::user()->id == 1){
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

                foreach ($products as $product_name) {
                    $product= Product::where('product_name', $product_name)->first();
                    $product->discount_id = $request->input('id');
                    $product->save();
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

        foreach ($allProducts as $product) {
            if (in_array($product->product_name, $products)) {
                $product->discount_id = $request->input('id');
            } 
            else if ($product->discount_id == $request->input('id')) {
                $product->discount_id = null;
            }        
            $product->save();
        }

        return response()->json(['message' => 'ok']);

    }

    public function deletePromotion(Request $request)
    {
        if (Auth::user()->id == 1) {
            $promotion = Discount::findOrFail($request->input('id'));
            $promotion->delete();
            return redirect()->route('admin_products')->with('success', 'Product deleted successfully!');
        } else {
            return redirect()->back();
        }
    }
}
