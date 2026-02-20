<?php

namespace App\Http\Controllers;

use App\Models\Material;
use App\Models\MaterialCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MateriBelajarController extends Controller
{
    /**
     * Display a listing of materi pembelajaran (admin).
     */
    public function index(Request $request)
    {
        $query = Material::query();

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($category = $request->input('category')) {
            $query->where('category_id', $category);
        }

        if ($status = $request->input('status')) {
            if ($status === 'published') {
                $query->where('is_published', true);
            } elseif ($status === 'draft') {
                $query->where('is_published', false);
            }
        }

        $sort = $request->input('sort', 'terbaru');
        switch ($sort) {
            case 'terlama':
                $query->oldest();
                break;
            case 'judul_az':
                $query->orderBy('title', 'asc');
                break;
            case 'judul_za':
                $query->orderBy('title', 'desc');
                break;
            default:
                $query->latest();
                break;
        }

        $materials = $query->paginate(12)->withQueryString();
        $categories = MaterialCategory::orderBy('order')->get();
        return view('admin.materi.index', compact('materials', 'categories'));
    }

    /**
     * Show the form for creating a new materi.
     */
    public function create()
    {
        $categories = MaterialCategory::orderBy('order')->get();
        return view('admin.materi.create', compact('categories'));
    }

    /**
     * Store a newly created materi in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category_id' => 'required|exists:material_categories,id',
            'file_path' => 'required|file|mimes:pdf|max:51200', // 50MB
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_published' => 'boolean',
        ]);

        // Store PDF file
        $filePath = null;
        if ($request->hasFile('file_path')) {
            $filePath = $request->file('file_path')->store('materials', 'public');
        }

        // Store thumbnail if provided
        $thumbnail = null;
        if ($request->hasFile('thumbnail')) {
            $thumbnail = $request->file('thumbnail')->store('materials/thumbnails', 'public');
        }

        Material::create([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'category_id' => $validated['category_id'],
            'file_path' => $filePath,
            'thumbnail' => $thumbnail,
            'is_published' => $request->has('is_published'),
        ]);

        return redirect()->route('materi.index')->with('success', 'Materi berhasil ditambahkan!');
    }

    /**
     * Display the specified materi.
     */
    public function show(Material $material)
    {
        return view('admin.materi.show', compact('material'));
    }

    /**
     * Show the form for editing the specified materi.
     */
    public function edit(Material $material)
    {
        $categories = MaterialCategory::orderBy('order')->get();
        return view('admin.materi.edit', compact('material', 'categories'));
    }

    /**
     * Update the specified materi in storage.
     */
    public function update(Request $request, Material $material)
    {

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category_id' => 'required|exists:material_categories,id',
            'file_path' => 'nullable|file|mimes:pdf|max:51200', // 50MB
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_published' => 'boolean',
        ]);

        // Update PDF file if new one provided
        if ($request->hasFile('file_path')) {
            // Delete old file
            if ($material->file_path) {
                Storage::disk('public')->delete($material->file_path);
            }
            $validated['file_path'] = $request->file('file_path')->store('materials', 'public');
        } else {
            unset($validated['file_path']);
        }

        // Update thumbnail if new one provided
        if ($request->hasFile('thumbnail')) {
            // Delete old thumbnail
            if ($material->thumbnail) {
                Storage::disk('public')->delete($material->thumbnail);
            }
            $validated['thumbnail'] = $request->file('thumbnail')->store('materials/thumbnails', 'public');
        } else {
            unset($validated['thumbnail']);
        }

        $validated['is_published'] = $request->has('is_published');

        $material->update($validated);

        return redirect()->route('materi.show', $material)->with('success', 'Materi berhasil diperbarui!');
    }

    /**
     * Remove the specified materi from storage.
     */
    public function destroy(Material $material)
    {
        // Delete files
        if ($material->file_path) {
            Storage::disk('public')->delete($material->file_path);
        }
        if ($material->thumbnail) {
            Storage::disk('public')->delete($material->thumbnail);
        }

        $material->delete();
        return redirect()->route('materi.index')->with('success', 'Materi berhasil dihapus!');
    }

    /**
     * Toggle publish status.
     */
    public function togglePublish(Material $material)
    {
        $material->update(['is_published' => !$material->is_published]);
        return redirect()->back()->with('success', 'Status publikasi berhasil diperbarui!');
    }

    /**
     * Delete thumbnail from materi.
     */
    public function deleteThumbnail(Material $material)
    {
        if ($material->thumbnail) {
            Storage::disk('public')->delete($material->thumbnail);
            $material->update(['thumbnail' => null]);
        }

        return redirect()->route('materi.edit', $material)->with('success', 'Thumbnail berhasil dihapus!');
    }

    /**
     * Display materi untuk user (published only).
     */
    public function userMaterials(Request $request)
    {
        $query = Material::where('is_published', true);

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($category = $request->input('category')) {
            $query->where('category_id', $category);
        }

        $sort = $request->input('sort', 'terbaru');
        switch ($sort) {
            case 'terlama':
                $query->oldest();
                break;
            case 'judul_az':
                $query->orderBy('title', 'asc');
                break;
            case 'judul_za':
                $query->orderBy('title', 'desc');
                break;
            default:
                $query->latest();
                break;
        }

        $materials = $query->paginate(12)->withQueryString();
        $categories = MaterialCategory::orderBy('order')->get();
        return view('user.materials', compact('materials', 'categories'));
    }
}
