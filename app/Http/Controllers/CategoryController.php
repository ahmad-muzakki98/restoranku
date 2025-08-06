<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::all();

        return view('admin.category.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return view('admin.category.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the request data
        $request->validate([
            'cat_name' => 'required|string|max:255',
            'description' => 'required|string|max:255',
        ]);

        // Create a new role
        Category::create($request->all());

        // Redirect to the role index page
        return redirect()->route('categories.index')->with('success', 'Category created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        // Find the category by ID
        $category = Category::findOrFail($id);

        // Return the view with to create a new category
        return view('admin.category.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Validate the request data
        $request->validate([
            'cat_name' => 'required|string|max:255',
            'description' => 'required|string|max:255',
        ]);

        // Find the category and update it
        $category = Category::findOrFail($id);
        $category->update($request->all());

        // Redirect to the category index page
        return redirect()->route('categories.index')->with('success', 'Category updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Find the category and delete it
        $category = Category::findOrFail($id);

        // Delete the category
        $category->delete();

        // Redirect to the category index with a success message
        return redirect()->route('categories.index')->with('success', 'Category deleted successfully.');
    }
}
