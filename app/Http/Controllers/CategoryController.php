<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Post;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::withCount('posts')->get();
        return view('categories.index', compact('categories'));
    }

    public function create()
    {
        return view('categories.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
            'description' => 'nullable|string',
        ]);

        Category::create($validated);

        return redirect()->route('categories.index')->with('success', 'Category created successfully.');
    }

    public function edit(Category $category)
    {
        return view('categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        if ($category->id === 10) {
            return back()->with('error', 'Cannot modify the Uncategorized category.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
            'description' => 'nullable|string',
        ]);

        $category->update($validated);

        return redirect()->route('categories.index')->with('success', 'Category updated successfully.');
    }

    public function destroy(Category $category)
    {
        if ($category->id === 10) {
            return back()->with('error', 'Cannot delete the Uncategorized category.');
        }

        $postsCount = Post::where('category_id', $category->id)->count();
        
        if ($postsCount > 0) {
            Post::where('category_id', $category->id)->update(['category_id' => 10]);
        }
        
        $category->delete();
        
        if ($postsCount > 0) {
            return redirect()->route('categories.index')->with('success', "Category deleted. {$postsCount} post(s) moved to 'Uncategorized'.");
        }
        
        return redirect()->route('categories.index')->with('success', 'Category deleted successfully.');
    }
}