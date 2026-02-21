<?php

namespace App\Http\Controllers;

use App\Models\Information;
use App\Models\InformationCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class InformasiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Information::query();

        // Search by title or content
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%");
            });
        }

        // Filter by category
        if ($categoryId = $request->input('category')) {
            $query->where('category_id', $categoryId);
        }

        // Filter by status
        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }
        // If no status filter, show ALL statuses (active and archived)

        // Filter by date range
        if ($dateFrom = $request->input('date_from')) {
            $query->whereDate('published_date', '>=', $dateFrom);
        }

        if ($dateTo = $request->input('date_to')) {
            $query->whereDate('published_date', '<=', $dateTo);
        }

        $informations = $query->latest('published_date')
                            ->paginate(10)
                            ->withQueryString();
        $categories = InformationCategory::orderBy('order')->get();

        return view('admin.informasi.index', compact('informations', 'categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = InformationCategory::orderBy('order')->get();
        return view('admin.informasi.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'nullable|string',
            'category_id' => 'required|integer|exists:information_categories,id',
            'status' => 'required|in:active,archived',
            'published_date' => 'required|date',
            'photo' => 'nullable|image|max:5120',
        ], [
            'photo.image' => 'File harus berupa gambar (JPG, PNG, GIF, WebP)',
            'photo.max' => 'Ukuran gambar maksimal 5MB',
        ]);

        // Handle photo upload
        if ($request->hasFile('photo')) {
            $validated['photo'] = $request->file('photo')->store('informasi', 'public');
        }

        Information::create($validated);

        return redirect()->route('informasi.index')
                        ->with('success', 'Informasi berhasil disimpan');
    }

    /**
     * Display the specified resource.
     */
    public function show(Information $informasi)
    {
        return view('admin.informasi.show', compact('informasi'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Information $informasi)
    {
        $categories = InformationCategory::orderBy('order')->get();
        return view('admin.informasi.edit', compact('informasi', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Information $informasi)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'nullable|string',
            'category_id' => 'required|integer|exists:information_categories,id',
            'status' => 'required|in:active,archived',
            'published_date' => 'required|date',
            'photo' => 'nullable|image|max:5120',
        ], [
            'photo.image' => 'File harus berupa gambar (JPG, PNG, GIF, WebP)',
            'photo.max' => 'Ukuran gambar maksimal 5MB',
        ]);

        // Handle photo upload
        if ($request->hasFile('photo')) {
            // Delete old photo if exists
            if ($informasi->photo && Storage::disk('public')->exists($informasi->photo)) {
                Storage::disk('public')->delete($informasi->photo);
            }
            $validated['photo'] = $request->file('photo')->store('informasi', 'public');
        }

        $informasi->update($validated);

        return redirect()->route('informasi.index')
                        ->with('success', 'Informasi berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Information $informasi)
    {
        // Delete photo if exists
        if ($informasi->photo && Storage::disk('public')->exists($informasi->photo)) {
            Storage::disk('public')->delete($informasi->photo);
        }

        $informasi->delete();

        return redirect()->route('informasi.index')
                        ->with('success', 'Informasi berhasil dihapus');
    }

    /**
     * Store a new information category (for modal)
     */
    public function storeCategory(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:information_categories,name',
        ]);

        $category = InformationCategory::create($validated);

        // Check if it's an AJAX request
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Kategori berhasil ditambahkan!',
                'category' => $category
            ], 201);
        }

        return redirect()->back()->with('success', 'Kategori berhasil ditambahkan!');
    }

    /**
     * Delete an information category
     */
    public function destroyCategory($id)
    {
        try {
            $category = InformationCategory::findOrFail($id);

            // Check if category has related informations
            $informationCount = $category->informations()->count();

            if ($informationCount > 0) {
                return response()->json([
                    'success' => false,
                    'message' => "Kategori tidak dapat dihapus karena masih digunakan oleh $informationCount informasi. Pindahkan atau hapus informasi terlebih dahulu."
                ], 409);
            }

            $category->delete();

            return response()->json([
                'success' => true,
                'message' => 'Kategori berhasil dihapus!'
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menghapus kategori: ' . $e->getMessage()
            ], 500);
        }
    }
}

