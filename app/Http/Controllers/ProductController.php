<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;

use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use App\Models\User;
use Illuminate\Http\Request;
use App\Events\NotificationsEvent;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

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
        if (!ctype_digit($id)) {
            abort(404);
        }
        
        try {
            $product = Product::findOrFail($id);
        
            $sortBy = request('sort_by', 'date');
        
            if (Auth::check()) {
                $userId = auth()->id();
                $userShoppingCart = $product->shoppers()->where('user_id', $userId)->first();
        
                $userReview = $product->reviews()->where('user_id', $userId)->first();
        
                $reviews = $product->reviews()->orderBySortOption($sortBy)->where('id', '!=', optional($userReview)->id)->paginate(3)->appends(['sort_by' => $sortBy]);
            } else {
                $userShoppingCart = 0;
                $userReview = null;
                $reviews = $product->reviews()->orderBySortOption($sortBy)->paginate(3)->appends(['sort_by' => $sortBy]);
            }
        
            return view('pages.product-details', ['product' => $product, 'shoppingCartEntry' => $userShoppingCart, 'userReview' => $userReview, 'reviews' => $reviews]);
        } catch (ModelNotFoundException $e) {
            abort(404);
        }
    }

    public function getProductInfo($id) {
        $product = Product::findOrFail($id);
        return response()->json([
            'success' => true,
            'product_id' => $id,
            'price' => $product->price,
            'product_name' => $product->product_name,
            'discount' => $product->discount ? $product->discount->percentage : 0, 
            'product_path' => file_exists(public_path("storage/products/" . $product->id . "_1.png")) ? true : false,
        ]);
    }

    public function search(Request $request)
    {
        $searchQuery = $request->input('search_query');
        $sortOption = $request->input('sort_option', 'ts_rank');

        $selectedBrands = $request->input('selected_brands');
        $selectedCategories = $request->input('selected_categories');

        $brands = explode(',', $selectedBrands);
        $brands = array_map('intval', array_filter($brands));

        $categories = explode(',', $selectedCategories);
        $categories = array_map('intval', array_filter($categories));

        $minPrice = $request->input('min-price');
        $maxPrice = $request->input('max-price');
    
        $discount = $request->input('selected_discount');

        list($sortColumn, $sortDirection) = $this->extractSortOptions($sortOption);

        if ($searchQuery === '') {
            $products = Product::orderBy($sortColumn, $sortDirection)->paginate(10);
        } else {
            $searchWords = explode(' ', $searchQuery);

            $query = Product::query();

            foreach ($searchWords as $word) {
                $query->where(function ($query) use ($word) {
                    $query->whereRaw("product_name ILIKE ?", ["%$word%"])
                          ->orWhereHas('brand', function ($query) use ($word) {
                              $query->whereRaw("brand_name ILIKE ?", ["%$word%"]);
                          })
                          ->orWhere('description', 'ILIKE', "%$word%")
                          ->orWhere('extra_information', 'ILIKE', "%$word%");
                });
            }

            $AllBrands = $query->pluck('brand_id')->unique()->map(function ($brandId) {
                return Brand::find($brandId);
            })->sortBy(function ($brand) {
                return strtolower($brand->brand_name);
            });

            $AllCategories = $query
                ->join('productcategory', 'productcategory.product_id', '=', 'product.id')
                ->join('category', 'category.id', '=', 'productcategory.category_id')
                ->pluck('category.id')
                ->unique()
                ->map(function ($categoryId) {
                    return Category::find($categoryId);
                })
                ->sortBy(function ($category) {
                    return strtolower($category->category_name);
                });
            //dd($AllCategories);

            if (!empty($brands)) {
                $query->whereIn('brand_id', $brands);
            }
        
            if (!empty($minPrice)) {
                $query->where('price', '>=', $minPrice);
            }
        
            if (!empty($maxPrice)) {
                $query->where('price', '<=', $maxPrice);
            }
        
            if ($request->has('selected_discount') && $request->input('selected_discount') === 'discount'){
                $query->has('discount');
            }
        
            if (!empty($categories)) {
                $query->whereHas('categories', function ($categoryQuery) use ($categories) {
                    $categoryQuery->whereIn('category.id', $categories);
                });
            }

            if ($sortColumn == 'avg_rating'){
                $products = $query
                    ->select('product.*')
                    ->selectRaw('(SELECT AVG(rating) FROM review WHERE review.product_id = product.id) AS avg_rating')
                    ->orderByRaw("
                        CASE 
                            WHEN (SELECT COUNT(*) FROM review WHERE review.product_id = product.id) > 0 
                            THEN 1 
                            ELSE 0 
                        END DESC,
                        ts_rank(product_tsv, plainto_tsquery('english', ?)) DESC
                    ", [$searchQuery])
                    ->orderBy($sortColumn === 'rating' ? 'avg_rating' : $sortColumn, $sortDirection)
                    ->paginate(10)
                    ->appends([
                        'search_query' => $searchQuery,
                        'sort_option' => $sortOption,
                        'brands' => $brands,
                        'min-price' => $minPrice,
                        'max-price' => $maxPrice,
                        'selected_discount' => $discount,
                        'categories' => $categories,
                    ]);
            }
            elseif ($sortColumn == 'ts_rank'){
                $products = $query
                    ->select('product.*')
                    ->selectRaw('(SELECT AVG(rating) FROM review WHERE review.product_id = product.id) AS avg_rating')
                    ->orderByRaw("ts_rank(product_tsv, plainto_tsquery('english', ?)) DESC", [$searchQuery])
                    ->paginate(10)
                    ->appends([
                        'search_query' => $searchQuery,
                        'sort_option' => $sortOption,
                        'brands' => $brands,
                        'min-price' => $minPrice,
                        'max-price' => $maxPrice,
                        'selected_discount' => $discount,
                        'categories' => $categories,
                    ]);
            }
            else {
                $products = $query
                ->select('product.*')
                ->selectRaw('(SELECT AVG(rating) FROM review WHERE review.product_id = product.id) AS avg_rating')
                ->orderByRaw("ts_rank(product_tsv, plainto_tsquery('english', ?)) DESC", [$searchQuery])
                ->orderBy($sortColumn, $sortDirection)
                ->paginate(10)
                ->appends([
                    'search_query' => $searchQuery,
                    'sort_option' => $sortOption,
                    'brands' => $brands,
                    'min-price' => $minPrice,
                    'max-price' => $maxPrice,
                    'selected_discount' => $discount,
                    'categories' => $categories,
                ]);
            }
        }
    
        return view('pages.search', ['products' => $products, 'searchQuery' => $searchQuery, 'AllBrands' => $AllBrands, 'AllCategories' => $AllCategories]);
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
        if($product->price != $request->input('price') || ($product->stock == 0 && $request->input('stock') != 0) || $request->input('stock') == 1){
            $usersToNotify = [];
                $wishlisted= $product->wishlistedBy()->distinct()->pluck('user_id')->toArray();
                $usersToNotify = array_merge($usersToNotify, $wishlisted);
                $usersToNotify = array_unique($usersToNotify);
                foreach ($usersToNotify as $userId) {
                    broadcast(new NotificationsEvent($userId));
                }
        }

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


    public function addProduct(Request $request)
    {
        try {
            if (Auth::user()->is_admin){
                $request->validate([
                    'product_name' => 'required',
                    'description' => 'required',
                    'extra_information' => 'required',
                    'brand_name' => 'required',
                    'price' => 'required|numeric|min:0',
                    'categories' => 'required',
                    'stock' => 'required|numeric|min:0',
                ]);
                
                $brand = Brand::where(['brand_name' => $request->brand_name])->first();
    
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
        if (Auth::user()->is_admin) {
            $product = Product::findOrFail($request->input('id'));
            $product->delete();
            return redirect()->route('admin_products')->with('success', 'Product deleted successfully!');
        } else {
            return redirect()->back();
        }
    }

    public function addPhoto(Request $request, $product_id){
        $request->validate([
            'photo' => 'required',
        ]);
        $files = Storage::files('public/products');
        $files = array_filter($files, function ($file) use ($product_id) {
            return substr(basename($file), 0, strpos(basename($file), '_')) == $product_id;
        });
        Storage::putFileAs('public/products/', $request->file('photo'), $product_id . '_' . (count($files) + 1) . '.png');
        return redirect()->route('admin_products')->with('success', 'Photo added successfully!');
    }

    public function deletePhoto(Request $request)
    {
        if (Auth::user()->is_admin) {
            $product_id = $request->input('product_id');
            $number = $request->input('number');
            Storage::delete('public/products/' . $product_id . '_' . $number . '.png');
            $files = Storage::files('public/products');
            $files = array_filter($files, function ($file) use ($product_id) {
                return substr(basename($file), 0, strpos(basename($file), '_')) == $product_id;
            });
            $counter = 1;
            foreach ($files as $file){
                Storage::move($file, "public/products/{$product_id}_{$counter}.png");
                $counter +=1;
            }
            return redirect()->route('admin_products')->with('success', 'Photo deleted successfully!');
        } else {
            return redirect()->back();
        }
    }

    private function extractSortOptions($sortOption)
    {
        switch ($sortOption) {
            case 'price_asc':
                return ['price', 'asc'];
            case 'price_desc':
                return ['price', 'desc'];
            case 'rating_asc':
                return ['avg_rating', 'asc'];
            case 'rating_desc':
                return ['avg_rating', 'desc'];
            case 'product_name_asc':
                return ['product_name', 'asc'];
            case 'product_name_desc':
                return ['product_name', 'desc'];
            default:
                return ['ts_rank', 'asc'];
        }
    }

    public function getUniqueBrands($searchQuery)
    {
        $query = Product::query();

        if (!empty($searchQuery)) {
            foreach (explode(' ', $searchQuery) as $word) {
                $query->whereRaw("product_tsv @@ to_tsquery('english', ?)", [$word]);
            }
        }

        return $query->pluck('brand_id')->unique()->map(function ($brandId) {
            return Brand::find($brandId)->brand_name;
        });
    }

    function ensureArray($value)
    {
        return is_array($value) ? $value : [$value];
    }
    
}
