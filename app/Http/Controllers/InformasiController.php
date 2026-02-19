<?php

namespace App\Http\Controllers;

use App\Models\Information;
use Illuminate\Http\Request;

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
        if ($category = $request->input('category')) {
            $query->where('category', $category);
        }

        // Filter by status
        if ($status = $request->input('status')) {
            $query->where('status', $status);
        } else {
            // Default: Only show active information if no status filter is applied
            $query->where('status', 'active');
        }

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

        return view('admin.informasi.index', compact('informations'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.informasi.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category' => 'required|string|max:100',
            'status' => 'required|in:active,archived',
            'published_date' => 'required|date',
        ]);

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
        return view('admin.informasi.edit', compact('informasi'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Information $informasi)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category' => 'required|string|max:100',
            'status' => 'required|in:active,archived',
            'published_date' => 'required|date',
        ]);

        $informasi->update($validated);

        return redirect()->route('informasi.index')
                        ->with('success', 'Informasi berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Information $informasi)
    {
        $informasi->delete();

        return redirect()->route('informasi.index')
                        ->with('success', 'Informasi berhasil dihapus');
    }
}
