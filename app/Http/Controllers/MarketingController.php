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
     * Display learning materials.
     */
    public function learning()
    {
        return view('marketing.learning');
    }

    /**
     * Display materi (PDF materials).
     */
    public function materi()
    {
        $materials = Material::where('is_published', true)->latest()->paginate(12);
        $categories = MaterialCategory::orderBy('order')->get();
        return view('marketing.materi', compact('materials', 'categories'));
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
