<?php

namespace App\Http\Controllers;

use App\Helpers\ImageHelper;
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
     *
     * Supports two photo input methods:
     *  1. temp_photos[] — string paths from background async upload (preferred)
     *  2. photos[]      — direct file upload (fallback / legacy)
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'          => 'required|string|max:255',
            'description'    => 'required|string|min:5',
            'category'       => 'required|in:Bank Cessie,AYDA,Lelang',
            'status'         => 'required|in:Available,Sold Out',
            'location'       => 'nullable|string|max:255',
            'gmap_link'      => 'nullable|url|max:1000',
            'temp_photos'    => 'nullable|array',
            'temp_photos.*'  => 'nullable|string',
            'photos'         => 'nullable|array',
            'photos.*'       => 'image|mimes:jpeg,png,jpg,gif,webp|max:5120',
        ]);

        $photos = [];

        // --- Method 1: move temp files uploaded in background ---
        if (!empty($validated['temp_photos'])) {
            $photos = array_merge($photos, $this->moveTempPhotos($validated['temp_photos']));
        }

        // --- Method 2: direct file upload fallback ---
        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $photo) {
                $path     = $photo->store('assets', 'public');
                $photos[] = $path;
                ImageHelper::compressImage($path);
                ImageHelper::generateThumbnail($path);
            }
        }

        $validated['photos'] = $photos;
        unset($validated['temp_photos']);

        // Sanitize description HTML from Quill
        if (!empty($validated['description'])) {
            $validated['description'] = $this->sanitizeQuillHtml($validated['description']);
        }

        Asset::create($validated);

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
     *
     * Supports two photo input methods:
     *  1. temp_photos[] — string paths from background async upload (preferred)
     *  2. photos[]      — direct file upload (fallback / legacy)
     */
    public function update(Request $request, Asset $asset)
    {
        $validated = $request->validate([
            'title'          => 'required|string|max:255',
            'description'    => 'required|string|min:5',
            'category'       => 'required|in:Bank Cessie,AYDA,Lelang',
            'status'         => 'required|in:Available,Sold Out',
            'location'       => 'nullable|string|max:255',
            'gmap_link'      => 'nullable|url|max:1000',
            'temp_photos'    => 'nullable|array',
            'temp_photos.*'  => 'nullable|string',
            'photos'         => 'nullable|array',
            'photos.*'       => 'image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'deleted_photos' => 'nullable|array',
        ]);

        $photos = $asset->photos ?? [];

        // Remove deleted photos and their physical files
        $deletedPhotos = $request->input('deleted_photos', []);
        if (!empty($deletedPhotos)) {
            foreach ($deletedPhotos as $deletedPhoto) {
                if (Storage::disk('public')->exists($deletedPhoto)) {
                    Storage::disk('public')->delete($deletedPhoto);
                }
                // Also try to delete its thumbnail
                $thumbPath = ImageHelper::thumbPath($deletedPhoto);
                if (Storage::disk('public')->exists($thumbPath)) {
                    Storage::disk('public')->delete($thumbPath);
                }
            }
            $photos = array_values(array_filter($photos, fn($p) => !in_array($p, $deletedPhotos)));
        }

        // --- Method 1: move temp files uploaded in background ---
        if (!empty($validated['temp_photos'])) {
            $photos = array_merge($photos, $this->moveTempPhotos($validated['temp_photos']));
        }

        // --- Method 2: direct file upload fallback ---
        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $photo) {
                $path     = $photo->store('assets', 'public');
                $photos[] = $path;
                ImageHelper::compressImage($path);
                ImageHelper::generateThumbnail($path);
            }
        }

        $validated['photos'] = $photos;
        unset($validated['temp_photos'], $validated['deleted_photos']);

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
     * Move temp-uploaded photos to the permanent assets/ directory.
     * Thumbnails are also moved (or regenerated if missing).
     *
     * @param  array  $tempPaths  e.g. ["temp/uuid.jpg", ...]
     * @return array              permanent paths e.g. ["assets/uuid.jpg", ...]
     */
    private function moveTempPhotos(array $tempPaths): array
    {
        $permanent = [];

        foreach ($tempPaths as $tempPath) {
            $tempPath = trim((string) $tempPath);

            // Safety: only allow paths inside temp/
            if (!str_starts_with($tempPath, 'temp/')) {
                continue;
            }

            $disk = Storage::disk('public');

            if (!$disk->exists($tempPath)) {
                continue; // File missing — skip silently
            }

            $filename    = basename($tempPath);
            $assetPath   = 'assets/' . $filename;
            $thumbTemp   = ImageHelper::thumbPath($tempPath);
            $thumbAsset  = ImageHelper::thumbPath($assetPath);

            // Move main file
            $disk->move($tempPath, $assetPath);

            // Move or regenerate thumbnail
            if ($disk->exists($thumbTemp)) {
                // Ensure thumbs directory exists
                $thumbDir = dirname($disk->path($thumbAsset));
                if (!is_dir($thumbDir)) {
                    mkdir($thumbDir, 0755, true);
                }
                $disk->move($thumbTemp, $thumbAsset);
            } else {
                ImageHelper::generateThumbnail($assetPath);
            }

            $permanent[] = $assetPath;
        }

        return $permanent;
    }

    /**
     * Sanitize HTML content from Quill editor
     */
    private function sanitizeQuillHtml($html)
    {
        // Remove script tags and javascript
        $html = preg_replace('/<script[^>]*>.*?<\/script>/is', '', $html);
        $html = preg_replace('/on\w+\s*=\s*["\'][^"\']*["\']/i', '', $html);
        $html = preg_replace('/on\w+\s*=\s*[^\s>]*/i', '', $html);

        return trim($html);
    }
}
