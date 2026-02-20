<?php

namespace App\Http\Controllers;

use App\Models\MaterialCategory;
use Illuminate\Http\Request;

class MaterialCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = MaterialCategory::orderBy('order')->paginate(15);
        return view('admin.categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.categories.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:material_categories,name',
            'description' => 'nullable|string',
            'order' => 'nullable|integer|min:0',
        ]);

        $category = MaterialCategory::create($validated);

        // Check if it's an AJAX request
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Kategori berhasil ditambahkan!',
                'category' => $category
            ], 201);
        }

        return redirect()->route('categories.index')->with('success', 'Kategori berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(MaterialCategory $materialCategory)
    {
        // Not required for this feature, but included for completeness
        return view('admin.categories.show', compact('materialCategory'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(MaterialCategory $materialCategory)
    {
        return view('admin.categories.edit', compact('materialCategory'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, MaterialCategory $materialCategory)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:material_categories,name,' . $materialCategory->id,
            'description' => 'nullable|string',
            'order' => 'nullable|integer|min:0',
        ]);

        $materialCategory->update($validated);

        return redirect()->route('categories.index')->with('success', 'Kategori berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MaterialCategory $materialCategory)
    {
        // Check if category has materials
        if ($materialCategory->materials()->count() > 0) {
            return redirect()->route('categories.index')->with('error', 'Tidak bisa menghapus kategori yang masih memiliki materi!');
        }

        $materialCategory->delete();

        return redirect()->route('categories.index')->with('success', 'Kategori berhasil dihapus!');
    }
}
