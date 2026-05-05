<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class AssetController extends Controller
{
    /**
     * Display a listing of assets.
     */
    public function index(Request $request)
    {
        $query = Asset::query();

        // Search by title or location
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('location', 'like', "%{$search}%");
            });
        }

        // Filter by category
        if ($category = $request->input('category')) {
            $query->where('category', $category);
        }

        // Filter by status
        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        $assets = $query->latest()->paginate(10)->withQueryString();
        return view('admin.assets.index', compact('assets'));
    }

    /**
     * Show the form for creating a new asset.
     */
    public function create()
    {
        return view('admin.assets.create');
    }

    /**
     * Store a newly created asset in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string|min:5',
            'category' => 'required|in:Bank Cessie,AYDA,Lelang',
            'status' => 'required|in:Available,Sold Out',
            'location' => 'nullable|string|max:255',
            'gmap_link' => 'nullable|url|max:1000',
            'photos' => 'nullable|array',
            'photos.*' =>'image|mimes:jpeg,png,jpg,gif,webp|max:5120',
        ]);

        Log::info('Validated data:', $validated);

        $photos = [];
        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $photo) {
                $path = $photo->store('assets', 'public');
                $photos[] = $path;
            }
        }

        $validated['photos'] = $photos;

        // Sanitize description HTML from Quill
        if (!empty($validated['description'])) {
            $validated['description'] = $this->sanitizeQuillHtml($validated['description']);
            Log::info('Description after sanitization:', ['description' => $validated['description']]);
        }

        $created = Asset::create($validated);
        Log::info('Asset created with ID:', ['id' => $created->id, 'description' => $created->description]);

        // Regenerate session to prevent session issues
        session()->regenerate();

        return redirect()->route('assets.index')->with('success', 'Aset berhasil ditambahkan!');
    }

    /**
     * Display the specified asset.
     */
    public function show(Asset $asset)
    {
        return view('admin.assets.show', compact('asset'));
    }

    /**
     * Show the form for editing the specified asset.
     */
    public function edit(Asset $asset)
    {
        return view('admin.assets.edit', compact('asset'));
    }

    /**
     * Update the specified asset in storage.
     */
    public function update(Request $request, Asset $asset)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string|min:5',
            'category' => 'required|in:Bank Cessie,AYDA,Lelang',
            'status' => 'required|in:Available,Sold Out',
            'location' => 'nullable|string|max:255',
            'gmap_link' => 'nullable|url|max:1000',
            'photos' => 'nullable|array',
            'photos.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'deleted_photos' => 'nullable|array',
        ]);

        $photos = $asset->photos ?? [];

        // Remove deleted photos (and delete physical files from storage)
        $deletedPhotos = $request->input('deleted_photos', []);
        if (!empty($deletedPhotos)) {
            foreach ($deletedPhotos as $deletedPhoto) {
                // Delete the physical file from storage
                if (Storage::disk('public')->exists($deletedPhoto)) {
                    Storage::disk('public')->delete($deletedPhoto);
                }
            }
            $photos = array_filter($photos, function($photo) use ($deletedPhotos) {
                return !in_array($photo, $deletedPhotos);
            });
            $photos = array_values($photos); // Re-index array
        }

        // Add new photos
        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $photo) {
                $path = $photo->store('assets', 'public');
                $photos[] = $path;
            }
        }

        $validated['photos'] = $photos;

        // Sanitize description HTML from Quill
        if (!empty($validated['description'])) {
            $validated['description'] = $this->sanitizeQuillHtml($validated['description']);
        }

        $asset->update($validated);

        // Regenerate session to prevent session issues
        session()->regenerate();

        return redirect()->route('assets.show', $asset)->with('success', 'Aset berhasil diperbarui!');
    }

    /**
     * Remove the specified asset from storage.
     */
    public function destroy(Asset $asset)
    {
        $asset->delete();
        return redirect()->route('assets.index')->with('success', 'Aset berhasil dihapus!');
    }

    /**
     * Update asset status.
     */
    public function updateStatus(Request $request, Asset $asset)
    {
        $validated = $request->validate([
            'status' => 'required|in:Available,Sold Out',
        ]);

        $asset->update($validated);
        return redirect()->back()->with('success', 'Status aset berhasil diperbarui!');
    }

    /**
     * Sanitize HTML content from Quill editor
     */
    private function sanitizeQuillHtml($html)
    {
        // Allow specific HTML tags from Quill
        $allowed_tags = ['<p>', '</p>', '<h1>', '</h1>', '<h2>', '</h2>', '<h3>', '</h3>',
                         '<strong>', '</strong>', '<em>', '</em>', '<u>', '</u>', '<s>', '</s>',
                         '<ol>', '</ol>', '<ul>', '</ul>', '<li>', '</li>',
                         '<blockquote>', '</blockquote>', '<pre>', '</pre>', '<code>', '</code>',
                         '<br>', '<br/>', '<br />'];

        // Remove script tags and javascript
        $html = preg_replace('/<script[^>]*>.*?<\/script>/is', '', $html);
        $html = preg_replace('/on\w+\s*=\s*["\'][^"\']*["\']/i', '', $html);
        $html = preg_replace('/on\w+\s*=\s*[^\s>]*/i', '', $html);

        // Trim whitespace
        return trim($html);
    }
}
