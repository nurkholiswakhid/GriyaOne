<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\Notification;
use App\Models\Content;
use App\Models\Material;
use App\Models\MaterialCategory;
use App\Models\Information;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use ZipArchive;

class MarketingController extends Controller
{
    /**
     * Display marketing dashboard.
     */
    public function dashboard()
    {
        // Informasi Terbaru
        $informations = Information::where('status', 'active')
            ->latest('published_date')
            ->take(3)
            ->get();

        // Statistik Listing/Asset
        $totalListings      = Asset::count();
        $availableListings  = Asset::where('status', 'Available')->count();
        $soldListings       = Asset::where('status', 'Sold Out')->count();

        // Listing berdasarkan kategori
        $listingsByCategory = Asset::selectRaw('category, COUNT(*) as count')
            ->groupBy('category')
            ->get();

        // Listing terbaru
        $recentListings = Asset::latest()->take(5)->get();

        return view('marketing.marketing', compact(
            'informations',
            'totalListings',
            'availableListings',
            'soldListings',
            'listingsByCategory',
            'recentListings'
        ));
    }

    /**
     * Display assets for marketing (listing).
     */
    public function assets()
    {
        return view('marketing.assets');
    }

    /**
     * Download asset photos.
     */
    public function downloadPhotos(Asset $asset)
    {
        if (!$asset->photos || count($asset->photos) === 0) {
            return back()->with('error', 'Aset ini tidak memiliki foto');
        }

        // Create a temporary ZIP file
        $zipPath = storage_path('app/temp/assets-' . $asset->id . '-' . time() . '.zip');
        $directory = dirname($zipPath);

        if (!is_dir($directory)) {
            mkdir($directory, 0755, true);
        }

        $zip = new ZipArchive();
        if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
            return back()->with('error', 'Gagal membuat file ZIP');
        }

        $photoCount = 0;
        foreach ($asset->photos as $index => $photo) {
            $photoPath = storage_path('app/public/' . $photo);
            if (file_exists($photoPath)) {
                $fileName = $asset->id . '_' . ($index + 1) . '_' . basename($photo);
                $zip->addFile($photoPath, $fileName);
                $photoCount++;
            }
        }

        $zip->close();

        if ($photoCount === 0) {
            @unlink($zipPath);
            return back()->with('error', 'Tidak ada foto yang dapat diunduh');
        }

        // Download the ZIP file with proper Safari compatibility headers
        $fileName = 'Aset_' . str_replace(' ', '_', $asset->title) . '_' . now()->format('Y-m-d') . '.zip';

