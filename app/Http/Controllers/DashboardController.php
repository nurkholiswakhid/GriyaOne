<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Asset;
use App\Models\Content;
use App\Models\Information;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function userDashboard()
    {
        $this->checkRole('user');
        return view('user.user');
    }

    public function marketingDashboard()
    {
        $this->checkRole('marketing');

        // Informasi Terbaru
        $informations = Information::where('status', 'active')
            ->latest('published_date')
            ->take(3)
            ->get();

        // Statistik Listing/Asset
        $totalListings = Asset::count();
        $availableListings = Asset::where('status', 'Available')->count();
        $soldListings = Asset::where('status', 'Sold Out')->count();

        // Listing berdasarkan kategori
        $listingsByCategory = Asset::selectRaw('category, COUNT(*) as count')
            ->groupBy('category')
            ->get();

        // Listing terbaru
        $recentListings = Asset::latest()->take(5)->get();

        return view('marketing.marketing', compact('informations', 'totalListings', 'availableListings', 'soldListings', 'listingsByCategory', 'recentListings'));
    }

    public function adminDashboard()
    {
        $this->checkRole('admin');

        // Statistik Pengguna
        $totalUsers = User::count();
        $adminUsers = User::where('role', 'admin')->count();
        $marketingUsers = User::where('role', 'marketing')->count();
        $regularUsers = User::where('role', 'user')->count();
        $recentUsers = User::orderBy('created_at', 'desc')->limit(5)->get();

        // Statistik Aset
        $totalAssets = Asset::count();
        $availableAssets = Asset::where('status', 'Available')->count();
        $soldAssets = Asset::where('status', 'Sold')->count();
        $assetsByCategory = Asset::select('category')
            ->selectRaw('count(*) as count')
            ->groupBy('category')
            ->get();

        // Statistik Konten
        $totalContent = Content::count();
        $publishedContent = Content::where('is_published', true)->count();
        $draftContent = Content::where('is_published', false)->count();
        $totalContentViews = 0; // Kolom 'views' sudah dihapus dari tabel contents

        // Statistik Notifikasi
        $unreadNotifications = Notification::where('is_read', false)->count();
        $totalNotifications = Notification::count();

        // Chart Data - User Growth (last 7 days)
        $userGrowth = User::selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->where('created_at', '>=', now()->subDays(7))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Chart Data - Asset Distribution
        $assetDistribution = Asset::selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->get();

        $data = [
            'totalUsers' => $totalUsers,
            'adminUsers' => $adminUsers,
            'marketingUsers' => $marketingUsers,
            'regularUsers' => $regularUsers,
            'recentUsers' => $recentUsers,
            'totalAssets' => $totalAssets,
            'availableAssets' => $availableAssets,
            'soldAssets' => $soldAssets,
            'assetsByCategory' => $assetsByCategory,
            'totalContent' => $totalContent,
            'publishedContent' => $publishedContent,
            'draftContent' => $draftContent,
            'totalContentViews' => $totalContentViews,
            'unreadNotifications' => $unreadNotifications,
            'totalNotifications' => $totalNotifications,
            'userGrowth' => $userGrowth,
            'assetDistribution' => $assetDistribution,
        ];

        return view('admin.admin', $data);
    }

    private function checkRole($requiredRole)
    {
        if (Auth::user()->role !== $requiredRole) {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }
    }
}
