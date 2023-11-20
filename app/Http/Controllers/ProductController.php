<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;

use App\Models\Product;
use App\Models\Brand;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Retrieve main categories
        $products = Product::take(5)->get();

        // Pass main categories to the view
        return view('pages.mainpage', compact('products'));
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
    public function show($id)
    {
        $product = Product::findOrFail($id);
        if (Auth::check()){
            $userId = auth()->id();
            $userShoppingCart = $product->shoppers()->where('user_id', $userId)->first();
        }
        else{
            $userShoppingCart = 0;
        }
        
        return view('pages.product-details', ['product' => $product, 'shoppingCartEntry' => $userShoppingCart]);
    }

    public function search(Request $request)
    {
        $searchQuery = $request->input('search_query');
        $searchWords = explode(' ', $searchQuery);
        if($searchQuery == ''){
            $products = Product::all();
        } else { 
            $query = Product::query();

            foreach ($searchWords as $word) {
                $query->whereRaw("product_tsv @@ to_tsquery('english', ?)", [$word]);
            }

            $products = $query->get();
        }
        return view('pages.search', compact('products'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        //
    }

    /**
     * Sort the specified resource.
     */
    public function sort(Request $request)
    {
        $option = $request->input('sort-button');

        if ($option === 'price') {
            $sortedProducts = Product::orderBy('price')->get();
        } 
        elseif ($option === 'rating') {
            $sortedProducts = Product::with('reviews')->withAvg('reviews', 'rating')->orderByDesc('reviews_avg_rating')->get();
        } 
        else {
            $sortedProducts = Product::all();
        }

        return response()->json(['products' => $sortedProducts]);
    }

    public function addProduct(Request $request)
    {
        try {
            if (Auth::user()->id == 1){
                $request->validate([
                    'product_name' => 'required',
                    'description' => 'required',
                    'extra_information' => 'required',
                    'brand_name' => 'required',
                    'price' => 'required|numeric|min:0',
                    'category_id' => 'required|exists:category,id',
                    'stock' => 'required|numeric|min:0',
                ]);
    
                $brand = Brand::firstOrCreate(['brand_name' => $request->brand_name]);
    
                $product = Product::create([
                    'product_name' => $request->input('product_name'),
                    'description' => $request->input('description'),
                    'extra_information' => $request->input('extra_information'),
                    'brand_id' => $brand->id,
                    'price' => $request->input('price'),
                    'stock' => $request->input('stock'),
                ]);
    
                $product->categories()->attach($request->input('category_id'));
    
                $product->save();
    
                return redirect()->route('admin_page')->with('success', 'Product added successfully!');
            } else {
                return redirect()->back();
            }
        } catch (\Exception $e) {
            return redirect()->route('admin_page')->with('error', 'Failed to add product: ' . $e->getMessage());
        }
    }
    

    public function deleteProduct(Request $request, $id)
    {
        if (Auth::user()->id == 1) {
            $product = Product::findOrFail($id);
            $product->delete();
            return redirect()->route('admin_page')->with('success', 'Product deleted successfully!');
        } else {
            return redirect()->back();
        }
    }

    public function adminEditProduct(Request $request, $id)
    {
        if (Auth::user()->id == 1) {
            $request->validate([
                'product_name' => 'required',
                'description' => 'required',
                'extra_information' => 'required',
                'brand_name' => 'required',
                'category_id' => 'required|exists:category,id',
                'price' => 'required|numeric|min:0',
                'stock' => 'required|numeric|min:0',
            ]);

            $product = Product::findOrFail($id);

            $brand = Brand::firstOrCreate(['brand_name' => $request->brand_name]);

            $product->update([
                'product_name' => $request->input('product_name'),
                'description' => $request->input('description'),
                'extra_information' => $request->input('extra_information'),
                'brand_id' => $brand->id,
                'price' => $request->input('price'),
                'stock' => $request->input('stock'),
            ]);

            $product->categories()->sync([$request->input('category_id')]);

            return redirect()->route('admin_page')->with('success', 'Product updated successfully!');
        } else {
            return redirect()->back();
        }
    }


}