        return response()->download(
            $zipPath,
            $fileName,
            [
                'Content-Type' => 'application/zip',
                'Content-Disposition' => 'attachment; filename="' . $fileName . '"'
            ]
        )->deleteFileAfterSend(true);
    }

    /**
     * Download selected photos as ZIP file (for modal selection).
     * Accepts photo indices as query parameter or JSON body.
     */
    public function downloadSelectedPhotos(Request $request, Asset $asset)
    {
        if (!$asset->photos || count($asset->photos) === 0) {
            return response()->json(['error' => 'Aset ini tidak memiliki foto'], 400);
        }

        // Get photo indices from query param or JSON body
        $indices = $request->input('indices') ?? [];

        // If indices is a string (from query param), parse it as JSON
        if (is_string($indices)) {
            $indices = json_decode($indices, true) ?? [];
        }

        // Validate indices
        $indices = array_filter($indices, fn($i) => is_numeric($i) && $i >= 0 && $i < count($asset->photos));
        $indices = array_unique(array_map('intval', $indices));
        sort($indices);

        if (empty($indices)) {
            return response()->json(['error' => 'Tidak ada foto yang dipilih'], 400);
        }

        // Create a temporary ZIP file
        $zipPath = storage_path('app/temp/assets-' . $asset->id . '-' . time() . '.zip');
        $directory = dirname($zipPath);

        if (!is_dir($directory)) {
            mkdir($directory, 0755, true);
        }

        $zip = new ZipArchive();
        if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
            return response()->json(['error' => 'Gagal membuat file ZIP'], 500);
        }

        $photoCount = 0;
        foreach ($indices as $index) {
            if (isset($asset->photos[$index])) {
                $photo = $asset->photos[$index];
                $photoPath = storage_path('app/public/' . $photo);

                if (file_exists($photoPath)) {
                    $fileName = $asset->id . '_foto_' . ($index + 1) . '_' . basename($photo);
                    $zip->addFile($photoPath, $fileName);
                    $photoCount++;
                }
            }
        }

        $zip->close();

        if ($photoCount === 0) {
            @unlink($zipPath);
            return response()->json(['error' => 'Tidak ada foto yang dapat diunduh'], 400);
        }

        // Download the ZIP file with proper Safari compatibility headers
        $fileName = 'Aset_' . str_replace(' ', '_', $asset->title) . '_' . now()->format('Y-m-d') . '.zip';

        return response()->download(
            $zipPath,
            $fileName,
            [
                'Content-Type' => 'application/zip',
                'Content-Disposition' => 'attachment; filename="' . $fileName . '"'
            ]
        )->deleteFileAfterSend(true);
    }

    /**
     * Display learning materials (Video YouTube).
     */
    public function learning(Request $request)
    {
        $query = Content::where('is_published', true);

        // Search
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Sort
        $sort = $request->input('sort', 'latest');
        switch ($sort) {
            case 'oldest':
                $query->oldest();
                break;
            case 'title_asc':
                $query->orderBy('title', 'asc');
                break;
            case 'title_desc':
                $query->orderBy('title', 'desc');
                break;
            case 'latest':
            default:
                $query->latest();
        }

        $materials = $query->paginate(9);

        $stats = [
            'total' => Content::where('is_published', true)->count(),
            'total_all' => Content::count(),
        ];

        return view('marketing.learning', compact('materials', 'stats'));
    }

    /**
     * Display materi (PDF materials).
     */
    public function materi(Request $request)
    {
        $query = Material::where('is_published', true);

        // Search
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Filter by category
        if ($request->filled('category')) {
            $query->where('category_id', $request->input('category'));
        }

        // Sort
        $sort = $request->input('sort', 'latest');
        switch ($sort) {
            case 'oldest':
                $query->oldest();
                break;
            case 'title_asc':
                $query->orderBy('title', 'asc');
                break;
            case 'title_desc':
                $query->orderBy('title', 'desc');
                break;
            case 'latest':
            default:
                $query->latest();
        }

        $materials = $query->paginate(9);
        $categories = MaterialCategory::orderBy('order')->get();

        $stats = [
            'total' => Material::where('is_published', true)->count(),
            'total_categories' => MaterialCategory::count(),
        ];

        return view('marketing.materi', compact('materials', 'categories', 'stats'));
    }

    /**
     * Display informasi terbaru (latest information).
     */
    public function informasi(Request $request)
    {
        $query = Information::where('status', 'active');

        // Search
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%");
            });
        }

        // Filter by category
        if ($request->filled('category')) {
            $query->where('category_id', $request->input('category'));
        }

        // Sort
        $sort = $request->input('sort', 'terbaru');
        switch ($sort) {
            case 'terlama':
                $query->oldest('published_date');
                break;
            case 'a-z':
                $query->orderBy('title', 'asc');
                break;
            case 'z-a':
                $query->orderBy('title', 'desc');
                break;
            case 'terbaru':
            default:
                $query->latest('published_date');
        }

        $informations = $query->paginate(12);

        return view('marketing.informasi', compact('informations'));
    }

    /**
     * Display marketing notifications.
     */
    public function notifications()
    {
        return view('marketing.notifications');
    }

    /**
     * Mark notification as read for marketing user.
     */
    public function markNotificationAsRead(Notification $notification)
    {
        if ($notification->user_id !== Auth::id()) {
            abort(403);
        }

        $notification->markAsRead();
        return redirect()->back()->with('success', 'Notifikasi ditandai sudah dibaca');
    }

    /**
     * Delete notification for marketing user.
     */
    public function deleteNotification(Notification $notification)
    {
        if ($notification->user_id !== Auth::id()) {
            abort(403);
        }

        $notification->delete();
        return redirect()->back()->with('success', 'Notifikasi dihapus');
    }
}
