<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\Asset;
use App\Models\Information;
use App\Models\Content;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class NotificationController extends Controller
{
    /**
     * Get dashboard notifications in JSON format
     */
    public function getDashboardNotifications(): JsonResponse
    {
        $notifications = [];
        $now = Carbon::now();

        // Get latest information (from last 7 days)
        $latestInformations = Information::where('created_at', '>=', $now->clone()->subDays(7))
            ->orderBy('created_at', 'desc')
            ->limit(3)
            ->get()
            ->map(function ($info) {
                return [
                    'id' => 'info-' . $info->id,
                    'type' => 'info',
                    'title' => 'Informasi Terbaru',
                    'message' => $info->title ?? 'Informasi baru telah ditambahkan',
                    'created_at' => $info->created_at,
                    'timestamp' => $info->created_at->timestamp,
                ];
            });

        // Get new assets (from last 7 days)
        $newAssets = Asset::where('created_at', '>=', $now->clone()->subDays(7))
            ->orderBy('created_at', 'desc')
            ->limit(3)
            ->get()
            ->map(function ($asset) {
                return [
                    'id' => 'asset-' . $asset->id,
                    'type' => 'new_asset',
                    'title' => 'Aset Baru',
                    'message' => 'Aset baru: ' . ($asset->title ?? 'Tanpa judul'),
                    'created_at' => $asset->created_at,
                    'timestamp' => $asset->created_at->timestamp,
                ];
            });

        // Get sold out assets (updated in last 7 days)
        $soldOutAssets = Asset::where('status', 'Sold Out')
            ->where('updated_at', '>=', $now->clone()->subDays(7))
            ->orderBy('updated_at', 'desc')
            ->limit(3)
            ->get()
            ->map(function ($asset) {
                return [
                    'id' => 'soldout-' . $asset->id,
                    'type' => 'sold_out',
                    'title' => 'Aset Sold Out',
                    'message' => 'Aset sudah terjual: ' . ($asset->title ?? 'Tanpa judul'),
                    'created_at' => $asset->updated_at,
                    'timestamp' => $asset->updated_at->timestamp,
                ];
            });

        // Merge all notifications and sort by most recent
        $notifications = $latestInformations
            ->concat($newAssets)
            ->concat($soldOutAssets)
            ->sortByDesc('timestamp')
            ->values()
            ->toArray();

        return response()->json($notifications);
    }

    /**
     * Display a listing of notifications.
     */
    public function index()
    {
        $notifications = Notification::latest()->paginate(15);
        $unreadCount = Notification::where('is_read', false)->count();
        return view('admin.notifications.index', compact('notifications', 'unreadCount'));
    }

    /**
     * Send notification for new asset.
     */
    public function sendNewAssetNotification(Asset $asset)
    {
        $users = \App\Models\User::where('role', 'marketing')->get();

        foreach ($users as $user) {
            Notification::create([
                'user_id' => $user->id,
                'type' => 'asset_new',
                'asset_id' => $asset->id,
                'title' => 'Aset Baru Tersedia',
                'message' => "Aset baru '{$asset->title}' telah ditambahkan. Kategori: {$asset->category}, Harga: Rp " . number_format($asset->price, 0, ',', '.'),
                'icon' => '🆕',
            ]);
        }

        return true;
    }

    /**
     * Send notification for sold out asset.
     */
    public function sendSoldOutNotification(Asset $asset)
    {
        $users = \App\Models\User::where('role', 'marketing')->get();

        foreach ($users as $user) {
            Notification::create([
                'user_id' => $user->id,
                'type' => 'asset_sold',
                'asset_id' => $asset->id,
                'title' => 'Aset Terjual',
                'message' => "Aset '{$asset->title}' telah terjual. Selamat atas penjualan ini!",
                'icon' => '✅',
            ]);
        }

        return true;
    }

    /**
     * Mark notification as read.
     */
    public function markAsRead(Notification $notification)
    {
        $notification->markAsRead();
        return redirect()->back()->with('success', 'Notifikasi ditandai sebagai sudah dibaca');
    }

    /**
     * Mark all as read.
     */
    public function markAllAsRead()
    {
        Notification::where('is_read', false)->update([
            'is_read' => true,
            'read_at' => now(),
        ]);
        return redirect()->back()->with('success', 'Semua notifikasi ditandai sebagai sudah dibaca');
    }

    /**
     * Delete notification.
     */
    public function destroy(Notification $notification)
    {
        $notification->delete();
        return redirect()->back()->with('success', 'Notifikasi dihapus');
    }

    /**
     * Bulk send notifications to users.
     */
    public function sendBulk(Request $request)
    {
        $validated = $request->validate([
            'user_ids' => 'required|array',
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'type' => 'required|in:system,info',
        ]);

        foreach ($validated['user_ids'] as $userId) {
            Notification::create([
                'user_id' => $userId,
                'type' => $validated['type'],
                'title' => $validated['title'],
                'message' => $validated['message'],
                'icon' => $validated['type'] === 'system' ? '⚠️' : 'ℹ️',
            ]);
        }

        return redirect()->back()->with('success', 'Notifikasi berhasil dikirim ke ' . count($validated['user_ids']) . ' pengguna');
    }
}
