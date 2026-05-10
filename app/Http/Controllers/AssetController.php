<?php

namespace App\Http\Controllers;

use App\Jobs\ProcessAssetImages;
use App\Models\Asset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AssetController extends Controller
{
    /**
     * Display a listing of assets.
     */
    public function index(Request $request)
    {
        $query = Asset::query();

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('location', 'like', "%{$search}%");
            });
        }

        if ($category = $request->input('category')) {
            $query->where('category', $category);
        }

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
     * Supports both normal form submit and AJAX (X-Requested-With: XMLHttpRequest).
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'required|string|min:5',
            'category'    => 'required|in:Bank Cessie,AYDA,Lelang',
            'status'      => 'required|in:Available,Sold Out',
            'location'    => 'nullable|string|max:255',
            'gmap_link'   => 'nullable|url|max:1000',
            'photos'      => 'nullable|array',
            'photos.*'    => 'image|mimes:jpeg,png,jpg,gif,webp|max:10240',
        ]);

        // Store uploaded files — fast, no GD processing here
        $newPaths = [];
        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $photo) {
                $newPaths[] = $photo->store('assets', 'public');
            }
        }

        $validated['photos'] = $newPaths;

        // Sanitize description HTML from Quill
        if (!empty($validated['description'])) {
            $validated['description'] = $this->sanitizeQuillHtml($validated['description']);
        }

        $asset = Asset::create($validated);

        // Dispatch background job to compress & generate thumbnails (non-blocking)
        if (!empty($newPaths)) {
            ProcessAssetImages::dispatch($newPaths);
        }

        session()->regenerate();

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success'  => true,
                'redirect' => route('assets.index'),
                'message'  => 'Aset berhasil ditambahkan!',
            ]);
        }

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
     * Supports both normal form submit and AJAX.
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
            'photos'         => 'nullable|array',
            'photos.*'       => 'image|mimes:jpeg,png,jpg,gif,webp|max:10240',
            'deleted_photos' => 'nullable|array',
        ]);

        $photos = $asset->photos ?? [];

        // Remove deleted photos
        $deletedPhotos = $request->input('deleted_photos', []);
        if (!empty($deletedPhotos)) {
            foreach ($deletedPhotos as $deletedPhoto) {
                if (Storage::disk('public')->exists($deletedPhoto)) {
                    Storage::disk('public')->delete($deletedPhoto);
                }
                // Also delete thumbnail
                $thumbPath = \App\Helpers\ImageHelper::thumbPath($deletedPhoto);
                if (Storage::disk('public')->exists($thumbPath)) {
                    Storage::disk('public')->delete($thumbPath);
                }
            }
            $photos = array_values(array_filter($photos, fn($p) => !in_array($p, $deletedPhotos)));
        }

        // Store new uploaded files — fast, no GD here
        $newPaths = [];
        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $photo) {
                $path       = $photo->store('assets', 'public');
                $photos[]   = $path;
                $newPaths[] = $path;
            }
        }

        $validated['photos'] = $photos;

        if (!empty($validated['description'])) {
            $validated['description'] = $this->sanitizeQuillHtml($validated['description']);
        }

        $asset->update($validated);

        // Dispatch background job for new photos only
        if (!empty($newPaths)) {
            ProcessAssetImages::dispatch($newPaths);
        }

        session()->regenerate();

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success'  => true,
                'redirect' => route('assets.show', $asset),
                'message'  => 'Aset berhasil diperbarui!',
            ]);
        }

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
     * Sanitize HTML content from Quill editor.
     */
    private function sanitizeQuillHtml(string $html): string
    {
        $html = preg_replace('/<script[^>]*>.*?<\/script>/is', '', $html);
        $html = preg_replace('/on\w+\s*=\s*["\'][^"\']*["\']/i', '', $html);
        $html = preg_replace('/on\w+\s*=\s*[^\s>]*/i', '', $html);
        return trim($html);
    }
}
