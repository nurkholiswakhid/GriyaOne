<?php

namespace App\Http\Controllers;

use App\Models\Content;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class VideoMediaPembelajaranController extends Controller
{
    /**
     * Display a listing of video pembelajaran.
     */
    public function index(Request $request)
    {
        $query = Content::query();

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
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

        $contents = $query->paginate(6)->withQueryString();
        return view('admin.VideoMediaPembelajaran.index', compact('contents'));
    }

    /**
     * Show the form for creating a new content.
     */
    public function create()
    {
        return view('admin.VideoMediaPembelajaran.create');
    }

    /**
     * Store a newly created content in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'file_path' => 'nullable|string|max:500',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_published' => 'boolean',
        ]);

        $thumbnail = null;
        if ($request->hasFile('thumbnail')) {
            $thumbnail = $request->file('thumbnail')->store('contents', 'public');
        }

        $validated['thumbnail'] = $thumbnail;
        $validated['is_published'] = $request->has('is_published');

        Content::create($validated);

        return redirect()->route('contents.index')->with('success', 'Video berhasil ditambahkan!');
    }

    /**
     * Display the specified content.
     */
    public function show(Content $content)
    {
        return view('admin.VideoMediaPembelajaran.show', compact('content'));
    }

    /**
     * Show the form for editing the specified content.
     */
    public function edit(Content $content)
    {
        return view('admin.VideoMediaPembelajaran.edit', compact('content'));
    }

    /**
     * Update the specified content in storage.
     */
    public function update(Request $request, Content $content)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'file_path' => 'nullable|string|max:500',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_published' => 'boolean',
        ]);

        if ($request->hasFile('thumbnail')) {
            // Delete old thumbnail
            if ($content->thumbnail) {
                Storage::disk('public')->delete($content->thumbnail);
            }
            $validated['thumbnail'] = $request->file('thumbnail')->store('contents', 'public');
        }

        $validated['is_published'] = $request->has('is_published');

        $content->update($validated);

        return redirect()->route('contents.show', $content)->with('success', 'Video berhasil diperbarui!');
    }

    /**
     * Remove the specified content from storage.
     */
    public function destroy(Content $content)
    {
        if ($content->thumbnail) {
            Storage::disk('public')->delete($content->thumbnail);
        }
        $content->delete();
        return redirect()->route('contents.index')->with('success', 'Video berhasil dihapus!');
    }

    /**
     * Toggle publish status.
     */
    public function togglePublish(Content $content)
    {
        $content->update(['is_published' => !$content->is_published]);
        return redirect()->back()->with('success', 'Status publikasi berhasil diperbarui!');
    }

    /**
     * Delete thumbnail from content.
     */
    public function deleteThumbnail(Content $content)
    {
        if ($content->thumbnail) {
            Storage::disk('public')->delete($content->thumbnail);
            $content->update(['thumbnail' => null]);
        }

        return redirect()->route('contents.edit', $content)->with('success', 'Thumbnail berhasil dihapus!');
    }
}
