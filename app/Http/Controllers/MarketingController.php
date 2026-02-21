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

class MarketingController extends Controller
{
    /**
     * Display marketing dashboard.
     */
    public function dashboard()
    {
        $informations = Information::where('status', 'active')
            ->latest('published_date')
            ->take(3)
            ->get();

        return view('marketing.marketing', compact('informations'));
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
        // In a real implementation, this would create a zip file and download
        // For now, redirect back with success message
        return redirect()->back()->with('success', 'Download fitur akan segera tersedia');
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
    public function informasi()
    {
        $informations = Information::latest()->paginate(12);
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
