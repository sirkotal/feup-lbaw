<?php

namespace App\Http\Controllers;
use App\Models\Category;
use App\Models\Product;
use App\Models\Brand;
use App\Models\productCategory;
use Illuminate\Http\Request;

class CategoryController extends Controller
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
    public function show(Request $request, $id)
    {
        if (!ctype_digit($id)) {
            abort(404);
        }
        try {
            $category = Category::where('id', $id)->first();
    
            $sortOption = $request->input('sort_option', 'product_name_asc');
            $selectedBrands = $request->input('selected_brands');
            $minPrice = $request->input('min-price');
            $maxPrice = $request->input('max-price');
            $discount = $request->input('selected_discount');
        
            if ($category->subcategories()->exists()) {
                $subcategoriesIds = $this->getDescendantIds($category);
        
                $query = Product::whereHas('categories', function ($query) use ($subcategoriesIds) {
                    $query->whereIn('category_id', $subcategoriesIds);
                });
            } else {
                $query = $category->products();
            }

            $AllBrands = $query->pluck('brand_id')->unique()->map(function ($brandId) {
                return Brand::find($brandId);
            })->sortBy(function ($brand) {
                return strtolower($brand->brand_name);
            });
        
            $brands = explode(',', $selectedBrands);
            $brands = array_map('intval', array_filter($brands));
        
            if (!empty($brands)) {
                $query->whereIn('brand_id', $brands);
            }

            if (!empty($minPrice)) {
                $query->where('price', '>=', $minPrice);
            }
        
            if (!empty($maxPrice)) {
                $query->where('price', '<=', $maxPrice);
            }
        
            if ($request->has('selected_discount') && $request->input('selected_discount') === 'discount') {
                $query->has('discount');
            }
        
            list($sortColumn, $sortDirection) = $this->extractSortOptions($sortOption);

            if($sortColumn == 'avg_rating'){
                $products = $query
                ->select('product.*')
                        ->selectRaw('(SELECT AVG(rating) FROM review WHERE review.product_id = product.id) AS avg_rating')
                        ->orderByRaw("
                            CASE 
                                WHEN (SELECT COUNT(*) FROM review WHERE review.product_id = product.id) > 0 
                                THEN 1 
                                ELSE 0 
                            END DESC")
                ->orderBy($sortColumn, $sortDirection)->paginate(10)->appends([
                    'sort_option' => $sortOption,
                    'brands' => $brands,
                    'min-price' => $minPrice,
                    'max-price' => $maxPrice,
                    'selected_discount' => $discount,
                ]);
            }
            else {
                $products = $query->orderBy($sortColumn, $sortDirection)->paginate(10)->appends([
                    'sort_option' => $sortOption,
                    'brands' => $brands,
                    'min-price' => $minPrice,
                    'max-price' => $maxPrice,
                    'selected_discount' => $discount,
                ]);
            }
        
            return view('pages.search-category', ['products' => $products, 'category' => $category->category_name, 'category_id' => $category->id, 'AllBrands' => $AllBrands]);
        }
        catch (ModelNotFoundException $e) {
            abort(404);
        }
    }

    private function getDescendantIds(Category $category)
    {
        $descendantIds = collect([$category->id]);

        foreach ($category->subcategories as $subcategory) {
            $descendantIds = $descendantIds->merge($this->getDescendantIds($subcategory));
        }

        return $descendantIds->toArray();
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        //
    }
    public function showMainCategories()
    {
        $mainCategories = Category::whereNull('parent_category_id')->get();

        return view('pages.mainpage', compact('mainCategories'));
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
}
