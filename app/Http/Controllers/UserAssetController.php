<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\AssetBookmark;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Auth;
use ZipArchive;
use Illuminate\Support\Facades\Storage;

class UserAssetController extends Controller
{
    /**
     * Display a listing of assets for users.
     */
    public function listing(Request $request)
    {
        $query = Asset::query();

        // Ambil ID aset yang sudah di-bookmark (sekali saja)
        $bookmarkedIds = AssetBookmark::where('user_id', Auth::user()->id)
            ->pluck('asset_id')
            ->toArray();

        // Filter hanya aset yang di-bookmark
        if ($request->input('saved') === '1') {
            $query->whereIn('id', $bookmarkedIds);
        }

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

        // Filter by location
        if ($location = $request->input('location')) {
            $query->where('location', $location);
        }

        // Filter by status
        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        // Sort
        $sortMap = [
            'oldest'     => ['created_at', 'asc'],
            'title_asc'  => ['title', 'asc'],
            'title_desc' => ['title', 'desc'],
        ];
        [$col, $dir] = $sortMap[$request->input('sort', 'newest')] ?? ['created_at', 'desc'];
        $query->orderBy($col, $dir);

        $assets    = $query->paginate(9)->withQueryString();
        $locations = Asset::select('location')->distinct()->orderBy('location')->get();

        return view('user.assets.listing', compact('assets', 'locations', 'bookmarkedIds'));
    }

    /**
     * Toggle bookmark (simpan/hapus) sebuah aset untuk user yang sedang login.
     */
    public function toggleBookmark(Asset $asset)
    {
        $user = Auth::user();

        $bookmark = AssetBookmark::where('user_id', $user->id)
            ->where('asset_id', $asset->id)
            ->first();

        if ($bookmark) {
            $bookmark->delete();
            $saved = false;
        } else {
            AssetBookmark::create([
                'user_id'  => $user->id,
                'asset_id' => $asset->id,
            ]);
            $saved = true;
        }

        return response()->json([
            'saved'      => $saved,
            'asset_id'   => $asset->id,
            'message'    => $saved ? 'Aset berhasil disimpan.' : 'Aset dihapus dari simpanan.',
        ]);
    }

    /**
     * Get broadcast text for an asset (API endpoint).
     */
    public function getBroadcastText(Asset $asset)
    {
        $broadcastText = "Halo! Ada aset menarik dari kategori {$asset->category} nih!\n\n";
        $broadcastText .= "Judul: {$asset->title}\n";
        $broadcastText .= "Lokasi: {$asset->location}\n";
        $broadcastText .= "Status: " . ($asset->status === 'Available' ? 'Tersedia' : 'Terjual') . "\n\n";
        $broadcastText .= "Tunggu apalagi? Hubungi kami sekarang untuk info lebih lanjut!";

        return response()->json([
            'text' => $broadcastText,
            'asset_id' => $asset->id,
            'asset_title' => $asset->title,
        ]);
    }

    /**
     * Download asset photos as ZIP file.
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

        // Download the ZIP file
        $fileName = 'Aset_' . str_replace(' ', '_', $asset->title) . '_' . now()->format('Y-m-d') . '.zip';

        return response()->download(
            $zipPath,
            $fileName,
            ['Content-Type' => 'application/zip']
        )->deleteFileAfterSend(true);
    }

    /**
     * Download single photo.
     */
    public function downloadSinglePhoto(Asset $asset, $photoIndex)
    {
        if (!$asset->photos || !isset($asset->photos[$photoIndex])) {
            return back()->with('error', 'Foto tidak ditemukan');
        }

        $photoPath = storage_path('app/public/' . $asset->photos[$photoIndex]);

        if (!file_exists($photoPath)) {
            return back()->with('error', 'File foto tidak ditemukan');
        }

        $fileName = $asset->id . '_foto_' . ($photoIndex + 1) . '.' . pathinfo($photoPath, PATHINFO_EXTENSION);

        return response()->download($photoPath, $fileName);
    }
}
