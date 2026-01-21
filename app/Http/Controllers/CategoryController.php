<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Helpers\CacheHelper;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = CacheHelper::getActiveCategories();
            
        return view('pages.categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pages.categories.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'color' => 'nullable|string|max:7',
            'is_active' => 'nullable|boolean',
            'sort_order' => 'nullable|integer',
        ]);

        $category = Category::create($validated);
        
        // Cache'i temizle
        CacheHelper::clearCategoryCache();

        if ($request->expectsJson() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'id' => $category->id,
                'category' => $category,
                'message' => __('common.category_created')
            ]);
        }

        return redirect()->route('categories.index')
            ->with('success', __('common.category_created'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        return view('pages.categories.show', compact('category'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        return view('pages.categories.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'color' => 'nullable|string|max:7',
            'is_active' => 'nullable|boolean',
            'sort_order' => 'nullable|integer',
        ]);

        $category->update($validated);
        
        // Cache'i temizle
        CacheHelper::clearCategoryCache();

        return redirect()->route('categories.index')
            ->with('success', __('common.category_updated'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        $category->delete();
        
        // Cache'i temizle
        CacheHelper::clearCategoryCache();

        return redirect()->route('categories.index')
            ->with('success', __('common.category_deleted'));
    }
}
