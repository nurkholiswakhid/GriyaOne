<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\Notification;
use Illuminate\Http\Request;

class MarketingController extends Controller
{
    /**
     * Display marketing dashboard.
     */
    public function dashboard()
    {
        return view('marketing.dashboard');
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
        if ($notification->user_id !== auth()->id()) {
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
        if ($notification->user_id !== auth()->id()) {
            abort(403);
        }

        $notification->delete();
        return redirect()->back()->with('success', 'Notifikasi dihapus');
    }
}
