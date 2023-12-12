<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;

use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use App\Models\User;
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
    public function promotionProducts()
    {
        // Retrieve main categories
        $products = Product::whereNotNull('discount_id')->paginate(10);

        // Pass main categories to the view
        return view('pages.promotions', compact('products'));
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
    public function getProductInfo($id) {
        $product = Product::findOrFail($id);
        return response()->json([
            'success' => true,
            'product_id' => $id,
            'price' => $product->price,
            'product_name' => $product->product_name,
            'discount' => $product->discount ? $product->discount->percentage : 0, 
        ]);
    }

    public function search(Request $request)
    {
        $searchQuery = $request->input('search_query');
        $searchWords = explode(' ', $searchQuery);
        if($searchQuery == ''){
            $products = Product::paginate(5);
        } else { 
            $query = Product::query();

            foreach ($searchWords as $word) {
                $query->whereRaw("product_tsv @@ to_tsquery('english', ?)", [$word]);
            }

            $products = $query->paginate(5);
        }

        $brands = $products->pluck('brand_id')->unique();
        $brands = Brand::whereIn('id', $brands)->get();

        return view('pages.search', ['products' => $products, 'searchQuery' => $searchQuery, 'brands' => $brands]);
    }

    /**
     * Updates a product.
     */
    public function edit(Request $request)
    {   
        $this->authorize('editProduct', User::class);

        $request->validate([
            'product_name' => 'required',
            'description' => 'required',
            'extra_information' => 'required',
            'brand_name' => 'required|exists:brand,brand_name',
            'categories' => 'required',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|numeric|min:0',
        ]);

        $product = Product::findOrFail($request->input('id'));

        $brand = Brand::where('brand_name', $request->input('brand_name'))->first();

        $product->update([
            'product_name' => $request->input('product_name'),
            'description' => $request->input('description'),
            'extra_information' => $request->input('extra_information'),
            'brand_id' => $brand->id,
            'price' => $request->input('price'),
            'stock' => $request->input('stock'),
        ]);

        $categories = json_decode($request->input('categories'));

        $categories_ids = [];
        foreach ($categories as $category_name) {
            $categories_ids[]= Category::where('category_name', $category_name)->get()->value('id');
        }

        $product->categories()->sync($categories_ids);

        return response()->json(['message' => 'ok']);

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
        $searchQuery = $request->input('search_query');

        if ($option === 'price') {
            $sortedProducts = Product::where('product_name', 'ILIKE', '%' . $searchQuery . '%')->orderBy('price')->paginate(5);
        } 
        
        elseif ($option === 'rating') {
            $sortedProducts = Product::where('product_name', 'ILIKE', '%' . $searchQuery . '%')
                ->with('reviews')
                ->withAvg('reviews', 'rating')
                ->orderByDesc('reviews_avg_rating')
                ->get();
        } 
        else {
            $sortedProducts = Product::where('product_name', 'ILIKE', '%' . $searchQuery . '%')->paginate(5);
        }

        $userCart = [];
        if (Auth::check()) {
            $userId = Auth::id();
            $userCart = User::find($userId)->shoppingCart()->pluck('quantity', 'product_id')->toArray();
        }
        else {
            $cartData = $request->input('cart');
            $userCart = json_decode($cartData, true) ?? [];
        }

        $sortedProducts->transform(function ($product) use ($userCart) {
            $productId = $product->id;
            $product->quantityInCart = $userCart[$productId] ?? 0;
            return $product;
        });

        $productsWithRating = $sortedProducts->map(function ($product) {
            $isWishlisted = false;
            if (Auth::check()) {
                $userId = Auth::id();
                $isWishlisted = $product->wishlistedBy()->where('user_id', $userId)->exists();
            }
            return [
                'id' => $product->id,
                'product_name' => $product->product_name,
                'product_path' => $product->product_path,
                'brand' => [
                    'brand_name' => $product->brand->brand_name
                ],
                'price' => $product->price,
                'stock' => $product->stock,
                'avg_rating' => $product->reviews->avg('rating'),
                'wishlisted' => $isWishlisted,
                'quantityInCart' => $product->quantityInCart ?? 0,
                'discount' => $product->discount ? $product->discount->percentage :0,
            ];
        });

        return response()->json(['products' => $productsWithRating, 'option'=> $option]);
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
                    'categories' => 'required',
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
                
                $categories = json_decode($request->input('categories'));

                $categories_ids = [];
                foreach ($categories as $category_name) {
                    $categories_ids[]= Category::where('category_name', $category_name)->get()->value('id');
                }

                $product->categories()->attach($categories_ids);
                $product->save();
    
                return redirect()->route('admin_products')->with('success', 'Product added successfully!');
            } else {
                return redirect()->back();
            }
        } catch (\Exception $e) {
            return redirect()->route('admin_products')->with('error', 'Failed to add product: ' . $e->getMessage());
        }
    }
    

    public function deleteProduct(Request $request)
    {
        if (Auth::user()->id == 1) {
            $product = Product::findOrFail($request->input('id'));
            $product->delete();
            return redirect()->route('admin_products')->with('success', 'Product deleted successfully!');
        } else {
            return redirect()->back();
        }
    }
}
