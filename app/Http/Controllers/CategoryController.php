<?php

namespace App\Http\Controllers;
use App\Models\Category;
use App\Models\Product;
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
    public function show($id)
    {
        $category = Category::where('id', $id)->first();

        if ($category->subcategories()->exists()) {
            $subcategoriesIds = $this->getDescendantIds($category);
            $products = Product::whereHas('categories', function ($query) use ($subcategoriesIds) {
                $query->whereIn('category_id', $subcategoriesIds);
            })->paginate(10);
        } else {
            $products = $category->products()->paginate(10);
        }
        return view('pages.search-category', ['products' => $products,'category' => $category->category_name]);
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
}
